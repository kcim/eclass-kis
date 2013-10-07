<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

if (!$ck_course_id){
    
    //start login to iportfolio 
    $kis_data['start'] = 1;
    $kis_data['login_iportfolio_url'] = "/home/portfolio/";
    kis_ui::loadTemplate('start', $kis_data, $format);
    die;
}

$libkis_iportfolio = $libkis->loadApp('iportfolio');
$lpf = new libpf_sbs();
$lo  = new libportfolio_group($lpf->course_db);
$lgs = new growth_scheme();
    
if ($kis_user['type']==kis::$user_types['teacher']){
    
    $liblp2 = new libpf_lp2($kis_user['id'], 0, $kis_user['id'], 'publish');
    switch ($q[0]){
		case 'schoolrecords':
			$q[1] = $q[1]? $q[1]: 'awards';
			

			// school_year_id & school_year_term_id 
			$school_year_id = $school_year_id? $school_year_id: $libkis_iportfolio->schoolyear;
			$school_year_term_id = $school_year_term_id? $school_year_term_id: 0;
			include_once($PATH_WRT_ROOT."includes/libclubsenrol.php");
			$libenroll = new libclubsenrol($libkis_iportfolio->schoolyear);

			$data['recordType'] = $q[1];
			if($q[2]=='student'&&!empty($studentId)){//Student List
				//Class
				$classInfo = current(kis_utility::getAcademicYearClasses(array('user_id'=>$studentId)));
				$classId = $classInfo['class_id'];
				$class_name = $classInfo['class_name_'.$intranet_session_language];
				// Parameter 
				$page   = $page?   $page: 1;
				$amount = $amount? $amount: 10;
				$order = $order? $order: '';
				$sortby = $sortby? $sortby: 'schoolyear';
				$StudentList = current(kis_utility::getUsers(array('user_id'=>$studentId)));
				$data['studentId'] = $studentId;
				$data['school_year_id'] = $school_year_id;
				$data['school_year_term_id'] = $school_year_term_id;
				$term_onchange = "$('form.filter_form').submit();";
				if($q[1] == 'activities'){
					list($total,$kis_data['activity_record']) = $libkis_iportfolio->getActivityRecordList($data,$sortby,$order,$amount,$page);
				}else{
					list($total,$kis_data['award_record']) = $libkis_iportfolio->getAwardRecordList($data,$sortby,$order,$amount,$page);
				}
				$NavArr[] = array('schoolrecords/'.$q[1].'/?classId='.$classId,$class_name);
				$NavArr[] = array('',$StudentList['user_name_'.$intranet_session_language]);
				$kis_data['StudentList'] = $libkis_iportfolio->getStudentSelectionByClassId($classId,$studentId);
				$kis_data['main_template'] = 'schoolrecords/teacher_'.$q[1].'_student_list';
			}else if(($q[2]=='new')||($q[2]=='edit')){ //Edit Student Record
				$data['recordId'] = $recordId;
				if($q[1] == 'activities'){
					list($total,$RetrieveList) = $libkis_iportfolio->getActivityRecordList($data);
				}else{
					list($total,$RetrieveList) = $libkis_iportfolio->getAwardRecordList($data);
				}
				
				if($q[2]=='edit' && $total>0){
					$RetrieveList = current($RetrieveList);
					$studentId = $RetrieveList['user_id'];
					$studentName = $RetrieveList['user_name'];
					$school_year_id = $RetrieveList['school_year_id'];
					$school_year_term_id = $RetrieveList['school_year_term_id'];
				}else{
					$StudentInfo = current(kis_utility::getUsers(array('user_id'=>$studentId)));
					$studentName = $StudentInfo['user_name_'.$intranet_session_language];
				}
				$kis_data['school_record_action'] = $q[2];
				$kis_data['RetrieveList'] = $RetrieveList;
				//Class
				$classInfo = current(kis_utility::getAcademicYearClasses(array('user_id'=>$studentId)));
				$classId = $classInfo['class_id'];
				$class_name = $classInfo['class_name_'.$intranet_session_language];				
				$NavArr[] = array('schoolrecords/'.$q[1].'/?classId='.$classId,$class_name);
				if(!empty($studentId)){
					$NavArr[] = array('schoolrecords/'.$q[1].'/student/?studentId='.$studentId,$studentName);
				}
				$NavArr[] = array('',$kis_lang[$q[2].'_'.$q[1]]);
				$kis_data['main_template'] = 'schoolrecords/teacher_'.$q[1].'_student_edit';
			}else{ //Class List
				//Class
				$classInfo = current(kis_utility::getAcademicYearClasses(array('class_id'=>$classId)));
				$classId = $classInfo['class_id'];
				$class_name = $classInfo['class_name_'.$intranet_session_language];
				$data['classId'] = $classId;
				$data['keyword'] = $keyword;
				$data['school_year_id'] = $school_year_id;
				$data['school_year_term_id'] = $school_year_term_id;
				$kis_data['record'] = $libkis_iportfolio->getSchoolRecordCountList($data,$sortby,$order,$amount,$page);
				$kis_data['classId'] = $classId;
				$kis_data['main_template'] = 'schoolrecords/teacher_schoolrecord_list';
			}
			
			$kis_data['classId'] = $classId;
			$kis_data['studentId'] = $studentId;
			$kis_data['recordType'] = $q[1];
			$kis_data['NavigationBar'] = $libkis_iportfolio->getNavigationBar($NavArr);
			$kis_data['select_academicYear'] = getSelectAcademicYear("school_year_id", "",1,"",$school_year_id);
			$kis_data['ClassList'] = $libkis_iportfolio->getClassSelection('classId','classId',0,$classId,' class="auto_submit"');
			$kis_data['select_academicYearTerm'] = $libenroll->Get_Term_Selection('school_year_term_id', $school_year_id, $school_year_term_id, $term_onchange, $NoFirst=1, $NoPastTerm=0, $withWholeYear=1);			
			$kis_data['keyword'] = intranet_htmlspecialchars(stripslashes($keyword));
			break;
			    	
		case 'assessmentreport':
			switch ($q[1]){
				case 'assessment_class':
					/* Assessment Data */
					$assessmentArr = current($libkis_iportfolio->getAssessmentList($assessmentId));
					$kis_data['title'] = $assessmentArr['title'].' - '.$assessmentArr['classname'];
					$NavArr[] = array('assessmentreport/teacher_list',$kis_lang['Assessment']['AssessmentList']);
					$NavArr[] = array('',$kis_data['title']);
					$kis_data['NavigationBar'] = $libkis_iportfolio->getNavigationBar($NavArr);
					$kis_data['assessmentArr'] = $assessmentArr;
					
					/* Parameter */
					$status = $status? $status: 'all';
					$kis_data['assessment_student_list'] = $libkis_iportfolio->getStudentAssessmentList($assessmentId, $assessmentArr['classId'], '', $status, $keyword);
					$kis_data['assessment_id'] = $assessmentId;
					$kis_data['StatusSelection'] = $libkis_iportfolio->getStatusSelection('UploadStatus','status','status',1,$status,' class="auto_submit"');
					$kis_data['ClassList'] = $libkis_iportfolio->getClassSelection('classId','classId',1,$assessmentArr['classId'],' disabled');
					$kis_data['main_template'] = 'assessmentreport/teacher_assessment_class';
					break;
				default:
				
					/* Parameter */
					$page   = $page?   $page: 1;
					$amount = $amount? $amount: 10;
					$order = $order? $order: '';
					$sortby = $sortby? $sortby: 'title';
					$kis_data['keyword'] = $keyword;
					$status = $status? $status: 'all';
					$classId = $classId? $classId: 'all';
					$assessment_cnt = $libkis_iportfolio->getTotalAssessmentCount($classId, $status, $kis_data['keyword']);
					$kis_data['assessment_list'] = $libkis_iportfolio->getAssessmentList($assessmentId='',$classId, $status, $kis_data['keyword'], $sortby, $order, $amount, $page);
					$kis_data['uploadCountArr'] = $libkis_iportfolio->getClassStudentAssessmentCount();
					$total = $assessment_cnt;
					$kis_data['ClassList'] = $libkis_iportfolio->getClassSelection('classId','classId',1,$classId,' class="auto_submit"');
					$kis_data['CreateNewClassList'] = $libkis_iportfolio->getClassSelection('classId','classId',1,'all');
					$kis_data['StatusSelection'] = $libkis_iportfolio->getStatusSelection('ReleaseStatus','status','status',1,$status,' class="auto_submit"');
					$kis_data['main_template'] = 'assessmentreport/teacher_assessment_list';
			}
			
		break;
		default: //studentaccount
			/* Class */
			$classId = $classId? $classId: '';
			if(empty($classId)||$classId=='all'){
				$className = $kis_lang['wholeschool'];
			}else{
				$classInfo = current(kis_utility::getAcademicYearClasses(array('class_id'=>$classId)));
				$className = $classInfo['class_name_'.$intranet_session_language];
			}
			/* Student */
			$studentId = $studentId? $studentId: '';
			$lib_student_kis = new kis($studentId);
			if(!$lib_student_kis->user){
				if($classId == 'all'){
					$firstStudentInfo = current(kis_utility::getUsers(array('academic_year_id'=>$libkis_iportfolio->schoolyear,'user_type'=>'S')));
				}else{
					$firstStudentInfo = current(kis_utility::getUsers(array('class_id'=>$classId,'user_type'=>'S')));
				}
				$studentId = $firstStudentInfo['user_id'];
				$lib_student_kis = new kis($studentId);
			}
			$studentInfo = $lib_student_kis->user;
			$studentName = $studentInfo['user_name_'.$intranet_session_language];
			switch ($q[1]){
				case 'studentinfo':
					$kis_data['ClassList'] = $libkis_iportfolio->getClassSelection('classId','classId',1,$classId);
					$kis_data['className'] = (empty($className)||!empty($keyword))?$kis_lang['wholeschool']:$className;
					$kis_data['StudentList'] = $libkis_iportfolio->getStudentSelectionByClassId($classId,$studentId);
					$NavArr[] = array('studentaccount',$kis_lang['StudentAccount']['ClassList']);
					$NavArr[] = array('studentaccount/class/?classId='.$classId,$className);
					$NavArr[] = array('',$studentName);
					switch($showPage){
						case 'schoolrecord':
							/* Parameter */
							$page   = $page?   $page: 1;
							$amount = $amount? $amount: 10;
							$order = $order? $order: '';
							$recordId = $recordId? $recordId: '';
							$sortby = $sortby? $sortby: 'schoolyear'; 
							list($kis_data['retrieve_action'],$kis_data['retrieve_type']) = explode('_',$recordType);
							$kis_data['retrieve_type'] = ($kis_data['retrieve_type']=='activities')? $kis_data['retrieve_type']: 'awards';
							if(($kis_data['retrieve_action']=='new')||($kis_data['retrieve_action']=='edit'&&!empty($recordId))){
								$kis_data['retrieve_data'] = $libkis_iportfolio->editSchoolRecord($classId,$studentId,$recordId,$recordType);
							}else{
								$kis_data['retrieve_action'] = 'view';
								$data['studentId'] = $studentId;
								$data['showAllTerms'] = true;
								list($total,$kis_data['retrieve_data']) = ($kis_data['retrieve_type']=='activities')?$libkis_iportfolio->getActivityRecordList($data,$sortby,$order,$amount,$page):$libkis_iportfolio->getAwardRecordList($data,$sortby,$order,$amount,$page);
							}
							break;
						case 'assessment':
							/* Parameter */
							$page   = $page?   $page: 1;
							$amount = $amount? $amount: 10;
							$order = $order? $order: '';
							$sortby = $sortby? $sortby: 'assessment_title';
							$recordId = $recordId? $recordId: '';
							list($kis_data['retrieve_action'],$kis_data['retrieve_type']) = explode('_',$recordType);
							$kis_data['file_path'] = $file_path;
							list($total,$kis_data['retrieve_data']) = $libkis_iportfolio->getStudentAssessmentByStudentId($recordId='',$classId,$studentId,$sortby,$order,$amount,$page,$showAll=true);						
							break;						
						default:
							$kis_data['retrieve_data'] = $libkis_iportfolio->getStudentInfoHTML($studentInfo);
							
					}
					$kis_data['NavigationBar'] = $libkis_iportfolio->getNavigationBar($NavArr);
					$kis_data['studentInfo'] = $studentInfo;
					$kis_data['studentName'] = $studentName;
					$kis_data['showPage'] = $showPage;
					$kis_data['main_template'] = 'studentaccount/teacher_student_info';
					break;
				case 'class':
					/* Parameter */
					$page   = $page?   $page: 1;
					$amount = $amount? $amount: 10;
					$view = $view? $view: 'cover';
					$keyword = TRIM($keyword);
					$NavArr[] = array('studentaccount',$kis_lang['StudentAccount']['ClassList']);
					$NavArr[] = array('',$className);
					$kis_data['NavigationBar'] = $libkis_iportfolio->getNavigationBar($NavArr);
					$kis_data['classId'] = $classId;
					$kis_data['keyword'] = intranet_htmlspecialchars(stripslashes($keyword));
					$kis_data['ClassList'] = $libkis_iportfolio->getClassSelection('classId','classId',1,$classId,' class="auto_submit"');
					list($total,$kis_data['ClassStudentList']) = $libkis_iportfolio->getTeacherClassStudentList($classId,$keyword,$amount,$page);
	
					$kis_data['main_template'] = ($view=='cover')?'studentaccount/teacher_class_iportfolio':'studentaccount/teacher_class_iportfolio_list';
					break;
				default:
					/* Parameter */
					$page   = $page?   $page: 1;
					$amount = $amount? $amount: 10;
					$order = $order? $order: '';
					$sortby = $sortby? $sortby: 'SortClassName';
					list($total,$kis_data['ClassList']) = $libkis_iportfolio->getTeacherClassList($sortby,$order,$amount,$page,$keyword);
	
					$kis_data['main_template'] = 'studentaccount/teacher_class_list';
			
			}
		break;
    }
	
}else if ($kis_user['type']==kis::$user_types['parent']){
    
    $liblp2 = new libpf_lp2($kis_user['current_child'], 0, $kis_user['current_child'], 'publish');
    $kis_data['student_info'] = $libkis->getStudentDetail();
    switch ($q[0]){
	    
	    case 'schoolrecords':
			/* Parameter */
			$page   = $page?   $page: 1;
			$amount = $amount? $amount: 10;
			$order = $order? $order: '';
			$sortby = $sortby? $sortby: 'schoolyear';
			$data['studentId'] = $kis_user['current_child'];
			switch ($q[1]){
				case 'activities':
				list($total,$kis_data['activity_record']) = $libkis_iportfolio->getActivityRecordList($data,$sortby,$order,$amount,$page);
				$kis_data['main_template'] = 'schoolrecords/activities';
				break;
				default:
				list($total,$kis_data['award_record']) = $libkis_iportfolio->getAwardRecordList($data,$sortby,$order,$amount,$page);
				$kis_data['main_template'] = 'schoolrecords/awards';
				break;
			}
			
	    break;
	
	    case 'assessmentreport':
			/* Parameter */
			$page   = $page?   $page: 1;
			$amount = $amount? $amount: 10;
			$order = $order? $order: '';
			$sortby = $sortby? $sortby: 'report_title';
			list($total,$kis_data['student_assessment_record']) = $libkis_iportfolio->getStudentAssessmentByStudentId($recordId='',$classId='',$kis_user['current_child'],$sortby,$order,$amount,$page);
			$kis_data['main_template'] = 'assessmentreport/parent_assessment_list';

	    break;
	
	    case 'sbs':
			switch ($q[1]){
				default:
					if(!empty($ck_course_id))
						$kis_data['schoolbasescheme_record'] = $libkis_iportfolio->getStudentSchoolBasedSchemeRecord();
					$kis_data['lpf'] = $lpf;
					$kis_data['main_template'] = 'sbs/index';
				break;
			}
	    break;
	    case 'learningportfolio':
		$kis_data['eclass40_httppath'] = $eclass40_httppath;
		if($ck_memberType=='P'){
			$ck_memberType = 'S';
			session_register("ck_memberType");
		}
		if($ck_intranet_user_id==$kis_user['id']){
			$ck_intranet_user_id = $kis_user['current_child'];
			session_register("ck_intranet_user_id");
		}		
		/* Parameter */
		$page   = $page?   $page: 1;
		$amount = $amount? $amount: 10;
		$order = $order? $order: '';
		$sortby = $sortby? $sortby: '';
		list($total,$kis_data['learningportfolio_record']) = $libkis_iportfolio->getStudentPortfolios($keyword,$page-1,$amount);
		$kis_data['main_template'] = 'learningportfolio';
	    break;
	
	    default:
		$kis_data['guardians'] = $libkis_iportfolio->getStudentGuardians();
		$kis_data['admitted_classes'] = $libkis_iportfolio->getStudentAdmittedClasses();
		$kis_data['main_template'] = 'information';
	    break;
	
	    
	}
}
$kis_data['PageBar'] = array($page,$amount,$total,$sortby,$order);
kis_ui::loadTemplate('main', $kis_data, $format);
?>