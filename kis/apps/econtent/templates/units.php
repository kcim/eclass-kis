
<div class="navigation_bar">
    <a href="#/apps/econtent"><?=$kis_lang['subjectlist']?> </a><span><?=$kis_lang[$subject]?></span></div><div id="table_filter">
    
    <form id="filter_form">    
	<select name="type">
	<option value=""><?=$kis_lang['all']?></option>
	<? foreach (array_keys($kis_config['econtent'][$subject]) as $unit_title): ?>
	    <option value="<?=$unit_title?>" <?=$type==$unit_title?'selected="selected"':''?> ><?=$kis_lang[$unit_title]?></option>
	<? endforeach; ?>
	</select>
    </form>
</div>
<p class="spacer"></p>


<p class="spacer"></p>
<ul class="subject_list">
    <? foreach ($units as $unit_title=>$unit): ?>
	<? foreach ($unit as $item): ?>
	    <li class="<?=$item['image']?>">
		<a target="_blank" href="<?=$item['href']?>">
		    <span><i><?=$kis_lang[$unit_title]?></i><?=$kis_lang[$item['title']]?></span>
		</a>
	    </li>
	<? endforeach; ?>
    <? endforeach; ?>

</ul>

<p class="spacer"></p>      