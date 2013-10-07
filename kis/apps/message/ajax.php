<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$libkis_message = $libkis->loadApp('message');

switch ($action){
    
    case 'removemails':
	
	foreach ($mail_ids as $mail_id){ 
	    $attachment_paths = $libkis_message->removeMail($mail_id);
	    foreach($attachment_paths as $attachment_path){
		unlink($file_path.$attachment_path);
	    }
	}
	
    break;
    
    case 'movemails':
	
	foreach ($mail_ids as $mail_id){ 
		
	    $libkis_message->moveMailToFolder($mail_id, $folder_id);
	}
	
	$folder_name = array_search($folder_id, kis_message::$folder_types);
	$kis_data['folder_name'] = $folder_name===false? $folder_id: $folder_name;
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'markmails':
	
	foreach ($mail_ids as $mail_id){
	    
	    if ($markas == 1){
		$libkis_message->markMailAsRead($mail_id);
	    }else if ($markas == -1){
		$libkis_message->markMailAsUnRead($mail_id);
	    }
    
	}
	$kis_data['draft_count'] = $libkis_message->getDraftMailCount();
	$kis_data['inbox_count'] = $libkis_message->getInboxUnreadMailCount();
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'submitreply':
	
	$libkis_message->updateMailNotification($mail_id, $message);
		
    break;

    case 'sendmail':
	
	$libkis_message->sendMail($mail_id);
	
    break;

    case 'setsettings':
	
	$libkis_message->setUserSettings(array(
	    'days_in_trash'=>$days_in_trash, 
	    'signature'=>$signature
	));
	
    break;

    case 'savedraft':
	
	$kis_data['mail_id'] = $libkis_message->setDraft($mail_id, array(
	   'recipients'=>$recipients,
	   'cc_recipients'=>$cc_recipients,
	   'bcc_recipients'=>$bcc_recipients,
	   'subject'=>htmlspecialchars($subject),
	   'message'=>$message,
	   'is_important'=>$is_important,
	   'is_notification'=>$is_notification,
	));
	
	$kis_data['draft_count'] = $libkis_message->getDraftMailCount();
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'attachfile':
		
	$file = $_FILES['file'];

	list($file_id, $file_url) = $libkis_message->setMailAttachment($mail_id, $file['name'], sha1_file($file['tmp_name']), $file['size']);
	
	if ($file_id){
	    
	    $file_dir = dirname($file_path.$file_url);

	    if (!is_dir($file_dir)){
		mkdir($file_dir, 0777, true);
	    }
	    move_uploaded_file($file['tmp_name'], $file_path.$file_url);
	    $kis_data['file_id'] = $file_id;
	    $kis_data['file_name'] = $file['name'];
	    
 
	}else{
	    $kis_data['error'] = 1;
	}
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'removefile':
		
	$file_url = $libkis_message->removeMailAttachment($mail_id, $file_id);
	
	if ($file_url){
	    unlink($file_path.$file_url);
	    $kis_data['success'] = '1';
	    

	}else{
	    $kis_data['error'] = 'file not removed';
	}
	
	
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'getfile':
	
	$attachment = $libkis_message->getAttachment($file_id);
	kis_utility::downloadFile($file_path.$attachment['path'], $attachment['name'], $attachment['size']);
	
    break;

    case 'searchusers':
	
	$user = $libkis_message->getSelectRecipientUsers(array('user_type'=>$user_type,'user_group'=>$user_group, 'keyword'=>trim($keyword)), $exclude_list);
	
	if (sizeof($user)>500){
	    $kis_data['ui'] = $kis_lang['toomanyresults'].'!';
	    
	}else if (!$user){
	    $kis_data['ui'] = $kis_lang['norecord'].'!';
	    
	}else{
	    ob_start();
	
	    kis_ui::loadTemplate('mail_users',array('users'=>$user, 'form_name'=>$target));
	    
	    $kis_data['ui'] = ob_get_clean();
	    
	    ob_end_clean;
	}
	 
	$kis_data['count'] = sizeof($user);
	echo $libjson->encode($kis_data);
	
    break;
}

?>
