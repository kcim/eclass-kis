<script>
kis.sms.message_new_init();
</script>

<div class="main_content_detail">
    <p class="spacer"></p>
    <div class="navigation_bar">
	<a href="#/apps/sms/"><?=$kis_lang['messages']?></a><span><?=$kis_lang['new'].' '.$kis_lang['message']?></span>
    </div>
    <p class="spacer"></p>
    <div class="table_board">
	<table class="form_table">
	    <tr>
		<td class="field_title"><?=$kis_lang['sendmessage']?> <span class="tabletextrequire">*</span></td>
		<td>
		    <select onchange="displayForm(this.value)">
			<option value="">-- <?=$kis_lang['select']?> --</option>
			<option value="USERS">to eClass users</option>
			<option value="GUARDIAN">to student guardian</option>
			<option value="MOBILE">using mobile phone numbers</option>
			<option value="CSV">using CSV file import (allow different message contents)</option>
		    </select>
		    </td>
	    </tr>
	    <tr id="divMOBILE">
		<td class="field_title"><?=$kis_lang['recipients']?> <span class="tabletextrequire">*</span></td>
		<td>
		    <textarea name="recipient" rows="5" cols="50"></textarea><br><font color="green"><?=$kis_lang['separatenumbersbysemicolon']?></font>
		</td>
	    </tr>
	    <tr id="divMESSAGE">
		<td class="field_title"><?=$kis_lang['message']?> <span class="tabletextrequire">*</span></td>
		    <td>
			<select id="MethodSelected" name="MethodSelected" onchange="this.form.Message.value=this.value; this.form.Message.focus()">
			    <option value="">-- <?=$kis_lang['select']?> <?=$kis_lang['messagetemplates']?> --</option>
			    <option value="S">Student Guardian UK</option>
			    <option value="i">Student Guardian UK Version 2.0</option>
			    <option value="Since you have not performed any action on the system for some time, the system has been logged out!">system logout alert</option>
			    <option value="通知XXX, 請留意...">學校通知</option>
			    <option value="今天某時某刻發生某事件，特此通知。">特發事件通知</option>
			</select>
			<br>
			<textarea name="message" id="message" cols="50" rows="4" wrap="virtual"></textarea><font>&nbsp;</font><input name="message_size" value="160" size="3" maxlength="3" type="TEXT">
		    </td>
		</tr>
		
	    <tr id="divNOTES">
		<td class="field_title">&nbsp;</td>
		<td><?=$kis_lang['note']?>:
		    <ol>
			<li><?=$kis_lang['note_1']?></li>
			<li><?=$kis_lang['note_2']?></li>
			<li><?=$kis_lang['note_3']?></li>
			<li><?=$kis_lang['note_4']?></li>
			<li><?=$kis_lang['note_5']?></li>
			<li><?=$kis_lang['note_6']?></li>
			<li><?=$kis_lang['note_7']?>
			    <ol type="i">
				<li><?=$kis_lang['note_7_1']?></li>
				<li><?=$kis_lang['note_7_2']?></li>
				<li><?=$kis_lang['note_7_3']?></li>
				<li><?=$kis_lang['note_7_4']?></li>
				<li><?=$kis_lang['note_7_5']?></li>
			    </ol>
			</li>
		    </ol>
		</td>
	    </tr>
	</table>
	<p class="spacer"></p><p class="spacer"></p><br>
    </div>
    <div class="edit_bottom">
	<input class="formbutton" value="<?=$kis_lang['submit']?>" type="button">
	<input class="formsubbutton" onclick="history.go(-1)" value="Cancel" type="button">
    </div>
</div>
                    