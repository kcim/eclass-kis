<script>
kis.message.compose_init({
    used_quota: '<?=$used_quota?>',
    total_quota: '<?=$total_quota?>',
    remain_quota: '<?=$remain_quota?>'
},{
    areyousuretodelete: '<?=$kis_lang['areyousureto']?> <?=$kis_lang['discardthedraft']?>?',
    draftsavedat: '<?=$kis_lang['draftsavedat']?>',
    uploadfileconfirm: '<?=$kis_lang['uploadfileconfirm']?>?',
    sendingmail: '<?=$kis_lang['sendingmail']?>',
    pleaseselectrecipients: '<?=$kis_lang['pleaseselectrecipients']?>!',
    sendmailwithoutsubject: '<?=$kis_lang['areyousureto']?><?=$kis_lang['sendmailwithoutsubject']?>?',
    attachmentsizeexceed: '<?=$kis_lang['attachmentsizeexceed']?>'
});
</script>

<div class="mail_list_filter">
    <div class="mail_tool">
	<a href="#" class="btn_trash" <?=!$draft['id']?'style="display:none"':''?> title="<?$kis_lang['delete']?>"></a>
    </div>
    <div class="mail_form_btn">
       <input class="formbutton" value="<?=$kis_lang['send']?>" type="button">
       <input class="formsubbutton mail_save_draft" value="<?=$kis_lang['saveasdraft']?>" type="button">
       <input class="formsubbutton mail_back" value="<?=$kis_lang['back']?>" onclick="history.go(-1)" type="button">
    </div>
    <div class="mail_save_time"></div>
                         
    <p class="spacer"></p>
</div>

<form class="mail_compose_form">
<div class="mail_compose_table_board" id="attach_file_area">
    <table class="mail_compose_table">
	<tr class="recipients">
	    <td class="field_title"><?=$kis_lang['recipients']?></td>
	    <td>
		<div class="mail_to">
		    <div class="mail_to_list">
		    <? kis_ui::loadTemplate('mail_users',array('users'=>$draft['recipients'], 'form_name'=>'recipients')) ?>
		    </div>
		    <div class="mail_to_btn"><div class="mail_icon_form">
			<a href="#" class="btn_select_ppl"><?=$kis_lang['select']?>
			    <span class="target" style="display:none">recipients</span>
			</a>
			<? if (!$draft['cc_recipients']): ?>
			<a href="#" class="btn_add add_cc"><?=$kis_lang['cc']?></a>
			<? endif; ?>
			<? if (!$draft['bcc_recipients']): ?>
			<a href="#" class="btn_add add_bcc"><?=$kis_lang['bcc']?></a>
			<? endif; ?>
		    </div></div>
		</div>
	    </td>
	</tr>
	<tr class="cc_recipients" <?=$draft['cc_recipients']?'':'style="display:none"'?>>
	    <td class="field_title"><?=$kis_lang['cc']?></td>
	    <td>
		<div class="mail_to">
		    <div class="mail_to_list">
		    <? kis_ui::loadTemplate('mail_users',array('users'=>$draft['cc_recipients'], 'form_name'=>'cc_recipients')) ?>
		    </div>
		    <div class="mail_to_btn"><div class="mail_icon_form">
			<a href="#" class="btn_select_ppl">
			    <?=$kis_lang['select']?>
			    <span class="target" style="display:none">cc_recipients</span>
			</a>
		    </div></div>
		</div>
	    </td>
	</tr>
	<tr class="bcc_recipients" <?=$draft['bcc_recipients']?'':'style="display:none"'?>>
	    <td class="field_title"><?=$kis_lang['bcc']?></td>
	    <td>
		<div class="mail_to">
		    <div class="mail_to_list">
		    <? kis_ui::loadTemplate('mail_users',array('users'=>$draft['bcc_recipients'], 'form_name'=>'bcc_recipients')) ?>
		    </div>
		    <div class="mail_to_btn"><div class="mail_icon_form">
			<a href="#" class="btn_select_ppl">
			    <?=$kis_lang['select']?>
			    <span class="target" style="display:none">bcc_recipients</span>
			</a>
		    </div></div>
		</div>
	    </td>
	</tr>
	<tr>
	    <td class="field_title"><?=$kis_lang['subject']?></td>
	    <td><input class="mail_title" name="subject" value="<?=$draft['subject']?>" type="text"></td>
	</tr>
	
	<tr>
	    <td>
		<?=$kis_lang['attachment']?>
	    </td>
	    <td>
		<div class="mail_icon_form attachment_list">
		    <a href="#" id="mail_attach" class="btn_add"><?=$kis_lang['add']?></a>
		    
		    <? foreach ((array)$draft['attachments'] as $attachment): ?>
		    <div class="attachment"><a class="btn_attachment" href="apps/message/ajax.php?action=getfile&file_id=<?=$attachment['id']?>"><?=$attachment['name']?></a>
			<a href="" class="btn_remove" id="remove_<?=$attachment['id']?>">
			    <span style="display:none;" class="file_size"><?=$attachment['size']?></span>
			</a>
		    </div>
		    <? endforeach; ?>
		</div>
	    </td>
	</tr>

	<tr>
	    <td colspan="2">
		<div class="mail_icon_form">
		    <input id="is_important" name="is_important" <?=$draft['is_important']?'checked':''?> value="1" type="checkbox">
		    <label for="is_important">
			<span class="title_mail_important"><?=$kis_lang['important']?></span>
		    </label>
		</div>
		<div class="mail_icon_form">
		    <input id="is_notification" name="is_notification" <?=$draft['is_notification']?'checked':''?> value="1" type="checkbox">
		    <label for="is_notification">
			<span class="title_mail_notification"><?=$kis_lang['notification']?></span>
		    </label>
		</div>
	    </td>
	</tr>
		    
    </table>
    <p class="spacer"></p>
	      
