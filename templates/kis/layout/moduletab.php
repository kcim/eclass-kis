<div class='module_tab'><ul class='main_menu'>
    <? foreach ($tabs as $i=>$tab): ?>
	<li <?=$tab==$current_tab? 'class="selected"':''?>><a href='<?=$i==$default_tab_index? $app_url: $app_url.$tab.'/'?>'>
	    <span class="tab_<?=$tab?>"><?=$kis_lang[$tab]?></span>
	</a></li>
    <? endforeach; ?>
</ul></div>
<p class="spacer"></p>