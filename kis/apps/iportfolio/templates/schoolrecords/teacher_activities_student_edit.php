<script>
$(function(){
    kis.iportfolio.teacher_activity_edit_init({
		please_fill_in:"<?=$kis_lang['please_fill_in']?>",
		please_select_student: '<?=$kis_lang['msg']['please_select_student']?> ',
		activity_name: '<?=strtolower($kis_lang['activity_name'])?> ',
		role: '<?=strtolower($kis_lang['role'])?> ',
	});
});
</script>
<div class="main_content">
<form id='activity_form'>
    <div class="main_content_detail">
        <? kis_ui::loadModuleTab(array('awards','activities'), 'activities', '#apps/iportfolio/schoolrecords/') ?>
        <p class="spacer"></p>
        <?=$kis_data['NavigationBar']?>
        <p class="spacer"></p>
         <div class="table_board">
			<table class="form_table">
			<?php if($kis_data['school_record_action']=='new'&&empty($kis_data['studentId'])){ ?>
				<tr class="mail_compose_table">
					<td class="field_title"><span class="tabletextrequire">*</span><?=$kis_lang['student']?></td>
					<td>
						<div class="mail_to">
							<div class="mail_to_list">
							<? kis_ui::loadTemplate('schoolrecords/select_users',array('users'=>$draft['recipients'], 'form_name'=>'target_user')) ?>
							</div>
							<div class="mail_to_btn">
								<div class="mail_icon_form"><a href="#" class="btn_select_ppl"><?=$kis_lang['select']?></a></div>
							</div>
						</div>
					</td>
				</tr>
			<?php } ?>	
			    <tr>
					<td class="field_title"><?=$kis_lang['schoolyear']?></td>
					<td><?=$kis_data['select_academicYear']?></td>
				</tr>		
			    <tr>
					<td class="field_title"><?=$kis_lang['term']?></td>
					<td><span id="span_term"><?=$kis_data['select_academicYearTerm']?></span></td>
				</tr>					
			    <tr>
					<td class="field_title"><span class="tabletextrequire">*</span><?=$kis_lang['activity_name']?></td>
					<td ><?=kis_iportfolio::getFormField("text","activity_name",$kis_data['RetrieveList']['activity_name'],'edit',' class="textboxtext"')?></td>
				</tr>
			    <tr>
					<td class="field_title"><span class="tabletextrequire">*</span><?=$kis_lang['role']?></td>
					<td ><?=kis_iportfolio::getFormField("text","role",$kis_data['RetrieveList']['role'],'edit',' class="textboxtext"')?></td>
				</tr>	
				<tr>
					<td class="field_title"><?=$kis_lang['performance']?></td>
					<td><?=kis_iportfolio::getFormField("textarea","performance",$kis_data['RetrieveList']['performance'],'edit',' rows="4" wrap="virtual" class="textboxtext"')?></td>
				</tr>				
			    <tr>
					<td class="field_title"><?=$kis_lang['organization']?></td>
					<td ><?=kis_iportfolio::getFormField("text","organization",$kis_data['RetrieveList']['organization'],'edit',' class="textboxtext"')?></td>
				</tr>
				<col class="field_title" />
				<col  class="field_c" />
			</table>
			<p class="spacer"></p>
			<p class="spacer"></p><br />
        </div>
		<div class="edit_bottom">
		<?php if($kis_data['school_record_action']=='edit'){ ?>
			<input type="hidden" name="recordId" id="recordId" value="<?=$kis_data['RetrieveList']['activity_id']?>">
			<input type="hidden" name="studentId" id="studentId" value="<?=$kis_data['RetrieveList']['user_id']?>">
		<?php }elseif($kis_data['school_record_action']=='new'&&!empty($kis_data['studentId'])){ ?>	
			<input type="hidden" name="target_user[]" value="<?=$kis_data['studentId']?>">
			<input type="hidden" name="studentId" id="studentId" value="<?=$kis_data['studentId']?>">
		<?php } ?>
			<input type="hidden" name="school_record_action" id="school_record_action" value="<?=$kis_data['school_record_action']?>">
			<input type="submit" id="submitBtn" name="submitBtn" class="formbutton" value="<?=$kis_lang['submit']?>" />
			<input type="button" id="cancelBtn" name="cancelBtn" class="formsubbutton" value="<?=$kis_lang['cancel']?>" />
		</div>
    </div>
</form>
</div>
					
<form class='mail_select_user'>
    <h2><?=$kis_lang['findusers']?></h2>
    
    <?=$kis_lang['keyword']?>: <input type="text" name="keyword" style="float:right" value=""/>
    <p class="spacer"></p>
    <?=$kis_lang['class']?>: <?=$kis_data['ClassList']?>
    <p class="spacer"></p>
    <input type="hidden" name="exclude_list" value=""/>
    <p class="spacer"></p>
    <div class="button">  
	<input class="formbutton" value="<?=$kis_lang['search']?>" type="submit"/>
	<input class="formsubbutton" value="<?=$kis_lang['close']?>" type="submit"/>
    </div>
    
    <a class="mail_select_all" href="#"><?=$kis_lang['addall']?></a>
    <p class="spacer"></p>
    <div class="search_results">
    </div>
    
    
</form>					