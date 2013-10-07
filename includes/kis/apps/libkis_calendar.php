<?
class kis_calendar extends libdb implements kis_apps {
        
    private $user_id, $user_type, $student_id, $year, $events;
    private static $month_days = array(1=>31,28,31,30,31,30,31,31,30,31,30,31);
    public static $event_types = array('school_event', 'academic', 'group_event', 'holiday', 'school_holiday');

    public static function getAvailability($user_id, $user_type, $student_id){
	
	return array('calendar', 'btn_calendar', 'wood', '');
	
    }
        
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_PRIVILEGE"]["schoolsettings"]["isAdmin"] || $_SESSION["SSV_USER_ACCESS"]["SchoolSettings-SchoolCalendar"]){
	    return array('/home/system_settings/school_calendar/');
	}
	return array();
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	return 0;
	
    }
    public static function isLeapYear($year){
	
	if ($year % 400 == 0){
	    return true;
	}else if ($year % 100 == 0){
	    return false;
	}else if ($year % 4 == 0){
	    return true;
	}else{
	    return false;
	}
	
    }
    
    public function __construct($user_id, $user_type, $student_id, $params){

	global $intranet_db;

	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->user_type = $user_type;
	$this->student_id = $user_type;

	$this->academic_year_id = $params['academic_year_id']? $params['academic_year_id']: $_SESSION['CurrentSchoolYearID'];
	$this->year = $params['year']? $params['year']: date('Y');
	
	$this->loadAcademicYearRange();
	$this->loadYearEvents();
	
    }
    
    private function loadAcademicYearRange(){
	
	$sql = "SELECT
		    YEAR(MIN(TermStart)) as start_year,
		    MONTH(MIN(TermStart)) as start_month,
		    YEAR(MAX(TermEnd)) as end_year,
		    MONTH(MAX(TermEnd)) as end_month
		FROM ACADEMIC_YEAR_TERM
		WHERE AcademicYearID = ".$this->academic_year_id."
		GROUP BY AcademicYearID";
	    
	$range = current($this->returnArray($sql));
	
	$count = 1;
	for ($i=$range['start_year']; $i<=$range['end_year']; $i++){
	    
	    $from_month = $i==$range['start_year']? $range['start_month']: 1;
	    $to_month   = $i==$range['end_year']? $range['end_month']: 12;
	    
	    for ($j=$from_month; $j<=$to_month; $j++){
		
		$month['year'] = $i;
		$month['month'] = $j;
		$this->months[$count++] = $month;
		
	    }
	    
	}
		
	$this->start = mktime(0,0,0,$range['start_month'],1,$range['start_year']);
	$this->end   = mktime(0,0,0,$range['end_month']+1,1,$range['end_year']);
	
    }
    
    private function loadYearEvents(){
		
	$sql = "SELECT a.RelatedTo as id, a.Title as title, UNIX_TIMESTAMP(a.EventDate) As start_time, UNIX_TIMESTAMP(MAX(c.EventDate)) AS end_time,
		ABS(DATEDIFF(a.EventDate,MAX(c.EventDate)))+1 as total_days, a.IsSkipCycle, a.RecordType as type
		FROM INTRANET_EVENT AS a
		INNER JOIN INTRANET_EVENT c ON a.EventID = c.RelatedTo
		WHERE UNIX_TIMESTAMP(a.EventDate) BETWEEN ".$this->start." AND ".$this->end."
		GROUP BY a.RelatedTo
		ORDER By a.EventDate, a.RecordType DESC";
		
	$this->events = $this->returnArray($sql);
	
    }
    public function getLastModifedUser(){
	
	$sql = "SELECT u.ChineseName as name_b5, u.EnglishName as name_en, a.DateModified as last_modified, DATEDIFF(CURDATE(), a.DateModified) as last_modified_days
		FROM INTRANET_EVENT AS a
		INNER JOIN INTRANET_USER u on a.ModifyBy = u.UserID
		ORDER By a.DateModified DESC
		LIMIT 1";
		
	return current($this->returnArray($sql));
	
    }
    public function getAcademicYearMonths(){
	
	return $this->months;
	
    }
    
    public function getTotalDaysOfMonth($month){
	
	$total_days = self::$month_days[$month['month']];
	
	if ($month['month'] == 2){
	    $total_days = self::isLeapYear($month['year'])? 29 : 28;
	}
	
	return $total_days;

    }
    public function getYearEvents(){
	
	foreach ($this->months as $i=>$month){
	    $months[$i] = $this->getMonthEvents($i);
	}
	
	return $months;
    }
    
    public function getMonthEvents($index){
	
	$month = $this->months[$index];
	
	$first_date = getdate(mktime(0,0,0,$month['month']  , 1, $month['year']));
	$last_date  = getdate(mktime(0,0,0,$month['month']+1, 0, $month['year']));
	$total_days = $this->getTotalDaysOfMonth($month)+$first_date['wday']+6-$last_date['wday'];
		
	for ($i = 1; $i <= $total_days; $i++){
	    $days = $this->getDayEvents($i-$first_date['wday'], $index);
	    
	    $month['event_count']+=sizeof($days['events']);
	    $month['days'][$i] = $days;
	}
	
	return $month;
    }
    
    public function getDayEvents($day, $index){
	
	$month = $this->months[$index];
	
	$ts = mktime(0,0,0,$month['month'], $day, $month['year']);
	$day = getdate($ts);
	
	$day['events']=array();
	$exist_event_ids = array();
	
	foreach ($this->events as $event){
	    
	    if (!$exist_event_ids[$event['id']] && $event['start_time'] <= $ts && $event['end_time'] >= $ts && date('n',$ts) == $month['month']){
		$day['events'][] = $event;
	    }

	    $exist_event_ids[$event['id']] = true;
	}
	
	return $day;
	
    }
    
    public function getAllEvents(){
	return $this->events;
    }
			
}
?>