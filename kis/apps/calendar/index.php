<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$kis_data['current_date']  = getdate();

$kis_data['current_academic_year_id'] = $_SESSION['CurrentSchoolYearID'];
$kis_data['academic_year_id'] = $q[0]? $q[0]: $_SESSION['CurrentSchoolYearID'];
$kis_data['month_index'] 	= $q[1]? $q[1]: 0;
$kis_data['mday']	= $q[2]? $q[2]: $kis_data['current_date']['mday'];

$libkis_calendar = $libkis->loadApp('calendar',array('academic_year_id'=>$kis_data['academic_year_id']));
$kis_data['academic_years'] = kis_utility::getAcademicYears();
$kis_data['academic_year_months'] = $libkis_calendar->getAcademicYearMonths();

if ($q[2]){
    
    $kis_data['main_template'] = 'daily';
    
    
}else if ($q[1]>0 && $q[1]<=sizeof($kis_data['academic_year_months'])){
    
    $kis_data['months'][$kis_data['month_index']] = $libkis_calendar->getMonthEvents($q[1]);
    $kis_data['main_template'] = 'monthly';
    
    
}else{
    
    $kis_data['months'] = $libkis_calendar->getYearEvents();
    $kis_data['main_template'] = 'yearly';
    
}

if ($kis_user['type']==kis::$user_types['teacher']){
    $kis_data['calendar_menu_editable'] = true;
    $kis_data['last_modifed_user'] = $libkis_calendar->getLastModifedUser();
}

kis_ui::loadTemplate('main', $kis_data, $format);
?>