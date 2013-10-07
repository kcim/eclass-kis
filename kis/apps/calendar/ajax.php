<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');
$libkis_calendar = $libkis->loadApp('calendar',array('year'=>$year));

switch($action){
    
    case 'getmonthlyevents':

	kis_ui::loadTemplate('monthly_events', $kis_data);
    break;
    
}

?>
