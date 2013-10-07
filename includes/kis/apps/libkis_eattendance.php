<?

class kis_eattendance extends libdb implements kis_apps {
        
    private static $day_types = array(2=>'am','pm');
    private static $waive_types = array(1=>'absent', 'late', 'early');
    public static $attend_types = array('present','absent', 'late', 'outing');
    public static $leave_types = array(1=>'am','pm');
    private $user_id, $user_type, $student_id, $student_ids, $year, $month, $day, $summary;
     
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $plugin;
	
	if ($plugin['attendancestudent']){
	    
	    if ($user_type == kis::$user_types['teacher']){
		return array('eattendance', 'btn_eattendance', 'wood', '');
	    }else if ($user_type == kis::$user_types['parent']){
		return array('eattendance', 'btn_eattendance_p', '', '');
	    }else{
		return array();
	    }
	}
	
	return array();
    }
            
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-StudentAttendance"]){
	    return array('/home/eAdmin/StudentMgmt/attendance/');
	}
	return array();
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	return 0;
	
    }
    private static function getAttendanceTableName($year, $month){
		
	return sprintf("CARD_STUDENT_DAILY_LOG_%d_%02d", $year, $month);
	
    }

    public function __construct($user_id, $user_type, $student_id, $params){

	global $intranet_db;
	
	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->user_type = $user_type;
	$this->student_id = $student_id;
	$this->year = $params['year']? $params['year']: date('Y');
	$this->month = $params['month']? $params['month']: date('m');
	$this->day = $params['day']? $params['day']: date('d');
	$this->date = $this->year.'-'.sprintf('%02d',$this->month).'-'.sprintf('%02d',$this->day);
	$this->class_id = $params['class_id'];
	$this->table_name = self::getAttendanceTableName($this->year, $this->month);
	$this->permission = kis_utility::getGeneralSettings('StudentAttendance');
	
	if ($user_type==kis::$user_types['teacher'] && $params['class_id']){
	    $this->students = $this->getClassStudentIds($params['class_id']);
	}else{
	    $this->students = array($this->student_id);
	}
	
	$this->loadWaiveReords();
	
    }
    private function loadWaiveReords(){
	
	$start 	= mktime(0,0,0,1,1,$this->year);
	$end 	= mktime(0,0,0,1,1,$this->year+1);
	$users  = implode(",",$this->students);
	 
	$sql = "SELECT StudentID as user_id , UNIX_TIMESTAMP(RecordDate) as time , DayType as day_type, RecordType as type, RecordStatus as status
		FROM CARD_STUDENT_PROFILE_RECORD_REASON 
		WHERE RecordStatus='1' AND StudentID IN ($users) 
		AND UNIX_TIMESTAMP(RecordDate) BETWEEN $start AND $end";
		
	$records = $this->returnArray($sql);
	    
	foreach ($records as $record){
	    
	    $this->waive_record[$record['user_id']][date('n', $record['time'])][date('j', $record['time'])][self::$day_types[$record['day_type']]][self::$waive_types[$record['type']]] = $record['status'];
	    
	}
	
    }
    private function getClassStudentIds($class_id){
	
	$sql = "SELECT u.UserID
		FROM YEAR_CLASS_USER c
		INNER JOIN INTRANET_USER u ON u.UserID = c.UserID
		WHERE c.YearClassID = $class_id
		    AND u.RecordStatus='1' AND u.RecordType='2'";
		
	return $this->returnVector($sql);
	
    }
    private function getAttendanceSummary($students, $month, $day = null){
	
	$summary_values = array('present'=>0,'absent'=>0,'early_leave'=>0,'late'=>0,'outing'=>0);
	$summary = array('records_count'=>0,'total'=>$summary_values,'am'=>$summary_values,'pm'=>$summary_values);
	
	$attendances = $this->loadAttendances(array('students'=>$students,'day'=>$day), $month);
	
	foreach ($attendances as $i=>$a){
	    
	    if (!$a['id']) continue;
	    $summary['records_count']++;
	    	    
	    foreach (array('am','pm') as $t){
		
		$status = $a[$t.'_status'];
		$leave_status = $a['leave_status'];
		$modified = strtotime($a[$t.'_modified']);
		
		if ($summary[$t]['modified']<$modified){
		    
		    $summary[$t]['modified'] = $modified;
		    $summary[$t]['modified_by_b5'] = $a[$t.'_modified_by_b5'];
		    $summary[$t]['modified_by_en'] = $a[$t.'_modified_by_en'];
		    
		}
		if ($summary['total']['modified']<$modified){
		    
		    $summary['total']['modified'] = $modified;
		    $summary['total']['modified_by_b5'] = $a[$t.'_modified_by_b5'];
		    $summary['total']['modified_by_en'] = $a[$t.'_modified_by_en'];
		}
		
		if ($status == 'outing'){
		
		    $summary['total']['outing']+=0.5;
		    $summary[$t]['outing']++;
		    
		}else if ($status == 'absent'){
		    
		    if (!$this->waive_record[$a['user_id']][$month][$day][$t]['absent']){
			
			$summary['total']['absent']+=0.5;
			$summary[$t]['absent']++;
		    }
		    
		}else{	
		    
		    $present = 0.5;
		    if ($leave_status==$t && !$this->waive_record[$a['user_id']][$month][$day][$t]['early']){
			$summary['total']['early_leave']++;
			$summary[$t]['early_leave']++;
			$present = 0;
			$a[$t.'_status'] = 'earlyleave';
			
		    }
		    if ($status=='late' && !$this->waive_record[$a['user_id']][$month][$day][$t]['late']){
			$summary['total']['late']++;
			$summary[$t]['late']++;
			$a[$t.'_status'] = $a[$t.'_status']=='earlyleave'? 'lateandearlyleave': $a[$t.'_status'];
			$present = 0;
		    }
		    
		    $summary['total']['present']+=$present;
		    $summary[$t]['present']+=$present*2;

		}
		
	    }
	    
	}
	
	$summary['total']['modified'] = $summary['total']['modified']? date('Y-m-d H:i:s', $summary['total']['modified']): '';
	$summary['am']['modified'] = $summary['am']['modified']? date('Y-m-d H:i:s', $summary['am']['modified']): '';
	$summary['pm']['modified'] = $summary['pm']['modified']? date('Y-m-d H:i:s', $summary['pm']['modified']): '';
	
	
	return $summary;
    }
    
    private function getAttendances($students, $month, $day = null, $keyword='', $show_no_record=false, $sortby='', $order=''){
		
	$attendances = $this->loadAttendances(array('students'=>$students, 'day'=>$day, 'keyword'=>$keyword),
					      $month, $sortby, $order);
	
	$result_attendances = array();
	foreach ($attendances as $i=>$a){

	    if ($show_no_record || $a['id']){
		$result_attendances[] = $a;
	    }
	}
	
	return $result_attendances;
    }
    
    
    private function loadAttendances($params, $month, $sortby='', $order=''){
	
	extract($params);
	
	$table_name = self::getAttendanceTableName($this->year, $month);

	$sort .= $sortby? "$sortby $order, ":"";

	$cond .= isset($students)?  "u.UserID IN (".implode(",",$students).") AND": "";
	$cond .= isset($record_id)? "d.RecordID = $record_id AND":"";
	
	if (isset($day)){
	    
	    $day_number = " AND d.DayNumber = $day";
	    $date = $this->year.'-'.sprintf('%02d',$month).'-'.sprintf('%02d',$day);
	    
	    $fields .= "ra.Remark as am_remark,
			rp.Remark as pm_remark,
			rsa.Reason as am_reason,
			rsp.Reason as pm_reason,";
	    
	    $tables .= "LEFT JOIN CARD_STUDENT_DAILY_REMARK ra ON
			    ra.RecordDate = '$date' AND ra.StudentID = u.UserID
			    AND ra.DayType = '2'
			LEFT JOIN CARD_STUDENT_DAILY_REMARK rp ON
			    rp.RecordDate = '$date' AND rp.StudentID = u.UserID
			    AND rp.DayType = '3'
			LEFT JOIN CARD_STUDENT_PROFILE_RECORD_REASON rsa ON 
			    rsa.RecordDate = '$date' AND rsa.StudentID = u.UserID 
			    AND rsa.DayType = '2'
			LEFT JOIN CARD_STUDENT_PROFILE_RECORD_REASON rsp ON 
			    rsp.RecordDate = '$date' AND rsp.StudentID = u.UserID 
			    AND rsp.DayType = '3'";
		    
	}
	
	$sql = "SELECT
		    d.RecordID id,
		    u.UserID user_id,
		    u.ClassNumber user_class_number,
		    u.PhotoLink user_photo,
		    u.ChineseName user_name_b5,
		    u.EnglishName user_name_en,
		    d.DayNumber day,
		    
		    IF(d.AMStatus IS NULL, IF (d.InSchoolTime IS NOT NULL , 0, 1), d.AMStatus) am_status,
		    d.DateModified am_modified,
		    au.ChineseName am_modified_by_b5,
		    au.EnglishName am_modified_by_en,
		    		   
		    IF(d.PMStatus IS NULL,
			IF( (d.AMStatus IN (0,2) OR (d.InSchoolTime IS NOT NULL AND d.AMStatus IS NULL))
			    AND (d.LunchOutTime IS NULL OR d.LunchBackTime IS NOT NULL)
			, 0, 1)
		    , d.PMStatus) pm_status,
		    
		    d.PMDateModified pm_modified,
		    pu.ChineseName pm_modified_by_b5,
		    pu.EnglishName pm_modified_by_en,
		   
		    $fields
		    
		    d.LunchOutTime lunch_out_time,
		    d.LunchBackTime lunch_back_time,
		   
		    IF( (d.AMStatus IN (1,3) OR (d.AMStatus IS NULL AND d.InSchoolTime IS NULL)) AND
			(d.PMStatus IN (1,3) OR d.PMStatus IS NULL OR (d.LunchOutTime IS NOT NULL AND d.LunchBackTime IS NULL))
		    , '', d.InSchoolTime) in_school_time,
			
		    IF( (d.AMStatus IN (1,3) OR (d.AMStatus IS NULL AND d.InSchoolTime IS NULL)) AND
			(d.PMStatus IN (1,3) OR d.PMStatus IS NULL OR (d.LunchOutTime IS NOT NULL AND d.LunchBackTime IS NULL))
		    , '', d.LeaveSchoolTime) leave_school_time,
		    
		    d.LeaveStatus leave_status

		FROM INTRANET_USER u  
		LEFT JOIN $table_name d ON d.UserID=u.UserID $day_number
		LEFT JOIN INTRANET_USER au ON d.ModifyBy=au.UserID
		LEFT JOIN INTRANET_USER pu ON d.PMModifyBy=pu.UserID
		$tables 
		WHERE 
		    $cond (
			u.ClassNumber like '%$keyword%' OR
			u.ChineseName like '%$keyword%' OR
			u.EnglishName like '%$keyword%'
		    )
		GROUP BY u.UserID, day
		ORDER BY $sort u.ClassNumber";

	$items = $this->returnArray($sql);
	$attendances = array();
	
	foreach ($items as $item){
	   
	    $attendance = $item;
	    $attendance['leave_status'] = self::$leave_types[$item['leave_status']];
	    $attendance['am_status']    = self::$attend_types[$item['am_status']];
	    $attendance['pm_status']    = self::$attend_types[$item['pm_status']];
	    
	    if ($attendance['leave_status']=='am'){
		$attendance['am_status'] = $attendance['am_status']=='late'?'lateandearlyleave': 'earlyleave';
		
	    }else if ($attendance['leave_status']=='pm'){
		$attendance['pm_status'] = $attendance['pm_status']=='late'?'lateandearlyleave': 'earlyleave';
	    }
	    
	    $attendance['am_here'] = in_array($attendance['am_status'], array('present', 'late'));
					      
	    if (!$attendance['pm_status']){
		$attendance['pm_status']  = $attendance['am_here'] && (!$item['lunch_out_time'] || $item['lunch_back_time'])? 'present': 'absent';
	    }
	    $attendance['pm_here'] = in_array($attendance['pm_status'], array('present', 'late'));
	    
	    list($attendance['in_school_hour'],$attendance['in_school_min'],$attendance['in_school_sec']) = explode(':',$item['in_school_time']);
	    list($attendance['leave_school_hour'],$attendance['leave_school_min'],$attendance['leave_school_sec']) = explode(':',$item['leave_school_time']);
	    list($attendance['lunch_out_hour'],$attendance['lunch_out_min'],$attendance['lunch_out_sec']) = explode(':',$item['lunch_out_time']);
	    list($attendance['lunch_back_hour'],$attendance['lunch_back_min'],$attendance['lunch_back_sec']) = explode(':',$item['lunch_back_time']);
	    	    
	    $attendances[] = $attendance;
		
	}
	
	return $attendances;
    }
    
    private function getAttendanceDefaultReason(){
	
    }
    
    public function getAttendance(){
	
	return current($this->loadAttendances(array('students'=>array($this->student_id),'day'=>$this->day), $this->month));
	
    }
    public function setAttendance($params, $apm){
	
	extract($params);
		
	$apm_prefix = $apm=='pm'?'PM':'';
	
	if ($status=='lateandearlyleave'){
	    $status = 'late';
	    $leave_status = array_search($apm, self::$leave_types);
	}
	if ($status=='earlyleave'){
	    $status = 'present';
	    $leave_status = array_search($apm, self::$leave_types);
	}

	$status = array_search($status, self::$attend_types);
	$day_type = array_search($apm, self::$day_types);
	
	$sql = "UPDATE ".$this->table_name." SET
		    InSchoolTime = '$in_school_time',
		    LeaveSchoolTime = '$leave_school_time',
		    LeaveStatus = '$leave_status',
		    ".strtoupper($apm)."Status = '$status',
		    {$apm_prefix}DateModified = now(),
		    {$apm_prefix}ModifyBy = ".$this->user_id."
		WHERE
		    UserID = ".$this->student_id." AND
		    DayNumber = '".$this->day."'";
		    
	$this->db_db_query($sql);
	
	$sql = "UPDATE %s SET
		    %s = '%s'
		WHERE
		    RecordDate = '".$this->date."' AND
		    StudentID = ".$this->student_id." AND
		    DayType = '$day_type'";
	
	$this->db_db_query(sprintf($sql, 'CARD_STUDENT_PROFILE_RECORD_REASON', 'Reason', $reason));
	$this->db_db_query(sprintf($sql, 'CARD_STUDENT_DAILY_REMARK', 'Remark',$remark));
	
		    
	if (!$this->db_affected_rows()){
	    
	    $sql = "INSERT INTO ".$this->table_name." (
			InSchoolTime, LeaveSchoolTime,
			LeaveStatus, ".strtoupper($apm)."Status,
			{$apm_prefix}DateModified, {$apm_prefix}ModifyBy,
			DateInput, InputBy,
			DayNumber, UserID
		    )VALUES(
			'$in_school_time', '$leave_school_time',
			'$leave_status', '$status',
			now(), ".$this->user_id.",
			now(), ".$this->user_id.",
			'".$this->day."', ".$this->student_id."
		    )";
		    
	    $this->db_db_query($sql);
	    
	    $sql = "INSERT INTO %s (
			%s, RecordDate,
			StudentID, DayType
		    )VALUES(
			'%s', '".$this->date."',
			".$this->student_id.", '%s'
		    )";
		    
	    $this->db_db_query(sprintf($sql, 'CARD_STUDENT_PROFILE_RECORD_REASON', 'Reason', $reason, 2));
	    $this->db_db_query(sprintf($sql, 'CARD_STUDENT_DAILY_REMARK', 'Remark', $remark, 2));
	    $this->db_db_query(sprintf($sql, 'CARD_STUDENT_PROFILE_RECORD_REASON', 'Reason', $reason, 3));
	    $this->db_db_query(sprintf($sql, 'CARD_STUDENT_DAILY_REMARK', 'Remark', $remark, 3));
	}
	
    }

    public function getAllClassesDayAttendanceSummary($keyword='', $apm='total'){
	
	$classes = kis_utility::getAcademicYearClasses(array('keyword'=>$keyword));
	
	$classes_summary = array();
	foreach ($classes as $class){
	   
	    if (!$students = $this->getClassStudentIds($class['class_id'])) continue;
	    
	    $summary = $this->getAttendanceSummary($students, $this->month, $this->day);
	
	    $class['status_confirmed'] = sizeof($students) == $summary['records_count'];
	    $classes_summary[] = array_merge($summary[$apm], $class);
	    
	}
	
	return $classes_summary;
	
    }
    public function getClassDayAttendanceRecords($keyword, $sortby, $order){
	
	//$students = $this->getClassStudentIds($keyword);
	
	$attendances = $this->getAttendances($this->students, $this->month, $this->day, $keyword, true, $sortby, $order);
	
	return $attendances;
	
    }
    
    public function getStudentYearAttendanceSummary(){
	
	for ($i = 1; $i <= 12; $i++){
	    
	    $summary = $this->getAttendanceSummary($this->students, $i);
	    $months[$i] = $summary['total'];
	    
	}
	
	return $months;
	
    }
    
    public function getStudentMonthAttendanceSummary($apm){

	$summary = $this->getAttendanceSummary($this->students, $this->month);
	
	return $summary[$apm];
    }
    public function getStudentMonthAttendances(){
	
	$attendances =  $this->getAttendances($this->students, $this->month);
	
	return $attendances;
	
    }
    public function getStudentDayAttendances($keyword){
	
	$attendances =  $this->getAttendances($this->students, $this->month, $this->day, $keyword);
	
	return $attendances;
	
    }
    public function getAttendanceRecordedYears(){
	$sql = "SELECT DISTINCT Year FROM CARD_STUDENT_RECORD_DATE_STORAGE ORDER BY Year";
	return $this->returnVector($sql);
    }
    
    public function getReminderRecords($params, $sortby='', $order='', $amount='', $page=''){
	
	extract($params);
	
	$sort = $sortby? "$sortby $order,":"";
	$limit = $amount? " LIMIT ".(($page-1)*$amount).", $amount": "";
	
	if ($status==1){
	    $cond .= " r.DateOfReminder < CURDATE() AND";
	}else if ($status==2){
	    $cond .= " r.DateOfReminder >= CURDATE() AND";
	}
	
	$cond .= $reminder_id? " r.ReminderID = $reminder_id AND ":"";
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS
		    r.ReminderID id,
		    r.DateOfReminder as date,
		    IF (r.DateOfReminder < CURDATE(), 1, 0) is_past_record,
		    r.Reason reason,
		    su.UserID student_user_id,
		    su.EnglishName student_user_name_en,
		    su.ChineseName student_user_name_b5,
		    tu.UserID teacher_user_id,
		    tu.EnglishName teacher_user_name_en,
		    tu.ChineseName teacher_user_name_b5
                FROM CARD_STUDENT_REMINDER as r
		INNER JOIN INTRANET_USER su ON su.UserID = r.StudentID
		INNER JOIN INTRANET_USER tu ON tu.UserID = r.TeacherID
                WHERE $cond
		    ( su.EnglishName LIKE '%$keyword%' OR su.ChineseName LIKE '%$keyword%' OR tu.EnglishName LIKE '%$keyword%' OR tu.ChineseName LIKE '%$keyword%'
		    OR su.ClassName = '$keyword' OR r.Reason LIKE '%$keyword%')
                ORDER BY $sort
		    r.DateOfReminder desc
		$limit";
		
	$records = $this->returnArray($sql); echo mysql_error();
	$total   = current($this->returnVector('SELECT FOUND_ROWS();'));
	
	return array($total, $records);

    }
    public function createDailyReminderRecords($student_ids, $teacher_id, $reason, $start_ts, $end_ts, $weekdays){
	
	$this->Start_Trans();
	
	$ts = $start_ts;
	while ($ts <= $end_ts){
	    
	    $date_info = getdate($ts);
	    $date = date('Y-m-d', $ts);
	    $ts += 86400;
	    
	    if ($weekdays[$date_info['wday']]){
		
		foreach ($student_ids as $student_id){
 
		    $sql = "INSERT INTO CARD_STUDENT_REMINDER(
				StudentID, DateOfReminder,TeacherID,Reason,DateInput,DateModified
			    ) VALUES (
				$student_id,'$date',$teacher_id,'$reason',now(),now()
			    )";
 
		    if (!$this->db_db_query($sql)){
			
			$this->RollBack_Trans();
			return false;
		    }
		}
	    }
	}
	
	$this->Commit_Trans();
	return true;
    }
    
    public function getReminderRecord($reminder_id){
	
	list(, $reminders) = $this->getReminderRecords(array('reminder_id'=>$reminder_id));
	
	return current($reminders);
	
    }
    
    public function updateReminderRecord($reminder_id, $teacher_id, $reason, $ts){
	
	$date = date('Y-m-d', $ts);
	
	$sql = "UPDATE CARD_STUDENT_REMINDER SET
		    DateOfReminder = '$date',
		    TeacherID = '$teacher_id',
		    Reason = '$reason',
		    DateModified = now()
		WHERE
		    ReminderID = $reminder_id";
		    
	$this->db_db_query($sql);
	
    }
    
    public function removeReminderRecord($reminder_id){
	
	$sql = "DELETE FROM CARD_STUDENT_REMINDER
		WHERE ReminderID = $reminder_id";
		
	$this->db_db_query($sql);
	
    }
    
    
}
?>