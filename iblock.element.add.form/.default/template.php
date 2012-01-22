<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

	if (count($arResult['ERRORS']))
		echo ShowError(implode('<br>', $arResult['ERRORS']));

	if (strlen($arResult['MESSAGE']) > 0)
		echo ShowNote($arResult['MESSAGE']);

	if (empty($arParams['SUBMIT_NAME']))
		$arParams['SUBMIT_NAME'] = 'Создать';

?>

<div class="bx-vTable">
	<form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
		<?=bitrix_sessid_post()?>
		<?if($arParams['MAX_FILE_SIZE'] > 0):?>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?=$arParams['MAX_FILE_SIZE']?>"/>
		<?endif?>
		<?if(is_array($arResult['PROPERTY_LIST']) && count($arResult['PROPERTY_LIST'] > 0)):?>
			<?foreach($arResult['PROPERTY_LIST'] as $field_id):?>
				<?php

					$arField = &$arResult['PROPERTY_LIST_FULL'][$field_id];

					$field_label = intval($field_id) > 0 ? $arField['NAME'] : (empty($arParams["CUSTOM_TITLE_{$field_id}"]) ? GetMessage("IBLOCK_FIELD_{$field_id}") : $arParams["CUSTOM_TITLE_{$field_id}"]);
					$field_required = in_array($field_id, $arResult['PROPERTY_REQUIRED']) ? ' <span class="starrequired">*</span>' : '';

					if (intval($field_id) > 0)
					{
						if ($arField['PROPERTY_TYPE'] == 'T' && $arField['ROW_COUNT'] == 1)
							$arField['PROPERTY_TYPE'] = 'S';

						elseif ($arField['ROW_COUNT'] > '1' && ($arField['PROPERTY_TYPE'] == 'S' || $arField['PROPERTY_TYPE'] == 'N'))
							$arField['PROPERTY_TYPE'] = 'T';
					}

					elseif ($field_id == 'TAGS' && CModule::IncludeModule('search'))
						$arField['PROPERTY_TYPE'] = 'TAGS';

					$input_type = $arField['GetPublicEditHTML'] ? 'USER_TYPE' : $arField['PROPERTY_TYPE'];
					$input_num  = 1;

					if ($arField[$field_id]['MULTIPLE'] == 'Y')
					{
						$input_num = ($arParams['ID'] > 0 || count($arResult['ERRORS']) > 0) ? count($arResult['ELEMENT_PROPERTIES'][$field_id]) : 0;
						$input_num += $arField['MULTIPLE_CNT'];
					}

				?>
				<div class="field field-<?=$field_id?>">
					<label><?=$field_label?>:<?=$field_required?></label>
					<?php

						switch ($input_type)
						{
							case 'USER_TYPE':
								for ($i = 0; $i < $input_num; $i++)
								{
									if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
									{
										$value = intval($field_id) > 0 ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["~VALUE"] : $arResult["ELEMENT"][$field_id];
										$description = intval($field_id) > 0 ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["DESCRIPTION"] : "";
									}
									elseif ($i == 0)
									{
										$value = intval($field_id) <= 0 ? "" : $arField["DEFAULT_VALUE"];
										$description = "";
									}
									else
									{
										$value = "";
										$description = "";
									}
									echo call_user_func_array($arField["GetPublicEditHTML"], array($arField, array("VALUE" => $value, "DESCRIPTION" => $description,), array("VALUE" => "PROPERTY[".$field_id."][".$i."][VALUE]", "DESCRIPTION" => "PROPERTY[".$field_id."][".$i."][DESCRIPTION]", "FORM_NAME" => "iblock_add",),));
								}
							break;

							case 'TAGS':
								$APPLICATION->IncludeComponent("bitrix:search.tags.input", "", array("VALUE" => $arResult["ELEMENT"][$field_id], "NAME" => "PROPERTY[{$field_id}][0]", "TEXT" => 'size="'.$arField["COL_COUNT"].'"',), null, array("HIDE_ICONS" => "Y"));
							break;

							case 'HTML':
								$LHE = new CLightHTMLEditor;
								$LHE->Show(array('id' => preg_replace("/[^a-z0-9]/i", '', "PROPERTY[".$field_id."][0]"), 'width' => '100%', 'height' => '200px', 'inputName' => "PROPERTY[".$field_id."][0]", 'content' => $arResult["ELEMENT"][$field_id], 'bUseFileDialogs' => false, 'bFloatingToolbar' => false, 'bArisingToolbar' => false, 'toolbarConfig' => array('Bold', 'Italic', 'Underline', 'RemoveFormat', 'CreateLink', 'DeleteLink', 'Image', 'Video', 'BackColor', 'ForeColor', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull', 'InsertOrderedList', 'InsertUnorderedList', 'Outdent', 'Indent', 'StyleList', 'HeaderList', 'FontList', 'FontSizeList',),));
							break;

							case 'T':
								for ($i = 0; $i < $input_num; $i++)
								{
									if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
										$value = intval($field_id) > 0 ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE"] : $arResult["ELEMENT"][$field_id];
									else
										if ($i == 0)
											$value = intval($field_id) > 0 ? "" : $arField["DEFAULT_VALUE"];
										else
											$value = '';
									?>
										<textarea cols="<?=$arField["COL_COUNT"]?>" rows="<?=$arField["ROW_COUNT"]?>" name="PROPERTY[<?=$field_id?>][<?=$i?>]"><?=$value?></textarea>
									<?
								}
							break;

							case "S":
							case "N":
								for ($i = 0; $i < $input_num; $i++)
								{
									if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
										$value = intval($field_id) > 0 ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE"] : $arResult["ELEMENT"][$field_id];
									else
										if ($i == 0)
											$value = intval($field_id) <= 0 ? "" : $arField["DEFAULT_VALUE"];
										else
											$value = "";
									?>
								<input type="text" name="PROPERTY[<?=$field_id?>][<?=$i?>]" size="25" value="<?=$value?>"/><br/><?
									if ($arField["USER_TYPE"] == "DateTime"):?><?
										$APPLICATION->IncludeComponent('bitrix:main.calendar', '', array('FORM_NAME' => 'iblock_add', 'INPUT_NAME' => "PROPERTY[".$field_id."][".$i."]", 'INPUT_VALUE' => $value,), null, array('HIDE_ICONS' => 'Y'));
										?><br/>
									<small><?=GetMessage("IBLOCK_FORM_DATE_FORMAT")?><?=FORMAT_DATETIME?></small><?
									endif
									?><br/><?
								}
							break;

							case "F":
								for ($i = 0; $i < $input_num; $i++)
								{
									$value = intval($field_id) > 0 ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE"] : $arResult["ELEMENT"][$field_id];
									?>
								<input type="hidden" name="PROPERTY[<?=$field_id?>][<?=$arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE_ID"] : $i?>]" value="<?=$value?>"/>
								<input type="file" size="<?=$arField["COL_COUNT"]?>" name="PROPERTY_FILE_<?=$field_id?>_<?=$arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE_ID"] : $i?>"/>
								<br/>
									<?

									if (!empty($value) && is_array($arResult["ELEMENT_FILES"][$value]))
									{
										?>
									<input type="checkbox" name="DELETE_FILE[<?=$field_id?>][<?=$arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$field_id][$i]["VALUE_ID"] : $i?>]" id="file_delete_<?=$field_id?>_<?=$i?>" value="Y"/>
									<label for="file_delete_<?=$field_id?>_<?=$i?>"><?=GetMessage("IBLOCK_FORM_FILE_DELETE")?></label>
									<br/>
										<?

										if ($arResult["ELEMENT_FILES"][$value]["IS_IMAGE"])
										{
											?>
										<img src="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>" height="<?=$arResult["ELEMENT_FILES"][$value]["HEIGHT"]?>" width="<?=$arResult["ELEMENT_FILES"][$value]["WIDTH"]?>" border="0"/>
										<br/>
											<?
										}
										else
										{
											?>
											<?=GetMessage("IBLOCK_FORM_FILE_NAME")?>: <?=$arResult["ELEMENT_FILES"][$value]["ORIGINAL_NAME"]?>
										<br/>
											<?=GetMessage("IBLOCK_FORM_FILE_SIZE")?>: <?=$arResult["ELEMENT_FILES"][$value]["FILE_SIZE"]?> b
										<br/>
										[
										<a href="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>"><?=GetMessage("IBLOCK_FORM_FILE_DOWNLOAD")?></a>]
										<br/>
											<?
										}
									}
								}

							break;

							case 'L':
								// Предоствращаем множественную привязку к разделам, если выставлена соответствующая опция
								if ($field_id == 'IBLOCK_SECTION' && $arParams['USE_SINGLE_IBLOCK_SECTION'] == 'N')
									$arField['MULTIPLE'] = 'N';

								// Скрываем уровень вложенности разделов от глаз посетителей
								if ($field_id == 'IBLOCK_SECTION' && $arParams['HIDE_IBLOCK_SECTION_LEVELS'] == 'Y')
									foreach ($arField['ENUM'] as &$value)
										$value['VALUE'] = trim($value['VALUE'], ' .');

								if ($arField["LIST_TYPE"] == "C") $type = $arField["MULTIPLE"] == "Y" ? "checkbox" : "radio";
								else
									$type = $arField["MULTIPLE"] == "Y" ? "multiselect" : "dropdown";

								switch ($type):
									case "checkbox":
									case "radio":
										foreach ($arField["ENUM"] as $key => $arEnum)
										{
											$checked = false;
											if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
											{
												if (is_array($arResult["ELEMENT_PROPERTIES"][$field_id]))
												{
													foreach ($arResult["ELEMENT_PROPERTIES"][$field_id] as $arElEnum)
													{
														if ($arElEnum["VALUE"] == $key)
														{
															$checked = true;
															break;
														}
													}
												}
											}
											else
											{
												if ($arEnum["DEF"] == "Y") $checked = true;
											}

											?>
										<input type="<?=$type?>" name="PROPERTY[<?=$field_id?>]<?=$type == "checkbox" ? "[".$key."]" : ""?>" value="<?=$key?>" id="property_<?=$key?>"<?=$checked ? " checked=\"checked\"" : ""?> />
										<label for="property_<?=$key?>"><?=$arEnum["VALUE"]?></label><br/>
											<?
										}
										break;

									case "dropdown":
									case "multiselect":
										?>
										<select name="PROPERTY[<?=$field_id?>]<?=$type == "multiselect" ? "[]\" size=\"".$arField["ROW_COUNT"]."\" multiple=\"multiple" : ""?>">
											<?
											if (intval($field_id) > 0) $sKey = "ELEMENT_PROPERTIES";
											else $sKey = "ELEMENT";

											foreach ($arField["ENUM"] as $key => $arEnum)
											{
												$checked = false;
												if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
												{
													foreach ($arResult[$sKey][$field_id] as $elKey => $arElEnum)
													{
														if ($key == $arElEnum["VALUE"])
														{
															$checked = true;
															break;
														}
													}
												}
												else
												{
													if ($arEnum["DEF"] == "Y") $checked = true;
												}
												?>
												<option value="<?=$key?>" <?=$checked ? " selected=\"selected\"" : ""?>><?=$arEnum["VALUE"]?></option>
												<?
											}
											?>
										</select>
											<?
										break;

								endswitch;
							break;
						}
					?>
				</div>
			<?endforeach?>

			<?if($arParams['USE_CAPTCHA'] == 'Y' && $arParams['ID'] <= 0):?>
				<label>Введите слово на картинке:<span class="starrequired">*</span></label>
				<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>"/>
				<img class="bx-vTable-captchaPicture" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" width="180" height="40" alt="CAPTCHA"/>
				<div class="bx-vTable-captchaArrow">&rarr;</div>
				<input type="text" name="captcha_word" maxlength="50" value="">
				<br clear="all">
			<?endif?>
		<?endif?>

		<input type="submit" name="iblock_submit" value="<?=$arParams['SUBMIT_NAME']?>"/>
		<?if(strlen($arParams["LIST_URL"]) > 0 && $arParams["ID"] > 0):?>
			<input type="submit" name="iblock_apply" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>"/><br/>
			<a href="<?=$arParams["LIST_URL"]?>"><?=GetMessage("IBLOCK_FORM_BACK")?></a>
		<?endif?>
	</form>
</div>