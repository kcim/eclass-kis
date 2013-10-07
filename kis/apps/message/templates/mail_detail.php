<script>
kis.message.mail_detail_init({
    mail_id: '<?=$mail['id']?>',
    folder: '<?=$tab?>',
    mail_real_folder: '<?=$mail['real_folder_id']?>'
},{
    areyousuretodelete: '<?=$kis_lang['areyousureto']?> <?=$kis_lang['delete']?>?'
});
</script>
<div class="mail_list_filter">
   
    <div class="mail_form_btn">
	<div class="mail_icon_form">
	    <? if ($tab=='trash'): ?>
	    <a href="#" class="btn_restore"><?=$kis_lang['restore']?></a>
	    <? else: ?>
	    <a href="#/apps/message/compose/?id=<?=$mail['id']?>&action=reply" class="btn_reply"><?=$kis_lang['reply']?></a>
	    <a href="#/apps/message/compose/?id=<?=$mail['id']?>&action=replyall" class="btn_replyall"><?=$kis_lang['replytoall']?></a>
	    <a href="#/apps/message/compose/?id=<?=$mail['id']?>&action=forward" class="btn_forward"> <?=$kis_lang['forward']?></a>
	    <? endif; ?>
	    <span class="sep">|</span>
	    <? if ($mail['previous'] && !$no_nav):?>
		<a href="#/apps/message/<?=$tab?>/<?=$mail['previous']?>/<?=$query_string?>" class="btn_previous_mail"><?=$kis_lang['previous']?></a>
	    <? endif; ?>
	    <? if ($mail['next'] && !$no_nav):?>
		<a href="#/apps/message/<?=$tab?>/<?=$mail['next']?>/<?=$query_string?>" class="btn_next_mail"><?=$kis_lang['next']?></a>
	    <? endif; ?>
	</div>
    </div>
    
    <div class="mail_tool"><a href="#" class="btn_trash" title="<?=$kis_lang['delete']?>"></a></div>
    <div id="table_filter">
    
	<select class="mail_moveto">
	    <option value=""><?=$kis_lang['moveto']?> :</option>
	    <? foreach ($user_folders as $user_folder): ?>
	    <? if ($user_folder['id']!=$mail['folder_id']): ?>
	    <option value="<?=$user_folder['id']?>" ><?=$user_folder['name']?></option>
	    <? endif; ?>
	    <? endforeach; ?>
	   		     
	</select>
	     		   
    </div>
    <? if ($mail_sent): ?>
    <span class="mail_note">
	[<?=$kis_lang['mailsentsuccessfully']?>]
    </span>
    <? endif; ?>
    <p class="spacer"></p>
