<?

$PATH_WRT_ROOT = "../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$libkis_accountmanage = $libkis->loadApp('accountmanage', array('target_user_id'=>$kis_user['id']));

$kis_data['user_detail'] = $libkis->getUserDetail();
$kis_data['permission'] = $libkis_accountmanage -> getUserTypePermission($kis_user['type']);

switch ($q[0]){
    
    case 'contactinfo':
	
	$main_template = 'contactinfo';
    break;

    case 'changepassword':
	
	if ($kis_data['permission']['CanUpdatePassword']){
	    $kis_data['token'] = $_SESSION['kis']['password_token'] = md5(time().mt_rand());
	    $main_template = 'changepassword';
	    
	}else{
	    $main_template = 'personalinfo';
	}
	
    break;

    default:
    
	$main_template = 'personalinfo';
    break;
 
}

$menu_items = $kis_data['permission']['CanUpdatePassword']? array('personalinfo','','contactinfo','','changepassword'): array('personalinfo','','contactinfo');

?>
<div class="content_board_menu">
<? kis_ui::loadLeftMenu($menu_items, $q[0], "#/myaccount/");?>
    <div class="main_content">
    <? kis_ui::loadTemplate($main_template, $kis_data); ?>
    </div><p class="spacer"></p>
</div>