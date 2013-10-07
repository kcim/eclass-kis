<? foreach ((array)$users as $i=>$user): ?>
    <span class="mail_user">
	
	<div class="mail_user_name">
	<?=$user['user_name_'.$lang]?$user['user_name_'.$lang]:$user['user_name_en']?>
	</div>
	
	<div class="mail_icon_form">
	    <? if ($form_name): ?>
	    <input type="hidden" name="<?=$form_name?>[]" value="<?=$user['user_id']?>"/>
	    <? endif; ?>

	    <a href="#" class="btn_add" id="add_<?=$user['user_id']?>"></a>

	    <a href="#" class="btn_remove" id="remove_<?=$user['user_id']?>"></a>

	</div>
	<span class="comma"><?=$i==sizeof($users)-1?'':','?></span>
	<p class="spacer"></p>
	
	<div class="mail_user_detail">
	    <? if ($user['user_photo']): ?>
		<img src="<?=$user['user_photo']?>"/>
	    <? endif; ?>
	    <div>
	    <?=$user['user_name_b5']?$user['user_name_b5'].'<br/>':''?>  <?=$user['user_name_en']?><br/>
	    
	    (<?=$user['user_class_name']?$user['user_class_name'].' ':''?><?=$kis_lang['userrecordtype_'.$user['user_type']]?>)
	    
	    </div>
	    <a href="#/apps/message/compose/?recipients[]=<?=$user['user_id']?>"><?=$kis_lang['sendmail']?></a>
	    <p class="spacer"></p>
	</div>
    </span>
<? endforeach; ?>
