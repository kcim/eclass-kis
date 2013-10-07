<script>
$(function(){
    kis.enotice.notice_result_stat_init();
});
</script>
<div class="main_content_detail">
    <span style="display:none;" class="notice_title"><?=$notice['number']?> - <?=$notice['title']?></span>

    <? if ($has_read_rights): ?>
	    <? kis_ui::loadModuleTab(array('notice','signresult'), 'signresult', '#/apps/enotice/noticelist/'.$notice['id'].'/' )?>
	    <p class="spacer"></p>
    <? endif; ?>
               
    <div class="table_board">
    
	<table class="common_table_list edit_table_list">

	    <tr>
		<th><?=$kis_lang['class']?></th>
		<th><?=$kis_lang['noofstudents']?></th>
		<th><?=$kis_lang['signed']?></th>
	    </tr>
	    
	    <? foreach ($classes as $class): ?>
	    <tr>
		<td><a href="#/apps/enotice/noticelist/<?=$notice['id']?>/signresult/<?=$class['class_id']?>/"><?=$class['class_name_'.$lang]?></a></td>
		<td><?=$class['total_students']?></td>
		<td><?=$class['total_signed_students']?></td>
	    </tr>
		<?
		    $total_students += $class['total_students'];
		    $total_signed_students += $class['total_signed_students'];
		?>
	    <? endforeach; ?>
	    
	    <tr class="row_total">
		<td><?=$kis_lang['total']?></td>
		<td><?=$total_students?></td>
		<td><?=$total_signed_students?></td>
	    </tr>
					
	 </table>
    </div>
             <p class="spacer"></p>&nbsp;
</div>
                    