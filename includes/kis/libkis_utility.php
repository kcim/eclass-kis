<?
class kis_utility extends libdb{
    
    private static $libdb;
    private static $unsafe_file_char = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
        	
    private static function getLibdb(){
	return self::$libdb? self::$libdb: self::$libdb = new libdb();
    }
    public static function getDirAttachments($base_path, $attachments_path){
	
	global $file_path;
	
	$path = $file_path.$base_path.$attachments_path;
	
	$attachments = array();
	
	if (is_dir($path)){
	    
	    foreach (scandir($path) as $file){
		
		if (is_file($path.'/'.$file)){
		    $attachment['url'] = $base_path.$attachments_path.'/'.$file;
		    $attachment['name'] = preg_replace('`^.*/`u','', $attachment['url']);
		     
		    $attachments[] = $attachment;
		}
		
	    }
	}
	
	return $attachments;
    }
    public static function getSaveFileName($file_name){
	 
	return str_replace(self::$unsafe_file_char, '', $file_name);
    }
    
    public static function downloadFile($path, $name=false, $size=false){
	
	$name = $name? $name: preg_replace('`^.*/`u','', $path);
	
	header('Content-Disposition: attachment; filename="'.$name.'"');
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	
	if ($size){
	    header("Content-length: $size");
	}
	header("Content-Description: File Transfer");
	
	readfile($path);
    }
    
    public static function getUsers($params = array()){
			
	extract($params);
	$cond = "";
	$cond .= isset($user_id)&&!empty($user_id)? "u.UserID = $user_id AND ": "";
	$cond .= isset($user_ids)? "u.UserID IN ('".implode("','",$user_ids)."') AND ": "";
	$cond .= isset($user_type)? "u.RecordType = '".array_search($user_type, kis::$user_record_types)."' AND ": "";
	$cond .= isset($excludes)? "u.UserID NOT IN ('".implode("','",$excludes)."') AND ": "";

	if (isset($class_id)&&!empty($class_id)){
	    
	    $tables .= "LEFT JOIN YEAR_CLASS_USER cu ON cu.UserID = u.UserID ";
	    $cond .= "cu.YearClassID = $class_id AND ";
	    
	}	
	if (isset($academic_year_id)&&!empty($academic_year_id)){
	    
	    $tables .= "LEFT JOIN YEAR_CLASS_USER cu ON cu.UserID = u.UserID ";
	    $tables .= "LEFT JOIN YEAR_CLASS yc ON yc.YearClassID = cu.YearClassID ";
	    $cond .= "yc.AcademicYearID = $academic_year_id AND ";
	    
	}
	
	$libdb = self::getLibdb();
	
	$sql = "SELECT
		u.UserID user_id,
		u.EnglishName user_name_en,
		u.ChineseName user_name_b5,
		u.Gender user_gender,
		u.PhotoLink user_photo,
		u.ClassName as user_class_name,
		u.ClassNumber as user_class_number,
		u.RecordType user_type
		FROM INTRANET_USER u
		    $tables
		WHERE $cond
		u.RecordStatus = 1 AND 
		(
		    u.UserLogin LIKE '%$keyword%' OR
		    u.EnglishName LIKE '%$keyword%' OR
		    u.ChineseName LIKE '%$keyword%' OR
		    u.ClassName LIKE '%$keyword%'
		)
		ORDER BY u.ClassName, u.EnglishName";

	return $libdb->returnArray($sql);
    
    }
    
    public static function getAcademicYearGroups($params=array()){
	
	extract($params);
	$libdb = self::getLibdb();
	
	$cond .= isset($group_ids)? "g.GroupID IN (".implode(',',$group_ids).") AND ": "";
	$cond .= isset($excludes)? "g.GroupID NOT IN (".implode(',',$excludes).") AND ": "";
	$tables .= isset($user_id)? "INNER JOIN INTRANET_USERGROUP ug ON ug.GroupID = g.GroupID AND ug.UserID = $user_id": "";
	
	$academic_year_id = isset($academic_year_id)? $academic_year_id: $_SESSION['CurrentSchoolYearID'];
	$hide_basic_groups = isset($hide_basic_groups)? '':'OR g.AcademicYearID IS NULL';
	
	$sql = "SELECT
		    g.GroupID group_id,
		    g.Title group_name_en,
		    g.TitleChinese group_name_b5,
		    g.RecordType group_type,
		    g.GroupLogoLink group_photo,
		    if (g.AcademicYearID IS NULL, '' ,g.Title) sort_order
		FROM INTRANET_GROUP g
		$tables
		WHERE $cond (g.AcademicYearID = $academic_year_id $hide_basic_groups) AND (
			g.Title LIKE '%$keyword%' OR
			g.TitleChinese LIKE '%$keyword%'
		    )
		ORDER BY sort_order asc, GroupID asc";
		
	return $libdb->returnArray($sql);
	
    }
    
