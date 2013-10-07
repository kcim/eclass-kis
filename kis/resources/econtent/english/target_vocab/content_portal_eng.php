<?php
	$items = array("Head", "Eyes", "Nose", "Mouth", "Ear", "Tooth", "Hand", "Arm", "Foot", "Leg", "Finger", "Tongue");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>::eClass :: Content Resources :: English :: Target Vocabulary ::</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" href="css/content_style.css" type="text/css" media="screen" />
	<script type="text/javascript" src="script/jquery.min.js"></script>
	<script type="text/javascript" src="script/common.js"></script>
	<script type="text/javascript">
		var current_item_number = 1;		
		
		window.onload = function(){				
			$("#id_right_frame").attr("src","vocab_page_input.php?" + "vocab="+ "<?php echo($items[0]);?>");
			
			$("[name='btn_item']").click(function(){
				$(".panel_left_item_select").attr("class", "panel_left_item");
				$(this).attr("class", "panel_left_item_select");
				$("[name='current_item']").html($(this).attr("item_name"));
				$("[name='title_num']").html("(" + $(this).attr("item_number") + " of " + <?php echo(sizeof($items));?> + ")");
				
				$("#id_right_frame").attr("src","vocab_page_input.php?" + "vocab="+ $(this).attr("item_name"));
				current_item_number = $(".panel_left_item_select").attr("item_number");
			});
			
			$("#id_btn_next").click(function(){ 
				var sum = <?php echo sizeof($items); ?>;
				if(current_item_number == sum){
					current_item_number = 1;
				}else current_item_number++;
				
				$("#item_"+current_item_number).trigger("click");
			});
			
			$("#id_btn_prev").click(function(){ 
				var sum = <?php echo sizeof($items); ?>;
				if(current_item_number == 1){
					current_item_number = sum;
				}else current_item_number--;
				
				$("#item_"+current_item_number).trigger("click");
			});
		};
	</script>
</head>
<body>

<body class="content_panel_eng">
	<div class="content_panel_left" id="id_panel_left">
		<div class="left_header">
			<div class="left_icon_eng"></div>
			<div class="left_title" id="id_left_title">
				<span name="current_item"><?php echo($items[0]);?></span>
				<span class="title_num"><?php echo(sizeof($items));?></span>
			</div>
		</div>
		<div class="panel_left_list">
			<?php 
				for($i = 0; $i < sizeof($items) ; $i++){
					$item_number = $i +1;
					if($i == 0){
						echo ( '<div item_name="'.$items[$i].'" item_number="'.$item_number.'" class="panel_left_item_select" 
						name="btn_item" id="item_'.$item_number.'">'.$items[$i].'</div>');
					}else{
						echo ('<div item_name="'.$items[$i].'" item_number="'.$item_number.'" class="panel_left_item" 
						name="btn_item" id="item_'.$item_number.'">'.$items[$i].'</div>');					
					}
				}
			?>
		</div>
	</div>
	
	<div class="content_panel_right" id="id_panel_right">
		<div class="right_header">
			<div class="right_title_bg"></div>
			<div class="right_icon" id="id_list_ctrl" onclick="ShowList();"></div>
			<div class="right_title" id="id_right_title">
				<span name="current_item"><?php echo($items[0]);?></span>
				<span name="title_num" class="title_num">(1 of <?php echo(sizeof($items));?>)</span>
			</div>
			<div class="btn_prev" id="id_btn_prev"></div>
			<div class="btn_next" id="id_btn_next"></div>
			<div class="btn_close" id="id_btn_close"></div>
		</div>
		
		<iframe class="right_frame" id="id_right_frame" ></iframe>
	</div>
	
</body>
</html>