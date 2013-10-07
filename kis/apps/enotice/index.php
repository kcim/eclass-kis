<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

if ($kis_user['type']==kis::$user_types['teacher']){
    
    switch ($q[0]){
	
	case 'settings':
	    $libkis_enotice = $libkis->loadApp('enotice');
	    $kis_data['main_template'] = 'settings';
	break;
    
	default:
	    
	    if ($q[1]=='edit'){
		
		$libkis_enotice = $libkis->loadApp('enotice', array('notice_id'=>$notice_id));
				
		$kis_data['notice'] = $libkis_enotice->getNotice();
		
		if ($action=='copy' && $libkis_enotice->hasNoticeReadRights()){
		    
		    $kis_data['notice']['recipients']=array();
		    $kis_data['notice']['total_signed_students']=0;
		    $kis_data['notice']['id']='';
		    $kis_data['notice']['status']=='';
		    
		    
		}else if (!$libkis_enotice->hasNoticeEditRights()){
		    
		    $show_notice_list = true;
		    
		}
		
		if (!$show_notice_list){
		    
		    list(,$kis_data['templates']) = $libkis_enotice->getAllNotices(array('status'=>3));
		       
		    $kis_data['is_copy'] = $action=='copy'? 1: 0;
		    $kis_data['classes'] = kis_utility::getAcademicYearClasses();
		    $kis_data['classlevels'] = kis_utility::getAcademicYearClassLevels();
		    $kis_data['groups'] = kis_utility::getAcademicYearGroups(array('hide_basic_groups'=>true,'excludes'=>$kis_data['notice']['recipients']['groups']));
		    $kis_data['default_return_days'] = $libkis_enotice->getPermission('defaultNumDays');
		    $kis_data['default_return_days'] = $kis_data['default_return_days']? $kis_data['default_return_days']: 7;
		    $kis_data['max_options'] = $libkis_enotice->getPermission('MaxReplySlipOption');
		    //list($kis_data['total'], $kis_data['latest_notices']) = $libkis_enotice->getAllNotices(array(), 'issue_date', 'desc', 5, 1);
		
		    $kis_data['main_template'] = 'notice_form';
		    
		}
		
	    }else if ($q[1]){

		$libkis_enotice = $libkis->loadApp('enotice', array('notice_id'=>$q[1]));
		$kis_data['notice'] = $libkis_enotice->getNotice();
		$kis_data['has_read_rights'] = $libkis_enotice->hasNoticeReadRights();
		$kis_data['has_edit_rights'] = $libkis_enotice->hasNoticeEditRights();
		
		if ($kis_data['notice']){
		    
		    if ($q[2]=='signresult' && $kis_data['has_read_rights'] && $kis_data['notice']['status']==1){
			
			if ($q[3] || kis_enotice::$notice_types[$kis_data['notice']['type']]=='applicablestudents'){
			    
			    $kis_data['class'] = current(kis_utility::getAcademicYearClasses(array('class_id'=>$q[3])));
			    $kis_data['class_results'] = $libkis_enotice->getNoticeClassReplies($q[3]);
			    
			    $kis_data['main_template'] = 'notice_result_class';
			    
			}else{
			    
			    $kis_data['classes'] = $libkis_enotice->getNoticeReplyClassStats();
			    $kis_data['main_template'] = 'notice_result_stat';
			}
			
		    }else if ($q[2]=='editreply' && $kis_data['has_edit_rights']){
			
			$kis_data['student'] = $libkis->getStudentDetail();
			$kis_data['reply']     = $libkis_enotice->getNoticeReply();
			$kis_data['replyable'] = $libkis_enotice->hasNoticeReplyRights();
			$kis_data['main_template'] = 'notice_detail_parent';
			
		    }else{
			
			$kis_data['main_template'] = 'notice_detail_teacher';
		    }
		    
		}else{
		    $show_notice_list = true;
		}
		    
	    }else{
		
		$libkis_enotice = $libkis->loadApp('enotice');
		$show_notice_list = true;
	    }
	    

	    if ($show_notice_list){
		
		$kis_data['page']   = $page?   $page: 1;
		$kis_data['amount'] = $amount? $amount: 10;
		$kis_data['has_normal_rights'] = $libkis_enotice->hasNormalRights();
		$kis_data['has_full_rights'] = $libkis_enotice->hasFullRights();
		$kis_data['can_create_notice'] = $libkis_enotice->hasNoticeCreateRights();
		
		list($kis_data['total'], $kis_data['notices']) = $libkis_enotice->getAllNotices(array(
		    'keyword'=>$search, 'status'=>$status), $sortby, $order, $kis_data['amount'], $kis_data['page']
		);

		$kis_data['main_template'] = 'notices_teacher';
	    }
	    
	break;
	
    }
    
}else if ($kis_user['type']==kis::$user_types['parent']){
    
    $libkis_enotice = $libkis->loadApp('enotice', array('notice_id'=>$q[0]));
    
    $kis_data['notice']    = $libkis_enotice->getNotice();
    $kis_data['reply']     = $libkis_enotice->getNoticeReply();    
    
    if ($kis_data['reply']){ //reply found, notice exists
	
	if ($kis_data['reply']['status'] == 0) {//mark as read
	    $libkis_enotice->setNoticeReply(array('status'=>1));
	}
	
	$kis_data['student'] = $libkis->getStudentDetail();
	$kis_data['replyable'] = $libkis_enotice->hasNoticeReplyRights();
	$kis_data['hide_button_after_submit'] = $libkis_enotice->getPermission('NotAllowReSign');
	
	$kis_data['main_template'] = 'notice_detail_parent';
	
    }else{ //notice not exists, show list
	
	$kis_data['page']   = $page?   $page: 1;
	$kis_data['amount'] = $amount? $amount: 10;

	list($kis_data['total'], $kis_data['notices']) = $libkis_enotice->getAllNotices(array(
	    'status'=>1, 'keyword'=>$search, 'signed'=>$signed, 'past'=>$past? $past: 1), 'issue_date', 'desc', $kis_data['amount'], $kis_data['page']
	);
	
	$kis_data['main_template'] = 'notices_parent';
    }
    
}

kis_ui::loadTemplate('main', $kis_data, $format);
?>