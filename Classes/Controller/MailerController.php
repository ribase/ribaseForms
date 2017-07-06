<?php
namespace WondrousForms\WondrousForms\Controller;


use \WondrousForms\WondrousForms\Domain\Validator;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
  /***************************************************************
   *
   *  Copyright notice
   *
   *  (c) 2015 Sebastian Thadewald <sebastian@wondrous.ch>, Wondrous LLC
   *
   *  All rights reserved
   *
   *  This script is part of the TYPO3 project. The TYPO3 project is
   *  free software; you can redistribute it and/or modify
   *  it under the terms of the GNU General Public License as published by
   *  the Free Software Foundation; either version 3 of the License, or
   *  (at your option) any later version.
   *
   *  The GNU General Public License can be found at
   *  http://www.gnu.org/copyleft/gpl.html.
   *
   *  This script is distributed in the hope that it will be useful,
   *  but WITHOUT ANY WARRANTY; without even the implied warranty of
   *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   *  GNU General Public License for more details.
   *
   *  This copyright notice MUST APPEAR in all copies of the script!
   ***************************************************************/

/**
 * MailerController
 */
class MailerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

  /**
   * action list
   *
   * @return void
   */
  public function listAction()
  {

    $arguments = $this->request->getArguments();

    $settings = $this->settings;

    if($arguments['validated'] == true)
    {
      if(isset($arguments['formValues'])){
        foreach ($arguments['formValues'] as $key => $value) {

          $this->view->assign($key, $value);

        }
      }
      if(isset($arguments['validatedForms'])){
        foreach ($arguments['validatedForms'] as $key => $value) {
          $this->view->assign($key, $value);
        }
      }
    }
    $this->view->assign('option', $settings);

  }

  public function sendAction()
  {

    $formContent = $this->request->getArguments();
    $formValues = array_intersect_key($formContent['content'], array_flip(preg_grep('/^(wdMailer_)(.*_*)(?<!_Required)$/', array_keys($formContent['content']))));

    $receiver = $this->settings['receiver'];
    $subject = $this->settings['subject'];


    $validatedForm = $this->validateForms($formContent['content']);

    if($validatedForm['reload'] == true ){

      $listParams['validated']  = true;
      $listParams['formValues'] = $formValues;
      $listParams['validatedForms'] = $validatedForm;
      $this->redirect('list', NULL, NULL, $listParams);
      return;
    }

    $ergebnis = array_merge($formValues, $validatedForm);

    if(!$ergebnis['wdMailer_Email_Required']){
      $recipient = $ergebnis['wdMailer_EmailFeedback_Required'];
    }else{
      $recipient = $ergebnis['wdMailer_Email_Required'];
    }

    $this->sendMail($recipient,$receiver,$ergebnis, $subject);

  }

  public function sendMail($recipient, $recipientAdmin, $formValues, $subject)
  {
    $templateAdmin = 'Mailer/AdminMail.html';
    $template = 'Mailer/UserMail.html';

    $formValues['subject'] = $subject;

    // Send the AdminMail
    $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
    $mail->setSubject($subject);
    $mail->setFrom(array('no-reply@claraspital.ch' => 'St Claraspital'));
    $mail->setTo($recipientAdmin);
    $mail->setBody($this->getRenderedEmailTemplate($formValues, $templateAdmin), 'text/html');
    $mail->send();


    //Send the UserMail
    $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
    $mail->setSubject($subject);
    $mail->setFrom(array('no-reply@claraspital.ch' => 'St Claraspital'));
    $mail->setTo($recipient);
    $mail->setBody($this->getRenderedEmailTemplate($formValues, $template), 'text/html');
    $mail->send();

    return;

  }

  public function getRenderedEmailTemplate($variables, $template) {
    $tenplateLayoutRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:wondrous_forms/Resources/Private/Layouts/');
    $templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:wondrous_forms/Resources/Private/Templates/');
    $templatePathAndFilename = $templateRootPath . $template;


    $cleanedVariables[] = '';

    // Clean up the required from the array keys
    foreach ($variables as $key => $value) {
      $newkey = str_replace('_Required','', $key);
      $cleanedVariables[$newkey] = $value;
    }

    // Create an Instance of a Fluid StandaloneView Object
    $view = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
    $view->setLayoutRootPath($tenplateLayoutRootPath);
    $view->setTemplatePathAndFilename($templatePathAndFilename);
    $extensionName = $this->request->getControllerExtensionName();
    $view->getRequest()->setControllerExtensionName($extensionName);

    foreach ($cleanedVariables as $key => $value) {
      if(is_array($value)){
        $str = implode (", ", $value);
        $cleanedVariables[$key] = $str;
      }
    }

    $view->assign('mailContent', $cleanedVariables);
    $emailTemplate = $view->render();

    return $emailTemplate;
  }

  public function validateForms($formValues)
  {

    $fieldsToValidate = array_intersect_key($formValues, array_flip(preg_grep('/_(?!.*_)(Required)$/', array_keys($formValues))));

    $isEmptyValidator = new Validator\IsEmptyValidator;

    $validatedFields = $isEmptyValidator->isEmpty($fieldsToValidate);

    if (in_array("isEmpty", $validatedFields)) {
      $validatedFields['reload'] = true;
    }else {
      $validatedFields['reload'] = false;
    }


    return $validatedFields;

  }

}
