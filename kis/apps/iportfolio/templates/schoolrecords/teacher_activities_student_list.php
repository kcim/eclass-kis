<script>
$(function(){
    kis.iportfolio.teacher_schoolrecord_list_init({
		are_you_sure_to_delete: '<?=$kis_lang['msg']['are_you_sure_to_delete']?> ',
	});
});
</script>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
<div class="main_content">
 <? kis_ui::loadModuleTab(array('awards','activities'), 'activities', '#apps/iportfolio/schoolrecords/') ?>
  <?=$kis_data['NavigationBar']?>
    <div class="table_board">
        <div class="Content_tool">
		<a href="#" class="new"><?=$kis_lang['new']?></a></div>
		<p class="spacer"></p>
		<form class="filter_form">
			<div id="table_filter">
				<?=$kis_data['select_academicYear']?>
				<span id="span_term"><?=$kis_data['select_academicYearTerm']?></span>
				<input type="hidden" name="classId" id="classId" value="<?=$kis_data['classId']?>"> 
				<input type="hidden" name="retrieve_type" id="retrieve_type" value="activities"> 
			</div> 
			<div class="page_no student_no" ><span><?=$kis_lang['student']?> :</span><?=$kis_data['StudentList']?></div>
		</form>
        <p class="spacer"></p>&nbsp;
        <table class="common_table_list edit_table_list">
			<col   nowrap="nowrap"/>
			<thead>
				<tr>
					<th width="5%">#</th>
					<th width="5%"><?kis_ui::loadSortButton('class_name','class', $sortby, $order)?></th>
					<th width="10%"><?kis_ui::loadSortButton('user_name','student', $sortby, $order)?></th>
					<th width="12%"><?kis_ui::loadSortButton('schoolyear','schoolyear', $sortby, $order)?></th>
					<th width="13%"><?kis_ui::loadSortButton('semester','term', $sortby, $order)?></th>
					<th width="15%"><?kis_ui::loadSortButton('activity_name','activity_name', $sortby, $order)?></th>
					<th width="10%"><?kis_ui::loadSortButton('role','role', $sortby, $order)?></th>
					<th width="10%"><?kis_ui::loadSortButton('performance','performance', $sortby, $order)?></th>
					<th width="10%"><?kis_ui::loadSortButton('organization','organization', $sortby, $order)?></th>
					<th width="10%">&nbsp;</th>
			  </tr>
			</thead>
			<tbody>
			<?php 
				$record_cnt = count($kis_data['activity_record']);
				if($record_cnt>0){
					for($a=0;$a<$record_cnt;$a++){ 
						$_record = $kis_data['activity_record'][$a];
			?>
					<tr id="tr_<?=$_record['activity_id']?>">
						<td><?=($a+1)?></td>
						<td><?=$_record['class_name']?></td>
						<td><?=$_record['user_name']?></td>
						<td><?=$_record['schoolyear']?></td>
						<td><?=$_record['semester']?></td>
						<td><?=stripslashes($_record['activity_name'])?></td>
						<td><?=stripslashes($_record['role'])?></td>
						<td><?=stripslashes($_record['performance'])?></td>
						<td><?=stripslashes($_record['organization'])?></td>
						<td>
							<div class="table_row_tool row_content_tool">
								<a href="#" class="edit_dim" title="<?=$kis_lang['edit']?>"></a>
								<a href="#" class="copy_dim" title="<?=$kis_lang['copy']?>"></a>
								<a href="#" class="delete_dim delete_table_record" title="<?=$kis_lang['delete']?>"></a>
							</div>
						</td>
					</tr>
				<?php } ?>			
			<?php }else{ ?>
					<tr>
					  <td colspan="10"><?=$kis_lang['norecord']?></td>
					</tr>
			<?php } ?>				  
			</tbody>
		</table>
		<p class="spacer"></p>
		<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>     
		<p class="spacer"></p><br />
    </div>
</div>