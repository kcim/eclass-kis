<?

class kis_website extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	return array();
	/*
	$title = $user_type == kis::$user_types['teacher']? 'websitecms': 'website';
	return array($title, 'btn_website', '', '/kis/website/');
	*/
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