<a href='#<?=$field?>,<?=$torder?>' class='sortbutton <?=$corder?>'><?=$kis_lang[$title]?></a>
<? if ($sortby==$field): ?>
<form id="sort_form" class="filter_form">
    <input name="sortby" value="<?=$field?>" type="hidden"/>
    <input name="order" value="<?=$order?>" type="hidden"/>
</form>
<? endif; ?>