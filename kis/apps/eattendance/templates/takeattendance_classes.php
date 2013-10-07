<script>
$(function(){
    kis.eattendance.takeattendance_classes_init();
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
	    <input name="" class="formsmallbutton" value="<?=$kis_lang['go']?>" type="submit"/>
	</div>
	<div class="search"><!--<a href="#">Advanced</a>-->
	    <input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	</div>
    </form>
    <p class="spacer"></p>
    <div class="attendance_class_list">
	
	<? foreach ($classes as $class): ?>
	<ul class="<?=$class['status_confirmed']? 'status_confirmed': ''?>">
	    <li class="class_name"> <a href="#/apps/eattendance/takeattendance/?class_id=<?=$class['class_id']?>&date=<?=$date?>&time=<?=$apm?>"><?=$class['class_name_'.$lang]?></a></li>
	    <li class=""><a href="javascript:void(0)" ><span><?=$kis_lang['present']?></span><em><?=$class['present']?></em></a></li>
	    <li class="row_late"><a href="javascript:void(0)"><span><?=$kis_lang['late']?></span><em><?=$class['late']?></em></a></li>
	    <li class="row_late"><a href="javascript:void(0)"><span><?=$kis_lang['earlyleave']?></span><em><?=$class['early_leave']?></em></a></li>
	    <li class="row_absent"><a href="javascript:void(0)"><span><?=$kis_lang['absent']?></span><em><?=$class['absent']?></em></a></li>
	    <li class="row_left"><a href="javascript:void(0)"><span><?=$kis_lang['outing']?></span><em><?=$class['outing']?></em></a></li>
	    <? if ($class['modified']): ?>
	    <span class="date_time"><?=$kis_lang['lastupdated']?>:<br/><?=$class['modified']?><em>by <?=$class['modified_by_'.$lang]?></em></span>
	    <? endif; ?>
	</ul>
	<? endforeach; ?>

    </div>
</div>           