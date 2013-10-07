<script>
$(function(){
    kis.iportfolio.teacher_schoolrecord_list_init({
		are_you_sure_to_delete: '<?=$kis_lang['msg']['are_you_sure_to_delete']?> ',
	});
});
</script>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
<div class="main_content">
 <? kis_ui::loadModuleTab(array('awards','activities'), 'awards', '#apps/iportfolio/schoolrecords/') ?>
 <?=$kis_data['NavigationBar']?>
    <div class="table_board">
        <div class="Content_tool">
		<a href="#" class="new"><?=$kis_lang['new']?></a></div>
		<p class="spacer"></p>
		<form class="filter_form">
			<div id="table_filter">
				<?=$kis_data['select_academicYear']?>
				<span id="span_term"><?=$kis_data['select_academicYearTerm']?></span>
				<input type="hidden" name="retrieve_type" id="retrieve_type" value="awards"> 
				<input type="hidden" name="classId" id="classId" value="<?=$kis_data['classId']?>"> 
			</div>
			<div class="page_no student_no" ><span><?=$kis_lang['student']?> :</span><?=$kis_data['StudentList']?></div>
		</form>
        <p class="spacer"></p>&nbsp;
        <table class="common_table_list edit_table_list">
			<col   nowrap="nowrap"/>
			<thead>
				<tr>
					<th width="20">#</th>
					<th><?kis_ui::loadSortButton('schoolyear','schoolyear', $sortby, $order)?></th>
					<th><?kis_ui::loadSortButton('semester','term', $sortby, $order)?></th>
					<th><?kis_ui::loadSortButton('award_name','award_title', $sortby, $order)?></th>
					<th><?kis_ui::loadSortButton('award_date','award_date', $sortby, $order)?></th>
					<th><?kis_ui::loadSortButton('remarks','remarks', $sortby, $order)?></th>
					<th><?kis_ui::loadSortButton('last_update_date','last_update_date', $sortby, $order)?></th>
					<th width="80">&nbsp;</th>
			  </tr>
			</thead>
			<tbody>
			<?php 
				$record_cnt = count($kis_data['award_record']);
				if($record_cnt>0){
					for($a=0;$a<$record_cnt;$a++){ 
						$_record = $kis_data['award_record'][$a];
			?>
					<tr id="tr_<?=$_record['award_id']?>">
						<td><?=($a+1)?></td>
						<td><?=$_record['schoolyear']?></td>
						<td><?=$_record['semester']?></td>
						<td><?=stripslashes($_record['award_name'])?></td>
						<td><?=$_record['award_date']?></td>
						<td><?=stripslashes($_record['remarks'])?></td>
						<td><?=$_record['last_update_date']?></td>
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
					  <td colspan="8"><?=$kis_lang['norecord']?></td>
					</tr>
			<?php } ?>				  
			</tbody>
		</table>
		<p class="spacer"></p>
		<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>     
		<p class="spacer"></p><br />
    </div>
</div>