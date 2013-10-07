<script>
$(function(){
    kis.eattendance.reminderrecord_create_init({
	pleaseentervalid: '<?=$kis_lang['pleaseentervalid']?>',
	students: '<?=$kis_lang['students']?>',
	correspondingteacher: '<?=$kis_lang['correspondingteacher']?>'
    });
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <form class="reminder_create_form">
    <div class="table_board">
	<table class="form_table">
	    <colgroup>
		<col class="field_title">
		<col class="field_c">
	    </colgroup>
	    <tbody>
	    <tr>
		<td class="field_title"><?=$kis_lang['students']?></td>
		<td>
		    <div class="notice_type_select" style="height:auto">
						
			<ul class="search_list" style="height:100px;overflow:auto">
			
			</ul>
		    </div>
		    <div class="notice_type_search" style="height:auto">
			<?=$kis_lang['class']?>:
			<select class="search_user_class">
			    <option value="">-- <?=$kis_lang['select']?> --</option>
			    <? foreach ($classes as $class): ?>
			    <option value="<?=$class['class_id']?>"><?=$class['class_name_'.$lang]?></option>
			    <? endforeach; ?>
			    
			</select>
			
			<ul class="search_list" style="height:100px;overflow:auto">
			    
			</ul>
		    </div>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['repeat']?></td>
		<td>
		    <select name="type">
			
			<option value="single"><?=$kis_lang['single']?></option>
			<option value="daily"> <?=$kis_lang['daily']?></option>
			<option value="weekly"><?=$kis_lang['weekly']?></option>
		    </select>
		    
		</td>
	    </tr>
	    <tr class="row_date">
		<td class="field_title"><?=$kis_lang['reminderdate']?></td>
		<td>
		    <input size="10" readonly type="text" name="date" value="<?=$today_date?>"/>
		</td>
	    </tr>
	    <tr style="display:none" class="row_startdate">
		<td class="field_title"><?=$kis_lang['reminderstartdate']?></td>
		<td>
		    <input size="10" readonly type="text" name="start_date" value="<?=$today_date?>"/>
		</td>
	    </tr>
	    <tr style="display:none" class="row_enddate">
		<td class="field_title"><?=$kis_lang['reminderenddate']?></td>
		<td>
		    <input size="10" readonly type="text" name="end_date" value="<?=$today_date?>"/>
		</td>
	    </tr>
	    <tr style="display:none" class="row_weekdays">
		<td class="field_title"><?=$kis_lang['weekdays']?></td>
		<td>
		    
		    <input type="checkbox" name="sunday" value="1"/><?=$kis_lang['sunday']?>
		    <input type="checkbox" name="monday" value="1"/><?=$kis_lang['monday']?>
		    <input type="checkbox" name="tuesday" value="1"/><?=$kis_lang['tuesday']?>
		    <input type="checkbox" name="wednesday" value="1"/><?=$kis_lang['wednesday']?>
		    <input type="checkbox" name="thursday" value="1"/><?=$kis_lang['thursday']?>
		    <input type="checkbox" name="friday" value="1"/><?=$kis_lang['friday']?>
		    <input type="checkbox" name="saturday" value="1"/><?=$kis_lang['saturday']?>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['correspondingteacher']?></td>
		<td>
		    <select name="teacher">
			<option value="">-- <?=$kis_lang['select']?> --</option>

			<? foreach ($teachers as $teacher): ?>
			<option value="<?=$teacher['user_id']?>"><?=$teacher['user_name_'.$lang]?></option>
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
<ul id="search_user_template" style="display:none">
    <li class="user">
	<span></span>
	<div class="table_row_tool">
	    <a title="<?=$kis_lang['remove']?>" class="add" href="#"></a>
	    <a title="<?=$kis_lang['remove']?>" class="delete" href="#"></a>
	    <input type="hidden" name="students[]" disabled class="id" value=""/>
	</div>
	
    </li>
</ul>
                    