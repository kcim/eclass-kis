<?php
	
	# Process $vocab_ary
	$word_display 	= trim($vocab);
	$vocab_class  	= strlen($word_display) > 16? "text_long" : "text_normal";
	$img_path 		= strtolower($word_display).".jpg";
	$mp3_path 		= strtolower($word_display).".mp3";
	$vocab_input  	= "<input id=\"vocab_input\" type=\"text\" class=\"vocab_input_box\" style=\"display:none\"/>";
	
	// $vocab_list["afternoon_n"] 	= array("word"=>"afternoon", "parts"=>"n", "img"=>"afternoon_n.jpg","mp3"=>"afternoon_n.mp3");
	// $vocab_list["friend_n"] 	= array("word"=>"friend", "parts"=>"n", "img"=>"friend_n.jpg","mp3"=>"friend_n.mp3");
	// $vocab_list["go_v"] 		= array("word"=>"go", "parts"=>"v", "img"=>"go_v.jpg","mp3"=>"go_v.mp3");
	// $vocab_list["morning_n"] 	= array("word"=>"morning", "parts"=>"n", "img"=>"morning_n.jpg","mp3"=>"morning_n.mp3");
	// $vocab_list["name_n"] 		= array("word"=>"name", "parts"=>"n", "img"=>"name_n.jpg","mp3"=>"name_n.mp3");
	// $vocab_list["school_n"] 	= array("word"=>"school", "parts"=>"n", "img"=>"school_n.jpg","mp3"=>"school_n.mp3");
	// $vocab_list["spell_v"] 		= array("word"=>"spell", "parts"=>"v", "img"=>"spell_v.jpg","mp3"=>"spell_v.mp3");
	// $vocab_list["teacher_n"] 	= array("word"=>"teacher", "parts"=>"n", "img"=>"teacher_n.jpg","mp3"=>"teacher_n.mp3");
	// $vocab_list["thank_you_ph"]	= array("word"=>"thank you", "parts"=>"ph", "img"=>"thank_you_ph.jpg","mp3"=>"thank_you_ph.mp3");
	
	# Check if target vocab length > 11
	if(!strstr($word_display,' ') && strlen($word_display)<=11){
		$vocab_input 	= "";
		
		for($i=0;$i<strlen($word_display);$i++){
			$vocab_input .= $vocab_input? "&nbsp" : "<div id=\"vocab_input\">";
			$vocab_input .= "<input id=\"vocab_input_$i\" name=\"vocab_input[]\" type=\"text\" class=\"vocab_input_box_s\" style=\"display:none\" maxlength=\"1\"/>";
		}
		$vocab_input .= "</div>";
	}
	
	if ($userBrowser->platform=="iPad" || $userBrowser->platform=="Andriod"){
		$listen_btn = "<a href=\"javascript:void(0)\" class=\"btn_listen\" title=\"Listen\" onclick=\"javascript:playVoice()\">&nbsp;</a>";
		$listen_btn .= "<audio id=\"sound\" preload=\"auto\">
		<source src=\"".$admin_url_path."/files/target_vocab/".$mp3_path."\" type=\"audio/mpeg\" />
		Your browser does not support the audio element. Suggesting best viewed with <a href=\"http://www.google.com/chrome\" target=\"_blank\">Google Chrome</a></audio>";
	}else{
		$listen_btn = "<a href=\"javascript:void(0)\" class=\"btn_listen\" title=\"Listen\" onclick=\"javascript:playVoice()\">&nbsp;</a>";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script language='JavaScript' src='script/jquery.min.js'></script>
<script language='JavaScript' src='script/musicPlayer.js'></script>
<script>
<?php if(!strstr($word_display,' ') && strlen($word_display)<=11){ ?>
		
		$(document).ready(function(){
			$(".vocab_pic").css("background-image", "url(sample/<?php echo $img_path;?>)"); 
			
			$(document).keydown(function(e){
<?php
			for($i=0;$i<strlen($word_display);$i++)
			{
				if ($userBrowser->platform!="iPad"){
					if($i==0)
					{
						echo '$(\'#vocab_input_'.$i.'\').keyup(function(e){
									if(e.keyCode > 48 && e.keyCode < 90)
									{
										$(\'#vocab_input_'.($i+1).'\').focus();
										$(\'#vocab_input_'.($i+1).'\').select();	
									}
							  });';
					}
					else if($i>0 && $i<(strlen($word_display)-1))
					{
						echo '$(\'#vocab_input_'.$i.'\').keyup(function(e){
									if(e.keyCode == 8)
									{
										$(\'#vocab_input_'.($i-1).'\').focus();
									//	$(\'#vocab_input_'.($i-1).'\').select();
									}
									else if(e.keyCode > 48 && e.keyCode < 90)
									{	
										$(\'#vocab_input_'.($i+1).'\').focus();
										$(\'#vocab_input_'.($i+1).'\').select();
									}
							  });';
					}
					else
					{
						echo '$(\'#vocab_input_'.$i.'\').keyup(function(e){
									if(e.keyCode == 8)
									{
										$(\'#vocab_input_'.($i-1).'\').focus();
										$(\'#vocab_input_'.($i-1).'\').select();
									}
									else if(e.keyCode == 13)
									{
										$(\'#vocab_input_'.$i.'\').blur();
										check_vocab();
									}
							  });';
					}
				}else{
					echo '$(\'#vocab_input_'.$i.'\').focus(function() {
							this.select(); 
							this.setSelectionRange(0, 9999);
							
						}).mouseup(function(e){
							e.preventDefault();
						});

						';				
					
				 	echo '$(\'#vocab_input_'.$i.'\').keyup(function(e){
								if(e.keyCode == 13)
								{
									$(\'#vocab_input_'.$i.'\').blur();
									check_vocab();
								}
						  });';	
				}
			}				
?>
				});
				<?=$ss_intranet_plugin['target_vocab_default_spelling_mode']? "spell_vocab();":""?>
			});
		
		function spell_vocab(){
			$('#vocab_content').removeClass('vocab_normal').addClass('vocab_input');
			$('#vocab_text').attr('style','display:none');
			$('#vocab_input').attr('style','');
			$('.vocab_input_box_s').attr('style','');
			$('.vocab_input_box_s').val('');
			$('#vocab_input_0').focus();
			
			$('#std_ans').attr('style','display:none');
			
			$('#vocab_correct').attr('style','display:none');
			
			$('#btn_check').attr('style','');
			$('#btn_check_disable').attr('style','display:none');
			$('#btn_reset').attr('style','');
			
			$('#check_answer_correct').attr('style','display:none');
			$('#check_answer_wrong').attr('style','display:none');
		}
		
		function check_vocab(){
			$('#vocab_input').attr('style','display:none');
			$('.vocab_input_box_s').attr('style','display:none');
			var correct_ans = $('#vocab_text').html();
			var std_ans = $('#vocab_input').val();
			
			var fields  = $(":input[name=vocab_input[]]").serializeArray();
			$.each(fields, function(i, field){
				std_ans = std_ans? std_ans + field.value : field.value;
			});
			
			std_ans_html = std_ans? std_ans : '&nbsp';
			$('#std_ans').html(std_ans_html);
			$('#std_ans').attr('style','');
			
			if(correct_ans.toLowerCase() == std_ans.toLowerCase()){
				$('#vocab_correct').attr('style','');
				$('#check_answer_correct').attr('style','');
				$('#check_answer_wrong').attr('style','display:none');
				
				var result_message = "Good job!";
				$('#result_message_correct').html(result_message);
			}
			else{
				$('#check_answer_correct').attr('style','display:none');
				$('#check_answer_wrong').attr('style','');
				
				var result_message = "Work hard, dear. This is the correct spelling:";
				$('#result_message_wrong').html(result_message);
				$('#show_answer').html(correct_ans);
			}
		}
		
		function page_reset(){
			$('#vocab_content').removeClass('vocab_input').addClass('vocab_normal');
			$('#vocab_text').attr('style','');
			$('#vocab_input').attr('style','display:none');
			$('.vocab_input_box_s').attr('style','display:none');
			
			$('#std_ans').attr('style','display:none');
			
			$('#btn_check').attr('style','display:none');
			$('#btn_check_disable').attr('style','');
			$('#btn_reset').attr('style','display:none');
			
			$('#vocab_correct').attr('style','display:none');
			
			$('#check_answer_correct').attr('style','display:none');
			$('#check_answer_wrong').attr('style','display:none');
		}
		
<?php } 
	  else { ?>
	  	
	  	$(document).ready(function(){
			$(document).keydown(function(e){
				$('#vocab_input').keyup(function(e){
					if(e.keyCode == 13)
					{
						$('#vocab_input').blur();
						check_vocab();
					}
				});
			});
			<?=$ss_intranet_plugin['target_vocab_default_spelling_mode']? "spell_vocab();":""?>		
		});
	  	
		function spell_vocab(){
			$('#vocab_content').removeClass('vocab_normal').addClass('vocab_input');
			$('#vocab_text').attr('style','display:none');
			$('#vocab_input').attr('style','');
			$('#vocab_input').val('');
			$('#vocab_input').focus();
			
			$('#std_ans').attr('style','display:none');
			
			$('#vocab_correct').attr('style','display:none');
			
			$('#btn_check').attr('style','');
			$('#btn_check_disable').attr('style','display:none');
			$('#btn_reset').attr('style','');
			
			$('#check_answer_correct').attr('style','display:none');
			$('#check_answer_wrong').attr('style','display:none');
		}
		
		function check_vocab(){
			$('#vocab_input').attr('style','display:none');
			
			var correct_ans = $('#vocab_text').html();
			var std_ans = $('#vocab_input').val();
			std_ans_html = std_ans? std_ans : '&nbsp';
			$('#std_ans').html(std_ans_html);
			$('#std_ans').attr('style','');
			
			if(correct_ans.toLowerCase() == std_ans.toLowerCase()){
				$('#vocab_correct').attr('style','');
				$('#check_answer_correct').attr('style','');
				$('#check_answer_wrong').attr('style','display:none');
				
				var result_message = "Good job!";
				$('#result_message_correct').html(result_message);
			}
			else{
				$('#check_answer_correct').attr('style','display:none');
				$('#check_answer_wrong').attr('style','');
				
				var result_message = "Work hard, dear. This is the correct spelling:";
				$('#result_message_wrong').html(result_message);
				$('#show_answer').html(correct_ans);
			}
		}
				
		function page_reset(){
			$('#vocab_content').removeClass('vocab_input').addClass('vocab_normal');
			$('#vocab_text').attr('style','');
			$('#vocab_input').attr('style','display:none');
			
			$('#std_ans').attr('style','display:none');
			
			$('#btn_check').attr('style','display:none');
			$('#btn_check_disable').attr('style','');
			$('#btn_reset').attr('style','display:none');
			
			$('#vocab_correct').attr('style','display:none');
			
			$('#check_answer_correct').attr('style','display:none');
			$('#check_answer_wrong').attr('style','display:none');
		}
		
<?php } ?>
		function playVoice(){
			<?php if ($userBrowser->platform=="iPad" || $userBrowser->platform=="Andriod"){ ?>
				document.getElementById("sound").play();
			<?php }else{ ?>
				$('#mp3_player').insertPlayer("sample/<?=$mp3_path?>",1,"flash/powervoice_player.swf");
			<?php } ?>
		}
