<script>
$(function(){
    kis.eattendance.reminderrecord_edit_init();
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <form class="reminder_edit_form">
    <div class="table_board">
	<table class="form_table">
	    <colgroup>
		<col class="field_title">
		<col class="field_c">
	    </colgroup>
	    <tbody><tr>
		<td class="field_title"><?=$kis_lang['studentname']?></td>
		<td>
		    <?=$reminder['student_user_name_'.$lang]?>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['reminderdate']?></td>
		<td>
		    <input size="10" readonly type="text" name="date" value="<?=$reminder['date']?>"/>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['correspondingteacher']?></td>
		<td>
		    <select name="teacher">
			<? foreach ($teachers as $teacher): ?>
			<option <?=$teacher['user_id']==$reminder['teacher_user_id']?'selected':''?> value="<?=$teacher['user_id']?>"><?=$teacher['user_name_'.$lang]?></option>
			<? endforeach; ?>
		    </select>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['reason']?></td>
		<td>
		    <textarea cols="50" name="reason"><?=$reminder['reason']?></textarea>
		</td>
	    </tr>
	    
	    </tbody>
	</table>
	<p class="spacer"></p>
    </div>
    <input type="hidden" name="reminder_id" value="<?=$reminder['id']?>"/>
    <div class="edit_bottom">
        <input class="formbutton" value="<?=$kis_lang['submit']?>" type="submit"/>
        <input class="formsubbutton" value="<?=$kis_lang['cancel']?>" type="button"/>
    </div>
    </form>
</div>
                    