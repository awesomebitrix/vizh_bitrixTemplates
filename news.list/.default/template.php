<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

	if (empty($arResult['ITEMS']))
		return;

?>

<?if($arParams['DISPLAY_TOP_PAGER']):?>
	<?=$arResult['NAV_STRING']?>
<?endif?>

<ul class="news-list">
	<?foreach($arResult['ITEMS'] as $arElement):?>
		<?php

			$this->AddEditAction(
				$arElement['ID'],
				$arElement['EDIT_LINK'],
				CIBlock::GetArrayByID($arElement['IBLOCK_ID'], 'ELEMENT_EDIT')
			);

			$this->AddDeleteAction(
				$arElement['ID'],
				$arElement['DELETE_LINK'],
				CIBlock::GetArrayByID($arElement['IBLOCK_ID'], 'ELEMENT_DELETE'),
				array('CONFIRM' => 'Будет удалена вся информация, связанная с этой записью. Продолжить?')
			);

			/*
			 * Так как, обычно, текст анонса не должен позволять редакторам использовать разметку HTML,
			 * а стандартное поле описания для анонса позволяет легко переключиться на визуальный редактор,
			 * то было решено использовать свойство DESCRIPTION (текстовое поле у которого указана высота).
			 *
			 * Если поле DESCRIPTION определено, то его значение берётся в качестве текста анонса,
			 * даже если оно пусто.
			 */

			if (isset($arElement['PROPERTIES']['DESCRIPTION']))
				$arElement['PREVIEW_TEXT'] = $arElement['PROPERTIES']['DESCRIPTION']['VALUE'];

		?>
		<li id="<?=$this->GetEditAreaId($arElement['ID'])?>">
			<?php

				if ($arParams['DISPLAY_PICTURE'] != 'N' && $arElement['PREVIEW_PICTURE']['SRC'])
					echo CFile::ShowImage(
						$arElement['PREVIEW_PICTURE']['SRC'],
						$arElement['PREVIEW_PICTURE']['WIDTH'],
						$arElement['PREVIEW_PICTURE']['HEIGHT'],
						sprintf('class="news-image" alt="%s" title="%1$s"', $arElement['NAME']),
						(!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arElement['DETAIL_TEXT'] && $arElement['USER_HAVE_ACCESS'])) ? $arElement['DETAIL_PAGE_URL'] : false
					);

			?>

			<div class="news-item">
				<?if($arParams['DISPLAY_DATE'] != 'N' && $arElement['DISPLAY_ACTIVE_FROM']):?>
					<span class="news-date"><?=$arElement['DISPLAY_ACTIVE_FROM']?></span>
				<?endif?>

				<?if($arParams['DISPLAY_NAME'] != 'N' && $arElement['NAME']):?>
					<?if(!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arElement['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS'])):?>
						<a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$arElement['NAME']?></a>
					<?else:?>
						<?=$arElement['NAME']?>
					<?endif?>
				<?endif?>

				<?if($arParams['DISPLAY_PREVIEW_TEXT'] != 'N' && $arElement['PREVIEW_TEXT']):?>
					<p><?=$arElement['PREVIEW_TEXT']?></p>
				<?endif?>
			</div>
		</li>
	<?endforeach?>
</ul>

<?if($arParams['DISPLAY_TOP_PAGER']):?>
	<?=$arResult['NAV_STRING']?>
<?endif?>
