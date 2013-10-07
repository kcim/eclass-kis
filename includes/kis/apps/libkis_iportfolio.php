<?
include_once($intranet_root."/includes/libpf-sbs.php");
include_once($intranet_root."/includes/libportfolio_group.php");
include_once($intranet_root."/includes/lib-growth-scheme.php");
include_once($intranet_root."/includes/libpf-lp2.php");

class kis_iportfolio extends libdb implements kis_apps {
        
    private $user_id, $user_type, $student_id;
	public static $attachment_url = "/file/iportfolio/";
   
    public static function getAvailability($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_PRIVILEGE"]["eclass"]["Access2Portfolio"]){
	    
	    if ($user_type == kis::$user_types['teacher']){
		return array('iportfolio', 'btn_iportfolio', 'wood', '');
	    }else if ($user_type == kis::$user_types['parent']){
		return array('iportfolio', 'btn_portfolio_p', '', '');
	    }else{
		return array();
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

		global $PATH_WRT_ROOT,$eclass_filepath,$eclass_version,$eclass_prefix,$intranet_db,$eclass_db,$ck_course_id;

		$this->db = $intranet_db;
		$this->user_id = $user_id;
		$this->user_type = $user_type;
		$this->student_id = $student_id;
		$this->course_id = $ck_course_id;
		$this->course_db = $eclass_prefix."c".$this->course_id;
		$this->schoolyear = $_SESSION['CurrentSchoolYearID'];
		$this->delimiters = array('questions'=>'#QUE#', 'options'=>'#OPT#', 'answers'=>'#ANS#');
    }
    
    public function getStudentGuardians(){
	
	global $eclass_db;
	
	$sql = "SELECT
		    a.RecordID guardian_id,
		    a.EnName name_en,
		    a.ChName name_b5,
		    a.Relation relation,
		    a.Phone tel,
		    a.EmPhone emergency_tel,
		    a.isMain is_main,
		    a.isSMS is_sms,
		    b.IsLiveTogether is_live_together,
		    b.IsEmergencyContact is_emergency_contact,
		    b.Occupation occupation,
		    a.Address address
		FROM $eclass_db.GUARDIAN_STUDENT_EXT_1 b
		INNER JOIN $eclass_db.GUARDIAN_STUDENT a
		    ON (a.RecordID=b.RecordID)
		WHERE a.UserID=".$this->student_id." AND a.Relation!=''
		ORDER BY is_main desc";	
	return $this->returnArray($sql);

    }
    
    public function getStudentAdmittedClasses(){
	
		$sql = "SELECT
				c.ClassTitleEN class_name_en,
				c.ClassTitleB5 class_name_b5,
				y.YearNameEN year_name_en,
				y.YearNameB5 year_name_b5
			FROM YEAR_CLASS_USER u
			INNER JOIN YEAR_CLASS c ON
				u.YearClassID = c.YearClassID
			INNER JOIN ACADEMIC_YEAR y ON
				y.AcademicYearID = c.AcademicYearID
			WHERE u.UserID = ".$this->student_id."
			ORDER BY y.AcademicYearID desc";
			
		
		return $this->returnArray($sql);
    }
	//copy from libportfolio.php
    function GetYearClassTitleFieldByLang($prefix="")
    {
      $firstChoice = Get_Lang_Selection($prefix."ClassTitleB5", $prefix."ClassTitleEN");
      $altChoice = Get_Lang_Selection($prefix."ClassTitleEN", $prefix."ClassTitleB5");
      
      $year_class_title_field = "IF($firstChoice IS NULL OR TRIM($firstChoice) = '', $altChoice, $firstChoice)";
    
      return $year_class_title_field;
    }	
	//School Records
    function getActivityRecordList($data=array(),$sortby='',$order='',$amount='',$page=''){
		global $eclass_db,$kis_lang,$intranet_session_language;
		extract($data);
		$sort = $sortby? "$sortby $order":"schoolyear";
		$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";
		$cond = '';
		if(!empty($recordId)){
			$cond .= " AND a.RecordID = '".$recordId."'";
		}else{
			$cond .= (!empty($studentId))?" AND a.UserID = '".$studentId."'":"";
			$cond .= (!empty($keyword))?" AND (iu.EnglishName LIKE '%".$keyword."%' OR iu.ChineseName LIKE '%".$keyword."%')":"";
			$cond .= (!empty($classId)&&$classId!='all')?" AND ycu.YearClassID = '".$classId."'":"";
			if(!$showAllTerms)
				$cond .= (!empty($school_year_term_id))?" AND a.YearTermID = '".$school_year_term_id."'":" AND a.YearTermID IS NULL";
			$cond .= (!empty($school_year_id))?" AND a.AcademicYearID = '".$school_year_id."'":"";
		}
		$sql = "SELECT 
					COUNT(*) 
				FROM 
					".$eclass_db.".ACTIVITY_STUDENT a
				INNER JOIN
					YEAR_CLASS_USER ycu ON a.UserID = ycu.UserID
				INNER JOIN
					YEAR_CLASS yc ON ycu.YearClassID = yc.YearClassID					
				INNER JOIN 
					INTRANET_USER iu ON a.UserID = iu.UserID				
				WHERE 
					yc.AcademicYearID = '".$this->schoolyear."' ".$cond;
		$total = current($this->returnVector($sql));
		$sql = "SELECT
					a.RecordID as activity_id,
					a.UserID as user_id,
					".getNameFieldByLang("iu.")." as user_name,
					".(($intranet_session_language=='en')?"yc.ClassTitleEN":"yc.ClassTitleB5")." as class_name,
					a.AcademicYearID as school_year_id,
					a.Year as schoolyear,
					a.YearTermID as school_year_term_id,
					if(a.Semester = '' , '".$kis_lang['Activity']['WholeYear']."',a.Semester) as  'semester',
					a.ActivityName as activity_name,
					a.Role AS role,
					a.Performance AS performance,
					a.Organization AS organization
				FROM 
					".$eclass_db.".ACTIVITY_STUDENT a
				INNER JOIN
					YEAR_CLASS_USER ycu ON a.UserID = ycu.UserID
				INNER JOIN
					YEAR_CLASS yc ON ycu.YearClassID = yc.YearClassID					
				INNER JOIN 
					INTRANET_USER iu ON a.UserID = iu.UserID				
				WHERE 
					yc.AcademicYearID = '".$this->schoolyear."'
				".$cond."
				ORDER BY $sort $limit";
		return array($total,$this->returnArray($sql));
    }
    function getSchoolRecordCountList($data){
		global $eclass_db,$kis_lang,$intranet_session_language;
		extract($data);
		$cond = '';
		$cond .= (!empty($keyword))?" AND (iu.EnglishName LIKE '%".$keyword."%' OR iu.ChineseName LIKE '%".$keyword."%')":"";
		$cond .= (!empty($classId)&&$classId!='all')?" AND ycu.YearClassID = '".$classId."'":"";
		$cond .= (!empty($school_year_term_id))?" AND a.YearTermID = '".$school_year_term_id."'":" AND a.YearTermID IS NULL";
		$cond .= (!empty($school_year_id))?" AND a.AcademicYearID = '".$school_year_id."'":"";
		$table = ($recordType=='activities')?"ACTIVITY_STUDENT":"AWARD_STUDENT";
		
		$StudentList = kis_utility::getUsers(array('class_id'=>$classId,'keyword'=>$keyword,'user_type'=>'S'));
		$StudentListArr = BuildMultiKeyAssoc($StudentList,'user_class_number');
		ksort($StudentListArr);
		$sql = "SELECT
					a.UserID,
					CONCAT(
						'<a href=\"#/apps/iportfolio/schoolrecords/".$recordType."/student/?&studentId=',
						a.UserID,
						'&school_year_id=".$school_year_id."&school_year_term_id=".$school_year_term_id."\">',
						COUNT(a.RecordID),
						'</a>'
					) as count
				FROM 
					".$eclass_db.".".$table." a
				INNER JOIN
					YEAR_CLASS_USER ycu ON a.UserID = ycu.UserID
				INNER JOIN
					YEAR_CLASS yc ON ycu.YearClassID = yc.YearClassID					
				INNER JOIN 
					INTRANET_USER iu ON a.UserID = iu.UserID				
				WHERE 
					yc.AcademicYearID = '".$this->schoolyear."'
				".$cond."
				GROUP BY UserID";	
		$UserRecordArr = $this->returnArray($sql);
		$UserRecordArr = BuildMultiKeyAssoc($UserRecordArr,'UserID',array('count'));
		foreach($StudentListArr as $_classNumber => $_studentInfo){
			$_userId = $_studentInfo['user_id'];
			$_userId = $_studentInfo['user_id'];
			$_defaultCnt = '<a href="#/apps/iportfolio/schoolrecords/'.$recordType.'/student/?studentId='.$_userId;
			$_defaultCnt .= '&school_year_id='.$school_year_id;
			$_defaultCnt .= '&school_year_term_id='.$school_year_term_id.'">';
			$_defaultCnt .= '0</a>';
			$StudentListArr[$_classNumber]['count'] = $UserRecordArr[$_userId]['count']?$UserRecordArr[$_userId]['count']:$_defaultCnt;
			$StudentListArr[$_classNumber]['user_name'] = $_studentInfo['user_name_'.$intranet_session_language];
		}
		return $StudentListArr;
    }	
    function getAwardRecordList($data=array(),$sortby='',$order='',$amount='',$page=''){ 
		global $eclass_db,$kis_lang,$intranet_session_language;
		extract($data);
		$sort = $sortby? "$sortby $order":"schoolyear";
		$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";
		$cond = '';
		if(!empty($recordId)){
			$cond .= " AND a.RecordID = '".$recordId."'";
		}else{
			$cond .= (!empty($studentId))?" AND a.UserID = '".$studentId."'":"";
			$cond .= (!empty($keyword))?" AND (iu.EnglishName LIKE '%".$keyword."%' OR iu.ChineseName LIKE '%".$keyword."%')":"";
			$cond .= (!empty($classId)&&$classId!='all')?" AND ycu.YearClassID = '".$classId."'":"";
			if(!$showAllTerms)
				$cond .= (!empty($school_year_term_id))?" AND a.YearTermID = '".$school_year_term_id."'":" AND a.YearTermID IS NULL";
			$cond .= (!empty($school_year_id))?" AND a.AcademicYearID = '".$school_year_id."'":"";
		}
		$sql = "SELECT 
					COUNT(*) 
				FROM 
					".$eclass_db.".AWARD_STUDENT a
				INNER JOIN
					YEAR_CLASS_USER ycu ON a.UserID = ycu.UserID
				INNER JOIN
					YEAR_CLASS yc ON ycu.YearClassID = yc.YearClassID					
				INNER JOIN 
					INTRANET_USER iu ON a.UserID = iu.UserID				
				WHERE 
					yc.AcademicYearID = '".$this->schoolyear."' ".$cond;
		$total = current($this->returnVector($sql));
		$sql = "SELECT
					a.RecordID as award_id,
					a.UserID as user_id,
					".getNameFieldByLang("iu.")." as user_name,
					".(($intranet_session_language=='en')?"yc.ClassTitleEN":"yc.ClassTitleB5")." as class_name,
					a.Year as schoolyear,
					a.AcademicYearID as school_year_id,
					a.YearTermID as school_year_term_id,
					if(a.Semester = '' , '".$kis_lang['Activity']['WholeYear']."',a.Semester) as  'semester',
					IF(a.AwardDate,DATE_FORMAT(a.AwardDate,'%Y-%m-%d'),'') As award_date,
					a.AwardName as award_name,
					a.Organization AS organization,
					a.SubjectArea as subject_area,
					a.ModifiedDate as last_update_date,
					a.Remark as remarks
				FROM 
					".$eclass_db.".AWARD_STUDENT a
				INNER JOIN
					YEAR_CLASS_USER ycu ON a.UserID = ycu.UserID
				INNER JOIN
					YEAR_CLASS yc ON ycu.YearClassID = yc.YearClassID					
				INNER JOIN 
					INTRANET_USER iu ON a.UserID = iu.UserID				
				WHERE 
					yc.AcademicYearID = '".$this->schoolyear."'
				".$cond."
				ORDER BY $sort $limit";
			
		return array($total,$this->returnArray($sql));
    }
		
	//SBS
	function getStudentSchoolBasedSchemeRecord($parent_id='',$assignment_id=''){
		global $eclass_db,$kis_lang,$ck_memberType,$ck_user_id;
		global $lo,$lpf,$lgs; //defined in apps/iportfolio/index.php
		$ParArr["StudentID"] = $this->student_id;
		$ParArr["Role"] = "STUDENT";
		$ParArr["SchoolYear"] = GET_ACADEMIC_YEAR();
		$ParArr["parent_id"] = $parent_id;
		$SchemeData = $lpf->GET_SCHOOL_BASED_SCHEME($ParArr);

		$InputArr["StudentID"] = $ParArr["StudentID"];
		$result = array();
		for($i = 0; $i < count($SchemeData); $i++){
 			$InputArr["assignment_id"] = $SchemeData[$i]["assignment_id"];
			$InputArr["title"] = $SchemeData[$i]["title"];
			$InputArr["modified"] = $SchemeData[$i]["modified"];
			$InputArr["deadline"] = $SchemeData[$i]["deadline"];
			$InputArr["starttime"] = $SchemeData[$i]["starttime"];
			$InputArr["instruction"] = $SchemeData[$i]["instruction"];
			$InputArr["status"] = $SchemeData[$i]["status"];
			$_phaseDataArr = $lpf->GET_SCHOOL_BASED_SCHEME_WITH_PHASE($InputArr);
			$_phaseDataArrCnt = count($_phaseDataArr);
			/* Prepare Array */
			$InputArr["PhaseData"] = array();
			for($j=0;$j<$_phaseDataArrCnt;$j++){
				$__thisPhaseRecordArr = $_phaseDataArr[$j];
				if(empty($assignment_id)||$__thisPhaseRecordArr["assignment_id"]==$assignment_id){
					/* Check Phase Right */
					$__phaseObj = $lgs->getPhaseInfo($__thisPhaseRecordArr["assignment_id"]);
					$__phaseObj = $__phaseObj[0];
					$__personRight = $lgs->getPhaseRight($__memberType, $__phaseObj);
					/* Check Phase Right */
					$__canEdit = (($ck_memberType=='P'&&$__phaseObj[8]=='S')||($ck_memberType==$__phaseObj[8]))?1:0;
					$__userId = $ck_user_id;
					$__status = $lpf->GET_PHASE_STATUS($__thisPhaseRecordArr);
					$__statusoriginal = $lpf->GET_PHASE_STATUS_ORIGINAL($__thisPhaseRecordArr);
					$__handinObj = $lgs->getStudentPhaseHandin($__userId, $__phaseObj);
					
					if($__statusoriginal=='on' && $__canEdit) //in progress and can edit
						$__thisPhaseRecordArr["status"] = 'on_edit';
					else
						$__thisPhaseRecordArr["status"] = $__statusoriginal;
						
					$__thisPhaseRecordArr["starttime"] = date('Y-m-d',strtotime($__thisPhaseRecordArr["starttime"]));
					$__thisPhaseRecordArr["deadline"] = date('Y-m-d',strtotime($__thisPhaseRecordArr["deadline"]));
					$__thisPhaseRecordArr["role"] = $lpf->GET_ROLE_TYPE($__thisPhaseRecordArr["marking_scheme"]);
					
					$__thisPhaseRecordArr["personright"] = $__personRight;	
					$__thisPhaseRecordArr["answersheet"] = $__phaseObj['answersheet'];	
					$__thisPhaseRecordArr["sheettype"] = $__phaseObj['sheettype'];	
					$__thisPhaseRecordArr["answer_display_mode"] = $__phaseObj['answer_display_mode'];	
					$__thisPhaseRecordArr["handinObj"] = $__handinObj;	
					$InputArr["PhaseData"][] = $__thisPhaseRecordArr;
					
				}
			}
			
			if($Status == "on" && $ModifiedDate == "")
				$InputArr["mode"] = 'edit';
			else
				$InputArr["mode"] = 'view';
			
			if(count($InputArr["PhaseData"])>0){
				$result[] = $InputArr;
			}
			
		}
		return $result;
	}
	function getStudentPortfolios($keyword='',$page=0,$amount='all'){
		global $liblp2,$kis_lang;
		
		list($total,$lps) = $liblp2->getUserPortfolios($keyword, $page*$amount, $amount);
		foreach ($lps as $lp){
			$lp['key'] 				= libpf_lp2::encryptPortfolioKey( $lp['web_portfolio_id'], $this->student_id);
			$lp['modified_days'] 	= libpf_lp2::getDaysWord($lp['modified']);
			$lp['published_days'] 	= libpf_lp2::getDaysWord($lp['published']);
			$lp['share_url'] 		= libpf_lp2::getPortfolioUrl($lp['key']);
			$data[]	= $lp;
		}
		return array($total,$data);
	}
	function getSBSContent($parent_id,$assignment_id){
		global $kis_lang,$lpf,$lgs;
		$SBSRecord = current($this->getStudentSchoolBasedSchemeRecord($parent_id));
		//$PhaseDataArr = current($SBSRecord['PhaseData']);
		$PhaseDataArr = $SBSRecord['PhaseData'];
		$Title = (!empty($SBSRecord['title']))?$SBSRecord['title']:'&nbsp;';
		$phaseBeforeTarget = array();
		$phaseAfterTarget = array();
		$foundTarget = false;
		for($i=0;$i<count($PhaseDataArr);$i++){
			$_phaseData = $PhaseDataArr[$i];
			if($assignment_id == $_phaseData['assignment_id'])
				$foundTarget = true;
			$PhaseTitle = (!empty($_phaseData['title']))?$_phaseData['title']:'&nbsp;';
			$PhaseDesc = (!empty($_phaseData['instruction']))?$_phaseData['instruction']:'&nbsp;';
			$PhaseRole = $_phaseData['role'];
			$starttime = $lpf->GET_DATE_DISPLAY($_phaseData['starttime']);
			$deadline = $lpf->GET_DATE_DISPLAY($_phaseData['deadline']);
			$sheettype = $_phaseData["sheettype"];
			$handinObj = $_phaseData['handinObj'];
			
			if($_phaseData["answer_display_mode"]==1){
				$answersheet = ($sheettype!=2) ? preg_replace('(\r\n|\n)', "<br>", $_phaseData["answersheet"]) : preg_replace('(\r\n|\n)', "", $_phaseData["answersheet"]);
			}else{
				$answersheet = str_replace("\"", "\\\"", undo_htmlspecialchars($_phaseData["answersheet"]));
				$answersheet = ($sheettype!=2) ? preg_replace('(\r\n|\n)', "<br>", str_replace("&amp;", "&", $answersheet)) : preg_replace('(\r\n|\n)', "", str_replace("&amp;", "&", $answersheet));		
			} 
			$answer = preg_replace('(\r\n|\n)', "<br>", str_replace("&amp;", "&", $handinObj["answer"]));
			switch($_phaseData['status']){
				case "on_edit":
						$TitleCSS = 'pop_title_phase_active';
						$can_edit = true;
						$showQuestion = true;
					break;					
				case "on":
						$TitleCSS = 'pop_title_phase_active';
						$can_edit = true;
						$showQuestion = true;
					break;
				case "done":
						$TitleCSS = 'pop_title_phase_finihsed';
						$can_edit = false;
						$showQuestion = true;
					break;
				default:	
						$TitleCSS = 'pop_title';
						$can_edit = false;
						$showQuestion = false;
			}
			$Par = array(
				'assignment_id'=>$_phaseData['assignment_id'],
				'sheettype'=>$sheettype,
				'answer'=>$answer,
				'answersheet'=>$answersheet,
				'answer_display_mode'=>$_phaseData["answer_display_mode"],
				'can_edit'=>$can_edit
			);
			if($showQuestion){
				$x = '';
					$x .= ($can_edit)?'<form id="ansForm_'.$_phaseData['assignment_id'].'" method="post">':'';
				$x .= '<div class="pop_edit pop_sbs_form">';
					$x .= '<div class="pop_title '.$TitleCSS.' ">';
						$x .= '<span><em> '.$Title.' </em><em> '.$PhaseTitle.'</em></span>';
					$x .= '</div>';
			
					$x .= '<div class="table_board">';
						$x .= '<div class="sbs_form_desc">'.$PhaseDesc.'<span class="sbs_phase_target">'.$PhaseRole.'</span><span class="sbs_phase_period">'.$starttime.' - '.$deadline.'</span><span class="clip"></span></div>';
						$x .= $this->getPhaseQuestionContent($Par);
					
					  $x .= '<p class="spacer"></p>
						  </div>';
						$x .= '<div class="edit_bottom">';
								
						if($can_edit){
							$x .= '<input type="hidden" id="parent_id" value="'.$parent_id.'">
								<input type="hidden" id="handin_id" value="'.$handinObj['handin_id'].'">
								<input name="submitBtn" type="button" class="formbutton" onclick="kis.iportfolio.sbs_submit_form('.$_phaseData['assignment_id'].');" value="Submit" />';
						}		
								$x .= '<input name="cancelBtn" type="button" class="formsubbutton" onclick="parent.$.fancybox.close();" value="Cancel" />
						  </div>';
					
					$x .= '</div>';
				$x .= ($can_edit)?'</form>':'';
				if($foundTarget)
					$phaseAfterTarget[] = $x;
				else
					$phaseBeforeTarget[] = $x;
			}
		}
		
		return array_merge($phaseAfterTarget, $phaseBeforeTarget); 
	}
	function getPhaseQuestionContent($Par){
		$x = '<table class="form_table">';
		if($Par["sheettype"]==2){
			$x .= '<tr>';
				$x .= '<td>'.$Par["answersheet"].'</td>';
			$x .= '</tr>';	
		}else{
			$QuestionArr = explode($this->delimiters['questions'], $Par["answersheet"]);
			$qCnt = count($QuestionArr);
			$AnswerArr = explode($this->delimiters['answers'], $Par["answer"]);	
			for($j=1; $j<$qCnt; $j++){
				$x .= $this->getAnswerSheetByQuestionType($Par["assignment_id"].'_'.$j,$QuestionArr[$j],$AnswerArr[$j],$Par["answer_display_mode"],$Par['can_edit']);
			}
		}
		$x .= '</table>';
		return $x;
	}
	function getAnswerSheetByQuestionType($ansIndex,$CurrentQuestion,$CurrentAnswer,$answer_display_mode,$can_edit){
		global $kis_lang;
		list($QuestionFormat, $QuestionContent, $Options) = explode("||", $CurrentQuestion);
		$QuestionContent = str_replace("\"", "\"\"", strip_tags(html_entity_decode($QuestionContent)));
		$OptionArr = explode($this->delimiters['options'], $Options);
		list($QuestionType,$row,$col) = explode(',',$QuestionFormat);
		$inputName = 'F_'.$ansIndex;
		$no_answer = "<i><font color='gray'>&lt;".$kis_lang['SchoolBasedScheme']['NoAnswer']."&gt;</font></i>";
		$x = '<tr>';
			$x .= '<td>'.$QuestionContent.'</br>';
			switch($QuestionType){
				case 1: case 2://True or False / MC
						if($can_edit){
							for($i=1;$i<count($OptionArr);$i++){
								$id = $inputName.'_'.$i;
								$checked = ($CurrentAnswer==($i-1))?'checked':'';
								$x .= '<input class="sbs_answer" type="radio" name="'.$inputName.'" id="'.$id.'"  value="'.($i-1).'" '.$checked.' />';
								$x .= '<label for="'.$id.'">';
								$x .= $OptionArr[$i];
								$x .= '</label>';
							}
						}else{
							$x .= $OptionArr[$CurrentAnswer+1]?$OptionArr[$CurrentAnswer+1]:$no_answer;
						}
					break;
				case 3://More than one option allowed
						$AnswerArr = explode(',',$CurrentAnswer);
						if(strlen($AnswerArr[0])||$can_edit){
							for($i=1;$i<count($OptionArr);$i++){
								$id = $inputName.'_'.$i;
								$checked = (in_array(($i-1),$AnswerArr))?'checked':'';
								$x .= '<input class="sbs_answer" type="checkbox" name="'.$inputName.'" id="'.$id.'"  value="'.($i-1).'" '.$checked.' />';
								$x .= '<label for="'.$id.'">';
								$x .= $OptionArr[$i];
								$x .= '</label>';
							}
						}else{
							$x .= $no_answer;
						}	
					break;
				case 4://Fill in Short
						if($can_edit){
							$x .= '<input class="sbs_answer" type="text" name="'.$inputName.'" size="80" value="'.$CurrentAnswer.'">';
						}else{
							$x .= $CurrentAnswer?$CurrentAnswer:$no_answer;
						}
					break;	
				case 5://Fill in Long
						if($can_edit){
							$x .= '<textarea class="sbs_answer" name="'.$inputName.'" cols="80" rows="5">'.str_replace("<br>", "\n", $CurrentAnswer).'</textarea>';
						}else{
							$x .= $CurrentAnswer?$CurrentAnswer:$no_answer;
						}		
					break;		
				case 6://Likert Scale
						$AnswerArr = explode(',',$CurrentAnswer);
						if(strlen($AnswerArr[0])||$can_edit){
							$x .= '<table id="TB_'.$ansIndex.'" cellspacing="5" cellpadding="5" border="0" align="left" name="TB_'.$ansIndex.'" style="width:auto;">';
							for($i=0;$i<=$row;$i++){
								$x .= '<tr>';
									$x .= '<td align="left">';
										$x .= ($i==0)?'&nbsp;':$i.'.';
									$x .= '</td>';
									$x .= '<td style="text-align:center;">';
										$x .= ($i==0)?'&nbsp;':$OptionArr[$i];
									$x .= '</td>';
									for($j=0;$j<$col;$j++){
										if($i>0)
											$checked = ($j==$AnswerArr[$i-1])?'checked="checked"':'';
										$inputName = 'RS_'.$ansIndex.'_'.($i-1);
										$x .= '<td style="text-align:center;">';
											$x .= ($i==0)?$OptionArr[$row+$j+1]:'<input class="sbs_answer" type="radio" name="'.$inputName.'" value="'.$j.'" '.$checked.'>';
										$x .= '</td>';
									}
								$x .= '</tr>';
							}
							$x .= '</table>';
						}else{
							$x .= $no_answer;
						}
					break;						
				case 7://Table-like
						$AnswerArr = explode('#MQA#',$CurrentAnswer);
						$hasAnswer = false;
						foreach($AnswerArr as $ans){
							if(strlen($ans)>0){
								$hasAnswer = true;
								break;
							}						
						}
						if($can_edit||$hasAnswer){
							$idx = 1;
							$x .= '<table id="TB_'.$ansIndex.'" cellspacing="5" cellpadding="5" border="0" align="left" name="TB_'.$ansIndex.'" style="width:auto;">';
							for($i=0;$i<=$row;$i++){
								$x .= '<tr>';
									$x .= '<td align="left">';
										$x .= ($i==0)?'&nbsp;':$i.'.';
									$x .= '</td>';
									$x .= '<td style="text-align:center;">';
										$x .= ($i==0)?'&nbsp;':$OptionArr[$i];
									$x .= '</td>';
									for($j=0;$j<$col;$j++){
										$inputName = 'RS_'.$ansIndex.'_'.($i-1).'_'.$j;
										$x .= '<td style="text-align:center;">';
											$x .= ($i==0)?$OptionArr[$row+$j+1]:'<input class="sbs_answer" type="text" size="10" value="'.$AnswerArr[$idx].'" name="'.$inputName.'">';
										$x .= '</td>';
										if($i>0)
											$idx++;
									}
								$x .= '</tr>';
							}
							$x .= '</table>';
						}else{
							$x .= $no_answer;
						}
					break;
				case 8: //Not Applicable
						$inputName = str_replace('F','NA',$inputName);
						$x .= '<input class="sbs_answer" type="hidden" name="NA" >';
					break;
			}
			$x .= '</td>';
		$x .= '</tr>';
		return $x;
	}
	function saveSBSContent($parent_id,$assignment_id,$handin_id,$ans_str){
		global $lgs,$ck_memberType,$ck_user_id;
		$phase_obj = $lgs->getPhaseInfo($assignment_id);
		$phase_obj = $phase_obj[0];
		$memberType = (($ck_memberType=='P'&&$phase_obj[8]=='S'))?'S':$ck_memberType;
		//$user_id = $lpf->getCourseUserID($this->student_id);
		$person_right = $lgs->getPhaseRight($memberType, $phase_obj);
		if ($person_right=="DO" || $person_right="REDO")
		{
			$answer = HTMLtoDB($ans_str);
			$update_status = ($person_right=="REDO") ? "LR" : "L";
			if ($handin_id!="")
			{
				$sql = "UPDATE $this->course_db.handin SET answer='$answer', filename=NULL, inputdate=now(), status='$update_status', comment=NULL WHERE handin_id='$handin_id' and user_id='$ck_user_id'";
			} 
			else
			{
				$newStatus = "NULL";
				$sql = "INSERT INTO $this->course_db.handin (assignment_id, user_id, answer, comment, status, inputdate, type) VALUES ('$assignment_id', '$ck_user_id', '$answer', '$newStatus', '$update_status', now(), 'sbs')";
			}
			$this->db_db_query($sql);
		} 
	}
	
	//Assessment Report Function
	function getClassInfo($ClassName=''){
		global $intranet_db;
		$cond = (!empty($ClassName))?" AND (ClassTitleEN like '%$ClassName%' OR ClassTitleB5 like '%$ClassName%')":"";
		$sql = "SELECT
				YearClassID as class_id,
				ClassTitleEN as class_name_en,
				ClassTitleB5 as class_name_b5
			FROM ".$intranet_db.".YEAR_CLASS				
			WHERE
				 AcademicYearID = ".$this->schoolyear."
				".$cond."
			ORDER BY ClassTitleEN
			";
		return $this->returnArray($sql);
    }
	function getClassSelection($ID,$Name='',$ShowAll=1,$DefaultValue='all',$Others=''){
		global $intranet_session_language,$kis_lang;
		$ClassList = $this->getClassInfo();
		$Name = (!empty($Name))?$Name:$ID;
		$x = '<select id="'.$ID.'" name="'.$Name.'" '.$Others.'>';
		$x .= ($ShowAll)?'<option value="all" '.(($DefaultValue=='all')?'selected':'').'>'.$kis_lang['Assessment']['AllClasses'].'</option>':'';
		for($i=0;$i<count($ClassList);$i++){
			$ClassName = $ClassList[$i]['class_name_'.$intranet_session_language];
			$ClassName = (!empty($ClassName))?$ClassName:$ClassList[$i]['class_name_en'];
			$x .= '<option value="'.$ClassList[$i]['class_id'].'" '.(($DefaultValue==$ClassList[$i]['class_id'])?'selected':'').'>';
			$x .= $ClassName;
			$x .= '</option>';
		}
		$x .= '</select>';
		return $x;
	}
	function getStatusSelection($Type,$ID,$Name='',$ShowAll=1,$DefaultValue='all',$Others=''){
		global $kis_lang;
		$Name = (!empty($Name))?$Name:$ID;
		$x = '<select id="'.$ID.'" name="'.$Name.'" '.$Others.' >';
		$x .= ($ShowAll)?'<option value="all" '.(($DefaultValue=='all')?'selected':'').'>'.$kis_lang['all'].'</option>':'';
		foreach($kis_lang['Assessment'][$Type] as $key => $status){
			$x .= '<option value="'.$key.'" '.(($DefaultValue==$key)?'selected':'').'>'.$status.'</option>';
		}
		$x .= '</select>';
		return $x;
	}
	function getTotalAssessmentCount($classId='',$status='',$keyword=''){
		global $eclass_db;
		//$classId
		$cond .= ($classId!='all')?" AND ClassName = ".$classId."":"";
		//$status
		$cond .= ($status!='all')?" AND ReleaseDate ".(($status==1)?"<=":">")." '".$today."'":"";
		//$keyword
		$cond .= (!empty($keyword))?" AND AssessmentTitle LIKE '%".$keyword."%'":"";
		
		$sql = "SELECT
					COUNT(*) cnt
				FROM
					".$eclass_db.".ASSESSMENT_REPORT 				
				WHERE
					AcademicYearID = '".$this->schoolyear."'
					".$cond."
		";
		$result = $this->returnVector($sql);
		return $result[0];
	}
	function getAssessmentSelectionByStudentId($classId='',$studentId='',$assessmentId=''){
		global $eclass_db,$intranet_session_language;
		list($total,$AssessmentList) = $this->getStudentAssessmentByStudentId($recordId='',$classId,$studentId);
		$AssessmentIDList = Get_Array_By_Key($AssessmentList,'AssessmentID');
	
		$cond = '';
		//$classId
		$cond .= (!empty($classId))?" AND ClassName = '".$classId."'":"";
		$sql = "
			SELECT 
				RecordID,
				AssessmentTitle
			FROM
				".$eclass_db.".ASSESSMENT_REPORT
			WHERE
				AcademicYearID = '".$this->schoolyear."'
			AND	
				RecordID NOT IN ('".implode("','",$AssessmentIDList)."')
			".$cond."	
		";
		$AssessmentList = $this->returnArray($sql);
		$x = "<select id='assessment_id' name='assessment_id'>";
		for($i=0;$i<count($AssessmentList);$i++){
			$selected = ($assessmentId==$AssessmentList[$i]['AssessmentID'])?" selected":"";
			$x .= "<option value='".$AssessmentList[$i]['AssessmentID']."'".$selected.">";
			$x .= $AssessmentList[$i]['AssessmentTitle'];
			$x .= "</option>";
		}
		$x .= "</select>";
		return $x;
	}	
	function getAssessmentList($assessmentId='',$classId='all',$releaseStatus='all',$keyword='',$sortby='',$order='',$amount='',$page=''){
		global $eclass_db,$intranet_db,$kis_lang,$intranet_session_language;
		$today = DATE('Y-m-d H:i:s');
		$cond = '';
		//$classId
		$cond .= ($classId!='all')?" AND r.ClassName = '".$classId."'":"";
		//$assessmentId
		$cond .= ($assessmentId!='')?" AND r.RecordID = '".$assessmentId."'":"";		
		//$releaseStatus
		$cond .= ($releaseStatus!='all')?" AND r.ReleaseDate ".(($releaseStatus==1)?"<=":">")." '".$today."'":"";
		//$keyword
		$cond .= (!empty($keyword))?" AND (r.AssessmentTitle LIKE '%$keyword%' OR c.ClassTitleEN LIKE '%$keyword%' OR c.ClassTitleB5 LIKE '%$keyword%')":"";
	
		$sort = $sortby? "$sortby $order":"title";
		$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";
		
		$sql = "SELECT
					r.RecordID as id,
					r.AssessmentTitle as title,
					r.ClassName as classId,
					DATE_FORMAT(r.ReleaseDate,'%Y-%m-%d') as release_date,
					".(($intranet_session_language=='b5')?"c.ClassTitleB5":"c.ClassTitleEN")." as classname,
					".getNameFieldByLang("u.")." as created_user,
					DATE_FORMAT(r.ModifiedDate,'%Y-%m-%d') as last_update_date,
					".getNameFieldByLang("iu.")." as modified_user
				FROM
					".$eclass_db.".ASSESSMENT_REPORT r
				INNER JOIN
					".$intranet_db.".YEAR_CLASS c ON r.ClassName = c.YearClassID
				INNER JOIN 
					".$intranet_db.".INTRANET_USER  u ON r.Createdby = u.UserID 	
				INNER JOIN 
					".$intranet_db.".INTRANET_USER  iu ON r.ModifiedBy = iu.UserID 						
				WHERE
					r.AcademicYearID = '".$this->schoolyear."'
					".$cond."
				ORDER BY $sort $limit
		";
		return $this->returnArray($sql);
	}
	function saveAssessment($title,$release_date,$classId){
		global $eclass_db;

		if($classId=='all'){
			$classArr = $this->getClassInfo();
			$classArr = Get_Array_By_Key($classArr,'class_id');
		}else{
			$classArr = (array)$classId;
		}	
		if(!empty($title)){
			$insertValuesArr = array();
			$sql = "INSERT INTO ".$eclass_db.".ASSESSMENT_REPORT
						(AcademicYearID,AssessmentTitle,ReleaseDate,ClassName,InputDate,Createdby,ModifiedDate,ModifiedBy)
					VALUES ";
			for($i=0;$i<count($classArr);$i++){
				$insertValuesArr[] = "(
						'".$this->schoolyear."',
						'".$title."',
						'".$release_date."',
						'".$classArr[$i]."',
						NOW(),
						'".$this->user_id."',
						NOW(),
						'".$this->user_id."'
					)";
			}
			$sql .= implode(',',$insertValuesArr);		
			$this->db_db_query($sql);
		}
	}
	function updateAssessment($assessmentId,$title,$release_date){
		global $eclass_db;
		$sql = "UPDATE ".$eclass_db.".ASSESSMENT_REPORT SET 
					AssessmentTitle = '".$title."',
					ReleaseDate = '".$release_date."',
					ModifiedDate = NOW(),
					ModifiedBy = '".$this->user_id."'
				WHERE
					RecordID = '".$assessmentId."'";		
		$this->db_db_query($sql);		
			
	}
	function removeAssessment($assessmentId){
		global $eclass_db;
		$sql = "DELETE FROM ".$eclass_db.".ASSESSMENT_REPORT 
				WHERE
					RecordID = '".$assessmentId."'";		
		$this->db_db_query($sql);		
			
	}	
	function getNavigationBar($NavArr){
		$x = '<div class="navigation_bar">';
		foreach((array)$NavArr as $key=>$arr){
			list($path,$name) = (array)$arr;
			if(!empty($path))
				$x .= '<a href="#/apps/iportfolio/'.$path.'">'.$name.'</a>';
			else
				$x .= '<span>'.$name.'</span>';
			$NavCnt--;
		}
		$x .= '</div>';
		return $x;
	}
	function getStudentAssessmentList($assessmentId='',$classId='',$studentIdAry=array(), $status='all', $keyword=''){
		/* Get Student List */
		global $intranet_session_language,$eclass_db,$intranet_db;
		$user_name_field = ($intranet_session_language=='b5')?'user_name_b5':'user_name_en';
		$cond = '';
		$cond .= (!empty($assessmentId))?" AND r.AssessmentID = '".$assessmentId."'":"";
		$cond .= (!empty($classId))?" AND r.ClassName = '".$classId."'":"";
		//$cond .= (!empty($keyword))?" OR r.ReportTitle LIKE '%".$keyword."%'":"";
		$cond .= (is_array($studentIdAry) && count($studentIdAry) > 0)?" AND r.UserID IN ('".(implode("','", $studentIdAry))."')":"";
		
		$sql = "
			SELECT 
				r.RecordID,
				r.UserID,
				DATE_FORMAT(r.InputDate,'%Y-%m-%d') as input_date,
				DATE_FORMAT(r.ModifiedDate,'%Y-%m-%d') as modified_date,
				".getNameFieldByLang("u.")." as created_user,		
				r.Status,
				r.ReportTitle
			FROM	
				".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD r
			INNER JOIN
				".$intranet_db.".INTRANET_USER u ON r.CreatedBy = u.UserID
			INNER JOIN
				".$intranet_db.".INTRANET_USER iu ON r.UserID = iu.UserID	
			WHERE
				r.AcademicYearID = '".$this->schoolyear."'
			".$cond."
		";
		$result = $this->returnArray($sql);
		$StudentAssessmentList = BuildMultiKeyAssoc($result,'UserID');
		$StudentAssessmentIDList = Get_Array_By_Key($result, 'UserID');
		if(is_array($studentIdAry) && count($studentIdAry) > 0){
			$StudentList = kis_utility::getUsers(array('user_ids'=>$studentIdAry,'keyword'=>$keyword));
		}else{
			switch($status){
				case '1': //File uploaded
					$StudentList = kis_utility::getUsers(array('class_id'=>$classId,'user_ids'=>$StudentAssessmentIDList,'keyword'=>$keyword,'user_type'=>'S'));	
					break;
				case '2':
					$StudentList = kis_utility::getUsers(array('class_id'=>$classId,'excludes'=>$StudentAssessmentIDList,'keyword'=>$keyword,'user_type'=>'S'));	
					break;
				default:
					$StudentList = kis_utility::getUsers(array('class_id'=>$classId,'keyword'=>$keyword,'user_type'=>'S'));	
			}
		}		
		$StudentIDList = BuildMultiKeyAssoc($StudentList,'user_class_number',array('user_id',$user_name_field));
		ksort($StudentIDList);	
		$NewStudentAssessmentList = array();
		foreach($StudentIDList as $_studentNumber => $_studentInfo){
			$_studentId = $_studentInfo['user_id'];
			$_studentName = $_studentInfo[$user_name_field];
			
			$_report = $StudentAssessmentList[$_studentId]['ReportTitle']?$StudentAssessmentList[$_studentId]['ReportTitle']:'';
			$_modifiedDate = $StudentAssessmentList[$_studentId]['input_date']?$StudentAssessmentList[$_studentId]['input_date']:'';;
			$_modifiedUser = $StudentAssessmentList[$_studentId]['created_user']?$StudentAssessmentList[$_studentId]['created_user']:'';;
			
			$NewStudentAssessmentList[$_studentNumber]['user_id'] = $_studentId;
			$NewStudentAssessmentList[$_studentNumber]['name'] = $_studentName;
			$NewStudentAssessmentList[$_studentNumber]['status'] = $StudentAssessmentList[$_studentId]['Status'];
			if(!empty($_report))
				$NewStudentAssessmentList[$_studentNumber]['assessment'] = array(
																			'title'=>$_report,
																			'path'=>kis_iportfolio::$attachment_url.'assessment/'.$assessmentId.'/'.$_studentId.'/'.$_report,
																			'modified_date'=>$_modifiedDate,
																			'modified_user'=>$_modifiedUser
																		);
		
			
		}
		
		return $NewStudentAssessmentList;
	}
	function getStudentAssessmentByStudentId($recordId='',$classId='',$studentId='',$sortby='',$order='',$amount='',$page='',$showAll=false){
		global $eclass_db,$intranet_db;
		$sort = $sortby? "$sortby $order":"report_title";
		$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";
		$studentId = $studentId? $studentId: $this->student_id;
		
		if($showAll){ //display all assessments 
			$cond = (!empty($classId))?" AND a.ClassName = '".$classId."'":"";
			$sql = "SELECT COUNT(*) FROM ".$eclass_db.".ASSESSMENT_REPORT a WHERE a.AcademicYearID = '".$this->schoolyear."'".$cond;
			$total = current($this->returnVector($sql));
			$sql = "SELECT 	
						r.UserID as user_id,
						r.RecordID as record_id,
						a.RecordID as AssessmentID,
						a.AssessmentTitle as assessment_title,
						DATE_FORMAT(r.ModifiedDate,'%Y-%m-%d') as modified_date,
						DATE_FORMAT(a.ReleaseDate,'%Y-%m-%d') as release_date,
						".getNameFieldByLang("u.")." as created_user,	
						".getNameFieldByLang("iu.")." as modified_user,	
						r.Status,
						r.ReportTitle as report_title
					FROM	
						".$eclass_db.".ASSESSMENT_REPORT a
					LEFT JOIN
						".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD r ON r.AssessmentID = a.RecordID
					AND
						(r.UserID = '".$studentId."' OR r.UserID is NULL)						
					LEFT JOIN
						".$intranet_db.".INTRANET_USER u ON a.CreatedBy = u.UserID
					LEFT JOIN
						".$intranet_db.".INTRANET_USER iu ON r.ModifiedBy = iu.UserID					
					WHERE
						a.AcademicYearID = '".$this->schoolyear."'
					
					".$cond."
					ORDER BY $sort $limit
			";
		}else{ //display those with records
			$cond = (!empty($recordId))?" AND r.RecordID = '".$recordId."'":"";
			$sql = "SELECT COUNT(*) FROM ".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD r WHERE r.UserID = ".$studentId.$cond;
			$total = current($this->returnVector($sql));
			$sql = "SELECT 	
						r.UserID as user_id,
						r.RecordID as record_id,
						r.AssessmentID,
						a.AssessmentTitle as assessment_title,
						DATE_FORMAT(r.ModifiedDate,'%Y-%m-%d') as modified_date,
						DATE_FORMAT(a.ReleaseDate,'%Y-%m-%d') as release_date,
						".getNameFieldByLang("u.")." as created_user,	
						".getNameFieldByLang("iu.")." as modified_user,	
						r.Status,
						r.ReportTitle as report_title
					FROM	
						".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD r
					INNER JOIN
						".$eclass_db.".ASSESSMENT_REPORT a ON r.AssessmentID = a.RecordID				
					INNER JOIN
						".$intranet_db.".INTRANET_USER u ON r.CreatedBy = u.UserID
					INNER JOIN
						".$intranet_db.".INTRANET_USER iu ON r.ModifiedBy = iu.UserID					
					WHERE
						r.AcademicYearID = '".$this->schoolyear."'
					AND
						r.UserID = '".$studentId."'
					".$cond."
					ORDER BY $sort $limit
			";
		}
		
		return array($total,$this->returnArray($sql));
	}
	function saveStudentAssessment($assessmentId,$classId,$studentId,$file_name){
		$AssessmentArr = current($this->getStudentAssessmentList($assessmentId,$classId,array($studentId)));
		if(count($AssessmentArr['assessment'])>0){ //remove old file first
			$this->removeStudentAssessment($assessmentId,$studentId,$file_name);
		}
		$this->insertStudentAssessment($assessmentId,$classId,$studentId,$file_name);
		
	}
	function updateStudentAssessment($assessmentId,$studentId,$status){	
		global $eclass_db;
		
		$sql = "UPDATE ".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD
			SET
				Status = '".$status."',
				ModifiedDate = NOW()
				
			WHERE
				AssessmentID = '".$assessmentId."'
			AND
				UserID = '".$studentId."'	
				
		";
		$this->db_db_query($sql);
	}
	function insertStudentAssessment($assessmentId,$classId,$studentId,$file_name){	
		global $eclass_db;
		$StudentInfoArr = current(kis_utility::getUsers(array('user_id'=>$studentId)));
		$sql = "INSERT INTO 
					".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD
					( AssessmentID,AcademicYearID,UserID,ClassName,ClassNumber,ReportTitle,Status,InputDate,Createdby,ModifiedDate,ModifiedBy)
				VALUES
					(
						'".$assessmentId."',
						'".$this->schoolyear."',
						'".$studentId."',
						'".$classId."',
						'".$StudentInfoArr['user_class_number']."',
						'".$file_name."',
						'0',
						NOW(),
						'".$this->user_id."',
						NOW(),
						'".$this->user_id."'
					)
				
		";
		$this->db_db_query($sql);
	}	
	function removeStudentAssessment($assessmentId,$classId,$studentId){
		global $eclass_db,$file_path;
		$AssessmentArr = current($this->getStudentAssessmentList($assessmentId,$classId,array($studentId)));
 		if(count($AssessmentArr['assessment'])>0){ //remove old file first
			$exist_file = $file_path.'/'.kis_iportfolio::$attachment_url.'assessment/'.$assessmentId.'/'.$studentId.'/'.$AssessmentArr['assessment']['title'];
			if (file_exists($exist_file)){
				unlink($exist_file);
			}
 			$sql = "
				DELETE FROM
					".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD
				WHERE
					AssessmentID = '".$assessmentId."'
				AND
					ClassName = '".$classId."'
				AND
					UserID = '".$studentId."'
			"; 
			$this->db_db_query($sql); 
		}
	}
	function getClassStudentAssessmentCount(){
		global $intranet_db,$eclass_db;
		$sql = "
				SELECT 
					r.RecordID,
					u.YearClassID,
					COUNT(*) cnt 
				FROM 
					".$eclass_db.".ASSESSMENT_REPORT r
				INNER JOIN
					".$intranet_db.".YEAR_CLASS_USER u ON r.ClassName = u.YearClassID
				WHERE 
					u.YearClassID IS NOT NULL
				GROUP BY
					r.RecordID,u.YearClassID";
		$StudentCnt = $this->returnArray($sql);
		$StudentCntArr = BuildMultiKeyAssoc($StudentCnt,'RecordID',array('YearClassID','cnt'));
		$output = array();
		foreach($StudentCntArr as $_recordId => $_studentCnt){
			$_classId = $_studentCnt['YearClassID'];
			$sql = "
				SELECT COUNT(*)  
					FROM ".$eclass_db.".ASSESSMENT_REPORT_STUDENT_RECORD 
				WHERE 
					AcademicYearID	= '".$this->schoolyear."'
				AND
					AssessmentID = '".$_recordId."'
				AND
					ClassName = '".$_classId."'";	
					
			$_record = $this->returnVector($sql);
			$output[$_recordId]['studentCnt'] = (!empty($_studentCnt['cnt']))?$_studentCnt['cnt']:0;
			$output[$_recordId]['assessmentCnt'] = (!empty($_record[0]))?$_record[0]:0;
			
		}
		return $output;
	}
	function getTeacherClassList($sortby='',$order='',$amount='',$page='',$keyword=''){
		global $eclass_db,$ck_user_id,$intranet_db;
		$sql =  "
			  SELECT
				MAX(inputdate)
			  FROM
				{$eclass_db}.course_count
			  WHERE
				DATE_FORMAT(inputdate, '%Y-%m-%d') < DATE_FORMAT(NOW(), '%Y-%m-%d') AND
				course_id = {$this->course_id} AND
				user_id = {$ck_user_id}
			";
		$last_login = current($this->returnVector($sql));
		$sql =  "
          CREATE TEMPORARY TABLE tempUpdatedSRRecord(
            YearClassID int(11),
            RecSum int(11),
            PRIMARY KEY (YearClassID)
          )
        ";
		$this->db_db_query($sql);
		$sql = "Select YearClassID From {$intranet_db}.YEAR_CLASS as yc Where yc.AcademicYearID = '".$this->schoolyear."'";
		$yearClassIdAry = $this->returnVector($sql);
		$SRModuleArr = array(
							"ACTIVITY_STUDENT",
							"AWARD_STUDENT"
						);
		for($i=0; $i<count($SRModuleArr); $i++){				
			$sql =	"
				INSERT INTO
				  tempUpdatedSRRecord
					(YearClassID, RecSum)
							SELECT
								ycu.YearClassID,
								count(a.UserID)
							FROM
								{$intranet_db}.INTRANET_USER as iu
							INNER JOIN
								{$eclass_db}.".$SRModuleArr[$i]." as a
							ON
								a.UserID = iu.UserID AND
								iu.RecordStatus = 1 AND
								iu.RecordType = 2
							INNER JOIN
							  {$intranet_db}.YEAR_CLASS_USER as ycu
							ON
							  iu.UserID = ycu.UserID
							WHERE
								a.ModifiedDate > '".$last_login."'
								And ycu.YearClassID In ('".implode("','", (array)$yearClassIdAry)."')
							
							GROUP BY
								ycu.YearClassID
							ON DUPLICATE KEY UPDATE
							  RecSum = RecSum + VALUES(RecSum)
						";
			$this->db_db_query($sql);	
		}
		//Get data		
		$sort = $sortby? "$sortby $order":"SortClassName";
		$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";
		$sql = "
			SELECT
				COUNT(DISTINCT yc.YearClassID)
			  FROM
				{$intranet_db}.YEAR_CLASS AS yc
			  LEFT JOIN {$intranet_db}.YEAR_CLASS_USER AS ycu
				ON  yc.YearClassID = ycu.YearClassID
			  LEFT JOIN {$intranet_db}.INTRANET_USER AS iu
				ON  ycu.UserID = iu.UserID
			  LEFT JOIN {$eclass_db}.PORTFOLIO_STUDENT AS ps
				ON  iu.UserID = ps.UserID
			  WHERE
				yc.AcademicYearID = '".$this->schoolyear."' AND
				yc.YearID != 0 AND
				iu.RecordType = 2 AND
				iu.RecordStatus = 1
		";
		$recordCount = current($this->returnVector($sql));
	
		$sql =  "
          SELECT
            CONCAT('<a href=\"#/apps/iportfolio/studentaccount/class/?classId=', yc.YearClassID,'\">', ".$this->GetYearClassTitleFieldByLang("yc.").", '</a>') as ClassName,
            ".$this->GetYearClassTitleFieldByLang("yc.")." as SortClassName,
            IF(t_SRRec.RecSum IS NULL, 0, t_SRRec.RecSum) AS NewSRCount,
            (count(uc.user_config_id) - count(ptl.portfolio_tracking_id)) AS NewLPCount,
            count(DISTINCT arsr.UserID) AS AssessmentCount ,
            count(DISTINCT ycu.YearClassUserID) AS StudentCount
          FROM
            {$intranet_db}.YEAR_CLASS AS yc
          LEFT JOIN {$intranet_db}.YEAR_CLASS_USER AS ycu
            ON  yc.YearClassID = ycu.YearClassID
          LEFT JOIN {$intranet_db}.INTRANET_USER AS iu
            ON  ycu.UserID = iu.UserID
          LEFT JOIN {$eclass_db}.PORTFOLIO_STUDENT AS ps
            ON  iu.UserID = ps.UserID
          LEFT JOIN ".$this->course_db.".user_config AS uc
            ON  ps.CourseUserID = uc.user_id AND
                uc.notes_published IS NOT NULL
		  LEFT JOIN {$eclass_db}.ASSESSMENT_REPORT_STUDENT_RECORD AS arsr 
			ON 	iu.UserID = arsr.UserID 		
          LEFT JOIN ".$this->course_db.".portfolio_tracking_last AS ptl
            ON  uc.user_id = ptl.student_id AND
                uc.web_portfolio_id = ptl.web_portfolio_id AND
                ptl.teacher_id = {$ck_user_id}
          LEFT JOIN tempUpdatedSRRecord AS t_SRRec
            ON  yc.YearClassID = t_SRRec.YearClassID
          WHERE
	    
            yc.AcademicYearID = '".$this->schoolyear."' AND
			yc.YearID != 0 AND
            iu.RecordType = 2 AND
            iu.RecordStatus = 1
	    AND (yc.ClassTitleEN LIKE '%$keyword%' OR yc.ClassTitleB5 LIKE '%$keyword%')
          GROUP BY
            yc.YearClassID
		ORDER BY $sort $limit
        ";
		return array($recordCount,$this->returnArray($sql));
	}
	function getTeacherClassStudentList($classId='',$search_name='',$amount='',$page=''){
		global $eclass_db,$ck_user_id;
		$cond = "";
		$cond .= (!empty($classId)&&$classId!='all')?" AND yc.YearClassID = '".$classId."'":"";
		$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount": "";
		if(!empty($search_name)){
			$sql = "
					SELECT 
						iu.UserID
					FROM
						INTRANET_USER iu
					INNER JOIN 
						YEAR_CLASS_USER AS ycu ON ycu.UserID = iu.UserID 
					INNER JOIN 
						YEAR_CLASS AS yc ON yc.YearClassID = ycu.YearClassID
					WHERE 
						yc.AcademicYearID = '".$this->schoolyear."' 
					AND
						iu.RecordType = 2 
					AND 
						iu.RecordStatus = 1
					AND 
					(
						iu.EnglishName LIKE '%".$search_name."%' 
						OR iu.ChineseName LIKE '%".$search_name."%'
					)
					".$cond."
			";
			$user_id = $this->returnVector($sql);
			$total = count($user_id);
			$cond .= ($total>0)?" AND iu.UserID IN ('".implode("','",$user_id)."') ":"";
		}else{
			$total = (!empty($classId)&&$classId!='all')?count(kis_utility::getUsers(array('class_id'=>$classId,'user_type'=>'S'))):count(kis_utility::getUsers(array('academic_year_id'=>$this->schoolyear,'user_type'=>'S')));
		}
		$ClassField = ($intranet_session_language=='en')?"yc.ClassTitleEN":"yc.ClassTitleB5";
		$sql = "
				SELECT 
					iu.UserID, 
					".$ClassField." as ClassName,
					".getNameFieldByLang2("iu.")." AS UserName,
					iu.UserLogin 
				FROM
					INTRANET_USER iu
				INNER JOIN 
						YEAR_CLASS_USER AS ycu ON ycu.UserID = iu.UserID 
				INNER JOIN 
					YEAR_CLASS AS yc  ON yc.YearClassID = ycu.YearClassID
				WHERE 
					yc.AcademicYearID = '".$this->schoolyear."' 
				AND
					iu.RecordType = 2 
				AND 
					iu.RecordStatus = 1					
				".$cond."
				ORDER BY ".$ClassField.",iu.ClassNumber
				$limit
		";

		return array($total,$this->returnArray($sql));
		
	}
	function getStudentSelectionByClassId($classId,$studentId){
		global $intranet_session_language;
		if($classId == 'all'){
			$studentInfo = kis_utility::getUsers(array('academic_year_id'=>$this->schoolyear,'user_type'=>'S'));
		}else{
			$studentInfo = kis_utility::getUsers(array('class_id'=>$classId,'user_type'=>'S'));
		}
		$x = "<a href='#' class='prev'> </a><span>";
		$x .= "<select id='studentId' name='studentId' class='auto_submit'>";
		for($i=0;$i<count($studentInfo);$i++){
			$selected = ($studentId==$studentInfo[$i]['user_id'])?" selected":"";
			$x .= "<option value='".$studentInfo[$i]['user_id']."'".$selected.">";
			$x .= $studentInfo[$i]['user_name_'.$intranet_session_language];
			$x .= "</option>";
		}
		$x .= "</select>";
		$x .= "</span><a href='#' class='next'> </a>";
		return $x;
	}
	function getStudentInfoHTML($studentInfo,$action='view'){
		global $kis_lang,$intranet_session_language,$kis_config;
		$this->student_id = $studentInfo['user_id'];
		$guardians = $this->getStudentGuardians();
		$admitted_classes = $this->getStudentAdmittedClasses();
			$x = '<div class="ipf_board">';
                /* Basic Information */
				$x .= '<h1>'.$kis_lang['basicinformation'].'</h1>';
				$x .= '<div class="ipf_info_board">';
					$x .= '<div class="ipf_info_detail">';       
						$x .= '<table>';
							/* English Name */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['englishname'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("text","EnglishName",$studentInfo['user_name_en'],$action,' class="textboxtext"');
								$x .= '</td>';
							$x .= '</tr>';
							/* Chinese Name */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['chinesename'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("text","ChineseName",$studentInfo['user_name_b5'],$action,' class="textboxtext"');
								$x .= '</td>';
							$x .= '</tr>';
							/* Gender */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['gender'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= $kis_lang['gender_'.$studentInfo['gender']];
								$x .= '</td>';
							$x .= '</tr>';
							/* Date of Birth */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['dateofbirth'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("text","DateOfBirth",$studentInfo['birth_date'],$action,' size="15"');
								$x .= '</td>';
							$x .= '</tr>';	
							/* Place of Birth */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['placeofbirth'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("text","PlaceOfBirth",$studentInfo['birth_place'],$action,' size="15"');
								$x .= '</td>';
							$x .= '</tr>';		
							/* Nationality */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['nationality'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("text","Nationality",$studentInfo['nationality'],$action,' class="textboxtext"');
								$x .= '</td>';
							$x .= '</tr>';	
							/* Home Phone No. */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['homephoneno'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("text","HomeTel",$studentInfo['home_tel'],$action,' size="15"');
								$x .= '</td>';
							$x .= '</tr>';		
							/* Home Address */
							$x .= '<tr>';
								$x .= '<td class="form_field_name">'.$kis_lang['homeaddress'].'</td>';
								$x .= '<td class="form_field_content">';
								$x .= kis_iportfolio::getFormField("textarea","HomeAddress",$studentInfo['address'],$action,' rows="4" wrap="virtual" class="textboxtext"');
								$x .= '</td>';
							$x .= '</tr>';										
						$x .= '</table>';
					$x .= '</div>';
					$x .= '<div  class="ipf_info_class">';
					/* Admission */
						$x .= '<table>';
							$x .= '<tr>
										<td class="form_field_name">'.$kis_lang['schoolyear'].'</td>
										<td class="form_field_name">'.$kis_lang['classname'].'</td> 
									</tr>';
						for($i=0;$i<count($admitted_classes);$i++){			
							$x .= '	<tr>
										<td class="form_field_content">'.$admitted_classes[$i]['year_name_'.$intranet_session_language].'</td>
										<td class="form_field_content">'.$admitted_classes[$i]['class_name_'.$intranet_session_language].'</td>
									</tr>';
						}
						$x .= '</table>';
						$x .= '<p class="spacer"></p><br />';
						$x .= '<table>
								<tr>
									<td class="form_field_name">'.$kis_lang['admissiondate'].'</td>
									<td class="form_field_content">'.$studentInfo['admission_date'].'</td>
								</tr>
						</table>';
						$x .= '</div>';
						$x .= '<p class="spacer"></p>';
					$x .= '</div>';
					$x .= '<p class="spacer"></p>';
					$x .= '<p class="spacer"></p>';
					/* Guardian Information */
					$x .= '<h1>'.$kis_lang['guardianinformation'].'</h1>';
					$x .= '<div class="ipf_info_board_g">';
					for($i=0;$i<$kis_config['iportfolio']['guardian']['quota'];$i++){
						$_guardian = $guardians[$i];
						$x .= '<div class="ipf_info_g_detail">';
							$x .= '<h2>('.(($i==0)?$kis_lang['mainguardian']:$kis_lang['guardian'].($i+1)).')</h2>';
							$x .= '<table>';
								/* English Name */
								$x .= '<tr>';
									$x .= '<td class="form_field_name">'.$kis_lang['englishname'].'</td>';
									$x .= '<td class="form_field_content">';
										$x .= kis_iportfolio::getFormField("text","EnglishNameP".($i+1),$_guardian['name_en'],$action);
									$x .= '</td>';
								$x .= '</tr>';
								/* Chinese Name */
								$x .= '<tr>';
									$x .= '<td class="form_field_name">'.$kis_lang['chinesename'].'</td>';
									$x .= '<td class="form_field_content">';
										$x .= kis_iportfolio::getFormField("text","ChineseNameP".($i+1),$_guardian['name_b5'],$action);
									$x .= '</td>';
								$x .= '</tr>';
								/* Relationship */
								$x .= '<tr>';
									$x .= '<td class="form_field_name">'.$kis_lang['relationship'].'</td>';
									$x .= '<td class="form_field_content">';
										$x .= ($action=='view')?((!empty($_guardian['relation']))?$kis_lang['StudentAccount']['Relation'][$_guardian['relation']]:'--'):$this->getRelationshipSelection("RelationshipP".($i+1),"RelationshipP".($i+1),$_guardian['relation']);
									$x .= '</td>';
								$x .= '</tr>';
								$x .= '</tr>';
								/* Phone No. */
								$x .= '<tr>';
									$x .= '<td class="form_field_name">'.$kis_lang['phoneno'].'</td>';
									$x .= '<td class="form_field_content">';
										$x .= kis_iportfolio::getFormField("text","PhoneP".($i+1),$_guardian['tel'],$action,' size="15"');
									$x .= '</td>';
								$x .= '</tr>';	
								/* Emergency Contact No. */
								$x .= '<tr>';
									$x .= '<td class="form_field_name">'.$kis_lang['emergencycontactno'].'</td>';
									$x .= '<td class="form_field_content">';
										$x .= kis_iportfolio::getFormField("text","EmergencyPhoneP".($i+1),$_guardian['emergency_tel'],$action,' size="15"');
									$x .= '</td>';
								$x .= '</tr>';										
							$x .= '</table>';
						$x .= '</div>'; 
						$x .= '<input type="hidden" name="guardianId'.($i+1).'" id="guardianId'.($i+1).'" value="'.$_guardian['guardian_id'].'">'; 
                    }
					$x .= '<input type="hidden" name="studentId" id="studentId" value="'.$this->student_id.'">'; 					
					$x .= '<input type="hidden" name="showPage" id="showPage">'; 					
                    $x .= '<p class="spacer"></p>';                         
                $x .= '</div>';
            $x .= '</div>';
	
		
		return $x;
	}
	function getRelationshipSelection($ID,$Name='',$DefaultValue='',$Others=''){
		global $intranet_session_language,$kis_lang;
		$relationshipArr = $kis_lang['StudentAccount']['Relation'];
		$Name = (!empty($Name))?$Name:$ID;
		$x = '<select id="'.$ID.'" name="'.$Name.'" '.$Others.'>';
		$x .= '<option value="">--'.$kis_lang['please_select'].'--</option>';
		foreach($relationshipArr as $key => $value){
			$x .= '<option value="'.$key.'" '.(($DefaultValue==$key)?'selected':'').'>';
			$x .= $value;
			$x .= '</option>';
		}
		$x .= '</select>';
		return $x;
	}
	public static function getFormField($InputType,$InputName,$InputValue,$Action,$Others=''){	
		if($Action=='view'){
			$x = (!empty($InputValue))?stripslashes($InputValue):"--";
		}else{
			switch($InputType){
				case 'text':
					$x = '<input type="text" name="'.$InputName.'" id="'.$InputName.'" value="'.stripslashes(intranet_htmlspecialchars($InputValue)).'" '.$Others.'>';
					break;
				case 'textarea':
					$x = '<textarea name="'.$InputName.'" id="'.$InputName.'"'.$Others.'>'.stripslashes(intranet_htmlspecialchars($InputValue)).'</textarea>';
					break;	
			}
		}
		return $x; 
	}
	
	function updateStudentInfo($data){
		global $kis_lang,$eclass_db,$kis_config;
		$this->student_id = $data['studentId'];
		//Personal Information
		$sql = "UPDATE INTRANET_USER SET
		    EnglishName = '".$data['EnglishName']."',
		    ChineseName = '".$data['ChineseName']."',
		    DateOfBirth = '".$data['DateOfBirth']."',
		    Address = '".$data['HomeAddress']."',
		    HomeTelNo = '".$data['HomeTel']."',
			DateModified  = NOW(),
		    ModifyBy = ".$this->user_id."
		WHERE UserID = ".$this->student_id;
		$this->db_db_query($sql);
		$sql = "UPDATE INTRANET_USER_PERSONAL_SETTINGS SET
		    Nationality = '".$data['Nationality']."',
		    PlaceOfBirth = '".$data['PlaceOfBirth']."',
		    Nationality_DateModified = NOW(),
			PlaceOfBirth_DateModified  = NOW()
		WHERE UserID = ".$this->student_id;
		$this->db_db_query($sql);		
	//Guardian	
		$issetMain = 0;
		for($i=1;$i<=$kis_config['iportfolio']['guardian']['quota'];$i++){
			$_guardianId = $data['guardianId'.$i];
			if(!empty($_guardianId)){
				$sql = "
				UPDATE $eclass_db.GUARDIAN_STUDENT SET
					EnName = '".$data['EnglishNameP'.$i]."',
					ChName = '".$data['ChineseNameP'.$i]."',
					Relation = '".$data['RelationshipP'.$i]."',
					Phone = '".$data['PhoneP'.$i]."',
					EmPhone = '".$data['EmergencyPhoneP'.$i]."'
				WHERE
					RecordID = '".$_guardianId."'
				AND
					UserID = '".$this->student_id."'";
				$this->db_db_query($sql);	
			}else{
				if(!empty($data['EnglishNameP'.$i])&&!empty($data['RelationshipP'.$i])){
					$IsMain = ($issetMain==0)?1:0;
					$issetMain = 1;
					$sql = "INSERT INTO $eclass_db.GUARDIAN_STUDENT (
								UserID,ChName,EnName,
								Phone,EmPhone,Relation,
								IsMain,InputDate,ModifiedDate
							) VALUES (
								'".$this->student_id."','".$data['ChineseNameP'.$i]."','".$data['EnglishNameP'.$i]."',
								'".$data['PhoneP'.$i]."','".$data['EmergencyPhoneP'.$i]."','".$data['RelationshipP'.$i]."',
								'$IsMain', NOW(), NOW()
							) ";
					$this->db_db_query($sql);	
					$record_id = mysql_insert_id();
					$sql = "INSERT INTO $eclass_db.GUARDIAN_STUDENT_EXT_1 (
								UserID,RecordID, IsLiveTogether, IsEmergencyContact, Occupation,
								AreaCode, Road, Address,
								InputDate,ModifiedDate
							) VALUES (
								'".$this->student_id."','".$record_id."', '', '', '','','', '',NOW(), NOW()
							)";		
					$this->db_db_query($sql);	
				}
			}
		}

	}
	function checkStudentInClass($studentId,$classId){
		$sql = "SELECT
					COUNT(*)
				FROM YEAR_CLASS_USER u
			INNER JOIN YEAR_CLASS c ON
				u.YearClassID = c.YearClassID 
			WHERE 
				u.UserID = '".$studentId."'
			AND 
				c.YearClassID = '".$classId."' 
			AND 	
				c.AcademicYearID = '".$this->schoolyear."'";
		return current($this->returnVector($sql));
	
	}
	function editSchoolRecord($classId,$studentId,$recordId,$recordType){
		global $kis_lang,$eclass_db,$PATH_WRT_ROOT;
		list($action,$type) = explode('_',$recordType);
		$StudentList = current(kis_utility::getUsers(array('user_id'=>$studentId)));
		/* Prepare SchoolYear */
		$schoolYearId = $this->schoolyear;
		if($action=='edit'){
			$data['recordId'] = $recordId;
			$data['studentId'] = $studentId;
			list($total,$RetrieveList) = ($type=='activities')?$this->getActivityRecordList($data):$this->getAwardRecordList($data);
			if($total>0){
				$RetrieveList = current($RetrieveList);
				$schoolYearId = $RetrieveList['school_year_id'];
				$schoolYearTermId = $RetrieveList['school_year_term_id'];
			}
		}
		$select_academicYear = getSelectAcademicYear("school_year_id", "",1,"",$schoolYearId);
		/* Prepare Term */
		include_once($PATH_WRT_ROOT."includes/libclubsenrol.php");
		$libenroll = new libclubsenrol($libkis_iportfolio->schoolyear);
		$select_academicYearTerm = $libenroll->Get_Term_Selection('school_year_term_id', $schoolYearId, $schoolYearTermId, '', $NoFirst=1, $NoPastTerm=0, $withWholeYear=1);
		
		$x = '<div class="navigation_bar"><span>'.$kis_lang[$recordType].'</span></div>';
		$x .= '<p class="spacer"></p>';
        $x .= '<div class="table_board">';
			$x .= '<table class="form_table">';
			/* Chinese Name */
				$x .= '<tr class="mail_compose_table">';
					$x .= '<td class="field_title">'.$kis_lang['chinesename'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","chinesename",$StudentList['user_name_b5'],'view').'</td>';
				$x .= '</tr>';
			/* English Name */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['englishname'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","englishname",$StudentList['user_name_en'],'view').'</td>';
				$x .= '</tr>';				
			/* Class Name */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['classname'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","classname",$StudentList['user_class_name'],'view').'</td>';
				$x .= '</tr>';	
			/* Class Number */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['class_number'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","class_number",$StudentList['user_class_number'],'view').'</td>';
				$x .= '</tr>';	
			/* SchoolYear */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['schoolyear'].'</td>';
					$x .= '<td>'.$select_academicYear.'</td>';
				$x .= '</tr>';		
			/* Term */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['term'].'</td>';
					$x .= '<td><span id="span_term">'.$select_academicYearTerm.'</span></td>';
				$x .= '</tr>';	
		if($type=='activities'){
			/* Activity Name */
				$x .= '<tr>';
					$x .= '<td class="field_title"><span class="tabletextrequire">*</span>'.$kis_lang['activity_name'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","activity_name",$RetrieveList['activity_name'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';	
			/* Role */
				$x .= '<tr>';
					$x .= '<td class="field_title"><span class="tabletextrequire">*</span>'.$kis_lang['role'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","role",$RetrieveList['role'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';					
			/* Performance */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['performance'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("textarea","performance",$RetrieveList['performance'],'edit',' rows="4" wrap="virtual" class="textboxtext"').'</td>';
				$x .= '</tr>';
			/* Organization */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['organization'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","organization",$RetrieveList['organization'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';				
		}else{		
			/* Award Title */
				$x .= '<tr>';
					$x .= '<td class="field_title"><span class="tabletextrequire">*</span>'.$kis_lang['award_title'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","award_name",$RetrieveList['award_name'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';
			/* Date */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['award_date'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","award_date",$RetrieveList['award_date'],'edit').'</td>';
				$x .= '</tr>';	
			/* Organization */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['award_organization'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","organization",$RetrieveList['organization'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';
			/* SubjectArea */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['subject_area'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","subject_area",$RetrieveList['subject_area'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';				
			/* Remark */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['remarks'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("textarea","remarks",$RetrieveList['remarks'],'edit',' rows="4" wrap="virtual" class="textboxtext"').'</td>';
				$x .= '</tr>';	
		}				
				$x .= '<col class="field_title" />';
				$x .= '<col  class="field_c" />';
			$x .= '</table>';
			$x .= '<p class="spacer"></p><p class="spacer"></p><br />';
		$x .= '</div>';
        $x .= '<div class="edit_bottom">';
            $x .= '<input type="hidden" id="recordId" name="recordId" value="'.$recordId.'" />';
            $x .= '<input type="hidden" id="cur_student_id" name="cur_student_id" value="'.$studentId.'" />';
            $x .= '<input type="hidden" id="recordType" name="recordType" value="'.$recordType.'" />';
            $x .= '<input type="button" id="submitBtn" class="formbutton" value="'.$kis_lang['submit'].'" />';
            $x .= '<input type="button" id="cancelBtn" class="formsubbutton" value="'.$kis_lang['cancel'].'" />';
        $x .= '</div>';
		
		return $x;
	}

	function insertAwardRecord($data){
		global $eclass_db,$kis_lang;
		$SchoolYearArr = current(kis_utility::getAcademicYears(array('AcademicYearID'=>$data['school_year_id'])));
		$SchoolYearTermArr = current(kis_utility::getAcademicYearTerm(array('AcademicYearID'=>$data['school_year_id'],'YearTermID'=>$data['school_year_term_id'])));
		$StudentList = current(kis_utility::getUsers(array('user_id'=>$data['cur_student_id'])));
		$fieldname = "
			UserID,
			AcademicYearID,
			Year,
			ClassName,
			ClassNumber,
			AwardDate,
			AwardName,
			Organization,
			SubjectArea,
			Remark,
			RecordType,
			RecordStatus,
		";
		$fieldvalue = "
			'".$data['cur_student_id']."',
			'".$data['school_year_id']."',
			'".$SchoolYearArr['academic_year_name_en']."',
			'".addslashes($StudentList['user_class_name'])."',
			'".$StudentList['user_class_number']."',
			'".$data['award_date']."',
			'".addslashes($data['award_name'])."',
			'".addslashes($data['organization'])."',
			'".addslashes($data['subject_area'])."',
			'".addslashes($data['remarks'])."',
			'1',
			'2',
		";		
		if(!empty($data['school_year_term_id'])){
			$fieldname .= 'YearTermID,Semester,';
			$fieldvalue .= "
				'".$data['school_year_term_id']."',
				'".addslashes($SchoolYearTermArr['academic_year_term_name_en'])."',
			";
		}else{
			$fieldname .= 'Semester,';
			$fieldvalue .= "'',";		
		}
		$fieldname .= "
			InputDate,
			ModifiedDate
		";
		$fieldvalue .= "
			NOW(),
			NOW()
		";		
		$sql = "INSERT INTO ".$eclass_db.".AWARD_STUDENT (".$fieldname.") values (".$fieldvalue.")";
		$this->db_db_query($sql);
	}
	function updateAwardRecord($data){
		global $eclass_db,$kis_lang;
		$SchoolYearArr = current(kis_utility::getAcademicYears(array('AcademicYearID'=>$data['school_year_id'])));
		$SchoolYearTermArr = current(kis_utility::getAcademicYearTerm(array('AcademicYearID'=>$data['school_year_id'],'YearTermID'=>$data['school_year_term_id'])));
		$StudentList = current(kis_utility::getUsers(array('class_id'=>$data['cur_class_id'],'user_id'=>$data['cur_student_id'])));
		$sql = "
				UPDATE ".$eclass_db.".AWARD_STUDENT 
				SET
					AcademicYearID = '".$data['school_year_id']."',
					Year = '".$SchoolYearArr['academic_year_name_en']."',
					AwardDate = '".$data['award_date']."',
					AwardName = '".addslashes($data['award_name'])."',
					Organization = '".addslashes($data['organization'])."',
					SubjectArea = '".addslashes($data['subject_area'])."',
					Remark = '".addslashes($data['remarks'])."',
		";
		if(!empty($data['school_year_term_id'])){
			$sql .= "
					YearTermID = '".$data['school_year_term_id']."',
					Semester = '".addslashes($SchoolYearTermArr['academic_year_term_name_en'])."',
			";
		}else{
			$sql .= "
					YearTermID = NULL,
					Semester = '',
			";		
		}
		$sql .= "
					ModifiedDate = NOW()
				WHERE
					RecordID = '".$data['recordId']."'
					
		";
		$this->db_db_query($sql);
	}
	function removeAwardRecord($recordId){
		global $eclass_db;
		$sql = "
				DELETE FROM
					".$eclass_db.".AWARD_STUDENT 
				WHERE
					RecordID = '".$recordId."'
				
		";
		$this->db_db_query($sql);
	}
	function removeActivityRecord($recordId){
		global $eclass_db;
		$sql = "
				DELETE FROM
					".$eclass_db.".ACTIVITY_STUDENT 
				WHERE
					RecordID = '".$recordId."'
				
		";
		$this->db_db_query($sql);
	}	
	function insertActivityRecord($data){
		global $eclass_db,$kis_lang;
		$SchoolYearArr = current(kis_utility::getAcademicYears(array('AcademicYearID'=>$data['school_year_id'])));
		$SchoolYearTermArr = current(kis_utility::getAcademicYearTerm(array('AcademicYearID'=>$data['school_year_id'],'YearTermID'=>$data['school_year_term_id'])));
		$StudentList = current(kis_utility::getUsers(array('user_id'=>$data['cur_student_id'])));
		$fieldname = "
			UserID,
			AcademicYearID,
			Year,
			ClassName,
			ClassNumber,
			Role,
			ActivityName,
			Organization,
			Performance,
		";
		$fieldvalue = "
			'".$data['cur_student_id']."',
			'".$data['school_year_id']."',
			'".$SchoolYearArr['academic_year_name_en']."',
			'".addslashes($StudentList['user_class_name'])."',
			'".$StudentList['user_class_number']."',
			'".addslashes($data['role'])."',
			'".addslashes($data['activity_name'])."',
			'".addslashes($data['organization'])."',
			'".addslashes($data['performance'])."',
		";		
		if(!empty($data['school_year_term_id'])){
			$fieldname .= 'YearTermID,Semester,';
			$fieldvalue .= "
				'".$data['school_year_term_id']."',
				'".addslashes($SchoolYearTermArr['academic_year_term_name_en'])."',
			";
		}else{
			$fieldname .= 'Semester,';
			$fieldvalue .= "'',";		
		}
		$fieldname .= "
			InputDate,
			ModifiedDate
		";
		$fieldvalue .= "
			NOW(),
			NOW()
		";		
		$sql = "INSERT INTO ".$eclass_db.".ACTIVITY_STUDENT (".$fieldname.") values (".$fieldvalue.")";

		$this->db_db_query($sql);
	}	
	function updateActivityRecord($data){
		global $eclass_db,$kis_lang;
		$SchoolYearArr = current(kis_utility::getAcademicYears(array('AcademicYearID'=>$data['school_year_id'])));
		$SchoolYearTermArr = current(kis_utility::getAcademicYearTerm(array('AcademicYearID'=>$data['school_year_id'],'YearTermID'=>$data['school_year_term_id'])));
		$StudentList = current(kis_utility::getUsers(array('class_id'=>$data['cur_class_id'],'user_id'=>$data['cur_student_id'])));
		$sql = "
				UPDATE ".$eclass_db.".ACTIVITY_STUDENT 
				SET
					AcademicYearID = '".$data['school_year_id']."',
					Year = '".$SchoolYearArr['academic_year_name_en']."',
					Role = '".addslashes($data['role'])."',
					ActivityName = '".addslashes($data['activity_name'])."',
					Organization = '".addslashes($data['organization'])."',
					Performance = '".addslashes($data['performance'])."',
		";
		if(!empty($data['school_year_term_id'])){
			$sql .= "
					YearTermID = '".$data['school_year_term_id']."',
					Semester = '".addslashes($SchoolYearTermArr['academic_year_term_name_en'])."',
			";
		}else{
			$sql .= "
					YearTermID = NULL,
					Semester = '',
			";		
		}
		$sql .= "
					ModifiedDate = NOW()
				WHERE
					RecordID = '".$data['recordId']."'
					
		";
		$this->db_db_query($sql);
	}	
	function editStudentAssessment($classId,$studentId,$recordId,$recordType){
		global $kis_lang,$eclass_db,$PATH_WRT_ROOT;
		list($action,$type) = explode('_',$recordType);

		$StudentList = current(kis_utility::getUsers(array('class_id'=>$classId,'user_id'=>$studentId)));
		$x = '<div class="navigation_bar"><span>'.$kis_lang[$recordType].'</span></div>';
		$x .= '<p class="spacer"></p>';
        $x .= '<div class="table_board">';
			$x .= '<table class="form_table">';
						
			/* Class Name */
				$x .= '<tr>';
					$x .= '<td class="field_title">'.$kis_lang['classname'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","classname",$StudentList['user_class_name'],'view').'</td>';
				$x .= '</tr>';	
			/* Assessment Title */
				$x .= '<tr>';
					$x .= '<td class="field_title"><span class="tabletextrequire">*</span>'.$kis_lang['assessment_title'].'</td>';
					$x .= '<td class="field_title">'.$this->getAssessmentSelectionByStudentId($classId,$studentId).'</td>';
				$x .= '</tr>';	
			/* Release Date */
				$x .= '<tr>';
					$x .= '<td class="field_title"><span class="tabletextrequire">*</span>'.$kis_lang['release_date'].'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","release_date",$RetrieveList['release_date'],'edit').'</td>';
				$x .= '</tr>';					
			/* Assessment File */
				$x .= '<tr>';
					$x .= '<td class="field_title"><span class="tabletextrequire">*</span>'.$kis_lang['Assessment']['AssessmentFile'] .'</td>';
					$x .= '<td>'.kis_iportfolio::getFormField("text","role",$RetrieveList['role'],'edit',' class="textboxtext"').'</td>';
				$x .= '</tr>';					
			
						
				$x .= '<col class="field_title" />';
				$x .= '<col  class="field_c" />';
			$x .= '</table>';
			$x .= '<p class="spacer"></p><p class="spacer"></p><br />';
		$x .= '</div>';
        $x .= '<div class="edit_bottom">';
            $x .= '<input type="hidden" id="recordId" name="recordId" value="'.$recordId.'" />';
            $x .= '<input type="hidden" id="cur_student_id" name="cur_student_id" value="'.$studentId.'" />';
            $x .= '<input type="hidden" id="cur_class_id" name="cur_class_id" value="'.$classId.'" />';
            $x .= '<input type="hidden" id="recordType" name="recordType" value="'.$recordType.'" />';
            $x .= '<input type="button" id="submitBtn" class="formbutton" value="'.$kis_lang['submit'].'" />';
            $x .= '<input type="button" id="cancelBtn" class="formsubbutton" value="'.$kis_lang['cancel'].'" />';
        $x .= '</div>';
		
		return $x;
	}	
 
}
?>