<?php
namespace WondrousForms\WondrousForms\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Sebastian Thadewald <sebastian@wondrous.ch>, Wondrous LLC
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class WondrousForms\WondrousForms\Controller\MailerController.
 *
 * @author Sebastian Thadewald <sebastian@wondrous.ch>
 */
class MailerControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \WondrousForms\WondrousForms\Controller\MailerController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('WondrousForms\\WondrousForms\\Controller\\MailerController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllMailersFromRepositoryAndAssignsThemToView()
	{

		$allMailers = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$mailerRepository = $this->getMock('WondrousForms\\WondrousForms\\Domain\\Repository\\MailerRepository', array('findAll'), array(), '', FALSE);
		$mailerRepository->expects($this->once())->method('findAll')->will($this->returnValue($allMailers));
		$this->inject($this->subject, 'mailerRepository', $mailerRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('mailers', $allMailers);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenMailerToView()
	{
		$mailer = new \WondrousForms\WondrousForms\Domain\Model\Mailer();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('mailer', $mailer);

		$this->subject->showAction($mailer);
	}
}
