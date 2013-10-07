<script>
$(function(){
    kis.enotice.notice_form_init({notice_id: '<?=$notice['id']?>',max_options: '<?=$max_options?>'}, {
	loadfromtemplate: '<?=$kis_lang['loadfromtemplate']?>',
	pleaseentervalid: '<?=$kis_lang['pleaseentervalid']?>',
	noticenumber: '<?=$kis_lang['noticenumber']?>',
	title: '<?=$kis_lang['title']?>',
	date: '<?=$kis_lang['date']?>',
	replyslip: '<?=$kis_lang['replyslip']?>',
	areyousureto: '<?=$kis_lang['areyousureto']?>',
	remove: '<?=$kis_lang['remove']?>',
	uploadfileconfirm: '<?=$kis_lang['uploadfileconfirm']?>?',
    });
});
</script>

<div class="main_content_detail">

    <p class="spacer"></p>
    <div class="navigation_bar">
    <span class="notice_title">
    <?=$is_copy? $kis_lang['copy'].' '.$kis_lang['from'].' "':''?>
    <?=$notice? $notice['number'].' - '.$notice['title']: $kis_lang['new'].' '.$kis_lang['notice']?>
    <?=$is_copy? '"':''?>
    </span>
    </div>

    <p class="spacer"></p>
    <form class="notice_form">
	<div class="table_board">
	<table class="form_table">
	    <col style="width:15%"/>
	    <col style="width:85%"/>
	  <tbody><tr>
	    <td class="field_title"><?=$kis_lang['audience']?></td>
	    <td style="padding:0">
		
		<input type="hidden" name="type" value="<?=$notice?$notice['type']:1?>"/>
		<div class="notice_type_tab">
		<? foreach (kis_enotice::$notice_types as $key=>$value): ?>
		     
		    <a href='#' <?=($key==$notice['type']&&!$is_copy) || ($key==1&&!$notice)? 'class="selected"':''?>>
			<?=$kis_lang[$value]?>
			<input type="hidden" class="type_id" value="<?=$key?>"/>
		      </a>
		<? endforeach; ?>
		
		</div>
		
		<div class="notice_type" id="notice_type_2" <?=kis_enotice::$notice_types[$notice['type']]=='somelevelsonly'?'':'style="display:none"'?>>
		    <ul class="notice_targets">
		    <? foreach ($classlevels as $classlevel): ?>
		    <li>
			<input type="checkbox" value="<?=$classlevel['classlevel_id']?>" <?=$notice['type']==2&&in_array($classlevel['classlevel_id'],$notice['recipients']['classlevels'])?'checked="checked"':''?> id="classlevel_<?=$classlevel['classlevel_id']?>" name="classlevels[]"/>
			<label for="classlevel_<?=$classlevel['classlevel_id']?>">
			    <?=$classlevel['classlevel_name_'.$lang]?>
			</label>
		    </li>
		    <? endforeach; ?>
		    </ul>
		</div>
		<div class="notice_type" id="notice_type_3" <?=kis_enotice::$notice_types[$notice['type']]=='someclassesonly'?'':'style="display:none"'?>>
		    <ul class="notice_targets">
		    <? foreach ($classes as $class): ?>
		    <li>
			<input type="checkbox" value="<?=$class['class_id']?>" <?=$notice['type']==3&&in_array($class['class_id'],(array)$notice['recipients']['classes'])?'checked="checked"':''?> id="class_<?=$class['class_id']?>" name="classes[]"/>
			<label for="classlevel_<?=$class['class_id']?>">
			    
			    <?=$class['class_name_'.$lang]?>
			</label>
		    </li>
		    <? endforeach; ?>
		    </ul>
		</div>
		<div class="notice_type" id="notice_type_4" <?=kis_enotice::$notice_types[$notice['type']]=='applicablestudents'?'':'style="display:none"'?>>
		    <div class="notice_type_select">
			    
			    <ul class="search_list">
				<? foreach ((array)$notice['recipient_details']['groups'] as $group): ?>
				<li class="group" title="<?=$kis_lang['group']?>">
				    <span><?=$group['group_name_'.$lang]?></span>
				    <div class="table_row_tool">
					<a title="<?=$kis_lang['add']?>" class="add" href="#"></a>
					<a title="<?=$kis_lang['remove']?>" class="delete" href="#"></a>
					<input type="hidden" class="id" name="groups[]" value="<?=$group['group_id']?>"/>
				    </div>
				    
				</li>
				<? endforeach; ?>
				<? foreach ((array)$notice['recipient_details']['users'] as $user): ?>
				<li class="user" title="<?=$user['user_name_'.$lang]?> (<?=$user['user_class_name']?> <?=$user['user_class_number']?>)">
				    <span><?=$user['user_name_'.$lang]?></span>
				    <div class="table_row_tool">
					<a title="<?=$kis_lang['add']?>" class="add" href="#"></a>
					<a title="<?=$kis_lang['remove']?>" class="delete" href="#"></a>
					<input type="hidden" class="id" name="users[]" value="<?=$user['user_id']?>"/>
				    </div>
				</li>
				<? endforeach; ?>
			    </ul>
		    </div>
		    <div class="notice_type_search">
			
			    <div class="title"><?=$kis_lang['groups']?></div>
			    
			    <ul class="search_list search_group">
				<? foreach ($groups as $group): ?>
				<li class="group" title="<?=$kis_lang['group']?>">
				    <span><?=$group['group_name_'.$lang]?></span>
				    <div class="table_row_tool">
					<a title="<?=$kis_lang['add']?>" class="add" href="#"></a>
					<a title="<?=$kis_lang['remove']?>" class="delete" href="#"></a>
					<input type="hidden" class="id" name="groups[]" disabled value="<?=$group['group_id']?>"/>
				    </div>
				</li>
				<? endforeach; ?>
			    </ul>	    
		    </div>
		    <div class="notice_type_search">
			
			    <div class="title"><?=$kis_lang['students']?>
				<div class="search" style="padding:0"><!--<a href="#">Advanced</a>-->
	    
				    <input type="text" placeholder="<?=$kis_lang['search']?>">
				</div>
			    </div>
			    <ul class="search_list search_user">
				<li class="user user_template" style="display:none" title="<?=$kis_lang['group']?>">
				    <span></span>
				    <div class="table_row_tool">
					<a title="<?=$kis_lang['remove']?>" class="add" href="#"></a>
					<a title="<?=$kis_lang['remove']?>" class="delete" href="#"></a>
					<input type="hidden" name="users[]" disabled class="id" value=""/>
				    </div>
				</li>
			    </ul>
		    </div>
		</div>
		<p class="spacer"></p>
	    </td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['loadfromtemplate']?></td>
	    <td><select class="choose_template">
		    <option value="">(<?=$kis_lang['newtemplate']?>)</option>
		    <optgroup>
			<? foreach ($templates as $template): ?>
			<option value="<?=$template['id']?>"><?=$template['number']?> - <?=$template['title']?></option>
			
			<? endforeach; ?>
		    </optgroup>
		</select>
		
	    </td>
	  </tr>
	  <tr>
	    <td class="field_title"><span style="display:none" class="tabletextrequire" id="error_number">*</span><?=$kis_lang['noticenumber']?></td>
	    <td>
		<input name="number" required value="<?=$is_copy?'': $notice['number']?>" class="notice_number" class="textboxnum" type="text">
		<span class="notice_number_status">
		    <span class='notice_number_yes' style='color:green;display:none;'>
			"<em></em>" <?=$kis_lang['isnotusedyet']?>
		    </span>
		    <span class="notice_number_no" style='color:red;display:none;'>
			"<em></em>" <?=$kis_lang['isusedalready']?>!
		    </span>
		    
		</span>
	    </td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['noticetitle']?></td>
	    <td><input name="title" required value="<?=$notice['title']?>" class="textboxtext" type="text"></td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['issuedate']?></td>
	    <td><input name="issue_date" readonly required value="<?=$notice['issue_date']?$notice['issue_date']:date('Y-m-d', $request_ts)?>" size="15" type="text"></td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['duedate']?></td>
	    <td><input name="due_date" readonly required value="<?=$notice['due_date']?$notice['due_date']:date('Y-m-d', strtotime("+$default_return_days day"))?>" size="15" type="text"></td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['noticecontent']?></td>
	    <td>
	      <textarea name="description" id="editor_content" style="display:none"><?=$notice['description']?></textarea>
	    </td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['attachments']?></td>
	    <td><div class="attachment_list mail_icon_form">
		<div class="Content_tool">
		    <a href="#" id="uploader_button" class="new"><?=$kis_lang['add']?></a>
		</div>
		
		<p class="spacer"></p>
		<? foreach ((array)$notice['attachments'] as $attachment): ?>
		<div class="attachment"><a class="btn_attachment" href="./apps/enotice/ajax.php?action=getfile&notice_id=<?=$notice['id']?>&file_name=<?=$attachment['name']?>"><?=$attachment['name']?></a>
		    <a href="#" class="btn_remove" title="<?=$kis_lang['remove']?>"></a>
		    <? if ($is_copy): ?>
			<input name="new_files[]" value="<?=$attachment['url']?>" type="hidden"/>
		    <? endif; ?>
		</div>
		<? endforeach; ?>
	    </div></td>
	  </tr>
	  <tr>
	    <td class="field_title"><?=$kis_lang['replyslip']?> <td>
	    <? if (!$notice['total_signed_students']): ?>
	    <div id="notice_questions">
		<input class="notice_preview_reply formsmallbutton" value="<?=$kis_lang['preview']?>" type="button"/>
		<div class="notice_questions_choose">
		    <strong><?=$kis_lang['new']?></strong>
		    <? foreach (kis_enotice::$question_types as $question_type): ?>
	    
		    <a href="#">
			<?=$kis_lang[$question_type]?>
			<input type="hidden" class="type" value="<?=$question_type?>"/>
		    
		    </a>
	    
		    <? endforeach; ?>
		</div>
		<p class="spacer"></p>
		<div class="notice_paper">
		   
		    <? foreach ((array)$notice['questions'] as $i=>$question): ?>
		    
		    <div class="notice_question <?=$question['type']?>">
			<strong><?=$i+1?></strong>
			<div class="notice_question_type"><?=$kis_lang[$question['type']]?></div>
			
			<div class="notice_question_container">
			    <? kis_ui::loadTemplate('questions/'.$question['type'], array('question'=>$question,  'answers'=> array(), 'attr'=>'disabled')) ?>
			</div>
			<div class="table_row_tool">
			    <a href="#" title="<?=$kis_lang['remove']?>" class="delete_dim"></a>
			</div>
			
			<p class="spacer"></p>
		    </div>
	
		    <? endforeach; ?>
		    <? kis_ui::loadNoRecord($notice['questions']?'display:none':'') ?>
		</div>
		
	    </div>
	    <p class="spacer"></p>
	    <input name="answer_all" id="answer_all" value="1" <?=$notice['answer_all']? 'checked':''?> type="checkbox"> <label for="answer_all"><?=$kis_lang['allquestionsrequireanswer']?></label> <br>

	    <? else: ?>
	    <input name="answer_all" id="answer_all" value="1" <?=$notice['answer_all']? 'checked':''?> disabled type="checkbox"> <label for="answer_all"><?=$kis_lang['allquestionsrequireanswer']?></label> <br>

	    <? endif; ?>
	    <input name="display_number" id="display_number" value="1" <?=$notice['display_number']? 'checked':''?>  type="checkbox"> <label for="display_number"><?=$kis_lang['displayquestionnumber']?></label>
	    </td>
					
	  </tr>
	  <tr>
	    <td class="field_title" rowspan="3"><?=$kis_lang['type']?>
	    </td>
	    <td> 
		<input name="status" id="status_1" value="1" <?=$notice['status']==1||!$notice? 'checked':''?> type="radio"><label for="status_1"><?=$kis_lang['tobedistributed']?></label><br>
		<span style="margin-left:20px;">
		    <input <?=$notice['status']==1? '':'disabled'?> name="email_parents" id="email_parents" value="1" type="checkbox"> <label for="email_parents"><?=$kis_lang['notifyparentsemail']?></label><br>
		</span>
		<span style="margin-left:20px;">
		    <input <?=$notice['status']==1? '':'disabled'?> name="email_students" id="email_students" value="1" type="checkbox"> <label for="email_students"><?=$kis_lang['notifystudentsemail']?></label>
		</span>
	    </td>
	  </tr>
	  <tr>
	    <td>
		<input name="status" id="status_2" value="2" <?=$notice['status']==2? 'checked':''?>  type="radio"><label for="status_2"><?=$kis_lang['nottobedistributed']?></label>
		<? if ($notice['total_signed_students']): ?>
		<span class="tabletextrequire" style="display:none">(<?=$kis_lang['allreplieswillberemoved']?>!)</span></td>
		<? endif; ?>
	    </td>
	  </tr>
	    <tr>
	    <td>
		<input name="status" type="radio" value="3" <?=$notice['status']==3? 'checked':''?>  id="status_3" value="3">
		<label for="status_3"><?=$kis_lang['saveastemplate']?></label><br/>
		(<?=$kis_lang['only']?> <font color="green"><?=$kis_lang['noticetitle']?></font>, <font color="green"><?=$kis_lang['noticecontent']?></font> <?=$kis_lang['and']?> <font color="green"><?=$kis_lang['replyslip']?></font> <?=$kis_lang['willbeusedintemplate']?>)<br>									</td>
	    </tr>
	  </tbody><colgroup><col class="field_title">
	    <col class="field_c">
	    </colgroup></table>

	    <p class="spacer"></p><p class="spacer"></p><br>
        </div>
        <div class="edit_bottom">
            <input class="formbutton" value="<?=$kis_lang['submit']?>" type="submit">
            <input class="formsubbutton" value="<?=$kis_lang['cancel']?>" type="button">
        </div>
	<input type="hidden" name="notice_id" value="<?=$is_copy? '':$notice['id']?>"/>

    </form>
