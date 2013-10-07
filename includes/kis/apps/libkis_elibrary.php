<?
include_once("$intranet_root/includes/libelibrary_plus.php");

class kis_elibrary extends libdb implements kis_apps {
        
    private $user_id;
    
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $plugin;
    
	$permission = elibrary_plus::getUserPermission($user_id);
	
	if ($permission['admin'] || $permission['circulation']){
	    return array('elibrary', 'btn_elib', '', '/home/library_sys/admin/book/');
	   	    
	}/*else if ($plugin['library_management_system'] && $permission['elibplus_opened']){
	    return array('elibrary', 'btn_elib', '', '/home/eLearning/elibplus/');
	}*/
	
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