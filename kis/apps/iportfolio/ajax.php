<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$libkis_iportfolio = $libkis->loadApp('iportfolio');

$lpf = new libpf_sbs();
$lo  = new libportfolio_group($lpf->course_db);
$lgs = new growth_scheme();

if ($kis_user['type']==kis::$user_types['teacher']){
    
    $liblp2 = new libpf_lp2($kis_user['id'], 0, $kis_user['id'], 'publish');
    switch ($action){
		case 'getAssessmentList':
			$libkis_iportfolio->getAssessmentList($assessmentId,$classId,$status,$keyword,$sortby,$order,$amount,$page);
			break;
		case 'getAssessmentById':
			$AssessmentArr = current($libkis_iportfolio->getAssessmentList($assessmentId));
			$kis_data['id'] = $AssessmentArr['id'];
			$kis_data['classId'] = $AssessmentArr['classId'];
			$kis_data['title'] = $AssessmentArr['title'];  
			$kis_data['release_date'] = $AssessmentArr['release_date'];  
			echo $libjson->encode($kis_data); 
			break;			
		case 'saveAssessment':
			$libkis_iportfolio->saveAssessment($title,$release_date,$classId);
			break;
		case 'copyAssessment':
			$AssessmentArr = current($libkis_iportfolio->getAssessmentList($assessmentId));
			$title = $AssessmentArr['title']."(".$kis_lang['copy'].")";  
			$release_date = $AssessmentArr['release_date'];  
			$classId = $AssessmentArr['classId'];
			$libkis_iportfolio->saveAssessment($title,$release_date,$classId);
			break;
		case 'updateAssessment':
			$libkis_iportfolio->updateAssessment($assessmentId,$title,$release_date);
			break;	
		case 'removeAssessment':
			$libkis_iportfolio->removeAssessment($assessmentId);
			break;	
		case 'addStudentAssessment':
			$folder = 'assessment/'.$assessmentId.'/'.$studentId;
			$file_url = kis_iportfolio::$attachment_url.$folder;
			$file_name = kis_utility::getSaveFileName($_FILES['file']['name']);
			$path = $file_path.$file_url;
			$libkis_iportfolio->saveStudentAssessment($assessmentId,$classId,$studentId,$file_name);
			if (!file_exists($path)) mkdir($path, 0755, true);
			move_uploaded_file($_FILES['file']['tmp_name'], $path.'/'.$file_name);
			
			$AssessmentArr = current($libkis_iportfolio->getStudentAssessmentList($assessmentId,$classId,array($studentId)));
			$kis_data['folder'] = $folder;
			$kis_data['file_url'] = $file_url.'/'.$file_name;
			$kis_data['file_name'] = $file_name;  
			$kis_data['modified_date'] = $AssessmentArr['assessment']['modified_date'];  
			$kis_data['modified_user'] = $AssessmentArr['assessment']['modified_user'];  
			
			echo $libjson->encode($kis_data); 
			break;
		case 'removeStudentAssessment':
				$libkis_iportfolio->removeStudentAssessment($assessmentId,$classId,$studentId);
			break;
		case 'getStudentAssessmentFile':
			$AssessmentArr = current($libkis_iportfolio->getStudentAssessmentList($assessmentId,'',array($studentId)));
			$folder = 'assessment/'.$assessmentId.'/'.$studentId;
			$file_name = $AssessmentArr['assessment']['title'];
			$attachment_path = $file_path.kis_iportfolio::$attachment_url.$folder.'/'.$file_name;
			if (file_exists($attachment_path)){
				kis_utility::downloadFile($attachment_path, $file_name);
			} 
			break;
		case 'getStudentInfo':
			$lib_student_kis = new kis($studentId);
			$studentInfo = $lib_student_kis->user;
			echo $libkis_iportfolio->getStudentInfoHTML($studentInfo,$infoType);
			break;
		case 'updateStudentInfo':
			$libkis_iportfolio->updateStudentInfo($_POST);
			break;		
		case 'getTermSelection':
			include_once($PATH_WRT_ROOT."includes/libclubsenrol.php");
			$libenroll = new libclubsenrol($libkis_iportfolio->schoolyear);
			$term_onchange = ($isOnChange)?"$('form.filter_form').submit();":"";
			echo $libenroll->Get_Term_Selection('school_year_term_id', $AcademicYearID, '', $term_onchange, $NoFirst=1, $NoPastTerm=0, $withWholeYear=1);
			break;
		case 'saveSchoolRecord':
				list($action,$type) = explode('_',$_POST['recordType']);
				if($action=='new'||empty($_POST['recordId'])){
					if($type=='awards')
						$libkis_iportfolio->insertAwardRecord($_POST);
					else if($type=='activities')
						$libkis_iportfolio->insertActivityRecord($_POST);			
				}else{
					if($type=='awards')
						$libkis_iportfolio->updateAwardRecord($_POST);
					else if($type=='activities')
						$libkis_iportfolio->updateActivityRecord($_POST);		
				}
			break;		
		case 'removeSchoolRecord':
			if($retrieve_type=='awards')
				$libkis_iportfolio->removeAwardRecord($recordId);
			else if($retrieve_type=='activities')
				$libkis_iportfolio->removeActivityRecord($recordId);	
			break;		
		case 'copySchoolRecord':
			$para['recordId'] = $recordId; 
			if($retrieve_type=='awards'){
				list($total,$data) = $libkis_iportfolio->getAwardRecordList($para);
				if($total>0){
					$data = current($data);
					$data['award_name'] .= "(".$kis_lang['copy'].")"; 
					$data['cur_student_id'] = $data['user_id'];
					$libkis_iportfolio->insertAwardRecord($data);
				}
			}else if($retrieve_type=='activities'){
				list($total,$data) = $libkis_iportfolio->getActivityRecordList($para);
				if($total>0){
					$data = current($data);
					$data['activity_name'] .= "(".$kis_lang['copy'].")";
					$data['cur_student_id'] = $data['user_id'];
					$libkis_iportfolio->insertActivityRecord($data);
				}
			}	
			break;	
		case 'searchusers':
			$classId = (!empty($classId)&&$classId!='all')?$classId:'';
			$excludes = explode(',',$exclude_list);
			$user = kis_utility::getUsers(array('class_id'=>$classId,'keyword'=>trim($keyword),'excludes'=>$excludes));
	
			if (sizeof($user)>500){
				$kis_data['ui'] = $kis_lang['toomanyresults'].'!';
				
			}else if (!$user){
				$kis_data['ui'] = $kis_lang['norecord'].'!';
				
			}else{
				ob_start();
			
				kis_ui::loadTemplate('schoolrecords/select_users',array('users'=>$user, 'form_name'=>'target_user'));
				
				$kis_data['ui'] = ob_get_clean();
				
				ob_end_clean;
			}
			 
			$kis_data['count'] = sizeof($user);
			echo $libjson->encode($kis_data);
			break;
		case 'saveAwardRecord':
			$data['school_year_id'] = $school_year_id;
			$data['school_year_term_id'] = $school_year_term_id;
			$data['award_name'] = $award_name;
			$data['award_date'] = $award_date;
			$data['organization'] = $organization;
			$data['subject_area'] = $subject_area;
			$data['remarks'] = $remarks;
			if($school_record_action=='new'){ 
				for($i=0;$i<count($target_user);$i++){
					$data['cur_student_id'] = $target_user[$i];
					$libkis_iportfolio->insertAwardRecord($data);
				}
			}else{
				$data['recordId'] = $recordId;
				$libkis_iportfolio->updateAwardRecord($data);
			}
			break;
		case 'saveActivityRecord':
			$data['school_year_id'] = $school_year_id;
			$data['school_year_term_id'] = $school_year_term_id;
			$data['activity_name'] = $activity_name;
			$data['role'] = $_POST['role'];
			$data['organization'] = $organization;
			$data['performance'] = $performance;
			if($school_record_action=='new'){ 
				for($i=0;$i<count($target_user);$i++){
					$data['cur_student_id'] = $target_user[$i];
					$libkis_iportfolio->insertActivityRecord($data);
				}
			}else{
				$data['recordId'] = $recordId;
				$libkis_iportfolio->updateActivityRecord($data);
			}
			break;			
	default:
	break;
	
    }
	
}else if ($kis_user['type']==kis::$user_types['parent']){
    
    $liblp2 = new libpf_lp2($kis_user['current_child'], 0, $kis_user['current_child'], 'publish');
    switch ($action){
		case 'editSBSContent':
			$content = $libkis_iportfolio->getSBSContent($parentId,$assignmentId);
			echo $libjson->encode($content); 
			break;
		case 'saveSBSContent':
			$libkis_iportfolio->saveSBSContent($p_id,$a_id,$h_id,$ans_str);
			break;
		case 'getStudentAssessmentFile':
			$studentId = $kis_user['current_child'];
			$AssessmentArr = current($libkis_iportfolio->getStudentAssessmentList($assessmentId,'',array($studentId)));
			if(!$AssessmentArr['Status']){
				$libkis_iportfolio->updateStudentAssessment($assessmentId,$studentId,1);
			}
			$folder = 'assessment/'.$assessmentId.'/'.$studentId;
			$file_name = $AssessmentArr['assessment']['title'];
			$attachment_path = $file_path.kis_iportfolio::$attachment_url.$folder.'/'.$file_name;
			if (file_exists($attachment_path)){
				kis_utility::downloadFile($attachment_path, $file_name);
			}
			break;
	default:
	
	    
	break;
    
	
    }
}

?>