</div>
<p class="spacer"></p>


<div class="mail_compose_area">
<textarea id="editor_content" name="message"><?=$draft['message']?></textarea>
</div>

<input type="hidden" name="mail_id" value="<?=$draft['id']?>"/>
<p class="spacer"></p>
</form>

<form class='mail_select_user'>
    <h2><?=$kis_lang['findusers']?></h2>
    
    <?=$kis_lang['keyword']?>: <input type="text" name="keyword" style="float:right" value=""/>
    <p class="spacer"></p>
    <?=$kis_lang['group']?>: <select name="user_group" style="float:right" >
	<option value=""><?=$kis_lang['all']?> <?=$kis_lang['groups']?></option>
	<? foreach ($groups as $group): ?>
	<option value="<?=$group['group_id']?>"><?=$group['group_name_'.$lang]?></option>
	<? endforeach; ?>
    </select>
    <p class="spacer"></p>
    <?=$kis_lang['identity']?>:
    <label for="user_type_4"><?=$kis_lang['parent']?></label><input type="radio" id="user_type_4" name="user_type" value='4'/>
    <label for="user_type_3"><?=$kis_lang['staff']?></label><input type="radio" id="user_type_3" name="user_type" value='3'/>
    <label for="user_type_2"><?=$kis_lang['student']?></label><input type="radio" id="user_type_2" name="user_type" value='2'/>
    <label for="user_type_1"><?=$kis_lang['teacher']?></label><input type="radio" id="user_type_1" name="user_type" checked value='1'/>
    <input type="hidden" name="target" value=""/>
    <input type="hidden" name="exclude_list" value=""/>
    <p class="spacer"></p>
    <div class="button">  
	<input class="formbutton" value="<?=$kis_lang['search']?>" type="submit"/>
	<input class="formsubbutton" value="<?=$kis_lang['close']?>" type="submit"/>
    </div>
    
    <a class="mail_select_all" href="#"><?=$kis_lang['addall']?></a>
    <p class="spacer"></p>
    <div class="search_results">
    </div>
    
    
</form>

                    