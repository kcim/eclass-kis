<script>
$(function(){
    kis.enotice.notice_detail_parent_init({
	answer_all: <?=(int)$notice['answer_all']?>,
	questions_count: <?=sizeof($notice['questions'])?>,
	notice_id: '<?=$notice['id']?>',
	hide_button_after_submit: <?=(int)$hide_button_after_submit?>,
	student_id: '<?=$student['user_id']?>'
    },{
	pleaseanswerallquestions: '<?=$kis_lang['pleaseanswerallquestions']?>',
	signnotice: '<?=$kis_lang['signnotice']?>',
	signedby: '<?=$kis_lang['signedby']?>'
    });
	
    
});


</script>
<div class="notice_paper">
            	<div class="notice_paper_top"><div class="notice_paper_top_right"><div class="notice_paper_top_bg">
                    <h1 class="notice_title"><?=$notice['title']?></h1>
                    <div class="notice_num"><?=$notice['number']?></div>
                    <div class="notice_date"><?=$kis_lang['issuedate']?> : <?=$notice['issue_date']?> <br>
                    <?=$kis_lang['duedate']?> : <?=$notice['due_date']?></div>
                </div></div></div>
                
                <div class="notice_paper_content"><div class="notice_paper_content_right"><div class="notice_paper_content_bg">
                
		    <div class="notice_content">
			<?=$notice['description']?>
		    </div><p class="spacer"></p>
		    <?= $kis_lang['student']?>: <?=$student['user_name_'.$lang]?> (<?=$student['class_name']?>)
		    <p class="spacer"></p>
                    <div class="mail_icon_form">
			<? foreach ($notice['attachments'] as $attachment): ?>
			    <a class="btn_attachment" href="./apps/enotice/ajax.php?action=getfile&notice_id=<?=$notice['id']?>&file_name=<?=$attachment['name']?>"><?=$attachment['name']?></a>
			<? endforeach; ?>
			
		    </div>
		    <p class="spacer"></p>
		    <form id="notice_reply_form" >
		    <? foreach ($notice['questions'] as $i=>$question): ?>
                    <div class="notice_reply">
			
			<?=$notice['display_number']? ($i+1).'.': ''?>
			<? kis_ui::loadTemplate('questions/'.$question['type'], array('question'=>$question, 'answers'=> $reply['answers'][$i]?$reply['answers'][$i]:array(),'attr'=>$replyable?'':'disabled')) ?>
                    </div>
                    <? endforeach; ?>
		    <input type="hidden" name="student_id" value="<?=$reply['student_id']?>"/>
                    <div class="edit_bottom">
			
			<span class="notice_signed" <?=$reply['status']==2? '':'style="display:none;"'?>> <?=$reply['modified']?> <?=$kis_lang['signedby']?> <?=$reply['signer_name_'.$lang]?></span>
			<p class="spacer"></p>
			<? if ($replyable): ?>
                    	<input type="submit" value="<?=$kis_lang['sign']?>" id="sign_notice" class="formbutton">
			<input type="button" value="<?=$kis_lang['printpreview']?>" id="print_notice" class="formbutton">
                        <input type="button" value="<?=$kis_lang['cancel']?>" onclick="history.go(-1)" class="formsubbutton">
			<? else: ?>
			<input type="button" value="<?=$kis_lang['printpreview']?>" id="print_notice" class="formbutton">
                        <input type="button" value="<?=$kis_lang['back']?>" onclick="history.go(-1)" class="formsubbutton">
                        <? endif; ?> 
		        
                        
                    </div>
		    
		    </form>
                </div></div></div>
                
                <div class="notice_paper_bottom"><div class="notice_paper_bottom_right"><div class="notice_paper_bottom_bg">
                </div></div></div>
            	
            </div>