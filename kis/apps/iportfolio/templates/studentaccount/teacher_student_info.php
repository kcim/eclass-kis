<script>
$(function(){
    kis.iportfolio.teacher_student_info_init({
		please_fill_in:"<?=$kis_lang['please_fill_in']?>",
		relationship: '<?=strtolower($kis_lang['relationship'])?> ',
		emergencycontactno: '<?=strtolower($kis_lang['emergencycontactno'])?> ',
		eng_name: '<?=strtolower($kis_lang['englishname'])?> ',
		award_title: '<?=strtolower($kis_lang['award_title'])?> ',
		award_date: '<?=strtolower($kis_lang['award_date'])?> ',
		award_organization: '<?=strtolower($kis_lang['award_organization'])?> ',
		activity_name: '<?=strtolower($kis_lang['activity_name'])?> ',
		role: '<?=strtolower($kis_lang['role'])?> ',
		are_you_sure_to_delete: '<?=$kis_lang['msg']['are_you_sure_to_delete']?> ',
		editby1: '<?=$kis_lang['Assessment']['EditBy'][0]?>',
		editby2: '<?=$kis_lang['Assessment']['EditBy'][1]?>',
		upload: '<?=$kis_lang['Assessment']['Upload']?>',		
	});
	<?php if($kis_data['retrieve_action']!='view'){ ?>
		<?php 	if($kis_data['retrieve_type']=='awards'){ ?>
					kis.datepicker('#award_date');	
		<?php }elseif($kis_data['retrieve_type']=='assessment'){  ?>		
					kis.datepicker('#release_date');	
		<?php } ?>	
	<?php } ?>		
});
</script>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
<div class="main_content">
    <div class="main_content_detail">
	<!---->
        <?=$kis_data['NavigationBar']?>
        <div class="table_board">
			<form class="filter_form">
				<div id="table_filter"><?=$kis_data['ClassList']?></div>
				<div class="page_no student_no" ><span><?=$kis_lang['student']?> :</span><?=$kis_data['StudentList']?></div>
				<input type="hidden" name="showPage" id="showPage" value="<?=$kis_data['showPage']?>">
			<?php if($kis_data['retrieve_action']=='view') {?>
				<input type="hidden" name="recordType" id="recordType" value="<?=$kis_data['retrieve_action'].'_'.$kis_data['retrieve_type']?>">
			<?php } ?>
			</form>
			<p class="spacer"></p>
			<div class="teacher_iportfolio_student">
			<!----------->
                <div class="student_info">
					<img src="<?=kis::getUserPhoto($kis_data['studentInfo']['user_login']);?>">
					<span><?=$kis_data['studentName']?></span>
				</div>
                <div class="btn_ipf_tool">
                    <a href="#" id="a_info" class="btn_ipf_info<?=(($kis_data['showPage']=='info'||$kis_data['showPage']=='')?' btn_ipf_info_selected tool_selected':'')?>" title="<?=$kis_lang['studentinfo']?>"></a>
					<a href="#" id="a_schoolrecord" class="btn_ipf_schoolrecord<?=(($kis_data['showPage']=='schoolrecord')?' btn_ipf_schoolrecord_selected tool_selected':'')?>" title="<?=$kis_lang['schoolrecords']?>"></a>
					<a href="#" id="a_assessment" class="btn_ipf_assessment<?=(($kis_data['showPage']=='assessment')?' btn_ipf_assessment_selected tool_selected':'')?>"  title="<?=$kis_lang['assessmentreport']?>"></a>
					<a href="/home/portfolio/profile/sbs/index.php?StudentID=<?=$kis_data['studentInfo']['user_id']?>" class="btn_ipf_sbs" title="<?=$kis_lang['sbs']?>" target="_blank"></a>
					<a href="/home/portfolio/profile/learning_portfolio_teacher_v2.php?StudentID=<?=$kis_data['studentInfo']['user_id']?>" class="btn_ipf_lp" title="<?=$kis_lang['learningportfolio']?>" target="_blank"></a>
                </div>
                <div class="teacher_ipf_student_content_board">
					<div class="teacher_ipf_student_content">