</div>
<div class="mail_compose_table_board">
    <div class="mail_detail_title">
	  <div class="mail_icon">
	    <!--<a href="#" class="btn_star"></a>-->
	    <? if ($mail['is_important']): ?>
	    <span class="mail_important"></span>
	    <? endif; ?>
	  </div>
	  <h1><?=$mail['subject']?$mail['subject']:'<em>('.$kis_lang['nosubject'].')</em>'?></h1>
    </div>
    <p class="spacer"></p>
    <table class="mail_compose_table">
	<tbody>
	<tr>
	    <td class="field_title"><?=$kis_lang['sender']?></td>
	    <td><? kis_ui::loadTemplate('mail_users',array('users'=>array($mail))) ?></td>
	</tr>
	
	<? foreach (array('recipients', 'cc_recipients', 'bcc_recipients') as $type): ?>
	<? if ($mail[$type]):?>
	<tr>
	    <td class="field_title"><?=$kis_lang[$type]?></td>
	    <td><div class="mail_recipients">
	    <? kis_ui::loadTemplate('mail_users',array('users'=>array_slice($mail[$type],0,10))) ?>
	    </div>
	    <? if (sizeof($mail[$type])>10): ?>
		<a href="#" class="mail_recipients_more_btn">
		    ... (<?=(sizeof($mail[$type])-10).' '.$kis_lang['more']?>)
		</a>
		<p class="spacer"></p>
		<div class="mail_recipients mail_recipients_more" style="display:none">
		    <? kis_ui::loadTemplate('mail_users',array('users'=>array_slice($mail[$type],10))) ?>
		</div>

	    <? endif; ?>
	    </td>
	</tr>
	
	<? endif; ?>
	<? endforeach; ?>
	<tr>
	   <td class="field_title"><?=$kis_lang['date']?></td>
	   <td><?=date('Y-n-j g:i:s A', $mail['received'])?>
	   <span class="date_time"><?=date('Ym',$mail['received'])==date('Ym',$current_ts)? '('.kis_ui::getDaysAgoWord($mail['received'],$current_ts).')':''?></span></td>
	 </tr>
	 <tr>
	    <td>
		<?=$kis_lang['attachment']?>
	    </td>
	    <td>
		<div class="mail_icon_form">
		<? if ($mail['attachments']): ?>
		    <? foreach ($mail['attachments'] as $attachment): ?>
		    <div class="attachment">
			<a class="btn_attachment" href="apps/message/ajax.php?action=getfile&file_id=<?=$attachment['id']?>"><?=$attachment['name']?></a>
		    </div>
		    <? endforeach; ?>
		<? else: ?>
		    --
		<? endif; ?>
		</div>
	    </td>
	 </tr>
    </tbody></table>
    <p class="spacer"></p>
	     
</div>
<p class="spacer"></p>
<div class="mail_compose_area">
    <div class="mail_compose_content"><?=$mail['message']?></div>
</div>
<? if ($mail['is_notification']): ?>
    <div class="table_board mail_reply_table_board">
	<table class="mail_table_list">
	    <? if ($mail['is_original_copy']): ?>
    
	    <colgroup>
		<col style="width:2%"/>
		<col style="width:18%"/>
		<col style="width:60%"/>
		<col style="width:20%"/>
	    </colgroup>
	    <tr>
		<th width="10">&nbsp;</th>
		<th><?=$kis_lang['recipients']?></th>
		<th><?=$kis_lang['message']?> </th>
		<th><?=$kis_lang['lastreaddate']?></th>
	    </tr>
		
	    <? foreach (array('recipients', 'cc_recipients', 'bcc_recipients') as $type): ?>			       
	    <? foreach ($mail[$type] as $recipient):?>
	    <tr <?=!$recipient['is_read']?'class="mail_unread"':''?>>
		<td>
		    <? if ($recipient['message']): ?>
			<div class="mail_icon"><span class="mail_replied"></span></div>
		    <? endif; ?>
		</td>
		<td>
		    <?=$recipient['user_name_'.$lang]?$recipient['user_name_'.$lang]:$recipient['user_name_en']?> <?=$recipient['class_name']?'('.$recipient['class_name'].')':''?>
    
		</td>
		<td>
		    <?=$recipient['message']?$recipient['message']:'--'?>
		</td>
		<td>
		    <?=$recipient['is_read']?date('Y-n-j g:i:s A', $recipient['modified']):'<em>('.$kis_lang['unread'].')</em>'?>
		</td>
		
	    </tr>
	    <? endforeach; ?>
	    <? endforeach; ?>
	
	    <? else: ?>
	
	    <tr><th>
		<form class="mail_reply_form">
		    <?=$kis_lang['yourreply']?>:
		    <? if ($mail['reply_message']) :?>
			<textarea readonly name="message"><?=$mail['reply_message']?></textarea>
		    <? else: ?>
			
			<textarea name="message"><?=$mail['reply_message']?></textarea>
			<div class="edit_bottom">         
			    <input type="submit" value="<?=$kis_lang['submit']?>" class="formbutton" name="submit3">
			</div>
			<input type="hidden" name="mail_id" value="<?=$mail['id']?>"/>
		    <? endif; ?>
		</form>
	    </th></tr>
     
	    <? endif; ?>
	</table>
	
	<p class="spacer"></p>
    
    </div>
<? endif; ?>
                    