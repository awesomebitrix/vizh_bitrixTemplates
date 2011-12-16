<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

	$arTemplateParameters = array(
		'USER_PROPERTY_NAME' => array(
			'NAME' => GetMessage('USER_PROPERTY_NAME'),
			'TYPE' => 'STRING',
			'DEFAULT' => ''
		),

		'USE_EMAIL_AS_LOGIN' => array(
			'NAME' => 'Использовать Email в качестве логина (требуется jQuery)',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		),

		'SHOW_GROUP_POLICY' => array(
			'NAME' => GetMessage('SHOW_GROUP_POLICY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		),

		'SHOW_SECTION_HEADERS' => array(
			'NAME' => GetMessage('SHOW_SECTION_HEADERS'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		)
	);
