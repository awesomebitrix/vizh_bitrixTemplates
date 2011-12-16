<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

	$arTemplateParameters = array(
		"USER_PROPERTY_NAME" => array(
			"NAME" => GetMessage("USER_PROPERTY_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => ""
		),

		'SHOW_GROUP_POLICY' => array(
			'NAME' => GetMessage("SHOW_GROUP_POLICY"),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		)
	);
