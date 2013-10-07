<?
//Editing by 

include_once("$intranet_root/includes/libauth.php");
class kis_accountmanage extends libdb implements kis_apps {
        
    private $user_id, $libauth;
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
	
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-AccountMgmt"] || $_SESSION["SSV_PRIVILEGE"]["schoolsettings"]["isAdmin"]){
	    
	    if ($user_type == kis::$user_types['teacher']){
		return array('accountmanage', 'btn_acc_setting', '', '/home/eAdmin/AccountMgmt/StaffMgmt/?clearCoo=1');
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

	global $intranet_db;

	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->student_id = $student_id;
	$this->user_type = $user_type;
	
	$this->libauth = new libauth();
	$this->target_user_login = $this->getUserLogin($params['target_user_id']);
	$this->target_user_id = $params['target_user_id'];
	
    }
    private function getUserLogin($user_id){
	
	$sql = "SELECT UserLogin FROM INTRANET_USER WHERE UserID = $user_id";
	return current($this->returnVector($sql));
	
    }
    public function getUserInfoSettings($settings_type, $user_type){
	
	$user_record_type = array_search($user_type, kis::$user_record_types);

	$sql = "select SettingValue from GENERAL_SETTING where Module='UserInfoSettings' and SettingName like '%{$settings_type}_$user_record_type'";
	
	return current($this->returnVector($sql));
	
    }
    
    public function getUserTypePermission($user_type){
	
	$settings = kis_utility::getGeneralSettings('UserInfoSettings');
    	$user_record_type = array_search($user_type, kis::$user_record_types);
    
	$permission = array();
	foreach ($settings as $name=>$value){
	   
	    $type = substr($name, -2, 2);
	    
	    if ($type=="_$user_record_type"){
		$permission[substr($name, 0, -2)] = $value;
	    }
	    	    
	}
	
    	return $permission;
    }
    /*
    public function trimPermissionArray($permission){
    	$tmp[] = "";
    	for ($i=0; $i < sizeof($permission); $i++){
    		if (substr($permission[$i],0,3) == "Can"){
    			array_push($tmp,substr($permission[$i],0,-2));
    		}
    	}
    	return $tmp;
	}
	
	public function passwordChangePermission($permission){
		for ($i=0 ; $i < sizeof($permission);$i++){
			if ($permission[$i] == "CanUpdatePassword")
				return 1;
		}
		return 0;
	}
    */
    public function updateUserDetail($params){
	
	extract($params);
	
	$sql = "UPDATE INTRANET_USER SET
		    NickName = '$nick_name',
		    Gender = '$gender',
		    Address = '$address',
		    HomeTelNo = '$home_tel',
		    OfficeTelNo = '$office_tel',
		    MobileTelNo = '$mobile_tel',
		    URL = '$url',
		    FaxNo = '$fax',
		    UserEmail = '$email',
		    Country = '$country',
		    ModifyBy = ".$this->user_id."
		WHERE UserID = ".$this->target_user_id;
	
	$this->db_db_query($sql);
	
	return $this;	
	
    }
    public function updateUserPersonalPhoto($personal_photo){
		
	$sql = "UPDATE INTRANET_USER SET
		    PersonalPhotoLink = '$personal_photo',
		    ModifyBy = ".$this->user_id."
		WHERE UserID = ".$this->target_user_id;
	
	$this->db_db_query($sql);
	
	return $personal_photo_url;	
	
    }
    public function checkUserPassword($old_password){
	
	return $this->libauth->check_password($this->target_user_login, $old_password);
	
    }
    public function updateUserPassword($password){
	
	global $intranet_password_salt, $intranet_authentication_method;

	$fieldname = $intranet_authentication_method == "HASH"? "HashedPass = '".md5($this->target_user_login.$password.$intranet_password_salt)."', UserPassword = '',": "UserPassword = '$password',";

	$_SESSION['eclass_session_password'] = $password;	
	$sql = "UPDATE INTRANET_USER SET $fieldname DateModified = now(), LastModifiedPwd = NOW()  WHERE UserLogin = '".$this->target_user_login."'";
	$this->db_db_query($sql);
	
	$this->libauth->UpdateEncryptedPassword($this->target_user_id, $password);
    }
    
}
?>