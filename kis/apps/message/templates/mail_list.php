<script>
kis.message.mail_list_init({folder: '<?=$tab?>'},{
    areyousuretodelete: '<?=$kis_lang['areyousureto']?> <?=$kis_lang['delete']?> ',
    items: ' <?=$kis_lang['items']?>'
});
</script>
<div class="mail_list_filter">
					       
			   
    <form class="filter_form">                 
	<div class="search"><!--<a href="#">Advanced</a>-->
	    <input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
	</div>
    </form>
    
    <input type="button" class="formsubbutton mail_search_advanced" value="<?=$kis_lang['advanced']?>"/>
	
   <div class="mail_tool"  style="display:none;"><a href="#" id = "btn_trash" name = "btn_trash" class="btn_trash" title="<?=$kis_lang['delete']?>"></a></div>
    <div id="table_filter" style="display:none;">
	
	
	<select id = "mail_moveto" name = "mail_moveto" class="mail_moveto">
	    <option value=""><?=$kis_lang['moveto']?> :</option>
	    <? foreach ($user_folders as $user_folder): ?>
	    <? if ($user_folder['id']!=$current_folder_id): ?>
	    <option value="<?=$user_folder['id']?>" ><?=$user_folder['name']?></option>
	    <? endif; ?>
	    <? endforeach; ?>
	</select>
	
	<? if ($tab !='draft' && $tab != 'sent'): ?>
	<select id = "mail_markas" name = "mail_markas" class="mail_markas">
	    <option value=""><?=$kis_lang['markas']?> :</option>
	    <option value="-1"> <?=$kis_lang['unread']?></option>
	    <option value="1"> <?=$kis_lang['read']?></option>
	</select>
	<? endif; ?>
		   
    </div>
    <p class="spacer"></p>
    <div class="mail_search_advanced_form" <?=$is_advanced_search? '':'style="display:none"'?>><form class="filter_form">

	     
	<table>
	    
	    <tr>
		<td class="field_title"><?=$kis_lang['sender']?></td>
		<td><input type="text" class="textboxtext" value="<?=$sender?>" name="sender"></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['recipients']?></td>
		<td><input type="text" class="textboxtext" value="<?=$recipient?>" name="recipient"></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['cc']?></td>
		<td><input type="text" class="textboxtext" value="<?=$cc?>" name="cc"></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['subject']?></td>
		<td><input type="text" class="textboxtext" value="<?=$subject?>" name="subject"></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['content']?></td>
		<td><input type="text" class="textboxtext" value="<?=$message?>" name="message"></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['withattachments']?></td>
		<td><input type="checkbox" <?=$attachment?'checked':''?> value="1" name="attachment"></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['recorddate']?></td>
		<td><input type="text" size="15" value="<?=$received_from?>" name="received_from">
		 ~ 
		<input type="text" size="15" value="<?=$received_to?>" name="received_to">
		</td>
	    </tr>
		  
	    <colgroup><col class="field_title">
		<col class="field_c">
	    </colgroup>
	</table>
	<p class="spacer"></p>
       
	<input type="hidden" name="is_advanced_search" val="0"/>
	<div class="edit_bottom">         
	    <input type="button" value="<?=$kis_lang['submit']?>" class="formbutton" name="submit3">
	    
	</div>


    </form></div>
</div>

