<?php

	if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

?>
<div class="bx-auth-reg">
	<?if($USER->IsAuthorized()):?>
		<p><?=GetMessage('MAIN_REGISTER_AUTH')?></p>
	<?else:?>
		<?php

			if (count($arResult['ERRORS']) > 0)
			{
				foreach ($arResult['ERRORS'] as $key => $error)
					if (intval($key) == 0 && $key !== 0)
						$arResult['ERRORS'][$key] = str_replace('#FIELD_NAME#', '&quot;'.GetMessage("REGISTER_FIELD_{$key}").'&quot;', $error);

				ShowError(implode('<br />', $arResult['ERRORS']));
			}

			elseif ($arResult['USE_EMAIL_CONFIRMATION'] === 'Y')
				echo '<p>'.GetMessage('REGISTER_EMAIL_WILL_BE_SENT').'</p>'

		?>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
			<?endif?>

			<?if($arParams['SHOW_SECTION_HEADERS'] != 'N'):?>
				<h2><?=GetMessage("AUTH_REGISTER")?></h2>
			<?endif?>
			<?foreach($arResult["SHOW_FIELDS"] as $field_id):?>
				<?php
 					$field_label = '';
					$field_required = $arResult["REQUIRED_FIELDS_FLAGS"][$field_id] == 'Y' ? ' <span class="starrequired">*</span>' : '';

					if ($arParams['USE_EMAIL_AS_LOGIN'] == 'Y')
					{
						switch ($field_id)
						{
							case 'LOGIN':
								$field_label = GetMessage("REGISTER_FIELD_EMAIL");
							break;

							case 'EMAIL':
								?>
									<input type="hidden" name="REGISTER[EMAIL]" value="">
									<script type="text/javascript">
										$(".bx-auth-reg form").live('submit', function(){
											var email = $(".bx-auth-reg input[name='REGISTER[LOGIN]']").attr("value");
											$(".bx-auth-reg input[name='REGISTER[EMAIL]']").attr("value", email);
										})
									</script>
								<?
							continue(2);
						}
					}

				?>
				<?if($field_id == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true):?>
					<div class="field field-<?=$field_id?>">
						<label><?=!empty($field_label) ? $field_label : GetMessage("main_profile_time_zones_auto")?>:<?=$field_required?></label>
						<select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
							<option value=""><?=GetMessage("main_profile_time_zones_auto_def")?></option>
							<option value="Y"<?=$arResult["VALUES"][$field_id] == "Y" ? " selected=\"selected\"" : ""?>><?=GetMessage("main_profile_time_zones_auto_yes")?></option>
							<option value="N"<?=$arResult["VALUES"][$field_id] == "N" ? " selected=\"selected\"" : ""?>><?=GetMessage("main_profile_time_zones_auto_no")?></option>
						</select>

						<label><?=GetMessage("main_profile_time_zones_zones")?></label>
						<select name="REGISTER[TIME_ZONE]"<?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>>
							<?foreach($arResult["TIME_ZONE_LIST"] as $tz => $tz_name):?>
								<option value="<?=htmlspecialchars($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialchars($tz_name)?></option>
							<?endforeach?>
						</select>
					</div>
				<?else:?>
					<div class="field field-<?=$field_id?>">
						<label><?=!empty($field_label) ? $field_label : GetMessage("REGISTER_FIELD_".$field_id)?>:<?=$field_required?></label>
						<?php

							switch ($field_id)
							{
								// Пароль
								case "PASSWORD":
									?>
										<input size="30" type="password" name="REGISTER[<?=$field_id?>]" value="<?=$arResult["VALUES"][$field_id]?>" autocomplete="off" class="bx-auth-input">
										<?if($arResult["SECURE_AUTH"]):?>
											<span class="bx-auth-secure" id="bx_auth_secure" title="<?=GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
												<div class="bx-auth-secure-icon"></div>
											</span>
											<noscript>
												<span class="bx-auth-secure" title="<?=GetMessage("AUTH_NONSECURE_NOTE")?>">
													<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
												</span>
											</noscript>
											<script type="text/javascript">
												document.getElementById('bx_auth_secure').style.display = 'inline-block';
											</script>
										<?endif?>
									<?
								break;

								// Подтверждение пароля
								case "CONFIRM_PASSWORD":
									?>
										<input size="30" type="password" name="REGISTER[<?=$field_id?>]" value="<?=$arResult["VALUES"][$field_id]?>" autocomplete="off"/>
									<?
								break;

								// Пол
								case "PERSONAL_GENDER":
									?>
										<select name="REGISTER[<?=$field_id?>]">
											<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
											<option value="M"<?=$arResult["VALUES"][$field_id] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
											<option value="F"<?=$arResult["VALUES"][$field_id] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
										</select>
									<?
								break;

								// Страна проживания и работы
								case "PERSONAL_COUNTRY":
								case "WORK_COUNTRY":
									?>
										<select name="REGISTER[<?=$field_id?>]">
											<?foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value):?>
												<option value="<?=$value?>"<?if($value == $arResult["VALUES"][$field_id]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
											<?endforeach?>
										</select>
									<?
								break;

								// Персональный аватар и логотип работы
								case "PERSONAL_PHOTO":
								case "WORK_LOGO":
									?>
										<input size="30" type="file" name="REGISTER_FILES_<?=$field_id?>"/>
									<?
								break;

								// Заметки
								case "PERSONAL_NOTES":
								case "WORK_NOTES":
									?>
										<textarea cols="30" rows="5" name="REGISTER[<?=$field_id?>]"><?=$arResult["VALUES"][$field_id]?></textarea>
									<?
								break;

								// День рождения
								case "PERSONAL_BIRTHDAY":
									?>
										<small><?=$arResult["DATE_FORMAT"]?></small><br>
										<input size="30" type="text" name="REGISTER[<?=$field_id?>]" value="<?=$arResult["VALUES"][$field_id]?>">
										<?$APPLICATION->IncludeComponent('bitrix:main.calendar', '', array(
											'SHOW_INPUT' => 'N',
											'FORM_NAME' => 'regform',
											'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
											'SHOW_TIME' => 'N'
											), null, array("HIDE_ICONS" => "Y")
										)?>
									<?
								break;

								default:
									printf('<input size="30" type="text" name="REGISTER[%s]" value="%s"/>',
										$field_id,
										$arResult["VALUES"][$field_id]
									);
							}
						?>
					</div>
				<?endif?>
			<?endforeach?>

			<?/* Пользовательские свойства */?>
			<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
				<?if($arParams['SHOW_SECTION_HEADERS'] != 'N'):?>
					<h2><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></h2>
				<?endif?>
				<?foreach($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
					<label><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if($arUserField["MANDATORY"] == "Y"):?> <span class="required">*</span><?endif?></label>
					<?$APPLICATION->IncludeComponent("bitrix:system.field.edit", $arUserField["USER_TYPE"]["USER_TYPE_ID"], array(
							"bVarsFromForm" => $arResult["bVarsFromForm"],
							"arUserField" => $arUserField,
							"form_name" => "regform"
						), null, array("HIDE_ICONS" => "Y")
					)?>
				<?endforeach?>
			<?endif?>

			<?/* Капча */?>
			<?if($arResult['USE_CAPTCHA'] == 'Y'):?>
				<?if($arParams['SHOW_SECTION_HEADERS'] != 'N'):?>
					<h2><?=GetMessage('REGISTER_CAPTCHA_TITLE')?></h2>
				<?endif?>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA">
				<label><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></label>
				<input type="text" name="captcha_word" maxlength="50" value=""/>
			<?endif?>

			<div class="field field-submit">
				<input class="submit" type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>"/>
			</div>

			<?if($arParams['SHOW_GROUP_POLICY'] != 'N'):?>
				<p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
				<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>
			<?endif?>
		</form>
	<?endif?>
</div>