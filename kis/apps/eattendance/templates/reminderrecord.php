<script>
kis.eattendance.remainderrecord_init({areyousuretoremovethisrecord: '<?=$kis_lang['areyousureto']?><?=$kis_lang['removethisrecord']?>?'})
</script>

<div class="main_content_detail">
    <p class="spacer"></p>
    <div class="Content_tool"><a href="#/apps/eattendance/reminderrecord/create/" class="new"><?=$kis_lang['new']?></a></div>
    <p class="spacer"></p>
    <form class="filter_form">
	<div id="table_filter">
	    <select name="status" class="auto_submit">
		<option value=""><?=$kis_lang['all']?></option>
		<option value="1" <?=$status=='1'?'selected':''?>><?=$kis_lang['past']?></option>
		<option value="2" <?=$status=='2'?'selected':''?>><?=$kis_lang['coming']?></option>
	    </select>
	</div>
	<div class="search">
	    <input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	</div>
    </form>
    <p class="spacer"></p>
    <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
    

    <div class="table_board">
	
	
	<table class="common_table_list edit_table_list">
	    <colgroup>
		
		<col style="width:16%"/>
		<col style="width:17%"/>
		<col style="width:17%"/>
		<col style="width:43%"/>
		<col style="width:8%"/>
	    </colgroup>
	    <tr>
		
		<th><? kis_ui::loadSortButton('date','reminderdate', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('student_user_name_'.$lang,'studentname', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('teacher_user_name_'.$lang,'correspondingteacher', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('reason','reason', $sortby, $order)?></th>
		<th>&nbsp;</th>
	    </tr>
		
	    <? if ($reminders): ?>
		<? foreach ($reminders as $i=>$reminder): ?>
		<tr class="edit_table_row">
		   
		    <td><span <?=$reminder['is_past_record']?'style="color:gray"':''?>><?=$reminder['date']?></span></td>
		    <td><?=$reminder['student_user_name_'.$lang]?></td>
		    <td><?=$reminder['teacher_user_name_'.$lang]?></td>
		    <td><?=$reminder['reason']?></td>
		    <td>
			<div class="table_row_tool">
			    <a title="<?=$kis_lang['edit']?>" class="edit_dim" href="#/apps/eattendance/reminderrecord/edit/?reminder_id=<?=$reminder['id']?>"></a>
			    <a title="<?=$kis_lang['edit']?>" class="delete_dim" href="#">
				<input type="hidden" class="reminder_id" value="<?=$reminder['id']?>"/>
			    </a>
			</div>
		    </td>
		</tr>
		<? endforeach; ?>
		
	    </table>
		<? else: ?>
		
	    </table>
	    <? kis_ui::loadNoRecord() ?>
	    <? endif; ?>
	
	<p class="spacer"></p>
    </div>
    <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
</div>
                    