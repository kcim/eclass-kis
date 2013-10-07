<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>:: eClass KIS ::</title>
<link type="text/css" rel="stylesheet" media="screen" href="/templates/jquery/jquery.fancybox.css">
<link type="text/css" rel="stylesheet" media="screen" href="/templates/jquery/jquery.fancybox-thumbs.css">
<link type="text/css" rel="stylesheet" media="screen" href="/templates/jquery/ui-1.9.2/jquery-ui-1.9.2.custom.min.css">
<link type="text/css" rel="stylesheet" media="screen" href="/templates/kis/css/common.css">  
<script type="text/javascript" src="/templates/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/templates/jquery/jquery.address-1.5.min.js"></script>
<script type="text/javascript" src="/templates/jquery/jquery.masonry.min.js"></script>
<script type="text/javascript" src="/templates/jquery/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="/templates/jquery/ui-1.9.2/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="/templates/jquery/ui-1.9.2/jquery.ui.datepicker-zh-HK.js"></script>
<script type="text/javascript" src="/templates/jquery/plupload/plupload.full.js"></script>
<script type="text/javascript" src="/templates/html_editor/fckeditor.js"></script>
<script type="text/javascript" src="/templates/kis/js/config.js"></script>
<script type="text/javascript" src="/templates/kis/js/kis.js"></script>
<script type="text/javascript" src="/templates/kis/js/kis.myaccount.js"></script>
<style>
.ui-autocomplete {max-height: 200px;max-width: 200px;overflow-y: auto;overflow-x: hidden;font-size: 12px;font-family: Verdana, "微軟正黑體";}
.ui-autocomplete-category{font-style: italic;}
.ui-datepicker{font-size: 12px;width: 210px;font-family: Verdana, "微軟正黑體";}
.ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year {width:auto;}
.ui-selectable tr.ui-selecting td, .ui-selectable tr.ui-selected td{background-color: #fff7a3}
</style>
</head>

<body>
    <img src="http://<?=$_SERVER['SERVER_NAME']?>:<?=$_SERVER['SERVER_PORT']?>/images/kis/logo_kis.png" style="position:fixed;top:-100px"/>
    <div id="container" style="display:none">
    <script>
		
	$(function(){
	    
	    kis.init();
	    
	    $(".fancybox-thumb").fancybox({
		prevEffect : 'fade',
		nextEffect : 'fade',
		padding: 0,
		autoSize: true,
		closeBtn: false,
		helpers: {
		    title: {type: 'outside'}
		   
		}
	    });
	    
	    $(".fancybox-ajax").fancybox({
		prevEffect : 'fade',
		nextEffect : 'fade',
		padding: 0,
		type: 'ajax'
	    });
	    
	    $('.btn_logs').click(function(e){
		alert('Coming soon!');
		return false;
	    });
	    
	});
    </script>
    <div id="top_header">

	<a title="eClass KIS" class="logo" href="./" ></a>

	<div class="school_name"></div>
	<div class="user_btn">
	    
	    <a title="Log Out" class="btn_logout en" href="/logout.php">
		<span style="display:none" class="message">Are you sure to log out?</span>
	    </a>
	    <a title="登出" class="btn_logout b5" href="/logout.php">
		<span style="display:none" class="message">確認要登出嗎?</span>
	    </a>
	    
	    <a title="Change to English Language" class="btn_lang_eng b5" href="/lang.php?lang=en"></a>
	    <a title="切換至中文" class="btn_lang_chn en" href="/lang.php?lang=b5"></a>
	    
	    <span class="sep">|</span>
	    <a title="My Account" class="btn_user_acc en" href="#/myaccount/"></a>
	    <a title="我的帳戶" class="btn_user_acc b5" href="#/myaccount/"></a>
	    <span><em class="user_type"></em>, <em class="user_name"></em></span>            
	</div>

	<div class="parent_btn" style="display:none;">
	    <a class="btn_menu btn_menu_on" href="#"><span class="en">Menu</span><span class="b5">選單</span><em style="display:none"></em></a>
	    <div class="student_name_btn"><div class="student_name_btn_inside">
		<span class="student_name"><em class=""></em>
		    <span></span>
		    <select class="student_choose" style="display:none">
		
		    </select>
		</span>
		<span class="student_text en" href="#">Student</span>
		<span class="student_text b5" href="#">學生</span>
		<a class="btn_change_student" href="#"></a>
		<a class="btn_logs en" href="#/logs/"><span>Logs</span></a>
		<a class="btn_logs b5" href="#/logs/"><span>日誌</span></a>
		
		
	    </div></div>
	
	    
	</div>


    </div>
    <div class="container_slider">
	<div id="update_page" class="board" style="opacity:0">
	    <div class="board_top"><div class="board_top_right"><div class="board_top_bg"><div class="board_top_content">
		<div class="update_photo">
		    <img src=""/>
		</div>
		
		<div class="update_info">
			<h1></h1>
			<span class="en">Class</span>
			<span class="b5">班別</span>
			<a class="student_class"></a><br/>
			<span class="en">Class Teacher</span>
			<span class="b5">班主任</span>
			<a class="student_class_teacher"></a>
		</div>
		    
	    </div></div></div></div>
	    <div class="board_main"><div class="board_main_right"><div class="board_main_bg"><div class="board_main_content">
		    
    
	    </div></div></div></div>
	    
	    <div class="board_bottom"><div class="board_bottom_right"><div class="board_bottom_bg"></div></div></div>
	</div>
	
	
	<div id="main_portal" class="board">
		<div class="board_top"><div class="board_top_right"><div class="board_top_bg"><div class="board_top_content">
		
		
		</div></div></div></div>
		<div class="board_main"><div class="board_main_right"><div class="board_main_bg"><div class="board_main_content">
		    <!---->
		    <ul class="module_list">
			
		    </ul>    	
		    <!---->
			     <p class="spacer"></p>
			     
		</div></div></div></div>
		<div class="board_bottom"><div class="board_bottom_right"><div class="board_bottom_bg"></div></div></div>
		
	</div>
        
	<div id="module_page" class="board">
    
	    <div class="board_top"><div class="board_top_right"><div class="board_top_bg"><div class="board_top_content">
		<div class="navigation_bar">
		    <a class="btn_home en" href="#">Home</a>
		    <a class="btn_home b5" href="#">主頁</a>
		    <a class="module_title"></a>
		</div>
		
		<div class="module_admin" style="display:none">
		    <a class="en" title="Admin">Admin</a>
		    <a class="b5" title="管理員">管理員</a>
		</div>
	    </div></div></div></div>
	    
	    <div class="board_main"><div class="board_main_right"><div class="board_main_bg"><div class="board_main_content">
	    </div></div></div></div>
	    
	<div class="board_bottom"><div class="board_bottom_right"><div class="board_bottom_bg"></div></div></div>
		
    </div>
	
    </div>
      <p class="spacer"></p>
    
    </div>
    <div class="board_loading_overlay">
	<div class="b5">載入中...</div>
	<div class="en">Loading...</div>
    </div>
    <div class="attach_file_overlay">
	<div class="b5">將檔案拖放到這裡</div>
	<div class="en">Drop Files Here</div>
    </div>

   <div id="welcome_message" class="bg_message">
    Loading...Please wait
    <p class="logo_kis"></p>
    </div>
 
    
    <form id="login_form" class="login_board" style="display:none">
       	<h1></h1><!-- class="title_chn"-->
        <div class="login_box">
	    <span>Login ID :</span><input type="text" required autofocus name="UserLogin" value=""/>
	    <p class="spacer"></p>
	    <span>Password :</span><input type="password" required name="UserPassword"  value="" />
	    <p class="spacer"></p>
	    <a href="#">Forgot Password?</a>
        </div>
        <div class="btn_login"><a title="Sign In" href="#"></a><input id="login_button" type="image" style="opacity:0"/></div>
        <div class="system_msg">
	  <span class="error_msg" style="display:none;" id="stop_login">Invalid Login ID or Password!</span>
	  <span class="error_msg" style="display:none;" id="stop_">Please Log in again</span>
	  <span class="loading_msg" style="display:none;">Logging In...</span>
	</div>
        <div class="text_copyright">&copy;<?=date('Y')?> BroadLearning Education (Asia) Limited. All rights reserved.</div>          
        <div class="text_browser">
	    <span>Recommended browsers:</span>
	    <a title="Google Chrome" target="_blank" href="http://www.google.com/chrome" class="browser_chrome">12+</a>
	    <a title="Mozilla Firefox" target="_blank" href="http://www.mozilla.org" class="browser_firefox">4+</a>
	    <!--<a title="Internet Explorer" target="_blank" href="http://www.microsoft.com/ie" class="browser_ie">9+</a>-->
	</div>
        <input type="hidden" name="target_url" value="/kis/ajax.php?a=login"/>
    </form>
    <div class="footer"><a title="Powered by eClass" href="http://eclass.com.hk"></a><span></span></div>
</body></html>