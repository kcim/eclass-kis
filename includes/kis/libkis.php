<?
// modifying by : 
include_once("$intranet_root/includes/libuser.php");
include_once("$intranet_root/includes/libclass.php");
class kis extends libdb{
        
    private $user_id, $user_type, $user_name, $user_children = array();
    public $app, $user;
    public $libuser;
    public static $user_types = array('teacher'=>'T','parent'=>'P','student'=>'S');
    public static $user_record_types = array(1=>'T','S','P','A');
    public static $personal_photo_url = '/file/photo/personal/';
    public static $personal_photo_width = 100;
    public static $personal_photo_height = 130;

    public static function callAppService($app, $service, $params){
	
	global $kis_config, $intranet_root, $kis_lang, $PATH_WRT_ROOT;
	    
	if (in_array($app, $kis_config['apps'])){
	
	    include_once("$intranet_root/includes/kis/apps/libkis_$app.php");
	    return call_user_func(array("kis_$app", "service_$service"), $params);
	}
	    
    }
    public function __construct($user_id){

	global $intranet_db;

	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->user = $this->getUserDetail();
	
	if (!$_SESSION['kis']['user']){
	    	
	    $_SESSION['kis']['user']['type'] = self::$user_record_types[$this->user['type']];
	    $_SESSION['kis']['user']['name']['en'] = $this->user['user_name_en'];
	    $_SESSION['kis']['user']['name']['b5'] = $this->user['user_name_b5'];
	    
	}
	
	$this->user_type = $_SESSION['kis']['user']['type'];	
	$this->user_name = $_SESSION['kis']['user']['name'];
	
	switch ($this->user_type){
	    
	    case kis::$user_types['parent']:
		$this->student_id = $this->loadUserChildren();
	    break;
	    case kis::$user_types['teacher']:
		$this->student_id = $_REQUEST['student_id'];
	    break;
	    case kis::$user_types['student']:
		$this->student_id = $this->user_id;
	    break;
		
	}

    }
    private function loadUserChildren(){
	
	global $intranet_session_language;

	if (!isset($_SESSION['kis']['user']['children'])){
	    
	    $sql = "SELECT
			p.StudentID user_id,
			u.EnglishName as user_name_en,
			u.ChineseName as user_name_b5
		    FROM INTRANET_PARENTRELATION p
		    INNER JOIN INTRANET_USER u ON p.StudentID = u.UserID
		    WHERE
			u.ClassName IS NOT NULL AND
			u.ClassName <> '' AND
			p.ParentID = ".$this->user_id;
                        
	    $_SESSION['kis']['user']['children'] = $this->returnArray($sql);echo mysql_error();
	    
	}
	
	foreach ($_SESSION['kis']['user']['children'] as $child){
	    
	    $this->user_children[$child['user_id']] = $child['user_name_'.$intranet_session_language];
	}
	
	if (!$_SESSION['kis']['user']['current_child']){
	    
	    $_SESSION['kis']['user']['current_child'] = $_SESSION['kis']['user']['children'][0]['user_id'];
	}
	
	return $_SESSION['kis']['user']['current_child'];
    }
    public function getUserPhoto($UserLogin){
	    global $intranet_root;
	    $photo_filepath = "/file/user_photo/".$UserLogin.".jpg";
	    if(!file_exists($intranet_root.$photo_filepath)){
		    $photo_filepath = "/images/kis/blank.jpg";
	    }
	    return $photo_filepath;
    }
    public function getStudentDetail(){
		$student = $this->getUserDetail($this->student_id);
		$student['class_teachers'] = $this->getStudentClassTeacher();
		$student['photo'] = $this->getUserPhoto($student['user_login']);
		return $student;
    }
    public function getUserDetail($user_id=''){
		
	if (!$user_id) $user_id = $this->user_id;

	$sql = "SELECT
		    u.UserLogin user_login,
		    u.UserID user_id,
		    u.EnglishName user_name_en,
		    u.ChineseName user_name_b5,
		    u.NickName nick_name,
			u.PersonalPhotoLink personal_photo,
		    u.Gender gender,
		    u.Address address,
		    u.HomeTelNo home_tel,
		    u.OfficeTelNo office_tel,
		    u.MobileTelNo mobile_tel,
		    u.URL url,
		    u.FaxNo fax,
		    u.UserEmail email,
		    u.Country country,
		    u.RecordType type,
		    s.Nationality nationality,
		    s.PlaceOfBirth birth_place,
		    if (s.AdmissionDate=0,'',DATE_FORMAT(s.AdmissionDate, '%Y-%m-%d')) admission_date,
		    if (u.DateOfBirth=0,'',DATE_FORMAT(u.DateOfBirth, '%Y-%m-%d')) birth_date,
		    u.ClassName as class_name,
		    u.ClassNumber as class_number
		FROM INTRANET_USER u
		LEFT JOIN INTRANET_USER_PERSONAL_SETTINGS s ON u.UserID = s.UserID
		WHERE u.UserID = $user_id";

	return current($this->returnArray($sql));
	    
    }
    public function getStudentClassTeacher(){
	
	$sql = "SELECT 
		    u.UserID user_id,
		    u.TitleEnglish title_en,
		    u.TitleChinese title_b5,
		    u.EnglishName user_name_en,
		    u.ChineseName user_name_b5
		FROM YEAR_CLASS_TEACHER t
		INNER JOIN YEAR_CLASS c ON t.YearClassID = c.YearClassID
		INNER JOIN YEAR_CLASS_USER cu ON cu.YearClassID = c.YearClassID
		INNER JOIN INTRANET_USER as u ON t.UserID = u.UserID 
		WHERE
		    cu.UserID = ".$this->student_id." AND
		    c.AcademicYearID = ".$_SESSION['CurrentSchoolYearID'];
		
	return $this->returnArray($sql);
		    
    }
    public function changeCurrentStudent($student_id){
	
	if ($this->user_children[$student_id] || $this->user_type == kis::$user_types['teacher']){
	    $this->student_id =  $_SESSION['kis']['user']['current_child'] = $student_id;
	}
	return $this;	
    }
    
