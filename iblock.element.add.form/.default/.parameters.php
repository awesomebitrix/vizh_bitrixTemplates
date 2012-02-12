<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

	$arTemplateParameters = array(
		'SUBMIT_NAME' => array(
			'NAME' => 'Текст кнопки добавления элемента',
			'DEFAULT' => 'Создать'
		),
		
		'ERRORS_HIGHLITE_FIELDS' => array(
			'NAME' => 'Подсвечивать поля в которых произошла ошибка (альфа)',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		)
	);

	if (in_array('IBLOCK_SECTION', $arCurrentValues['PROPERTY_CODES']))
	{
		$arTemplateParameters['USE_SINGLE_IBLOCK_SECTION'] = array(
			'NAME' => 'Множественная привязка к разделам',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		);

		$arTemplateParameters['HIDE_IBLOCK_SECTION_LEVELS'] = array(
			'NAME' => 'Скрывать уровень вложенности разделов',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		);
	}
