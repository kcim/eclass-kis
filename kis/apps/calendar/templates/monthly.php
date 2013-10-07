<script>
$(function(){
    kis.calendar.monthly_init({
       previous: '<?=$academic_year_id?>/<?=$month_index-1?>',
       next: '<?=$academic_year_id?>/<?=$month_index+1?>'
    });
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
		
		<select class="month">
		    
		    <? foreach ($academic_year_months as $index=>$academic_year_month): ?>
			<option value="/apps/calendar/<?=$academic_year_id?>/<?=$index?>/" <?=$index==$month_index?'selected="selected"':''?>><?=$academic_year_month['year']?> <?=$kis_lang['month_'.$academic_year_month['month']]?></option>
		    <? endforeach; ?>

		</select>
		
	    </div>
	
     
	<ul class="calendar_tab">
	    <li><a href="#/apps/calendar/<?=$academic_year_id?>/"><?=$kis_lang['year']?></a></li>
	    <li class="selected"><a href="#/apps/calendar/<?=$academic_year_id?>/<?=$month_index?>/"><?=$kis_lang['month']?></a></li>
	    <p class="spacer"></p>
	</ul>
	<p class="spacer"></p>
    </div>
                	  <!---->
    <div class="calendar_table calendar_big">
	<h1>
	    <? if ($month_index>1): ?>
	    <input type="button" class="btn_previous">
	    <? endif; ?>
	    <?=$months[$month_index]['year']?> <?=$kis_lang['month_'.$months[$month_index]['month']]?>

	    <? if (sizeof($academic_year_months)>$month_index): ?>
	    <input type="button" class="btn_next">
	    <? endif; ?>

	</h1>
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
	   <? foreach ($months[$month_index]['days'] as $i=>$day):?>

	       <? if ($months[$month_index]['month']==$day['mon']): ?>
		   <li class="<?=$day['wday']==0?'day_sunday':''?> <? foreach ($day['events'] as $event): ?> day_<?=kis_calendar::$event_types[$event['type']]?> <? endforeach; ?>">
			<a>
			<em><?=$day['mday']?></em>
			</a>
		   </li>
	       <? else: ?>
		   <li class="blank_date"><div><em><?=$day['mday']?></em></div></li>
	       <? endif; ?>
		    
	   <? endforeach; ?>
	  
	   
	<p class="spacer"></p>
	</ul>
			     
        <p class="spacer"></p>
    </div>
</div>
