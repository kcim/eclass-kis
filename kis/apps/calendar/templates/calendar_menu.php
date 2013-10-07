<script>
$(function(){
    kis.calendar.calendar_menu_init({remove: '<?=$kis_lang['remove']?>'});
});
</script>
<div class="calendar_event_list">
    <div class="cal_clip"></div> 
    <div class="calendar_event_list_detail <?=$calendar_menu_editable?'calendar_event_list_detail_edit':''?>">
	<ul>
	    
	    <? foreach ($months as $m=>$mon):?>
		
		<li class="month_title calendar_events_month_<?=$m?>"><?=$mon['year']?> <?=$kis_lang['month_'.$mon['month']]?></li>
		<? foreach ($mon['days'] as $i=>$day):?>
		    <? foreach ($day['events'] as $event): ?>
			    <li class="title_<?=kis_calendar::$event_types[$event["type"]]?> calendar_events_month_<?=$m?>">
				<em>
				<?=date('M j',$event['start_time'])?><?=$event['start_time']!=$event['end_time']? ' - '.date(date('M',$event['start_time'])==date('M',$event['end_time'])? 'j': 'M j',$event['end_time']):''?>
				</em>
				<span><u><?=$event['total_days']?></u></span>
				<div class="event_title"><?=$event["title"]?></div>
				<div style="display:none" class="event_id"><?=$event["id"]?></div>
				<? if ($calendar_menu_editable && false): ?>
				<div class="table_row_tool">
				    <a class="delete_dim" href="#" title="<?=$kis_lang['remove']?>"></a><a class="edit_dim" href="#"></a>
				</div>
				<? endif; ?>
				<p class="spacer"></p>
			    </li>
		    <? endforeach; ?>
		<? endforeach; ?>
		<? if (!$mon['event_count']): ?>
		    <li class="calendar_events_month_<?=$m?>">
			<div><?=$kis_lang['norecord']?>!</div>
			<p class="spacer"></p>
		    </li>
		
		<? endif;?>
	    <? endforeach; ?>
	</ul>
    </div>         	
</div>
<? if ($calendar_menu_editable && $last_modifed_user): ?>
<div class="calendar_update"><?=$kis_lang['lastupdated']?>: <?=$last_modifed_user['name_'.$lang]?> <?=$last_modifed_user['last_modified_days']?> <?=$kis_lang['daysago'] ?></div>
<? endif; ?>
<div class="calendar_tool">
	<div class="Content_tool">
	    <? if ($calendar_menu_editable): ?>
	    <a href="/home/system_settings/school_calendar/import_holidays_events.php?AcademicYearID=<?=$academic_year_id?>" target="import_calendar" class="import"><?=$kis_lang['import']?></a>
	    <a href="/home/system_settings/school_calendar/export_holidays_events.php?AcademicYearID=<?=$academic_year_id?>" target="export_calendar" class="export"><?=$kis_lang['export']?></a>
	    <? endif; ?>
	    <a href="/home/system_settings/school_calendar/print_preview.php?p_color=0&p_month_event=&max_cal=2&AcademicYearID=<?=$academic_year_id?>" target="print_calendar" class="print"><?=$kis_lang['print']?></a>
	
	</div>
</div>
<p class="spacer"></p>