<?php 
		switch($kis_data['showPage']) {
			case 'schoolrecord':
?>
						<h1 class="teacher_ipf_student_content_title"><?=$kis_lang['StudentAccount']['schoolrecord']?></h1>
							<div class="module_tab">
                            	<ul>
                                	<li <?=((($kis_data['retrieve_type']=='awards'))?'class="selected"':'')?>><a href="#" id="a_award"><span><?=$kis_lang['awards']?></span></a></li>
                                    <li <?=((($kis_data['retrieve_type']=='activities'))?'class="selected"':'')?>><a href="#" id="a_activities"><span><?=$kis_lang['activities']?></span></a></li>
								</ul>
							</div>
				<?php if($kis_data['retrieve_action']=='view'){ ?>			
							<div class="table_board">
								<div class="Content_tool"><a href="#" class="new"><?=$kis_lang['new']?></a></div>
								<p class="spacer"></p>
								<table class="common_table_list edit_table_list">
									<col   nowrap="nowrap"/>
									<thead>
										<tr>
										      <th width="5%">#</th>
										<?php if($kis_data['retrieve_type']=='awards'){ ?>	  
											  <th width="10%"><?kis_ui::loadSortButton('schoolyear','schoolyear', $sortby, $order)?></th>
											  <th width="10%"><?kis_ui::loadSortButton('semester','term', $sortby, $order)?></th>
											  <th width="12%"><?kis_ui::loadSortButton('award_date','award_date', $sortby, $order)?></th>
											  <th width="15%"><?kis_ui::loadSortButton('award_name','award_title', $sortby, $order)?></th>
											  <th width="12%"><?kis_ui::loadSortButton('organization','award_organization', $sortby, $order)?></th>
											  <th width="10%"><?kis_ui::loadSortButton('subject_area','subject_area', $sortby, $order)?></th>
											  <th width="13%"><?kis_ui::loadSortButton('remarks','remarks', $sortby, $order)?></th>
											  <?php $row=9;?>
										<?php }else{ ?>
											  <th width="10%"><?kis_ui::loadSortButton('schoolyear','schoolyear', $sortby, $order)?></th>
											  <th width="12%"><?kis_ui::loadSortButton('semester','term', $sortby, $order)?></th>
											  <th width="20%"><?kis_ui::loadSortButton('activity_name','activity_name', $sortby, $order)?></th>
											  <th width="10%"><?kis_ui::loadSortButton('role','role', $sortby, $order)?></th>
											  <th width="15%"><?kis_ui::loadSortButton('performance','performance', $sortby, $order)?></th>
											  <th width="15%"><?kis_ui::loadSortButton('organization','organization', $sortby, $order)?></th>
											  <?php $row=8;?>
										<?php } ?>
											<th width="13%">&nbsp;</th>
									  </tr>
									</thead>
								<?php 
									$record_cnt = count($kis_data['retrieve_data']);
									if($record_cnt>0){
										for($i=0;$i<$record_cnt;$i++){ 
											$_record = $kis_data['retrieve_data'][$i];
											if($kis_data['retrieve_type']=='awards')
												$_recordId = $_record['award_id'];
											else
												$_recordId = $_record['activity_id'];
								?>
									<tbody>
										<tr id="tr_<?=$_recordId?>">
										  <td><?=($i+1)?></td>
										<?php if($kis_data['retrieve_type']=='awards'){ ?>	  
										  <td><?=kis_iportfolio::getFormField("text","schoolyear",$_record['schoolyear'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","semester",$_record['semester'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","award_date",$_record['award_date'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","award_name",$_record['award_name'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","organization",$_record['organization'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","subject_area",$_record['subject_area'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","remarks",$_record['remarks'],'view');?></td>
										<?php }else{ ?>  
										  <td><?=kis_iportfolio::getFormField("text","schoolyear",$_record['schoolyear'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","semester",$_record['semester'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","activity_name",$_record['activity_name'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","role",$_record['role'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","performance",$_record['performance'],'view');?></td>
										  <td><?=kis_iportfolio::getFormField("text","organization",$_record['organization'],'view');?></td>								
										<?php } ?>
										  <td class="common_table_list">
											<div class="table_row_tool row_content_tool">
												<a href="#" class="edit_dim" title="<?=$kis_lang['edit']?>"></a>
												<a href="#" class="copy_dim" title="<?=$kis_lang['copy']?>"></a>
												<a href="#" class="delete_dim delete_table_record" title="<?=$kis_lang['delete']?>"></a>
											</div>
										  </td>
									  </tr>
								
									  </tr>
									</tbody>
							<?php 
										} 
									}else{ 
							?>
									<tr>
									  <td colspan="<?=$row?>"><?=$kis_lang['norecord']?></td>
									</tr>
						<?php } ?>		
								</table>
								<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
								<p class="spacer"></p><p class="spacer"></p><br />
							</div>
		<?php }else{ ?>	
		<form id="recordForm">
			<?=$kis_data['retrieve_data']?>
		</form>	
		<?php } ?>	
		<input type="hidden" id="retrieve_type" name="retrieve_type" value="<?=$kis_data['retrieve_type']?>" />
        <!-- content content end-->
<?php
				break;
			case 'assessment':
?>
						<h1 class="teacher_ipf_student_content_title"><?=$kis_lang['assessmentreport']?></h1>
					<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>				
							<div class="table_board">
								<p class="spacer"></p>
								<table class="common_table_list edit_table_list">
									<col   nowrap="nowrap"/>
									<thead>
										<tr>
										    <th width="20">#</th>  
											<th><? kis_ui::loadSortButton('assessment_title','assessment_title', $sortby, $order)?></th>
											<th><?=$kis_lang['Assessment']['AssessmentFile']?></th>
											<th><? kis_ui::loadSortButton('release_date','release_date', $sortby, $order)?></th>
											<th><? kis_ui::loadSortButton('modified_date','uploaded_date', $sortby, $order)?></th>
									  </tr>
									</thead>
									<tbody>
								<?php 
									$record_cnt = count($kis_data['retrieve_data']);
									if($record_cnt>0){
										for($i=0;$i<$record_cnt;$i++){ 
											$_record = $kis_data['retrieve_data'][$i];
											$_trCss = (!empty($_record['record_id']))?'':' class="attendanceabsent"';
											if(!empty($_record['record_id'])){
												$_filePath = './apps/iportfolio/ajax.php?action=getStudentAssessmentFile&assessmentId='.$_record['AssessmentID'].'&studentId='.$_record['user_id'];
												//$_filePath = kis_iportfolio::$attachment_url.'assessment/'.$_record['AssessmentID'].'/'.$_record['user_id'].'/'.$_record['report_title'];
												$_time = $_record['modified_date'].'<em> '.$kis_lang['Assessment']['EditBy'][0].$_record['modified_user'].$kis_lang['Assessment']['EditBy'][1].'</em>';
												$_spanDisplay = '';
												$_buttonDisplay = ' style="display:none;"';
											}else{
												$_filePath = '';
												$_time = '-';
												$_spanDisplay = ' style="display:none;"';
												$_buttonDisplay = '';
											}
											$_attachment = '<a href="'.$_filePath.'" class="file_attachment">'.$kis_lang['view'].'</a>';
											$_attachment .= '<div class="table_row_tool"><a href="#" class="delete_dim delete_assessment_file" title="'.$kis_lang['delete'].'"></a></div>';
												
								?>
									
										<tr <?=$_trCss?> id="tr_<?=$_record['AssessmentID']?>">
											<td class="td_no"><?=($i+1)?></td>
											<td class="common_table_list"><?=$_record['assessment_title']?></td>
											<td id="td_attachment_<?=$_record['AssessmentID']?>">
												<span class="view_attachment" <?=$_spanDisplay?>><?=$_attachment?></span>
												<input type="button" class="assessment_upload_btn formsmallbutton" value="<?=$kis_lang['Assessment']['Upload']?>"  id="uploader_button_<?=$_record['AssessmentID']?>" <?=$_buttonDisplay?>/>
											</td>
											<td class="common_table_list"><?=$_record['release_date']?><span class="date_time"><em><?=$_record['created_user']?></em></span></td>
											<td class="common_table_list"><span class="date_time upload_time"><?=$_time?></span></td>
											
									  </tr>
								
									  </tr>
									
							<?php 
										} 
									}else{ 
							?>
									<tr>
									  <td colspan="5"><?=$kis_lang['norecord']?></td>
									</tr>
						<?php } ?>
								</tbody>						
								</table>
								<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
								<p class="spacer"></p><p class="spacer"></p><br />
							</div>
        <!-- content content end-->
<?php
				break;
			default:
?>
							<form id="studentInfoForm">
							<h1 class="teacher_ipf_student_content_title"><?=$kis_lang['studentinfo']?></h1>
							<div class="common_table_tool"><a href="#" class="tool_edit"><?=$kis_lang['edit']?></a></div>
							<!--DATA-->					
								<?=$kis_data['retrieve_data']?>
							<!--DATA-->			
							
							<div class="edit_bottom" style="display:none;">
								<input type="button" id="submitBtn" class="formbutton" value="<?=$kis_lang['submit']?>"/>
								<input type="button" id="cancelBtn" class="formsubbutton" value="<?=$kis_lang['cancel']?>"/>
							</div>
						</form>
<?php } ?>
					
					</div>
                </div>
				<p class="spacer"></p>   
			<!----------->	
            </div>
            <p class="spacer"></p>
        </div>
		<p class="spacer"></p>
	<!----> 
    </div> 
	<p class="spacer"></p>
</div>