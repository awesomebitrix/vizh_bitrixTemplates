<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

	$arTemplateParameters = array(
		'DISPLAY_DATE' => Array(
			'NAME' => 'Выводить дату элемента',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
		'DISPLAY_NAME' => Array(
			'NAME' => 'Выводить название элемента',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
		'DISPLAY_PICTURE' => Array(
			'NAME' => 'Выводить изображение для анонса',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
		'DISPLAY_PREVIEW_TEXT' => Array(
			'NAME' => 'Выводить текст анонса',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		)
	);