<div class="table_board">
		    
    <table class="mail_table_list">
	<colgroup>
	    <!--<col style="width:2%"/>-->
	    <col style="width:2%"/>
	    <col style="width:2%"/>
	    <col style="width:2%"/>
	    <col style="width:20%"/>
	    <col style="width:53%"/>
	    <col style="width:10%"/>
	    <col style="width:7%"/>
	    <col style="width:2%"/>
	</colgroup>
	<tr>
	    <!--<th><div class="mail_icon"><span title="Star" class="title_star"></span></div></th>-->
	    <th><div class="mail_icon"><span title="<?=$kis_lang['important']?>" class="title_mail_important"></span></div></th>
	    <th><div class="mail_icon"><span title="<?=$kis_lang['notification']?>" class="title_mail_notification"></span></div></th>
	    <th>&nbsp;</th>
	    
	    <th><?=in_array($tab,array('sent','draft'))?$kis_lang['recipients']:$kis_lang['sender']?></th>
	    <th><?=$kis_lang['subject']?> </th>
	    <th><?=$kis_lang['date']?></th>
	    <th><?=$kis_lang['size']?>(MB)</th>
	    <th>
	     <input type="checkbox" class="checkbox_check_all">
	    </th>
	</tr>
	
	<? if ($mails): ?>				   
	<? foreach ($mails as $mail): ?>		
	<tr <?=!$mail['status']?'class="mail_unread"':''?> id="mail_row_<?=$mail['id']?>">
	    <!--<td><div class="mail_icon"><a class="btn_star" href="javascript:void(0)"></a></div></td>-->
	    <td>
		<? if ($mail['is_important']): ?>
		    <div class="mail_icon"><span title="<?=$kis_lang['important']?>" class="mail_important"></span></div>
		<? endif; ?>
	    </td>
	    <td>
		<? if ($mail['is_notification']): ?>
		    <div class="mail_icon"><span title="<?=$kis_lang['notification']?>" class="mail_notification"></span></div>
		<? endif; ?>
	    </td>
	    <td>
		<? if ($mail['status']==2): ?>
		    <div class="mail_icon"><span class="mail_replied"></span></div>
		<? endif; ?>
	    </td>
	    <td>
	    <? if (in_array($tab,array('sent','draft'))): ?>
		<? kis_ui::loadTemplate('mail_users',array('users'=>array_slice($mail['recipients'],0,3))) ?>
		<? if (sizeof($mail['recipients'])>3): ?>
		    
		    <span class="mail_recipients_more_btn">
		    ... (<?=(sizeof($mail['recipients'])-3).' '.$kis_lang['more']?>)
		    </span>
		   
		<? elseif (!$mail['recipients']): ?>
		--
		<? endif; ?>
	    <? else: ?>
		<? kis_ui::loadTemplate('mail_users',array('users'=>array($mail))) ?>
	    <? endif; ?>
	    </td>
	    <td>
		
		<? if ($tab=='draft'): ?>
		    <a class="mail_subject <?=$mail['size']?'file_attachment':''?>" href="#/apps/message/compose/?id=<?=$mail['id']?>" >
		<? else: ?>
		    <a class="mail_subject <?=$mail['size']?'file_attachment':''?>" href="#/apps/message/<?=$tab?>/<?=$mail['id']?>/<?=$query_string?>">
		<? endif; ?>
		    
		<?=$mail['subject']?$mail['subject']:'<em>('.$kis_lang['nosubject'].')</em>'?>
		
		<? if ($mail['reply_count']!=-1): ?>
		    <strong>[<?=$kis_lang['replied']?>: <?=$mail['reply_count']?>/<?=$mail['recipients_count']+$mail['cc_recipients_count']+$mail['bcc_recipients_count']?>]</strong>
		<? endif; ?>
		</a>
	    </td>
	    <td>
		<span class="date_time">
		<?=kis_ui::getSimpleDateFormat($mail['received'], $current_ts)?>
		</span>
	    </td>
	    <td width="30"><?=$mail['size']?round($mail['size']/1024,2):'--'?></td>
	    <td><input type="checkbox" value="<?=$mail['id']?>" class="checkbox_item"></td>
	</tr>
	<? endforeach; ?>
	<? else: ?>
	</table>
	     <div class="no_record">
		<?=$kis_lang['norecord']?>!
	     </div>
	<? endif; ?>
		
    </table>
    <p class="spacer"></p>
    <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>		
    <p class="spacer"></p>
</div>

                     