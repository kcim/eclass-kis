<script>
$(function(){
    kis.calendar.yearly_init({academic_year_id: '<?=$academic_year_id?>'});
});
</script>

    <div class="calendar_content">
        <div class="calendar_top">
	    
		<div id="table_filter">
		    <select class="year">
			
			<? foreach ($academic_years as $academic_year): ?>
			    <option value="/apps/calendar/<?=$academic_year['academic_year_id']?>/" <?=$academic_year['academic_year_id']==$academic_year_id?'selected="selected"':''?>>
			    <?=$academic_year['academic_year_name_'.$lang]?> 
			    </option>
			<? endforeach; ?>

		    </select>
		    
		    <? if ($current_academic_year_id == $academic_year_id): ?>
		   
			<select class="show_month">
			    <option value="coming"><?=$kis_lang['comingmonths']?></option>
			    <option value="all" ><?=$kis_lang['allmonths']?></option>
			    
			</select>
		    
		    <? endif; ?>
		   
		</div>
	
	 
            <ul class="calendar_tab">
                <li class="selected"><a href="#/apps/calendar/<?=$academic_year_id?>/"><?=$kis_lang['year']?></a></li>
                <li><a class="month_tab" href="#/apps/calendar/<?=$academic_year_id?>/<?=$month?>/"><?=$kis_lang['month']?></a></li>
                <p class="spacer"></p>
            </ul>
            <p class="spacer"></p>
        </div>
    <? foreach ($months as $index=>$mon): ?>
    <? if ($current_academic_year_id != $academic_year_id):?>
        <div class="calendar_table calendar_small">
    <? else: ?>
        <div class="calendar_table calendar_small <?=$mon['month']>=$current_date['mon'] && $mon['year']>=$current_date['year']? '':'calendar_hidden'?>">
    <? endif; ?>
	
	<h1><?=$mon['year']?> <?=$kis_lang['month_'.$mon['month']]?></h1>
	<ul class="cal_head">
	    <li><?=$kis_lang['sun']?></li>
	    <li><?=$kis_lang['mon']?></li>
	    <li><?=$kis_lang['tue']?></li>
	    <li><?=$kis_lang['wed']?></li>
	    <li><?=$kis_lang['thu']?></li>
	    <li><?=$kis_lang['fri']?></li>
	    <li><?=$kis_lang['sat']?></li>
	</ul>   
	<ul>

	    <? foreach ($mon['days'] as $i=>$day):?>
	    
		    <? if ($mon['month']==$day['mon']): ?>
			<li class="<?=$day['wday']==0?'day_sunday':''?> <? foreach ($day['events'] as $event): ?> day_<?=kis_calendar::$event_types[$event['type']]?> <? endforeach; ?>">
			<a >
			<em><?=$day['mday']?></em>
			</a>
			</li>
		    <? else: ?>
			<li class="blank_date"><div><em></em></div></li>
		    <? endif; ?>	    
	    <? endforeach; ?>
	    
	<p class="spacer"></p>
	</ul>
	<p class="spacer"></p>
    </div>
    
    <? endforeach; ?>
</div>
   
        