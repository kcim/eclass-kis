 <div class="left_menu"><div class="left_menu_bottom"><div class="left_menu_main">
    <div align="center">
	<? if ($top_image): ?>
	  <img class="top_image" src="<?=$top_image?>"/>
	<? else:?>
	<? endif; ?>
    </div>
    <ul class="main_menu">
	  <? foreach ($tabs as $i=>$tab): ?>
	      <? if ($tab): ?>
	      <li <?=$tab==$current_tab? 'class="selected"':''?> id="li_<?=$tab?>"><a href="<?=$i==$default_tab_index? $app_url: $app_url.$tab.'/'?>"><?=$kis_lang[$tab]?></a></li>	    
	      <? else: ?>
	      <li class="break_line"></li>
	      <? endif; ?>
	  <? endforeach; ?>
	  <p class="spacer">&nbsp;</p>
    </ul>            
<p class="spacer">&nbsp;</p>
  </div> </div> </div>