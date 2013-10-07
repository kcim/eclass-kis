<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

if ($kis_user['type']==kis::$user_types['teacher']){
    
    $libkis_enotice = $libkis->loadApp('admission');
    
    switch ($q[0]){
	
	case 'settings':
	    
	    switch ($q[1]){
	    	case 'applicationform':
		    $main_template = 'settings/applicationform';
		break;
		default:
		    if ($q[2] == 'edit'){
			
			$main_template = 'settings/applicationperiod_form';
			
		    }else{
			
			$main_template = 'settings/applicationperiods';
		    }
		    
		break;
	    
	    }
	break;
    
	default:
	
	    if ($q[2]=='edit'){
					
		$main_template = 'level_form';
		
	    }else if ($q[2]){
		if ($q[3]=='edit'){
		    
		    $main_template = 'application_form';
		    
		}else if ($q[3]){
		    
		    switch ($q[4]){
			
			case 'parentinfo':
			    $main_template = 'application/parentinfo';
			break;
			case 'extramarks':
			    $main_template = 'application/extramarks';
			break;
			case 'paymentanddocs':
			    $main_template = 'application/paymentanddocs';
			break;
			default:
			    $main_template = 'application/studentinfo';
			break;
			
			
		    }
		    
		}else{
		    $main_template = 'applications';
		}
		
		
	    }else{
		$main_template = 'levels';
	    }
	    
	break;
	
    }
    
}else if ($kis_user['type']==kis::$user_types['parent']){

    
}

?>

    
<? if ($kis_user['type']==kis::$user_types['teacher']) : ?>

<div class="content_board_menu">
    
    <? kis_ui::loadLeftMenu(array('applicantslist','','settings'), $q[0], '#/apps/admission/'); ?>

    <div class="main_content">
    <? kis_ui::loadTemplate($main_template, $kis_data);?>
    </div>
    <p class="spacer"></p>

</div>

<? elseif ($kis_user['type']==kis::$user_types['parent']): ?>
    
    <div class="main_content">
   
    </div>
    <p class="spacer"></p>
<? endif; ?>