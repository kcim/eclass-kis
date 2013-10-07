<div class="common_table_bottom">
	<div class="record_no"><?=$kis_lang['records']?> <?=$from?> - <?=$to?>, <?=$kis_lang['total']?> <?=$total?></div>
	<div class="page_no">
		<a href="#<?=$page > 1? $page-1: $page?>" class="prev">&nbsp;</a>
		<span> <?=$kis_lang['page']?></span>
		
		<span><select class="change_page">
		<? for ($i = 0; $i < $total_pages; $i++): ?>
		<option value="<?=$i+1?>" <?=$page==$i+1? 'selected': ''?>><?=$i+1?></option>
		<? endfor; ?>
		</select></span>
		
		<a href="#<?=$page < $total_pages? $page+1: $page?>" class="next">&nbsp;</a>
		<span> | <?=$kis_lang['display']?> </span>
		
		<span><select class="change_amount">
		<? foreach ($amount_options as $amount_option): ?>
		<option value="<?=$amount_option?>" <?=$amount==$amount_option? 'selected':''?>><?=$amount_option?></option>
		<? endforeach; ?>
		</select></span>
		
		<? if (!$page_form_loaded): ?>
		<form class="filter_form page_form">
		    <input name="page" value="1" type="hidden"/>
		    <input name="amount" value="<?=$amount?>" type="hidden"/>
		</form>
		<? endif; ?>
		
		<span>/ <?=$kis_lang['page']?></span>
	</div>
</div>

<p class="spacer"></p>