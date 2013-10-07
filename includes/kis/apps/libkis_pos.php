<?

class kis_pos extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
	
	global $plugin;
	
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-ePOS"] && $plugin['ePOS'] && $plugin['payment']){
	    return array('pos', 'btn_pos', '', '/home/eAdmin/GeneralMgmt/pos');
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