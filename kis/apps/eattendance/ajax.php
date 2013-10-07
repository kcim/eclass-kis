<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');



if ($kis_user['type']==kis::$user_types['teacher']){
    
    $target_date = getdate($date? strtotime($date): time());
    $params = array('year'=>$target_date['year'], 'month'=>$target_date['mon'], 'day'=>$target_date['mday']);
    $libkis_attendance = $libkis->loadApp('eattendance', $params);

    switch ($action){

	case 'setattendance':
	    
	    $libkis_attendance->setAttendance(array(
		'status'=>$status,
		'in_school_time'=>$in_school_time,
		'leave_school_time'=>$leave_school_time,
		'reason'=>$reason,
		'remark'=>$remark				    
	    ), $apm);
	    
	    $attendance = $libkis_attendance->getAttendance();
	
	    $kis_data['modified']=$attendance[$apm.'_modified'];
	    $kis_data['modified_by']=$attendance[$apm.'_modified_by_'.$lang];
	    $kis_data['reason'] = $attendance[$apm.'_reason'];
	    $kis_data['remark'] = $attendance[$apm.'_remark'];
	    
	    echo $libjson->encode($kis_data);
	    
	break;
    
	case 'createreminderrecords';
	    	    
	    switch ($type){
		
		case 'single':
		    
		    $ts = strtotime($date);
		    $weekdays = array(1,1,1,1,1,1,1);
		    
		    $libkis_attendance->createDailyReminderRecords($students, $teacher, $reason, $ts, $ts, $weekdays);
		    
		break;
		    
		case 'daily':
		    
		    $start_ts = strtotime($start_date);
		    $end_ts = strtotime($end_date);
		    $weekdays = array(1,1,1,1,1,1,1);
		    
		    $libkis_attendance->createDailyReminderRecords($students, $teacher, $reason, $start_ts, $end_ts, $weekdays);
		    
		break;
		    
		case 'weekly':
		    
		    $start_ts = strtotime($start_date);
		    $end_ts = strtotime($end_date);
		    $weekdays = array($sunday,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday);
		    
		    $libkis_attendance->createDailyReminderRecords($students, $teacher, $reason, $start_ts, $end_ts, $weekdays);
		    
		break;
		
		
	    }
	    
	break;
	    
	case 'updatereminderrecord':
	    
	    $ts = strtotime($date);
	    
	    $libkis_attendance->updateReminderRecord($reminder_id, $teacher, $reason, $ts);
	    
	break;
    
	case 'removereminderrecord':
	    
	    $libkis_attendance->removeReminderRecord($reminder_id);
	    
	break;
    
	
    }
    
}

?>
