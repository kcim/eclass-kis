<div class="main_content_detail">
    <div class="navigation_bar">
	 <span><?=$notice['number']?> - <?=$notice['title']?></span>
    </div>
    <? kis_ui::loadModuleTab(array('notice','signresult'), 'signresult', '#/apps/enotice/noticelist/'.$notice['id'].'/' )?>
	<!--tab end-->
    <p class="spacer"></p> 
               
    <div class="table_board">
    
	<table class="common_table_list edit_table_list">

	    <tr>
		<th><?=$kis_lang['class']?></th>
		<th><?=$kis_lang['noofstudents']?></th>
		<th><?=$kis_lang['signed']?></th>
	    </tr>
	    
	    <? foreach ($classes as $class): ?>
	    <tr>
		<td><?=$class['name_'.$lang]?></td>
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
                    