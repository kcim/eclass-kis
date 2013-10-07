<?

class kis_econtent extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $kis_config;
	
	/*
	if ($kis_config['econtent']){
	    return array('econtent', 'btn_econtent', '', '');
	}
	*/
	
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