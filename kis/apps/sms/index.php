<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');
$libkis_message = $libkis->loadApp('sms');
if ($kis_user['type']==kis::$user_types['teacher']){
    
    switch ($q[0]){
	
	case 'usagereport':
	    $main_template ='usagereport';
	break;
    
	case 'messagetemplates':
	    $main_template ='messagetemplates';
	break;
	
	default:
	
	    if ($q[1]=='new'){

		$main_template ='message_new';
	    }else if ($q[1]){
		
		
		$main_template ='message_log';
	    }else{
		
		$main_template ='messages';
	    }
	    
	break;
	
    }

}

?>

<? if ($kis_user['type']==kis::$user_types['teacher']) : ?>

<div class="content_board_menu">
    
    <? kis_ui::loadLeftMenu(array('messages','','usagereport','','messagetemplates'), $q[0], '#/apps/sms/'); ?>

    <div class="main_content">
    <? kis_ui::loadTemplate($main_template, $kis_data);?>
    </div>
    <p class="spacer"></p>

</div>

<? endif; ?>