<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>:: eClass KIS ::</title>
    <link type="text/css" rel="stylesheet" href="/templates/kis/css/common.css"/>
    <script>
	window.print();
    </script>
    </head>
    <body style="background:none;">
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
                    <?= $kis_lang['student']?>: <?=$child['user_name_'.$lang]?> (<?=$child['class_name']?>)
		    <p class="spacer"></p>
		    <form id="notice_reply_form" >
		    <? foreach ($notice['questions'] as $i=>$question): ?>
                    <div class="notice_reply">

			<?=$notice['display_number']? ($i+1).'.': ''?>
			<? kis_ui::loadTemplate('questions/'.$question['type'], array('question'=>$question,  'answers'=> $reply['answers'][$i]?$reply['answers'][$i]:array(), 'attr'=>'disabled')) ?>
                    </div>
                    <? endforeach; ?>
		    
		    <div class="edit_bottom">
			<span class="notice_signed" <?=$reply['status']==2? '':'style="display:none;"'?>> <?=$reply['modified']?> <?=$kis_lang['signedby']?> <?=$reply['signer_name_'.$lang]?></span>
			<p class="spacer"></p>
		    </div>
		    </form>
                </div></div></div>
                
                <div class="notice_paper_bottom"><div class="notice_paper_bottom_right"><div class="notice_paper_bottom_bg">
                </div></div></div>
            	
            </div>
    </body>
</html>