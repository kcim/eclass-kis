<li class="month_title"><?=$kis_lang['month_'.$month]?> <?=$year?></li>

<? if (!$events): ?>
    <li>
	<div><?=$kis_lang['norecord']?>!</div>
	<p class="spacer"></p>
    </li>
<? else: ?>

    <? foreach ($events as $event): ?>
	
	<li class="title_<?=kis_calendar::$event_types[$event["type"]]?>">
	<em>
	    <?=date('M j',$event['start_time'])?><?=$event['start_time']!=$event['end_time']? ' - '.date('M j',$event['end_time']):''?> </em><span><u><?=date('j',$event['end_time'])-date('j',$event['start_time'])+1?></u></span><div><?=$event["title"]?></div>
	    <p class="spacer"></p>
	</li>
	
    <? endforeach; ?>
    
<? endif;?>
