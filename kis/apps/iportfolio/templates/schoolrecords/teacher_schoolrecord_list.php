<script>
$(function(){
    kis.iportfolio.teacher_schoolrecord_list_init({
		are_you_sure_to_delete: '<?=$kis_lang['msg']['are_you_sure_to_delete']?> ',
	});
});
</script>
<div class="main_content">
 <? kis_ui::loadModuleTab(array('awards','activities'), $kis_data['recordType'], '#apps/iportfolio/schoolrecords/') ?>
    <div class="table_board">
        <div class="Content_tool">
		<a href="#" class="new"><?=$kis_lang['new']?></a></div>
		<p class="spacer"></p>
		<form class="filter_form">
			<div id="table_filter">
				<?=$kis_data['ClassList']?>
				<?=$kis_data['select_academicYear']?>
				<span id="span_term"><?=$kis_data['select_academicYearTerm']?></span>
				<input name="goBtn" type="submit" class="formsmallbutton" value="Go" />
			</div>   
			<div class="search">
				<input type="hidden" name="retrieve_type" id="retrieve_type" value="<?=$kis_data['recordType']?>"> 
				<input type="text" style="width:150px" name="keyword" id="keyword" placeholder="<?=$kis_lang['enter_student_name']?>" value="<?=$kis_data['keyword']?>"/>
			</div>
		</form>
        <p class="spacer"></p>&nbsp;
        <table class="common_table_list edit_table_list">
			<col   nowrap="nowrap"/>
			<thead>
				<tr>
					<th><?=$kis_lang['class_number']?></th>
					<th><?=$kis_lang['student']?></th>
					<th><?=($kis_data['recordType']=='activities')?$kis_lang['activities_count']:$kis_lang['awards_count']?></th>
			  </tr>
			</thead>
			<tbody>
			<?php 
				$record_cnt = count($kis_data['record']);
				if($record_cnt>0){
					foreach($kis_data['record'] as $_classNumber => $_record){
			?>
					<tr>
						<td><?=$_classNumber?></td>
						<td><?=$_record['user_name']?></td>
						<td><?=$_record['count']?></td>
					</tr>
				<?php } ?>			
			<?php }else{ ?>
					<tr>
					  <td colspan="3"><?=$kis_lang['norecord']?></td>
					</tr>
			<?php } ?>				  
			</tbody>
		</table>
		<p class="spacer"></p>
		<p class="spacer"></p><br />
    </div>
</div>