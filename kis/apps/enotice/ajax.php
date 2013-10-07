<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');
$libkis_enotice = $libkis->loadApp('enotice', array('notice_id'=>$notice_id));

if ($kis_user['type']==kis::$user_types['teacher']){
    
    switch ($action){
	
	case 'checknoticenumber':
	    
	    $available = $libkis_enotice->checkNoticeNumber($notice_number);
	   
	    $res['notice_number'] = $notice_number;
	    $res['available'] = $available;
	    
	    echo $libjson->encode($res);
	    
	break;
	case 'gettemplate':
	case 'getnotice':
	    
	    $notice = $libkis_enotice->getNotice();
	    
	    if ($libkis_enotice->hasNoticeReadRights()){
		$notice['question_ui'] = array();
		
		foreach ($notice['questions'] as $question){
		    ob_start();
		    kis_ui::loadTemplate('questions/'.$question['type'], array('question'=>$question,  'answers'=> array(), 'attr'=>'disabled'));
		    $notice['questions_ui'][] = ob_get_clean();
		    ob_end_clean();
    
		}
		
	    }else{
		
		$kis_data['error'] = $kis_lang['permissiondenied'];
	    }
	    
	    echo $libjson->encode($notice);
	    
	break;
    
	case 'addfile':
	    
	    $notice = $libkis_enotice->getNotice();
	    
	    if ($libkis_enotice->hasNoticeEditRights()){
		$folder = 'tmp/'.$request_ts;
		
		$file_url = kis_enotice::$attachment_url.$folder;
		$file_name = kis_utility::getSaveFileName($_FILES['file']['name']);
		
		$path = $file_path.$file_url;
		
		if (!file_exists($path)) mkdir($path, 0755, true);
		move_uploaded_file($_FILES['file']['tmp_name'], $path.'/'.$file_name);
		
		$kis_data['folder'] = $folder;
		$kis_data['file_url'] = $file_url.'/'.$file_name;
		$kis_data['file_name'] = $file_name;
	    
	    }else{
		
		$kis_data['error'] = $kis_lang['permissiondenied'];
	    }
	    
	    echo $libjson->encode($kis_data);
	    
	    
	break;
	case 'getfile':
	    
	    $notice = $libkis_enotice->getNotice();
	    $notice_folder = $notice? $notice['attachment_folder']: $folder;
	    
	    $attachment_path = $file_path.kis_enotice::$attachment_url.$notice_folder.'/'.$file_name;
	    
	    if (file_exists($attachment_path)){
		kis_utility::downloadFile($attachment_path, $file_name);
	    }
	    
	break;
	case 'removefiles':
	    
	    foreach ($files as $file_url){
		unlink($file_path.$file_url);
		@rmdir(dirname($file_path.$file_url));
	    }
	    
	    
	break;
	case 'settemplate':
	case 'setnotice':
	    
	    $notice = $libkis_enotice->getNotice();
	    
	    if ($libkis_enotice->hasNoticeEditRights()){
		
		$questions = array();
		$choices_counter = 0;
		
		foreach ((array)$question_titles as $i=>$question_title){
    
		    $question = array(
			'title'=>htmlspecialchars($question_title),
			'type'=>$question_types[$i],
		    );

		    if ($question_choice_count[$i]){
			$question['options']=array();
			
			for ($choices_counter, $to=$choices_counter+$question_choice_count[$i]; $choices_counter<$to; $choices_counter++){
			    $question['options'][] = htmlspecialchars($question_choices[$choices_counter]);
			    
			}
			
		    }
		    
		    $questions[] = $question;
		    
		}
    
		$params['title'] = htmlspecialchars($title);
		$params['description'] = $description;
		$params['issue_date'] = $issue_date;
		$params['due_date'] = $due_date;
		$params['number'] = $number;
		$params['status'] = $status;
		$params['type'] = $type;
		if (!$notice['total_signed_students']){
		    $params['questions'] = $questions;
		    $params['answer_all'] = $answer_all;
		}
		$params['display_number'] = $display_number;
		$params['email_parents'] = $email_parents;
		$params['email_students'] = $email_students;
		
		$params['recipients']['classlevels'] = (array)$classlevels;
		$params['recipients']['classes'] = (array)$classes;
		$params['recipients']['groups'] = (array)$groups;
		$params['recipients']['users'] = (array)$users;
		
		$libkis_enotice->setNotice($params);
    
		$notice = $libkis_enotice->getNotice();
	    
		$attachment_dir = $file_path.kis_enotice::$attachment_url.$notice['attachment_folder'].'/';
		
		if (!file_exists($attachment_dir)){
		    mkdir($attachment_dir, 0755, true);
		}
		
		foreach ((array)$removed_files as $file_url){
		    unlink($file_path.$file_url);
		    @rmdir(dirname($file_path.$file_url));
		}
		
		foreach ((array)$new_files as $file_url){
		    copy($file_path.$file_url, $attachment_dir.basename($file_url));
		}
		
		$kis_data['notice_id'] = $notice['id'];
		
	    }else{
		
		$kis_data['error'] = $kis_lang['permissiondenied'];
		
	    }
	    
	    echo $libjson->encode($kis_data);
	    
	break;
	case 'removetemplate':
	    
	    if ($libkis_enotice->hasNoticeEditRights()){
		$libkis_enotice->removeNotice($template_id);
	    }
	    
	    list(,$kis_data['templates']) = $libkis_enotice->getAllNotices(array('status'=>3));
	    
	    echo $libjson->encode($kis_data);
	    
	break;
	case 'removenotice':
	    
	    $notice = $libkis_enotice->getNotice();
	    
	    if ($libkis_enotice->hasNoticeEditRights()){
		
		foreach ($notice['attachments'] as $attachment){
		    unlink($file_path.$attachment['url']);
		}
		rmdir($file_path.kis_enotice::$attachment_url.$notice['attachment_folder']);
		$libkis_enotice->removeNotice();
		
	    }
 
	break;

    }
    
}
switch ($action){
    
    case 'submitreply':
	    
	if (!$libkis_enotice->hasNoticeReplyRights()){
	    die('permission denied');
	}
       
	$reply = $libkis_enotice->setNoticeReply(array('answers'=>$answers, 'status'=>2, 'signer_id'=>$kis_user['id']));
	
	$kis_data['modified']  = date('Y-m-d');
	$kis_data['signed_by']  = $kis_user['name'];
	   
	echo $libjson->encode($kis_data);
	 
    break;
	
    case 'getprint':
	    
	    $kis_data['child'] = $libkis->getStudentDetail();
	    $kis_data['notice'] = $libkis_enotice->getNotice();
	    $kis_data['reply'] = $libkis_enotice->getNoticeReply();
	    kis_ui::loadTemplate('notice_print', $kis_data);
	    
    break;
    
	    
}

?>