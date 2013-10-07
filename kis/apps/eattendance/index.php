<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

if ($kis_user['type']==kis::$user_types['teacher']){
    
    switch ($q[0]){

	case 'monthlyrecord':
	    
	    $libkis_attendance = $libkis->loadApp('eattendance');
	    
	    $kis_data['classes'] = kis_utility::getAcademicYearClasses();
	    $kis_data['years'] = $libkis_attendance->getAttendanceRecordedYears();
	    $kis_data['current_academic_year_id'] = $_SESSION['CurrentSchoolYearID'];
	    
	    $kis_data['main_template'] = 'monthlyrecord_form';
	break;
    
	case 'reminderrecord':
	    $libkis_attendance = $libkis->loadApp('eattendance');
	    
	    if ($q[1]=='create'){
		
		$kis_data['today_date'] = date('Y-m-d');
		$kis_data['classes'] = kis_utility::getAcademicYearClasses();
		$kis_data['teachers'] = kis_utility::getUsers(array('user_type'=>kis::$user_types['teacher']));
		
		$kis_data['main_template'] = 'reminderrecord_create';
	    
	    }else if ($q[1]=='edit' && $kis_data['reminder']=$libkis_attendance->getReminderRecord($reminder_id)){
		
		$kis_data['teachers'] = kis_utility::getUsers(array('user_type'=>kis::$user_types['teacher']));
		
		$kis_data['main_template'] = 'reminderrecord_edit';
		
	    }else{
		
		$kis_data['page']   = $page?   $page: 1;
		$kis_data['amount'] = $amount? $amount: 20;
		$kis_data['offset'] = $amount*($page-1);
		    
		list($kis_data['total'], $kis_data['reminders']) = $libkis_attendance->getReminderRecords(
		    array('status'=>$status,'keyword'=>$search), $sortby, $order, $kis_data['amount'], $kis_data['page']
		);
	      
		$kis_data['main_template'] = 'reminderrecord';
	    
	    }

	break;

	default:
	
	    $target_ts = $date? strtotime($date): time();
	    $target_date = getdate($target_ts);
	    $kis_data['date'] = date('Y-m-d', $target_ts);
	    $kis_data['apm'] = $time? $time: 'am';
	    
	    
	    $params = array('year'=>$target_date['year'], 'month'=>$target_date['mon'], 'day'=>$target_date['mday'], 'class_id'=>$class_id);
	
	    $libkis_attendance = $libkis->loadApp('eattendance', $params);
	    
	    if ($class_id){
				
		$kis_data['students'] = $libkis_attendance->getClassDayAttendanceRecords($search, $sortby, $order);
		$kis_data['classes'] = kis_utility::getAcademicYearClasses();
		
		$kis_data['main_template'] = 'takeattendance_students';
	    }else{
		
		$kis_data['classes'] = $libkis_attendance->getAllClassesDayAttendanceSummary($search, $kis_data['apm']);
		$kis_data['main_template'] = 'takeattendance_classes';
	    }
	    
	break;
	
    }
    
}else{
     
    $kis_data['current_date']  = getdate();
    $kis_data['year']  = $q[0]? $q[0]: $kis_data['current_date']['year'];
    $kis_data['month'] = $q[1]? $q[1]: $kis_data['current_date']['mon'];
    
    $params = array('year'=>$kis_data['year'], 'month'=>$kis_data['month']);
    $libkis_attendance = $libkis->loadApp('eattendance', $params);
   
    
    if ($q[1]){
	
	$kis_data['attend_days'] = $libkis_attendance->getStudentMonthAttendances();
	$kis_data['main_template'] = 'monthlyrecord_parent';
    }else{
	
	$kis_data['summary_months'] = $libkis_attendance->getStudentYearAttendanceSummary();
	$kis_data['main_template'] = 'summary';
    }
    
	
}

kis_ui::loadTemplate('main', $kis_data, $format);
?>