<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

switch ($kis_data['tab']=$q[0]?$q[0]:'inbox'){
    
    case 'compose':
	
	$libkis_message = $libkis->loadApp('message');
	$settings = $libkis_message->getUserSettings();
	
	if ($id){
	    
	    $source = $libkis_message->getMail($id);
	    
	    if ($action && $source['folder_id'] != kis_message::$folder_types['draft']){
				
		//$kis_data['draft']['id'] = $source['id'];
		$kis_data['draft']['attachments'] = $source['attachments'];
		$kis_data['draft']['is_important'] = $source['is_important'];
		$kis_data['draft']['is_notification'] = $source['is_notification'];
		$kis_data['draft']['message'] = '<br/>'.$settings['signature'].'<br/>'.kis_message::getMailQuotedContent($source);
		
		if ($action == 'forward'){
		    
		    $kis_data['draft']['subject'] = 'Fw: '.$source['subject'];
		    
		}else{
		    
		    $kis_data['draft']['subject'] = 'Re: '.$source['subject'];
		    $kis_data['draft']['cc_recipients'] = $action == 'replyall'? $source['cc_recipients']: array();
		    $kis_data['draft']['recipients'] = $source['folder_id']!=kis_message::$folder_types['sent']? array(
			array(
			    'user_id'=>$source['user_id'],
			    'user_name_en'=>$source['user_name_en'],
			    'user_name_b5'=>$source['user_name_b5'],
			    'user_class_name'=>$source['user_class_name'],
			    'user_photo'=>$source['user_photo'],
			    'user_type'=>$source['user_type']
			)
		    ): $source['recipients'];
		    
		}

	    }else if ($source['folder_id'] == kis_message::$folder_types['draft']){
		
		$kis_data['draft'] = $source; 
	
	    }
	    
	}else{
	    
	    $kis_data['draft']['message'] = '<br/>'.$settings['signature'];
	    if ($recipients){
		
		$kis_data['draft']['recipients'] = array();
		foreach ((array)$recipients as $recipient){
		     $kis_data['draft']['recipients'][] = current(kis_utility::getUsers(array('user_id'=>$recipient)));
		}
	    }
	    
	}
	
	$kis_data['groups'] = kis_utility::getAcademicYearGroups(array('hide_basic_groups'=>true));
	
	$kis_data['main_template'] = 'compose';
	
    break;
    case 'settings':
	$libkis_message = $libkis->loadApp('message');
	$kis_data['settings'] = $libkis_message->getUserSettings();
	
	$kis_data['main_template'] = 'settings';
    break;

    default:

	$libkis_message = $libkis->loadApp('message', array('folder'=>$kis_data['tab']));
	$kis_data['user_folders'] = $libkis_message->getUserFolders();
	$kis_data['current_folder_id'] = $libkis_message->getCurrentFolderId();
	
	if($is_advanced_search){
	    $kis_data['query_string'] = '?no_nav=1';
	}else if ($search){
	    $kis_data['query_string'] = '?search='.$search;
	}
	
	if ($q[1]){
	    
	    $kis_data['mail'] = $libkis_message->getMail($q[1], array('keyword'=>$search));
	}
	
	if ($kis_data['mail']){
	    
	    $libkis_message->markMailAsRead($q[1]);
	    $kis_data['main_template'] = 'mail_detail';
	    
	}else{
	    
	    $amount = $amount? $amount: 20;
	    $page = $page? $page: 1;
	    
		// php_flag magic_quotes_gpc On in .htaccess file to handle backslash problem
	    list($kis_data['total'], $kis_data['mails']) = $libkis_message->getAllMails(array(
		'keyword'=>$search,
		'recipient'=>$recipient,
		'sender'=>$sender,
		'cc'=>$cc,
		'attachment'=>$attachment,
		'subject'=>$subject,
		'message'=>$message,
		'received_from'=>$received_from,
		'received_to'=>$received_to,
	    ), $amount, $page);
	    
	    $kis_data['main_template'] = 'mail_list';
	}
	
    break;
    
}

$kis_data['current_ts'] = time();
$kis_data['inbox_mail_count'] = $libkis_message->getInboxUnreadMailCount();
$kis_data['draft_mail_count'] = $libkis_message->getDraftMailCount();
$kis_data['used_quota'] = $libkis_message->getUserUsedQuota()/1024;
$kis_data['total_quota'] = $libkis_message->getUserTotalQuota()/1024;
$kis_data['remain_quota'] = $kis_data['total_quota'] - $kis_data['used_quota'];
$kis_data['used_percent'] = $kis_data['total_quota']>$kis_data['used_quota']? round($kis_data['used_quota']/$kis_data['total_quota']*100, 2): 100;

kis_ui::loadTemplate('main', $kis_data, $format);
?>