<script>
$(function(){
    kis.iportfolio.parent_assessment_list_init();
});
</script>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
<div class="table_board">
    <table class="common_table_list edit_table_list">
		<col   nowrap="nowrap"/>
		<thead>
			<tr>
			  <th width="20">#</th>
			  <th><? kis_ui::loadSortButton('assessment_title','assessment_title', $sortby, $order)?></th>
			  <th><? kis_ui::loadSortButton('release_date','release_date', $sortby, $order)?></th>
			  <th><? kis_ui::loadSortButton('modified_date','last_update_date', $sortby, $order)?></th>
		  </tr>
		</thead>
		<tbody>
		<?php 
			for($i=0;$i<count($kis_data['student_assessment_record']);$i++){
				$_record = $kis_data['student_assessment_record'][$i];
				$_newIcon = (!$_record['Status'])?'<img src="/images/kis/alert_new2.gif" width="28" height="11" border="0" align="texttop" />':'';
		?>
			<tr>
			  <td><?=($i+1)?></td>
			  <td><a class="assessment_link" href="./apps/iportfolio/ajax.php?action=getStudentAssessmentFile&assessmentId=<?=$_record['AssessmentID']?>"> <?=$_record['assessment_title']?> <?=$_newIcon?></a></td>
			  <td class="common_table_list"><?=$_record['release_date']?><span class="date_time"><em><?=$_record['created_user']?></em></span></td>
			  <td class="common_table_list"><?=$_record['modified_date']?><span class="date_time"><em><?=$_record['modified_user']?></em></span></td>
		  </tr>
		<?php } ?>			
		</tbody>
	</table>
          <p class="spacer"></p>
		  <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>  
		  <p class="spacer"></p><br />
        </div>