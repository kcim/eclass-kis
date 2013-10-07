<script>
kis.message.settings_init();
</script>
<form id="mail_settings">
<div class="table_board">

    <table class="form_table"><tbody>
    <tr>
	<td>
	<?=$kis_lang['daysintrash']?>
	</td>
    </tr>
    <tr>
	<td>
	    <select name="days_in_trash">
		<? foreach (kis_message::$days_in_trash_option as $day): ?>
		<option <?=$settings['days_in_trash']==$day?'selected':''?> value="<?=$day?>"><?=$day==-1?$kis_lang['dontremove']:$day.' '.$kis_lang['days']?></option>
		<? endforeach; ?>
	    </select>

	</td>
    </tr>
    <tr>
	<td colspan="2">
	<?=$kis_lang['pleasefillinyoursignature']?>
	</td>
    </tr>
    <tr>
	<td colspan="2">
	    <textarea id="editor_content" name="signature"><?=$settings['signature']?></textarea>
	</td>
    </tr>
     
    </tbody></table>
    <p class="spacer"></p>
</div>
<div class="edit_bottom">         
    <input id = "submit" name = "submit" type="submit"  value="<?=$kis_lang['save']?>" class="formbutton" name="submit3">
<!--<input type="button" value="<?=$kis_lang['back']?>" onclick="history.go(-1)" class="formsubbutton" name="submit2">-->
</div>
</form>