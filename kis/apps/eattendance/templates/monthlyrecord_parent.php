<script>
kis.eattendance.monthlyrecord_parent_init({monthdetails: '<?=$kis_lang['monthdetails']?>'});
</script>
<div id="table_filter">
    <select class="year">
	
	<? for ($i = $current_date['year']-2; $i <= $current_date['year']; $i++): ?>
	    <option value="/apps/eattendance/<?=$i?>/" <?=$i==$year?'selected="selected"':''?>><?=$i?></option>
	<? endfor; ?>

    </select>
    
    <select class="month">
	
	<? for ($i = 1; $i <= ($year == $current_date['year']? $current_date['mon']: 12); $i++): ?>
	    <option value="/apps/eattendance/<?=$year?>/<?=$i?>/" <?=$i==$month?'selected="selected"':''?>><?=$kis_lang['month_'.$i]?></option>
	<? endfor; ?>

    </select>
    
</div>
      
<p class="spacer"></p>

<div class="table_board">
    <table class="common_table_list edit_table_list">
						
	<tr>
	    <th><?=$kis_lang['date']?></th>
	    <th><?=$kis_lang['status']?></th>
	    <th><?=$kis_lang['in']?> / <?=$kis_lang['out']?></th>
	    <th><?=$kis_lang['time']?></th>
	    <th><?=$kis_lang['carriedby']?></th>
	    <th><?=$kis_lang['remark']?></th>
	    <th><?=$kis_lang['recordedby']?></th>
	</tr>
	
	<? if ($attend_days):?>
	    <? foreach ($attend_days as $attend_day): ?>
	       
		<tr>
		    <td><?=$year?>-<?=$month?>-<?=$attend_day['day']?> (AM)</td>
		    <td><span class="att_<?=$attend_day['am_status']?>"><?=$kis_lang[$attend_day['am_status']]?></span></td>
		    <td>
		    <? if (($attend_day['leave_school_hour']>=12 && $attend_day['in_school_hour']>=12) || !$attend_day['am_here']): ?>
			--
		    <? else: ?>
			<? if ($attend_day['in_school_hour']<12): ?>
			    <?=$kis_lang['in']?><br/>
			<? endif; ?>
			<? if ($attend_day['leave_school_hour']<12): ?>
			    <?=$kis_lang['out']?><br/>
			<? endif; ?>
		    <? endif; ?>
		    </td>
		    <td>
		    <? if (($attend_day['leave_school_hour']>=12 && $attend_day['in_school_hour']>=12) || !$attend_day['am_here']): ?>
			--
		    <? else: ?>
			<? if ($attend_day['in_school_hour']<12): ?>
			    <?=$attend_day['in_school_time']?><br/>
			<? endif; ?>
			<? if ($attend_day['leave_school_hour']<12): ?>
			    <?=$attend_day['leave_school_time']?><br/>
			<? endif; ?>
		    <? endif; ?>
		    </td>
		    <td>--</td>
		    <td><?=$attend_day['am_remark']?$attend_day['am_remark']:'--'?></td>
		    <td><span class="date_time"><?=$attend_day['am_modified']?><em><?=$attend_day['am_modified_by_'.$lang]?></em></span></td>
		</tr>
	      
		<tr>
		    <td><?=$year?>-<?=$month?>-<?=$attend_day['day']?> (PM)</td>
		    <td><span class="att_<?=$attend_day['pm_status']?>"><?=$kis_lang[$attend_day['pm_status']]?></span></td>
		    <td>
		    <? if (($attend_day['leave_school_hour']<12 && $attend_day['in_school_hour']<12) || !$attend_day['pm_here']): ?>
			--
		    <? else: ?>
			<? if ($attend_day['in_school_hour']>=12): ?>
			    <?=$kis_lang['in']?><br/>
			<? endif; ?>
			<? if ($attend_day['leave_school_hour']>=12): ?>
			    <?=$kis_lang['out']?><br/>
			<? endif; ?>
		    <? endif; ?>
		    </td>
		    <td>
		    
		    <? if (($attend_day['leave_school_hour']<12 && $attend_day['in_school_hour']<12) || !$attend_day['pm_here']): ?>
			--
		    <? else: ?>
			<? if ($attend_day['in_school_hour']>=12): ?>
			    <?=$attend_day['in_school_time']?><br/>
			<? endif; ?>
			<? if ($attend_day['leave_school_hour']>=12): ?>
			    <?=$attend_day['leave_school_time']?><br/>
			<? endif; ?>
		    <? endif; ?>
		    </td>
		    <td>--</td>
		    <td><?=$attend_day['pm_remark']?$attend_day['pm_remark']:'--'?></td>
		    <td><span class="date_time"><?=$attend_day['pm_modified']?><em><?=$attend_day['pm_modified_by_'.$lang]?></em></span></td>
		</tr> 
		
	    <? endforeach; ?>
	
	<? else: ?>
	</tbody></table>
	    <div class="no_record">
	       <?=$kis_lang['norecord']?>!
	    </div>
	<? endif; ?>
    </table>
    <p class="spacer"></p>
    <p class="spacer"></p><br>
</div>

                    
                    