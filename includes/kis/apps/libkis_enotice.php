<?
include_once("$intranet_root/includes/libnotice.php");

class kis_enotice extends libdb implements kis_apps {
        
    private $student_id, $user_type, $permissions;
    public static $attachment_url = "/file/notice/";
    private static $delimiters = array('questions'=>'#QUE#', 'options'=>'#OPT#', 'answers'=>'#ANS#');
    public static $question_types = array(
	1 => 'truefalse', 2 => 'mcsingle', 3 => 'mcmultiple', 
	4 => 'textshort', 5 => 'textlong', 6 => 'na', 
    );
    public static $notice_types = array(
	1 => 'wholeschool', 2 => 'somelevelsonly', 3 => 'someclassesonly', 
	4 => 'applicablestudents'
    );

    public static function getAvailability($user_id, $user_type, $student_id){
	
	global $plugin;
	
	if ($user_type == kis::$user_types['student']){
	    return array();
	}
	
	if ($plugin['notice'] && !$_SESSION["SSV_PRIVILEGE"]["notice"]["disabled"] && $_SESSION["SSV_PRIVILEGE"]["notice"]["canAccess"]){
	    
	 
	    return array('enotice','btn_enotice','','');
	}
	
	return array();
	
    }
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-eNotice"]){
	    return array('/home/eAdmin/StudentMgmt/notice/settings/basic_settings/');
	}
	
	return array();

    }
    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	if ($user_type == kis::$user_types['parent']){
	    
	    $libdb = new libdb();
	    
	     $sql = "SELECT COUNT(*) FROM INTRANET_NOTICE_REPLY c
		    INNER JOIN INTRANET_NOTICE a ON a.NoticeID = c.NoticeID 
		    WHERE c.StudentID = $student_id AND
			a.Module = 'kis' AND
			c.RecordStatus = 0 AND
			a.RecordStatus = 1 AND
			CURDATE() BETWEEN a.DateStart AND a.DateEnd";
	    return current($libdb->returnVector($sql));
	    
	}
	return 0;
	
    }
    private static function decodeNoticeAttachments($attachments_string){
		
	return kis_utility::getDirAttachments(self::$attachment_url.$attachments_string, $path);
    }
    private static function stripslashes_recursive($arr) {

	$rarr = array();
	foreach ((array)$arr as $k => $v) {
	    $rarr[$k] = is_array($v)? self::stripslashes_recursive($v): stripslashes($v);
	}
	return $rarr;
    }
    
    public function __construct($user_id, $user_type, $student_id, $params){
		
	global $intranet_db;
	
	$this->db = $intranet_db;
	$this->permissions = $_SESSION["SSV_PRIVILEGE"]["notice"];
	$this->permissions['isNormalAccess'] = $this->isUserInGroup($this->permissions['normalAccessGroupID']);
	$this->permissions['isFullAccess'] = $this->isUserInGroup($this->permissions['fullAccessGroup']);
	$this->is_admin = self::getAdminStatus($user_id, $user_type, $student_id);
	  
	$this->user_id = $user_id;
	$this->user_type = $user_type;
	$this->student_id = $student_id;
	
	if ($params['notice_id']){
	    $this->notice_id = $params['notice_id'];
	    $this->loadNotice();
	}
	
    }

    private function loadNotice(){
		
	global $libjson;
	
	$student = $this->user_type != kis::$user_types['teacher']? " AND c.StudentID = ".$this->student_id: "";
	
	$sql = "SELECT
		    a.NoticeID as id,
		    a.NoticeNumber as number,
		    a.Title as title,
		    a.Description as description,
		    a.RecordType as type,
		    a.RecordStatus as status,
		    a.Question as questions,
		    a.AllFieldsReq as answer_all,
		    a.DisplayQuestionNumber as display_number,
		    a.RecipientID as recipients,
		    a.IssueUserID as issue_user_id,
		    if(a.IssueUserID = ".$this->user_id.", 1, 0) is_issuer,
		    UNIX_TIMESTAMP(a.DateInput) as input_date,
		    DATE_FORMAT(a.DateStart,'%Y-%m-%d') as issue_date, DATE_FORMAT(a.DateEnd,'%Y-%m-%d') as due_date,
		    IF(a.DateEnd > CURDATE(), 0, 1) as is_overdue,
		    c.RecordStatus as reply_status,
		    COUNT(c.NoticeReplyID) as total_issued_students,
		    COUNT(IF(c.RecordStatus=2, 1, NULL)) as total_signed_students
		FROM INTRANET_NOTICE a
		LEFT JOIN INTRANET_NOTICE_REPLY c ON c.NoticeID = a.NoticeID $student
		WHERE a.NoticeID = '".$this->notice_id."' AND a.Module like 'kis%'
		GROUP BY a.NoticeID
		LIMIT 1";
		
	$notice = current($this->returnArray($sql));
	

	if ($notice){
	    
	    $notice['description'] = $notice['description'];
	    $notice['questions']   = $libjson->decode($notice['questions']);
	    $notice['attachment_folder'] = base64_encode($notice['id'].'_'.$notice['issue_user_id'].'_'.$notice['input_date']);
	    $notice['attachments'] = $this->decodeNoticeAttachments($notice['attachment_folder']);
	    $notice['recipients']  = $libjson->decode($notice['recipients']);
	    $notice['recipient_details']  = $this->getNoticeRecipientDetails($notice['recipients']);
	}
	
	$this->notice = $notice;


	
    }
    private function isUserInGroup($group_id){
	
	$sql = "SELECT COUNT(*) FROM INTRANET_USERGROUP WHERE GroupID = $group_id AND UserID = ".$this->user_id;
	return current($this->returnVector($sql));
	
    }
    private function isUserStudentClassTeacher(){
	
	$sql = "SELECT COUNT(*) FROM YEAR_CLASS_TEACHER t
		INNER JOIN YEAR_CLASS c ON t.YearClassID = c.YearClassID
		INNER JOIN YEAR_CLASS_USER cu ON cu.YearClassID = c.YearClassID
		WHERE t.UserID = ".$this->user_id." AND cu.UserID =  cu.UserID = ".$this->student_id;
		
	return current($this->returnVector($sql));
	
    }
    public function getPermission($permission){
	return $this->permissions[$permission];
    }
    public function hasNoticeReplyRights(){
	
	if ($this->hasFullRights()){
	    return true;
	}
	
	if ($this->user_type == kis::$user_types['teacher'] && $this->isUserStudentClassTeacher() && !$this->permission['isClassTeacherEditDisabled']){
	    return true;
	}
	    
	return (!$this->notice['is_overdue'] || $this->permissions['isLateSignAllow'])
		&& ($this->notice['reply_status'] < 2 || !$this->permissions['NotAllowReSign']);
	
    }
    public function hasNoticeEditRights(){
	
	if ($this->notice['is_issuer'] || $this->hasFullRights()){
	    return true;
	}
	if (!$this->notice){
	    return $this->hasNoticeCreateRights();
	}
	
	return false;
    }
    public function hasNoticeCreateRights(){
	
	return $this->permissions['hasIssueRight'] || $this->hasNormalRights();
	 
    }
    public function hasNoticeReadRights(){
	
	if ($this->notice['is_issuer'] || $this->hasNormalRights()){
	    return true;
	}
	
	return false;
	
    }
    public function hasNormalRights(){
	
	return $this->is_admin || $this->permissions['isFullAccess'] || $this->permissions['isNormalAccess'];
    }
    public function hasFullRights(){
	
	return $this->is_admin || $this->permissions['isFullAccess'];
    }
    public function setNotice($params){
	
	global $libjson;
	
	$this->notice = $this->notice_id? array_merge($this->notice, $params): $params;
	extract($this->notice);
	
	$questions = addslashes($libjson->encode(self::stripslashes_recursive($questions)));
	$recipients = $libjson->encode($recipients);
	
	if ($this->notice_id){
	    
	    $notice_id = $save_template? $template_id: $this->notice_id;
	    
	    $sql = "UPDATE INTRANET_NOTICE SET
			NoticeNumber = '$number',
			Title = '$title',
			Description = '$description',
			RecordType = '$type',
			RecordStatus = '$status',
			Question = '$questions',
			AllFieldsReq = '$answer_all',
			DisplayQuestionNumber = '$display_number',
			RecipientID = '$recipients',
			DateStart = '$issue_date',
			DateEnd = '$due_date',
			DateModified = now()
		    WHERE NoticeID = ".$notice_id;
	    
	    $this->db_db_query($sql);
	
	}else{
	    
	    $sql = "INSERT INTO INTRANET_NOTICE(
			NoticeNumber, Title, Description,
			RecordType, RecordStatus, Question, Module,
			AllFieldsReq, DisplayQuestionNumber, RecipientID,
			DateStart, DateEnd, DateInput, IssueUserID
		    )VALUES(
			'$number', '$title', '$description',
			'$type', '$status', '$questions', 'kis',
			'$answer_all', '$display_number', '$recipients',
			'$issue_date', '$due_date', now(), ".$this->user_id."
		    )";
		    
	    $this->db_db_query($sql);echo mysql_error();
	    $this->notice_id = $this->db_insert_id();
	    
	    
	}
	
	$this->loadNotice();
	
	if ($status==1){
	    return $this->distributeNoticeReplies($email_parents, $email_students);
	}else if ($status==2){
	    return $this->removeAllNoticeReplies();
	}

    }
    public function getNotice(){		
	return $this->notice;
    }
    public function checkNoticeNumber($notice_number){
	$sql = "SELECT COUNT(*) FROM INTRANET_NOTICE WHERE Module like 'kis%' AND NoticeNumber = '$notice_number'";
	
	return !current($this->returnVector($sql));
    }
    public function removeNotice($notice_id){
	
	$notice_id = $notice_id? $notice_id: $this->notice_id;
	
	$sql = "DELETE n,r
		FROM INTRANET_NOTICE n
		LEFT JOIN INTRANET_NOTICE_REPLY r ON r.NoticeID = n.NoticeID
		WHERE n.NoticeID = ".$notice_id;
		
	$this->db_db_query($sql);echo mysql_error();
	
	return $this;
    }
    private function removeAllNoticeReplies(){
	
	$sql = "DELETE FROM INTRANET_NOTICE_REPLY
		WHERE NoticeID = ".$this->notice_id;
		
	$this->db_db_query($sql);
	return array();
    }

    private function distributeNoticeReplies($email_parents=false, $email_students=false){
	
	global $kis_lang, $intranet_session_language;
	
	$users = $this->getNoticeReceivingUsers();
		
	$received_ids = $this->getNoticeReceivedUserIds();
	$user_ids = array();	
		
	foreach ($users as $user){
	    
	    $user_id = $user['user_id'];
	    $user_ids[] = $user_id;
	    
	    
	    if (!in_array($user_id, $received_ids)){
		
		$sql = "INSERT INTO INTRANET_NOTICE_REPLY(
		    NoticeID, StudentID, RecordStatus, DateInput
		)VALUES(
		    ".$this->notice_id.", $user_id, 0, now()
		)";
		
		$this->db_db_query($sql);
		
		$subject = sprintf($kis_lang['_noticemailsubject'], $this->notice['title']);
		$message = sprintf($kis_lang['_noticemailmessage'],
		       $this->notice['id'],
		       $this->notice['title'],
		       $this->notice['issue_date'],
		       $user['user_name_'.$intranet_session_language],
		       $this->notice['due_date']
		    );
	    }
		
	    if ($email_students){
		kis::callAppService('message', 'sendMail',
		    array(
			'id'=>-1,
			'recipients'=>array($user_id),
			'user_id'=>$this->user_id,
			'subject'=>$subject,
			'message'=>$message								 
		    )
		);
	    }
	    
	    if ($email_parents){
		
		$parent_ids = $this->getUserParentIds($user_id);
	     
		kis::callAppService('message', 'sendMail',
		    array(
			'id'=>-1,
			'recipients'=>$parent_ids,
			'user_id'=>$this->user_id,
			'subject'=>$subject,
			'message'=>$message								 
		    )
		);
	    }
	    
	}
	
	$sql = "DELETE FROM INTRANET_NOTICE_REPLY
		WHERE NoticeID = ".$this->notice_id." AND StudentID NOT IN (".implode(',', $user_ids).")";
		
	$this->db_db_query($sql);
    }
    private function getUserParentIds($user_id){
	
	$sql = "SELECT
		    p.ParentID user_id
		FROM INTRANET_PARENTRELATION p
		WHERE
		    p.StudentID = ".$user_id;
		                            
	return $this->returnVector($sql); 		
			
    }
    private function getNoticeReceivedUserIds(){
	
	$sql = "SELECT StudentID
		    FROM INTRANET_NOTICE_REPLY
		    WHERE NoticeID = ".$this->notice_id;
		    
	return $this->returnVector($sql);
	
    }
    private function getNoticeReceivingUsers(){
		
	global $libjson;
		
	switch ($this->notice['type']){
	    
	    case 1:
	    $classes = implode(',', kis_utility::getAcademicYearClasses());
	    $sql = "SELECT e.UserID
		    FROM YEAR_CLASS_USER e
		    INNER JOIN YEAR_CLASS f ON e.YearClassID = f.YearClassID
		   
		    WHERE f.AcademicYearID = ".$_SESSION['CurrentSchoolYearID'];
	    return kis_utility::getUsers(
		array(
		    'user_ids'=>$this->returnVector($sql),
		    'user_type'=>2
		)
	    );
	    
	    case 2:
	    $classlevels = implode(',', $this->notice['recipients']['classlevels']);
	    $sql = "SELECT e.UserID
		    FROM YEAR_CLASS_USER e 
		    INNER JOIN YEAR_CLASS f ON e.YearClassID = f.YearClassID
		    INNER JOIN YEAR y ON e.YearID = y.YearID
		  
		    WHERE y.YearID IN ($classlevels)";
	    
	    return kis_utility::getUsers(
		array(
		    'user_ids'=>$this->returnVector($sql),
		    'user_type'=>2
		)
	    );
    
	    case 3:
	    $classes = implode(',', $this->notice['recipients']['classes']);
	    $sql = "SELECT e.UserID
		    FROM YEAR_CLASS_USER e 
		    WHERE e.YearClassID IN ($classes)";
	    return kis_utility::getUsers(
		array(
		    'user_ids'=>$this->returnVector($sql),
		    'user_type'=>2
		)
	    );
	    
	    return $this->returnVector($sql);
    
	    case 4:
	    $groups = implode(',', $this->notice['recipients']['groups']);
	    $sql = "SELECT g.UserID 
		    FROM INTRANET_USERGROUP g
		    WHERE g.GroupID IN ($groups)";
		    
	    $users = array_unique(array_merge($this->notice['recipients']['users'], $this->returnVector($sql)));
	    
	    $users = kis_utility::getUsers(
		array(
		    'user_ids'=>$users,
		    'user_type'=>2
		)
	    );
	    
	    
	    return $users;
	
	}
	return array();
	
    }
    private function getNoticeRecipientDetails($recipients){
	
	$details = array();
	
	$details['classlevels'] = $recipients['classlevels']? kis_utility::getAcademicYearClassLevels(array('classlevel_ids'=>$recipients['classlevels'])): array();
	$details['classes'] = $recipients['classes']? kis_utility::getAcademicYearClasses(array('class_ids'=>$recipients['classes'])): array();
	$details['groups'] = $recipients['groups']? kis_utility::getAcademicYearGroups(array('group_ids'=>$recipients['groups'])): array();
	$details['users'] = $recipients['users']?  kis_utility::getUsers(array('user_ids'=>$recipients['users'])): array();
	    		
	return $details;
	    
    }
    public function getNoticeReply(){
	
	global $libjson;
		
	$sql = "SELECT c.RecordStatus as status, c.Answer as answers, DATE_FORMAT(c.DateModified,'%Y-%m-%d')  as modified,
		    d.EnglishName as signer_name_en, d.ChineseName as signer_name_b5, c.StudentID as student_id
		FROM INTRANET_NOTICE_REPLY c
		LEFT JOIN INTRANET_USER as d ON d.UserID = c.SignerID
		WHERE c.NoticeID = ".$this->notice_id." AND c.StudentID = ".$this->student_id;
		
	$notice_reply = current($this->returnArray($sql));
	
	if ($notice_reply){
	    $notice_reply['answers'] = $libjson->decode($notice_reply['answers']);
	}
	
	return $notice_reply;
    }
    public function setNoticeReply($params){
	
	global $libjson;
	
	extract($params);
	$answers = addslashes($libjson->encode(self::stripslashes_recursive($answers)));
	
	$sql = "UPDATE INTRANET_NOTICE_REPLY set
		    ".(isset($signer_id)? "SignerID = '$signer_id',":"")."
		    ".(isset($status)? "RecordStatus = '$status',":"")."
		    ".(isset($answers)? "Answer = '$answers',":"")."
		    DateModified = now()
		WHERE NoticeID = ".$this->notice_id." AND StudentID = ".$this->student_id." LIMIT 1";
	
	$this->db_db_query($sql);
	return $this;
	
    }
    public function getNoticeReplyClassStats(){
		
	$sql = "SELECT f.YearClassID as class_id, f.ClassTitleEN as class_name_en, f.ClassTitleB5 as class_name_b5,
		    COUNT(distinct(c.StudentID)) AS total_students, COUNT(if(c.RecordStatus=2, 1, NULL)) as total_signed_students
		FROM INTRANET_NOTICE_REPLY c
		INNER JOIN YEAR_CLASS_USER e ON e.UserID = c.StudentID
		INNER JOIN YEAR_CLASS f ON e.YearClassID = f.YearClassID
		WHERE c.NoticeID = ".$this->notice_id." AND f.AcademicYearID = ".$_SESSION['CurrentSchoolYearID']."
		GROUP BY f.YearClassID ";
			
	
	return $this->returnArray($sql);
    }
    public function getNoticeClassReplies($class_id=''){
	
	global $libjson;
	
	$class_id = $class_id? "INNER JOIN YEAR_CLASS_USER e ON e.UserID = c.StudentID AND e.YearClassID = $class_id": "";
	
	$sql = "SELECT
		    c.Answer answers,
		    c.RecordStatus status,
		    c.RecordType type,
		    c.DateModified modified,
		    su.UserID as student_user_id,
		    su.EnglishName as student_user_name_en,
		    su.ChineseName as student_user_name_b5,
		    pu.UserID as signer_user_id,
		    pu.EnglishName as signer_user_name_en,
		    pu.ChineseName as signer_user_name_b5
		FROM INTRANET_NOTICE_REPLY c
		INNER JOIN INTRANET_USER su ON su.UserID = c.StudentID
		LEFT JOIN INTRANET_USER pu ON pu.UserID = c.SignerID
		$class_id
		WHERE c.NoticeID = ".$this->notice_id."
		ORDER BY c.DateModified desc, su.ClassName asc, su.EnglishName asc";
	
	$results = $this->returnArray($sql);echo mysql_error();
	$replies = array();
	foreach($results as $result){
	    $result['answers'] = $libjson->decode($result['answers']);
	    $replies[] = $result;
	}
	
	return $replies;
	
    }
    public function getAllNotices($params, $sortby='', $order='', $amount='', $page=''){
	
	extract($params);
	
	$cond .= $status? " AND a.RecordStatus = $status ": "";
	$cond .= $signed? " AND c.RecordStatus ".($signed==1?'=':'<')." 2": '';
	$cond .= $past==1?   " AND CURDATE() BETWEEN a.DateStart AND a.DateEnd" : "";
	$cond .= $past==-1?  " AND CURDATE() > a.DateEnd": "";
	$cond .= $year?   " AND DATE_FORMAT(a.DateStart,'%Y')='$year'": "";
	$cond .= $month?  " AND DATE_FORMAT(a.DateStart,'%c')='$month'": "";
	$cond .= $this->user_type != kis::$user_types['teacher']? " AND c.StudentID = ".$this->student_id: "";
	
	$sort = $sortby? "$sortby $order,":"";

	$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";

	$sql = "SELECT SQL_CALC_FOUND_ROWS
		    a.NoticeID as id,
		    if(a.IsModule=1,'--',a.NoticeNumber) as number,
		    a.Title as title,
		    a.RecordType as type, 
		    DATE_FORMAT(a.DateStart,'%Y-%m-%d') as issue_date,
		    DATE_FORMAT(a.DateEnd,'%Y-%m-%d') as due_date,
		    c.RecordStatus as reply_status,
		    DATE_FORMAT(c.DateModified,'%Y-%m-%d') as modified,
		    d.EnglishName as signer_name_en,
		    d.ChineseName as signer_name_b5,
		    a.IssueUserID as issue_user_id,
		    if(a.IssueUserID = ".$this->user_id.", 1, 0) is_issuer,
		    COUNT(c.NoticeReplyID) as total_issued_students,
		    COUNT(IF(c.RecordStatus=2, 1, NULL)) as total_signed_students,
		    a.RecipientID as recipients,
		    a.RecordStatus as status
		FROM INTRANET_NOTICE a 
		LEFT JOIN INTRANET_NOTICE_REPLY c ON a.NoticeID = c.NoticeID
		LEFT JOIN INTRANET_USER d ON d.UserID = c.SignerID
		WHERE a.IsDeleted = 0 AND a.Module like 'kis%'
		    $cond
		    AND ( a.NoticeNumber like '%$keyword%' OR a.Title like '%$keyword%' OR a.Description like '%$keyword%' )
		GROUP BY a.NoticeID
		ORDER BY $sort issue_date desc, a.DateInput desc $limit ";

	$notices = $this->returnArray($sql);echo mysql_error();
	return array(current($this->returnVector('SELECT FOUND_ROWS();')), $notices);
    }

}
?>