<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RibaseForms.' . $_EXTKEY,
	'Ribaseformdisplay',
	array(
		'Mailer' => 'list, show, send'

	),
	// non-cacheable actions
	array(
    'Mailer' => 'list, show, send'

	)
);