</script>

<link href="css/target_vocab.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="main_board">
	<div class="main_board_content">
        <div class="main_btn">
            <?=$listen_btn?>
            <a href="javascript: void(0)" class="btn_spell" title="Spell" onclick="javascript:spell_vocab()">&nbsp;</a>
            <a href="javascript: void(0)" class="btn_check" title="Check" onclick="javascript:check_vocab()">&nbsp;</a>
            <!--<span class="btn_check_disable">&nbsp;</span>-->
        </div>
        
    
    	<div class="vocab_pic">
         <span>&nbsp;</span>
        </div>
      <div id="vocab_content" class="vocab_input text_normal"><!-- display only or input : vocab_normal, vocab_input | text size :text_normal, text_long  -->
        	<div class="vocab_text_top">
            	<!--<span class="vocab_text">Rainbow</span>-->
              <span id="vocab_text" class="vocab_text" style="display:none"><?=$word_display?></span>			  
	          <span id="std_ans" class="vocab_text" style="display:none"></span>
	          <?=$vocab_input?>
           	  <span class="vocab_correct" style="display:none"></span>
            <p class="spacer"></p>
          </div>
          <div class="vocab_text_bottom"></div>
		  <div class="vocab_type">(noun)</div>
          <p class="spacer"></p>
           
		   <!-- Show below content if the answer is correct -->
			<div id="check_answer_correct" class="result_correct" style="display:none">
				<span id="result_message_correct" class="result_message"></span>
			</div>
			
			<!-- Show below content if the answer is wrong -->          
			<div id="check_answer_wrong" class="result_wrong" style="display:none">
				<span id="result_message_wrong" class="result_message"></span>
				<span id="show_answer" class="show_answer"></span>
			</div> 
      </div>
    	
	<div id="mp3_player"></div>
    <p class="spacer"></p>
    </div>
  <div class="main_board_bottom"> 
    	<div class="reset_btn"><a href="vocab_page.htm" title="Reset">&nbsp;</a> <p class="spacer"></p></div>
   	  <div class="main_board_footer">Powered by <a href="#"><img src="images/logo_eclass_footer.gif" align="absmiddle" /></a></div>
  </div>
</div>
</body>

</html>