    public static function getAcademicYearClassLevels($params=array()){
	//
	extract($params);
	$libdb = self::getLibdb();
	
	$cond .= isset($classlevel_ids)? "u.YearID IN (".implode(',',$classlevel_ids).") AND ": "";
	$tables .= isset($user_id)? "INNER JOIN YEAR_CLASS_USER cu ON cu.YearClassID = c.YearClassID AND cu.UserID = $user_id": "";
	
	$academic_year_id = isset($academic_year_id)? $academic_year_id: $_SESSION['CurrentSchoolYearID'];
	
	$sql = "SELECT
		    y.YearID classlevel_id,
		    y.YearName classlevel_name_en,
		    y.YearName classlevel_name_b5,
		    y.YearName classlevel_name
		FROM YEAR y
		INNER JOIN YEAR_CLASS c ON
		    c.YearID = y.YearID AND
		    (c.AcademicYearID = $academic_year_id  OR c.AcademicYearID IS NULL)
		$tables
		WHERE
		    $cond
		    (c.AcademicYearID = $academic_year_id OR
			c.AcademicYearID IS NULL) AND
		    y.YearName LIKE '%$keyword%'
		ORDER BY y.YearName";
		
	return $libdb->returnArray($sql);
	
    }
    
    public static function getAcademicYearClasses($params=array()){
	
	extract($params);
	$libdb = self::getLibdb();
	
	$cond .= isset($class_ids)? "c.YearClassID IN (".implode(',',$class_ids).") AND ": "";
	$cond .= isset($class_id)? "c.YearClassID = $class_id AND ": "";
	$tables .= isset($user_id)? "INNER JOIN YEAR_CLASS_USER cu ON cu.YearClassID = c.YearClassID AND cu.UserID = $user_id": "";
	
	$academic_year_id = isset($academic_year_id)? $academic_year_id: $_SESSION['CurrentSchoolYearID'];
	
	$sql = "SELECT
		    c.YearClassID class_id,
		    c.ClassTitleEN class_name_en,
		    c.ClassTitleB5 class_name_b5
		FROM YEAR_CLASS c
		$tables
		WHERE
		    $cond
		    (c.AcademicYearID = $academic_year_id OR c.AcademicYearID IS NULL) AND (
			c.ClassTitleEN LIKE '%$keyword%' OR
			c.ClassTitleB5 LIKE '%$keyword%'
		    )
		ORDER BY c.ClassTitleEN";
		
	return $libdb->returnArray($sql);
	
    }
    
    public static function getAcademicYears($params = array()){
	
	extract($params);
	$libdb = self::getLibdb();
	
	$cond .= isset($AcademicYearID)? " AND AcademicYearID = $AcademicYearID": "";
	$cond .= isset($YearName)? " AND (YearNameEN = '".$YearName."' OR YearNameB5 = '".$YearName."')": "";

	$sql = "SELECT
		    AcademicYearID academic_year_id,
		    YearNameEN academic_year_name_en,
		    YearNameB5 academic_year_name_b5
		FROM ACADEMIC_YEAR
		WHERE 1
		$cond
		ORDER BY Sequence desc";
		
	return $libdb->returnArray($sql);
	
    }
    public static function getAcademicYearTerm($params = array()){
	
	extract($params);
	$libdb = self::getLibdb();
	
	$cond .= isset($AcademicYearID)? " AND AcademicYearID = $AcademicYearID": "";
	$cond .= isset($YearTermID)? " AND YearTermID = $YearTermID": "";
	$cond .= isset($YearTermName)? " AND (YearTermNameEN = '".$YearTermName."' OR YearTermNameB5 = '".$YearTermName."')": "";
	
	$sql = "SELECT
		    YearTermID academic_year_term_id,
		    YearTermNameEN academic_year_term_name_en,
		    YearTermNameB5 academic_year_term_name_b5
		FROM ACADEMIC_YEAR_TERM
		WHERE 1
		$cond";
		
	return $libdb->returnArray($sql);
	
    }
    public static function getGeneralSettings($module){
	
	$libdb = self::getLibdb();
	
	$sql = "SELECT SettingName, SettingValue FROM GENERAL_SETTING WHERE Module='$module'";
	$items = $libdb->returnArray($sql);
	
	$settings = array();
	foreach ($items as $item){ 
	    $settings[$item['SettingName']] = $item['SettingValue'];
	}
    	return $settings;
    }
    
}