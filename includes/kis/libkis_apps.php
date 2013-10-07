<?
/**
 * KIS Apps Interface
 *
 * @author Mick Chiu
 * @since 2012-03-27
 *
 */
interface kis_apps{
               
    public static function getAvailability($user_id, $user_type, $student_id);
    
    public static function getAdminStatus($user_id, $user_type, $student_id);
    
    public static function getNotificationCount($user_id, $user_type, $student_id);
    
    public function __construct($user_id, $user_type, $student_id, $params);
    
}
?>