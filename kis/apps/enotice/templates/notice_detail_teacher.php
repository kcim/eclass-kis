<script>
$(function(){
    kis.enotice.notice_detail_teacher_init();
});

</script>
<div class="main_content_detail">
          
				
    <span style="display:none;" class="notice_title"><?=$notice['number']?> - <?=$notice['title']?></span>

    <? if ($has_read_rights && $kis_data['notice']['status']==1): ?>
	   <? kis_ui::loadModuleTab(array('notice','signresult'), 'notice', '#/apps/enotice/noticelist/'.$notice['id'].'/' )?>
	    <p class="spacer"></p>
    <? endif; ?>
    <!--tab end-->
	
    <? if ($has_edit_rights): ?>
	<div class="common_table_tool"><a href="#/apps/enotice/noticelist/edit/?notice_id=<?=$notice['id']?>" class="tool_edit"><?=$kis_lang['edit']?></a></div>
	<p class="spacer"></p>
    <? endif; ?>
	 <!---->
	
      <p class="spacer"></p>
    <div class="table_board">
                        
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
		    
                    <div class="mail_icon_form">
			<? foreach ($notice['attachments'] as $attachment): ?>
			    <a class="btn_attachment" href="./apps/enotice/ajax.php?action=getfile&notice_id=<?=$notice['id']?>&file_name=<?=$attachment['name']?>"><?=$attachment['name']?></a>
			<? endforeach; ?>
			
		    </div>
		    <p class="spacer"></p>

		    <? foreach ($notice['questions'] as $i=>$question): ?>
                    <div class="notice_reply">

			<?=$notice['display_number']? ($i+1).'.': ''?>
			<? kis_ui::loadTemplate('questions/'.$question['type'], array('question'=>$question,  'answers'=> array(), 'attr'=>'disabled')) ?>
                    </div>
                    <? endforeach; ?>
	
                </div></div></div>
                
                <div class="notice_paper_bottom"><div class="notice_paper_bottom_right"><div class="notice_paper_bottom_bg">
                </div></div></div>
            	
	</div>
    </div>
    <p class="spacer"></p>&nbsp;
</div>
                    