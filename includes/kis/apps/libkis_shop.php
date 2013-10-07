<?

class kis_shop extends libdb implements kis_apps {
        
    private $user_id;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	return array();
	//$image = $user_type == kis::$user_types['teacher']? 'btn_shop': 'btn_shop_dim';
	//return array('shop', 'btn_shop', '', '');
	
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