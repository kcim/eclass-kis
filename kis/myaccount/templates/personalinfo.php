<script>
$(function(){
    kis.myaccount.personalinfo_init({
	recordsupdated: '<?=$kis_lang['recordsupdated']?>'
    })
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <form>
	<div class="table_board">
	    <table class="form_table">
		<colgroup><col class="field_title"/><col class="field_c"/></colgroup>
		<tr>
		    <td class="field_title"><?=$kis_lang['loginid']?></td>
		    <td><label><?=$user_detail['user_login']?></label></td>
		    <td rowspan="4" height="140" width="100">
			
			<? if ($permission["CanUpdatePersonalPhoto"]): ?>
			<div id="<?= $permission["CanUpdatePersonalPhoto"]?'myacc_photo':'photo' ?>" class="myacc_photo">    
			    <img  <?=$user_detail['personal_photo'] ?'':'style="display:none"'?> src="<?=$user_detail['personal_photo']?>?_=<?=time()?>"/>
			<? else: ?>
			<div class="myacc_photo">    
			<? endif; ?>
			    <div class="mail_icon_form" <?=$user_detail['personal_photo']?'':'style="display:none"'?>>
				<a id = "btn_remove" href="<?=$permission["CanUpdatePersonalPhoto"]?'#':'javascript: void(0)'?>" class="btn_remove"></a>
			    </div>
			</div>
			
			
			
		    </td>
		</tr>
		<tr>
		    <td class="field_title"><?=$kis_lang['englishname']?></td>
		    <td><?=$user_detail['user_name_en']?></td>
		</tr>
		<tr>
		    <td class="field_title"><?=$kis_lang['chinesename']?></td>
		    <td><?=$user_detail['user_name_b5']?></td>
		</tr>
		<tr>
		    <td class="field_title"><?=$kis_lang['nickname']?></td>
		    <td>

			<input id="nick_name" name="nick_name" value="<?=$user_detail['nick_name']?>" class="textboxtext" type="text" <?=$permission["CanUpdateNickName"]?'':"disabled ='disabled'"?>>

			<input type="hidden" name="nick_name" value="<?=$user_detail['nick_name']?>" <?=$permission["CanUpdateNickName"]?"disabled ='disabled'":""?>/>
	
		    </td>
		</tr>
		<tr>
		    <td class="field_title"><?=$kis_lang['gender']?></td>
		    <td colspan="2">
		
			<input <?=$user_detail['gender']=='M'?'checked':''?> name="gender" value="M" id="status4" type="radio" <?=$permission["CanUpdateGender"]?'':"disabled ='disabled'"?>> 
			<label for="status4"><?=$kis_lang['male']?></label>
		
			<input <?=$user_detail['gender']=='F'?'checked':''?> name="gender" value="F" id="status5" type="radio"<?=$permission["CanUpdateGender"]?'':"disabled ='disabled'"?>>
			<label for="status5"><?=$kis_lang['female']?></label>
					
			<input type="hidden" name="gender" value="<?=$user_detail['gender']?>" <?=$permission["CanUpdateGender"]?"disabled ='disabled'":""?>/>
		    </td>

		</tr>
	    </table>
	    <p class="spacer"></p>
	</div>
	
	<? if ($permission["CanUpdatePersonalPhoto"] || $permission["CanUpdateNickName"]|| $permission["CanUpdateGender"]):?>
	<div class="edit_bottom">
	    <input id = "submitForm" class="formbutton" value="<?=$kis_lang['submit']?>" type="button">
	<!--    <input class="formsubbutton" onclick="history.go(-1)" value="<?=$kis_lang['cancel']?>" type="button">-->
	</div>
	<? endif;?>
	
    <input type="hidden" name="home_tel" value="<?=$user_detail['home_tel']?>"/>
    <input type="hidden" name="office_tel" value="<?=$user_detail['office_tel']?>"/>
    <input type="hidden" name="mobile_tel" value="<?=$user_detail['mobile_tel']?>"/>
    <input type="hidden" name="fax" value="<?=$user_detail['fax']?>"/>
    <input type="hidden" name="address" value="<?=$user_detail['address']?>"/>
    <input type="hidden" name="country" value="<?=$user_detail['country']?>"/>
    <input type="hidden" name="url" value="<?=$user_detail['url']?>"/>
    <input type="hidden" name="email" value="<?=$user_detail['email']?>"/>
    </form>
</div>   