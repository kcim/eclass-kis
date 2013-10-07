<script>
$(function(){
    kis.eattendance.takeattendance_students_init({
	date: "<?=$date?>",
	apm: "<?=$apm?>"
    },{
	present: '<?=$kis_lang['present']?>',	
	absent: '<?=$kis_lang['absent']?>',
	late: '<?=$kis_lang['late']?>',
	earlyleave: '<?=$kis_lang['earlyleave']?>',
	lateandearlyleave: '<?=$kis_lang['lateandearlyleave']?>',
	outing: '<?=$kis_lang['outing']?>'
    });
});
</script>

<div class="main_content_detail">
    <p class="spacer"></p>
                      
    <form class="filter_form">
	<div id="table_filter">
	
	    <input name="date" readonly value="<?=$date?>" size="15" type="text"/>
	   
	    <select name="time">
		<option value="am" <?=$time=='am'?'selected':''?>>AM</option>
		<option value="pm" <?=$time=='pm'?'selected':''?>>PM</option>
	    </select>
	    <select name="class_id">
		<? foreach ($classes as $class): ?>
		    <option <?=$class['class_id']==$class_id?'selected':''?> value="<?=$class['class_id']?>"><?=$class['class_name_'.$lang]?></option>
		
		<? endforeach; ?>
	    </select>
	    <input name="" class="formsmallbutton" value="<?=$kis_lang['go']?>" type="submit"/>
	</div>
	<div class="search"><!--<a href="#">Advanced</a>-->
	    <input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	</div>
    </form>
    <p class="spacer"></p>
    <div class="table_board">
	<? if ($students): ?>
        <div class="common_table_tool">
	    <a class="tool_set edit" style="display:none" href="#"><?=$kis_lang['setabsenttopresent']?></a>
	    <a href="#" class="view tool_edit"><?=$kis_lang['edit']?></a>
	    <a href="#" class="edit tool_edit" style="display:none"><?=$kis_lang['finish']?></a>
	</div>
	<? endif; ?>
        <table class="common_table_list edit_table_list">
	    <colgroup>
		<col style="width:3%"/>
		<col style="width:15%"/>
		<col style="width:15%"/>
		<col style="width:11%"/>
		<col style="width:11%"/>	
		<col style="width:15%"/>
		<col style="width:15%"/>
		<col style="width:15%"/>
	    </colgroup>
	    <thead>
		<tr>
		    <th><? kis_ui::loadSortButton('user_class_number','#', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton('user_name_'.$lang,'student', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton($apm.'_status','status', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton('in_school_time','in', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton('leave_school_time','out', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton($apm.'_reason','reason', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton($apm.'_remark','remark', $sortby, $order)?></th>
		    <th><? kis_ui::loadSortButton($apm.'_modified','lastupdated', $sortby, $order)?></th>
		</tr>
	    </thead>
	    <tbody>
		
	<? if ($students): ?>
		<? foreach ($students as $i=>$student): ?>
		
		<tr class="attendance<?=$student[$apm.'_status']?>">
		    <td><?=$student['user_class_number']?></td>
		    <td><div class="stu_photo_name">
			<? if ($student['user_photo']): ?>
			<img src="<?=$student['user_photo']?>"/>
			<? endif; ?>
			<input type="hidden" class="student_id" value="<?=$student['user_id']?>"/>
			<input type="hidden" class="date" value="<?=$date?>"/>
			<span><?=$student['user_name_'.$lang]?></span></div></td>
		    <td>
			<span class="view status_view">
			<?=$student[$apm.'_status']? $kis_lang[$student[$apm.'_status']]:'--'?>
			</span>
			<span class="edit" style="display:none">
			<select class="status">
			    <option <?=$student[$apm.'_status']=='present'?'selected':''?> value="present"><?=$kis_lang['present']?></option>
			    <option <?=$student[$apm.'_status']=='absent'?'selected':''?> value="absent"><?=$kis_lang['absent']?></option>
			    <option <?=$student[$apm.'_status']=='late'?'selected':''?> value="late"><?=$kis_lang['late']?></option>
			    <option <?=$student[$apm.'_status']=='earlyleave'?'selected':''?> value="earlyleave"><?=$kis_lang['earlyleave']?></option>
			    <option <?=$student[$apm.'_status']=='lateandearlyleave'?'selected':''?> value="lateandearlyleave"><?=$kis_lang['lateandearlyleave']?></option>
			    <option <?=$student[$apm.'_status']=='outing'?'selected':''?> value="outing"><?=$kis_lang['outing']?></option>
			</select>
			</span>
		    </td>
		    <td>
			<span class="view in_school_time_view">
			<?=$student['in_school_time']&&!in_array($student[$apm.'_status'],array('outing','absent'))?$student['in_school_time']:'--'?>
			</span>
			<span class="edit" style="display:none">
			    <div class="absent_hide" <?=in_array($student[$apm.'_status'],array('outing','absent'))?'style="display:none"':''?>>
				<select class="in_school_hour">
				    <? for ($i=0; $i<=23; $i++): ?>
				    <option <?=$i==$student['in_school_hour']?'selected':''?> value="<?=sprintf('%02d',$i)?>"><?=sprintf('%02d',$i)?></option>
				    <? endfor; ?>
				</select> :
				<select class="in_school_min">
				    <? for ($i=0; $i<=59; $i++): ?>
				    <option <?=$i==$student['in_school_min']?'selected':''?> value="<?=sprintf('%02d',$i)?>"><?=sprintf('%02d',$i)?></option>
				    <? endfor; ?>
				</select>
			    </div>
			</span>
		    </td>
		    <td>
			<span class="view leave_school_time_view">
			<?=$student['leave_school_time']&&!in_array($student[$apm.'_status'],array('outing','absent'))?$student['leave_school_time']:'--'?>
			</span>
			<span class="edit" style="display:none">
			    <div class="absent_hide" <?=in_array($student[$apm.'_status'],array('outing','absent'))?'style="display:none"':''?>>
				<select class="leave_school_hour">
				    <? for ($i=0; $i<=23; $i++): ?>
				    <option <?=$i==$student['leave_school_hour']?'selected':''?> value="<?=sprintf('%02d',$i)?>"><?=sprintf('%02d',$i)?></option>
				    <? endfor; ?>
				</select> :
				<select class="leave_school_min">
				    <? for ($i=0; $i<=59; $i++): ?>
				    <option <?=$i==$student['leave_school_min']?'selected':''?> value="<?=sprintf('%02d',$i)?>"><?=sprintf('%02d',$i)?></option>
				    <? endfor; ?>
				</select>
			    </div>
			</span>
		    </td>
		    <td>
			<span class="view reason_view">
			<?=$student[$apm.'_reason']&&in_array($student[$apm.'_status'],array('outing','absent'))?$student[$apm.'_reason']:'--'?>
			</span>
			<span class="edit" style="display:none">
			    <textarea class="present_hide reason" <?=$student[$apm.'_status']=='present'?'style="display:none;width:100%"':'style="width:100%"'?>><?=$student[$apm.'_reason']?></textarea>
			</span>
		    </td>
		    <td>
			<span class="view remark_view">
			<?=$student[$apm.'_remark']?$student[$apm.'_remark']:'--'?>
			</span>
			<span class="edit" style="display:none">
			    <textarea class="remark" style="width:100%"><?=$student[$apm.'_remark']?></textarea>
			</span>
		    </td>
		    <td>
			
			<span class="date_time" <?=$student[$apm.'_modified_by_'.$lang]?'':'style="display:none"'?>>
			    <span class="modified_view"><?=$student[$apm.'_modified']?></span>
			    <em> <?=$kis_lang['by']?> <span class="modified_by_view"><?=$student[$apm.'_modified_by_'.$lang]?></span></em>
			</span>
			
		    </td>
		</tr>
		
		<? endforeach; ?>
		
	    </tbody>
	</table>
	
	 <? else: ?>
	</tbody></table>
	    <? kis_ui::loadNoRecord() ?>
	<? endif; ?>
        <p class="spacer"></p><p class="spacer"></p><br>
    </div>
</div> 