    public function getVisibleApps(){
	
	global $kis_config, $intranet_root, $kis_lang, $intranet_session_language, $PATH_WRT_ROOT;

	$available_apps = array();
    
	if (!$_SESSION['kis']['available_apps']){
	    
	    foreach ($kis_config['apps'] as $app){
	    
		include_once("$intranet_root/includes/kis/apps/libkis_$app.php");
		$class_name = "kis_$app";
		$_SESSION['kis']['available_apps'][$app] = call_user_func(array($class_name, 'getAvailability'), $this->user_id, $this->user_type, $this->student_id);
		$_SESSION['kis']['admin_apps'][$app] = call_user_func(array($class_name, 'getAdminStatus'), $this->user_id, $this->user_type, $this->student_id);
	    }
	    
	}
	
	foreach ($_SESSION['kis']['available_apps'] as $app=>$available_app){
	    
	    if ($available_app){
		list($name, $image, $bg_theme, $href) = $available_app;
		$available_apps[$app] = array('title'=>$kis_lang['app_'.$name],'image'=>$image,'background'=>$bg_theme,'href'=>$href);
		
		if ($_SESSION['kis']['admin_apps'][$app]){
		    list($admin) = $_SESSION['kis']['admin_apps'][$app];
		    $available_apps[$app]['admin'] = $admin;
		}
	    }
	    
	}
	
	return $available_apps;
	
    }
    public function getNotificationCounts(){
	
	global $kis_config, $intranet_root, $intranet_session_language, $PATH_WRT_ROOT;
	$notifications = array();
	
	foreach ($kis_config['apps'] as $app){
	    
	    include_once("$intranet_root/includes/kis/apps/libkis_$app.php");
	    $class_name = "kis_$app";
	    
	    $notifications[$app] = call_user_func(array($class_name, 'getNotificationCount'), $this->user_id, $this->user_type, $this->student_id);	    
	    
	}
	return $notifications;
	
    }
    public function loadApp($app, $params=array()){
	
	global $kis_config, $intranet_root, $intranet_db, $kis_lang, $intranet_session_language, $PATH_WRT_ROOT;
	if (in_array($app, $kis_config['apps'])){
	    
	    include_once("$intranet_root/includes/kis/apps/libkis_{$app}.php");	
	    include_once("$intranet_root/lang/kis/apps/lang_{$app}_{$intranet_session_language}.php");
	    $class_name = "kis_$app";
	    $app = new $class_name($this->user_id, $this->user_type, $this->student_id, $params);
	   
	}
	return $app;
	
    }
    
    public function getUserSchool(){
	
	global $intranet_root;
	
	if (!$_SESSION['kis']['school']){
	    
	    $imgfile = get_file_content($intranet_root."/file/schoolbadge.txt");
	
	    $_SESSION['kis']['school']['logo'] = ($imgfile == "" ? "/images/kis/logo_kis.png" : "/file/$imgfile");
	    $_SESSION['kis']['school']['name'] = $_SESSION['SSV_PRIVILEGE']['school']['name'];
	}
	
	return $_SESSION['kis']['school'];

    }
    public function getUserInfo(){
	
	global $kis_lang, $kis_config, $intranet_session_language;
	
	$user_info['id'] = $this->user_id;
	$user_info['name'] = $this->user_name[$intranet_session_language];
	$user_info['type'] = $this->user_type;
	$user_info['type_title'] = $kis_lang['usertype_'.$this->user_type];
	$user_info['current_child'] = $this->student_id;
	$user_info['children'] = $this->user_children;

	
	return $user_info;
	
    }
    

}
?>