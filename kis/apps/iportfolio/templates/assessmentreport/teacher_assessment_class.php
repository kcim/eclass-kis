<script>
$(function(){
    kis.iportfolio.assessment_class_init({
	view: '<?=$kis_lang['view']?>',
	delete: '<?=$kis_lang['delete']?>',
	please_fill_in: '<?=$kis_lang['please_fill_in']?> ',
	title: '<?=strtolower($kis_lang['assessment_title'])?> ',
	release_date: '<?=strtolower($kis_lang['release_date'])?> ',	
	editby1: '<?=$kis_lang['Assessment']['EditBy'][0]?>',
	editby2: '<?=$kis_lang['Assessment']['EditBy'][1]?>',
	upload: '<?=$kis_lang['Assessment']['Upload']?>',
	
	});
});
</script>
   <p class="spacer"></p>
        <?=$kis_data['NavigationBar']?>
		<div class="table_board">
            <div class="detail_title_box"><span><strong><?=$kis_data['title']?></strong></span>
                <div class="common_table_tool"><a href="#" class="tool_edit"><?=$kis_lang['edit']?></a></div>
                <p class="spacer"></p>
                <span class="detail_date"><span><?=$kis_lang['Assessment']['ReleaseDate']?> : <?=$kis_data['assessmentArr']['release_date']?>  </span></span>
                <p class="spacer"></p>
            </div>
            <p class="spacer"></p>
            <form class="filter_form" method="post"> 
                <div id="table_filter">
                    <?=$kis_data['StatusSelection']?>
                </div>
				<div class="search"><!--<a href="#">Advanced</a>-->
					<input type="text" name="keyword" class="auto_submit" id="keyword" placeholder="<?=$kis_lang['Assessment']['Search']?>" value="<?=$keyword?>"/>
				</div>
				<input type="hidden" id="assessmentId" name="assessmentId" value="<?=$kis_data['assessmentArr']['id']?>">
				<input type="hidden" id="classId" name="classId" value="<?=$kis_data['assessmentArr']['classId']?>">
			</form>
            <p class="spacer"></p>&nbsp;

          <table class="common_table_list edit_table_list">
							<col   nowrap="nowrap"/>
							<thead>
								<tr>
								  <th width="20">#</th>
								  <th width=""><?=$kis_lang['student']?></th>
								  <th><?=$kis_lang['Assessment']['AssessmentFile']?></th>
								  <th><?=$kis_lang['Assessment']['UploadedDate']?></th>
							  </tr>
							</thead>
					   <tbody>
						<?php 
							$cnt = 1;
							if(count($kis_data['assessment_student_list'])>0){
								foreach ($kis_data['assessment_student_list'] as $_studentNumber => $_studentAssessmentList){ 
								$_assessmentList = (array)$_studentAssessmentList['assessment'];
								$_assessmentCnt = count($_assessmentList);
								$_trCss = ($_assessmentCnt==0)?' class="attendanceabsent"':'';
								if($_assessmentCnt>0){
									$_filePath = './apps/iportfolio/ajax.php?action=getStudentAssessmentFile&assessmentId='.$assessment_id.'&studentId='.$_studentAssessmentList['user_id'];
									//$_filePath = kis_iportfolio::$attachment_url.'assessment/'.$_record['AssessmentID'].'/'.$_record['user_id'].'/'.$_record['report_title'];
									$_time = $_assessmentList['modified_date'].'<em> '.$kis_lang['Assessment']['EditBy'][0].$_assessmentList['modified_user'].$kis_lang['Assessment']['EditBy'][1].'</em>';
									$_spanDisplay = '';
									$_buttonDisplay = ' style="display:none;"';
								}else{
									$_filePath = '';
									$_time = '-';
									$_spanDisplay = ' style="display:none;"';
									$_buttonDisplay = '';
								}
								
								$_attachment = "<a href ='".$_filePath."' class='file_attachment'>". $kis_lang['view']."</a>";
								//onClick="window.open('http://www.google.com/','mywindow','width=400,height=350')"
								//$_attachment = '<div onclick = "'."window.open('$_filePath')".'" class="file_attachment"> View </div>';
								$_attachment .= '<div class="table_row_tool"><a href="#" class="delete_dim" title="'.$kis_lang['delete'].'"></a></div>';							
						?>
							<tr <?=$_trCss?> id="tr_<?=$_studentAssessmentList['user_id']?>">
								  <td><?=$cnt?></td>
								  <td><?=$_studentAssessmentList['name']?></td>
								<td id="td_attachment_<?=$_studentAssessmentList['user_id']?>">
									<span class="view_attachment" <?=$_spanDisplay?>><?=$_attachment?></span>
									<input type="button" class="assessment_upload_btn formsmallbutton" value="<?=$kis_lang['Assessment']['Upload']?>"  id="uploader_button_<?=$_studentAssessmentList['user_id']?>" <?=$_buttonDisplay?>/>
								</td>
								<td><span class="date_time"><?=$_time?></span></td>
						
							  </tr>
						<?php 
									$cnt++;
								} 
							}else{
						?>
								<tr>
								  <td colspan="4"><?=$kis_lang['norecord']?></td>
							  </tr>
						<?php
							}
						?>		
					  </tbody>
						</table>
          <p class="spacer"></p><p class="spacer"></p><br />
        </div>
 		<!--FancyBox-->
				
		<div id='edit_box' style="padding:5px;display:none;" class="pop_edit">
			<div class="pop_title">
				<span><?=$kis_lang['Assessment']['EditAssessment']?></span>
			</div>
			<div class="table_board" style="height:330px">
				<form id="assessmentForm" method="post">
				<table class="form_table">
					<tr>
						<td class="field_title"><?=$kis_lang['Assessment']['Title']?></td>
						<td ><input name="title" type="text" id="title" class="textboxtext" value="<?=$kis_data['assessmentArr']['title']?>"/></td>
					</tr>
					<tr>
						<td class="field_title"><?=$kis_lang['Assessment']['ReleaseDate']?></td>
						<td ><input name="release_date" type="text" id="release_date" size="15" value="<?=$kis_data['assessmentArr']['release_date']?>"/></td>
					</tr>
					<tr>
						<td class="field_title"><?=$kis_lang['Assessment']['Target']?></td>
						<td><?=$kis_data['ClassList']?></td>
					</tr>
				  <col class="field_title" />
				  <col  class="field_c" />
				</table>
		</form> 
      <p class="spacer"></p>
      </div>
        <div class="edit_bottom">         
           <input id="submitBtn" name="submitBtn" type="button" class="formbutton" value="Submit" />
           <input name="cancelBtn" type="button" class="formsubbutton" onclick="parent.$.fancybox.close();" value="<?=$kis_lang['cancel']?>" />
      </div>
		</div>
		                      
                    
                    
                    