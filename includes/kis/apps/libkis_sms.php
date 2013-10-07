<?

class kis_sms extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-SMS"]){
	    if ($user_type == kis::$user_types['teacher']){
		return array('sms', 'btn_sms', 'wood', '');
	    }
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