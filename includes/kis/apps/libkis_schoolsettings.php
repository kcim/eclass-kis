<?

class kis_schoolsettings extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $plugin;

	if ($_SESSION["SSV_USER_ACCESS"]["SchoolSettings-Campus"] && $plugin['Inventory']){
	    return array('schoolsettings', 'btn_school_setting', '', '/home/system_settings/location/');
	}
	if ($_SESSION["SSV_USER_ACCESS"]["SchoolSettings-Class"]){
	    return array('schoolsettings', 'btn_school_setting', '', '/home/system_settings/form_class_management/');
	}
	if ($_SESSION["SSV_USER_ACCESS"]["SchoolSettings-Group"]){
	    return array('schoolsettings', 'btn_school_setting', '', '/home/system_settings/group/?clearCoo=1');
	}
	if ($_SESSION["SSV_USER_ACCESS"]["SchoolSettings-SchoolCalendar"]){
	    return array('schoolsettings', 'btn_school_setting', '', '/home/system_settings/school_calendar/');
	}
	if ($_SESSION["SSV_PRIVILEGE"]["schoolsettings"]["isAdmin"]){
	    return array('schoolsettings', 'btn_school_setting', '', '/home/system_settings/role_management/');	    
	}
	
	return array();
    }
            
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	return array();
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	return 0;
	
    }
    public function __construct($user_id, $user_type, $student_id, $params){

	
	
    }
    
}
?>