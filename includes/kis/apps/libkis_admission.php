<?

class kis_admission extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $plugin;
	
	if ($plugin['eAdmission']){
	    
	    if ($user_type == kis::$user_types['teacher']){
		return array('admission', 'btn_admission', 'wood', '');
	    }
	}
	
	return array();	
    }
            
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-'eAdmission'"]){
	    return array('');
	}
	return array();
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	return 3;
	
    }
    public function __construct($user_id, $user_type, $student_id, $params){

	
	
    }
    
}
?>