</div>


<div id="notice_question_preview" style="display:none;width:720px;">
    <div class="notice_paper_top"><div class="notice_paper_top_right"><div class="notice_paper_top_bg"></div></div></div>
    <div class="notice_paper_content"><div class="notice_paper_content_right"><div class="notice_paper_content_bg">
                
	
    </div></div></div>
    
    <div class="notice_paper_bottom"><div class="notice_paper_bottom_right"><div class="notice_paper_bottom_bg">
    </div></div></div>
</div>
<ul id="search_user_template" style="display:none">
    <li class="user" title="<?=$kis_lang['users']?>">
	<span></span>
	<div class="table_row_tool">
	    <a title="<?=$kis_lang['remove']?>" class="add" href="#"></a>
	    <a title="<?=$kis_lang['remove']?>" class="delete" href="#"></a>
	    <input type="hidden" name="users[]" disabled class="id" value=""/>
	</div>
	
    </li>
</ul>
<div id="notice_question_template">
    
    <? foreach (kis_enotice::$question_types as $question_type): ?>
    <div class="notice_question <?=$question_type?>" style="display:none;">
	<strong></strong>
	<div class="notice_question_type"><?=$kis_lang[$question_type]?></div>
	
	<div class="notice_question_container">
	    <? kis_ui::loadTemplate('questions/'.$question_type, array('question'=>array('options'=>array('','')),  'answers'=> array(), 'attr'=>'disabled')) ?>
	</div>
	<div class="table_row_tool">
	    <a href="#" title="<?=$kis_lang['remove']?>" class="delete_dim"></a>
	</div>
	<p class="spacer"></p>
    </div>
    <? endforeach ?>
</div>

                    