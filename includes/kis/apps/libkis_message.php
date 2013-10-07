<?
// Editing by 
class kis_message extends libdb implements kis_apps {
        
    private $user_id;
    public static $attachment_url = "/file/mail/attachments/";
    public static $folder_types = array(
	'inbox'=>2,
	'sent'=>0,
	'draft'=>1,
	'trash'=>-1,
    );
    public static $days_in_trash_option = array(-1, 2, 3, 4, 5, 6, 7, 14, 21, 30, 60);
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	if ($user_type == kis::$user_types['student']){
	    return array();
	}
	return array('message', 'btn_message', 'wood');
	
    }      
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	return array();
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	$libkis_message = new self($user_id, $user_type, $student_id, array());

	return $libkis_message->getInboxUnreadMailCount();
	
    }
    
    public static function service_sendMail($mail){
	
	$libdb = new libdb();	
	
	extract($mail);
	

	foreach (array('recipients', 'cc_recipients', 'bcc_recipients') as $type){
	    //unique?
	    foreach ((array)$mail[$type] as $recipient){
		
		$recipient_id = $recipient;
		
		
		$sql = "INSERT INTO INTRANET_CAMPUSMAIL(
			    CampusMailFromID, UserID, UserFolderID, SenderID,
			    RecipientID, InternalCC,  
			    Subject, Message, AttachmentSize,
			    IsImportant, IsNotification,
			    RecordType, DateInput, DateInFolder
			) VALUES (
			    $id, $recipient_id, 2, $user_id,
			    '$recipients_raw', '$cc_recipients_raw', 
			    '$subject', '$message', '$attachment_size',
			    '$is_important', '$is_notification',
			    '$type', now(), now()
			)";
		    
		$libdb->db_db_query($sql);
		$sent_mail_id = $libdb->db_insert_id();
		
		if ($mail['is_notification']){
		    
		    $sql = "INSERT INTO INTRANET_CAMPUSMAIL_REPLY(
				CampusMailID, UserID, IsRead, DateInput
			    ) VALUES (
				$id, $recipient_id, 0, now()
			    )";
    
		    $libdb->db_db_query($sql);
		    
		}
		
		
		foreach ((array)$attachments as $attachment){
		    
		    extract($attachment);
		    $sql = "INSERT INTO INTRANET_IMAIL_ATTACHMENT_PART(
			CampusMailID, AttachmentPath, FileName, FileSize
		    )VALUES(
			$sent_mail_id, '$path', '$name', $size
		    )";
		    
		    $libdb->db_db_query($sql);
		}
		
	    }
	}
	
    }
    
    public static function getMailQuotedContent($mail){
	
	global $kis_lang, $intranet_session_language;
	
	$content .= '<br/>'.$mail['user_name_'.$intranet_session_language].' '.$kis_lang['wrote'].'...<br/>';
	$content .= $kis_lang['date'].' : '.date('Y-m-d H:i:s', $mail['received']).'<br/>';
	$content .= '<blockquote style="padding-right:0;padding-left:5px;margin-left:5px;border-left:black 2px solid;margin-right:0">'.$mail['message'].'</blockquote>';
	
	return $content;
    }
    public function __construct($user_id, $user_type, $student_id, $params){

	global $intranet_db;

	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->loadUserSettings();
	$this->cleanTrash();
	
	$this->folder_id = isset(self::$folder_types[$params['folder']])? self::$folder_types[$params['folder']]: $params['folder'];
	
	if ($this->folder_id==-1){
	    $this->folder_cond = "m.Deleted = 1 AND";
	}else if ($params['folder']){
	    $this->folder_cond = "m.Deleted <> 1 AND m.UserFolderID = ".$this->folder_id." AND";
	}
		
    }
    private function loadUserSettings(){
	
	$sql = "SELECT
		    Signature signature,
		    DaysInTrash days_in_trash
		FROM INTRANET_IMAIL_PREFERENCE
		WHERE UserID = ".$this->user_id;
	
	$this->settings = current($this->returnArray($sql));
	
	if (!$this->settings){
	    
	    $sql = "INSERT INTO INTRANET_IMAIL_PREFERENCE
		    (UserID) values (".$this->user_id.")";
		    
	    $this->db_db_query($sql);
	    
	}
	
	return $this;
    }

    public function getCurrentFolderId(){
	
	return $this->folder_id;
    }
    public function getUserSettings(){
	
	return $this->settings;
    }
    public function setUserSettings($params){
	
	extract($params);

	$sql = "UPDATE INTRANET_IMAIL_PREFERENCE
		SET
		    Signature = '$signature',
		    DaysInTrash = $days_in_trash
		WHERE UserID = ".$this->user_id;

	$this->db_db_query($sql);
	return $this;
    }
    public function getAllMails($params, $amount, $page){
	
	extract($params); 
	
	$cond .= $sender?"(u.EnglishName like '%$sender%' OR u.ChineseName like '%$sender%') AND ":"";
	$cond .= $subject?"m.Subject like '%$subject%' AND ":"";
	$cond .= $recipient?"(".$this->getUserId($recipient,"recipient").") AND ":"";
	$cond .= $cc?"(".$this->getUserId($cc,"cc").") AND ":"";
	//$cond .= $message?"m.Message like '%$message%' AND ":"";
	$cond .= $received_from?"m.DateInput >= '$received_from' AND ":"";
	$cond .= $received_to?"m.DateInput <= '$received_to' AND ":"";
	$cond .= $attachment?"m.AttachmentSize > 0 AND ":"";
	$limit = $page? " LIMIT ".(($page-1)*$amount).", $amount ": "";
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS
		    m.CampusMailID id,
		    m.CampusMailFromID original_id,
		    m.SenderID user_id,
		    u.EnglishName user_name_en,
		    u.ChineseName user_name_b5,
		    u.PhotoLink user_photo,
		    u.RecordType user_type,
		    m.UserFolderID folder_id,
		    m.Subject subject,
		    m.Message content,  		
		    m.RecipientID recipients_raw,
		    m.InternalCC cc_recipients_raw,
		    m.InternalBCC bcc_recipients_raw,
		    m.AttachmentSize size,
		    m.IsImportant is_important,
		    m.IsNotification is_notification,
		    UNIX_TIMESTAMP(m.DateInFolder) received,
		    m.RecordStatus status		    
		FROM INTRANET_CAMPUSMAIL m
		INNER JOIN INTRANET_USER u ON m.SenderID = u.UserID
		WHERE
		    ".$this->folder_cond ."
		    m.UserID = ".$this->user_id." AND 
		    $cond
		    (
			
			m.Subject LIKE '%$keyword%' OR
			u.EnglishName LIKE '%$keyword%' OR
			u.ChineseName LIKE '%$keyword%'
		    )
		ORDER BY m.DateInFolder desc, m.CampusMailID desc $limit";
	
	$records = $this->returnArray($sql);
	$total   = current($this->returnVector('SELECT FOUND_ROWS();'));
	$mails = array();
	
	// If the user uses advanced search on content
	if ($message){
		$size = sizeof($records);
		for ($i = 0 ; $i< $size ;$i++){
			if (!(preg_match("/$message/i", strip_tags($records[$i]['content']))))
				unset($records[$i]);
		}
		$records = array_values($records);
		$total = sizeof($records);
	}
	
	
	foreach ($records as $record){
  
	    $record['is_original_copy'] = !$record['original_id'] || $record['original_id']==$record['id']? "1" : "0";
	    $record['reply_count'] = $record['is_notification']&&$record['is_original_copy']? $this->getMailNotificationReplyCount($record['id']): -1;
	    $record['recipients'] = $this->getMailRecipients($record['recipients_raw'], $record['id']);
	    $record['recipients_count'] = sizeof($record['recipients']);

	    $record['cc_recipients_count'] = $record['cc_recipients_raw']? substr_count($record['cc_recipients_raw'],',')+1: 0;
	    $record['bcc_recipients_count'] = $record['bcc_recipients_raw']? substr_count($record['bcc_recipients_raw'],',')+1: 0;
		
	    array_push($mails,$record);
	    
	}
	
	return array($total, $mails);
    }
   
    private function getMailAttachments($mail_id){
	
	$sql = "SELECT
		    a.PartID id,
		    a.AttachmentPath path,
		    a.FileName name,
		    a.FileSize size
		FROM INTRANET_IMAIL_ATTACHMENT_PART a
		INNER JOIN INTRANET_CAMPUSMAIL c ON
		    a.CampusMailID = c.CampusMailID AND
		    c.UserID = ".$this->user_id."
		WHERE a.CampusMailID = $mail_id";
		
	return $this->returnArray($sql);
	
    }
    public function getAttachment($file_id){
	
	$sql = "SELECT
		    a.CampusMailID mail_id,
		    a.AttachmentPath path,
		    a.FileName name,
		    CAST(a.FileSize AS UNSIGNED) size
		FROM INTRANET_IMAIL_ATTACHMENT_PART a
		INNER JOIN INTRANET_CAMPUSMAIL c ON
		    a.CampusMailID = c.CampusMailID AND
		    c.UserID = ".$this->user_id."
		WHERE
		    PartID = $file_id";
		
	$attachment = current($this->returnArray($sql));echo mysql_error();
	if ($attachment){
	    $attachment['_path'] = $attachment['path'];
	    $attachment['path'] = self::$attachment_url.$attachment['path'];
	    
	}
	
	return $attachment;
    }
    private function updateMailAttachmentSize($mail_id, $size_change){
	
	if ($this->getUserTotalQuota() < $this->getUserUsedQuota()+round($size_change)){
	    return 0;
	}
	
	$sql = "UPDATE INTRANET_CAMPUSMAIL
		SET AttachmentSize = AttachmentSize + ($size_change)
		WHERE CampusMailID = $mail_id AND UserID = ".$this->user_id;
				
	$this->db_db_query($sql);
	
	return $this->db_affected_rows();
    }
    public function setMailAttachment($mail_id, $name, $hash_name, $size){
	
	if ($this->updateMailAttachmentSize($mail_id, $size/1024)==0){
	   return array(0, ''); 
	}
	
	$path = $hash_name.'_'.$size;
	$sql = "INSERT INTO INTRANET_IMAIL_ATTACHMENT_PART(
		    CampusMailID, AttachmentPath, FileName, FileSize
		)VALUES(
		    $mail_id, '$path', '$name', $size
		)";
				
	$this->db_db_query($sql);
	
	return array($this->db_insert_id(), self::$attachment_url.$path);
    }
    public function removeMailAttachment($mail_id, $file_id){
	
	$attachment = $this->getAttachment($file_id);
		
	if ($this->updateMailAttachmentSize($mail_id, $attachment['size']/-1024)==0){
	   return false; 
	}
			
	$sql = "DELETE FROM INTRANET_IMAIL_ATTACHMENT_PART
		WHERE PartID = $file_id";
		
	$this->db_db_query($sql);
	
	$sql = "SELECT COUNT(*) FROM INTRANET_IMAIL_ATTACHMENT_PART
		WHERE AttachmentPath = '".$attachment['_path']."'";
		
	if (current($this->returnVector($sql))){
	    return false;
	}
	
	return $attachment['path'];
	
    }
    private function getMailRecipients($recipients_string, $mail_id){
	
	global $libjson;
	
	$recipients = $recipients_string? explode(',',str_replace('U','',$recipients_string)): array();
	
	$recipient_infos = array();
	foreach((array)$recipients as $recipient){
	    
			
	    $sql = "SELECT u.UserID user_id,
			    u.EnglishName user_name_en,
			    u.ChineseName user_name_b5,
			    u.ClassName user_class_name,
			    u.PhotoLink user_photo,
			    u.RecordType user_type,
			    r.IsRead is_read,
			    r.Message message,
			    UNIX_TIMESTAMP(r.DateModified) modified
			FROM INTRANET_USER u
			LEFT JOIN INTRANET_CAMPUSMAIL_REPLY r ON u.UserID = r.UserID AND r.CampusMailID = $mail_id
			WHERE u.UserID = ".$recipient;
		    
	    $recipient_infos[] = current($this->returnArray($sql));
		
	}
	
	return $recipient_infos;
    }
    
    public function getUserId($name,$type){
    	$string_id = "";
    	$i=0;
    	$sql = "select u.UserID FROM INTRANET_USER u where u.ChineseName like '%$name%' or u.EnglishName like '%$name%'";
    	
    	$array_id = $this->returnArray($sql);
    	
 		$size = sizeof($array_id);
    	if ($size != 0){
    		foreach ($array_id as $id){
    			if ($type == "recipient")
    				$string_id .= "m.RecipientID like '%".$id['UserID']."'"." OR m.RecipientID like '%".$id['UserID']."%'". " OR m.RecipientID like '".$id['UserID']."%'";
    			else if ($type == "cc")
    				$string_id .= "m.InternalCC like '%".$id['UserID']."'"." OR m.InternalCC like '%".$id['UserID']."%'". " OR m.InternalCC like '".$id['UserID']."%'";;
				$i++;
				if ($i<$size)
						$string_id .= " OR ";
    		}
    	}
    	//echo $string_id;
    	return $string_id;
    }
	
	
	
    public function getMail($mail_id, $params=array()){
		
	extract($params);
	$sql = "SELECT
		    m.CampusMailID id,
		    m.CampusMailFromID original_id, 
		    m.SenderID user_id,
		    u.EnglishName user_name_en,
		    u.ChineseName user_name_b5,
		    u.ClassName user_class_name,
		    u.PhotoLink user_photo,
		    u.RecordType user_type,
		    m.RecipientID as recipients_raw,
		    m.UserFolderID as folder_id,
		    m.Deleted deleted,
		    m.UserFolderID real_folder_id,
		    m.InternalCC cc_recipients_raw,
		    m.InternalBCC bcc_recipients_raw,
		    m.AttachmentSize attachment_size,
		    m.Subject subject,
		    m.Message message,
		    UNIX_TIMESTAMP(m.DateInFolder) received,
		    m.IsImportant is_important,
		    m.IsNotification is_notification,
		    m.RecordType type,
		    m.RecordStatus status,
		    r.IsRead reply_read,
		    r.Message reply_message,
		    UNIX_TIMESTAMP(r.DateModified) reply_modified
		FROM INTRANET_CAMPUSMAIL m
		INNER JOIN INTRANET_USER u ON m.SenderID = u.UserID
		LEFT JOIN INTRANET_CAMPUSMAIL_REPLY r ON
		    m.CampusMailFromID = r.CampusMailID AND
		    r.UserID = ".$this->user_id."
		WHERE
		    ".$this->folder_cond ."
		    m.CampusMailID = $mail_id AND
		    m.UserID = ".$this->user_id."
		ORDER BY m.DateInFolder desc 
		";
		
	$mail = current($this->returnArray($sql));
	
	if ($mail){
	    
	    $mail['attachments'] = $this->getMailAttachments($mail['id']);echo mysql_error();
	    $mail['recipients'] = $this->getMailRecipients($mail['recipients_raw'], $mail_id);
	    $mail['cc_recipients'] = $this->getMailRecipients($mail['cc_recipients_raw'], $mail_id);
	    $mail['bcc_recipients'] = $this->getMailRecipients($mail['bcc_recipients_raw'], $mail_id);
	    $mail['folder_id'] = $mail['deleted']? "-1": $mail['folder_id'];
	    $mail['is_original_copy'] = !$mail['original_id'] || $mail['original_id']==$mail['id']? "1" : "0";
	   	    
	    list($mail['previous'], $mail['next']) = $this->getPreviousAndNextMailId($mail, $keyword);
		
	}
	return $mail;
	
    }
    private function getPreviousAndNextMailId($mail, $keyword){
	
	$sql = "SELECT
			CampusMailID id
		FROM INTRANET_CAMPUSMAIL m
		INNER JOIN INTRANET_USER u
		    ON m.SenderID = u.UserID
		WHERE
		    ".$this->folder_cond ."
		    UNIX_TIMESTAMP(m.DateInFolder) %s '".$mail['received']."' AND CampusMailID %s ".$mail['id']." AND
		    m.UserID = ".$this->user_id." AND
		    (
			m.Message LIKE '%%$keyword%%' OR
			m.Subject LIKE '%%$keyword%%' OR
			u.EnglishName LIKE '%%$keyword%%' OR
			u.ChineseName LIKE '%%$keyword%%'
		    )
		ORDER BY m.DateInFolder %s, m.CampusMailID %s
		LIMIT 1";
	
	$previous = current($this->returnVector(sprintf($sql,'>=','>','asc','asc')));
	$next = current($this->returnVector(sprintf($sql,'<=','<','desc','desc')));
	
	return array($previous, $next);
	
    }

    public function markMailAsRead($mail_id){
	
	$sql = "UPDATE INTRANET_CAMPUSMAIL
		SET
		    RecordStatus = 1,
		    DateModified = now()
		WHERE CampusMailID = $mail_id AND
		    UserID = ".$this->user_id." AND
		    (RecordStatus < 1 OR RecordStatus IS NULL)";
		    
	$this->db_db_query($sql);
	
	$this->updateMailNotification($mail_id); echo mysql_error();
		
	return $this;
    }
    
    public function markMailAsUnRead($mail_id){
	
	$sql = "UPDATE INTRANET_CAMPUSMAIL
		SET RecordStatus = NULL
		WHERE CampusMailID = $mail_id AND
		    UserID = ".$this->user_id." AND
		    RecordStatus IS NOT NULL";
		    
	$this->db_db_query($sql);
	
	return $this;
    }
    
    public function getInboxUnreadMailCount(){
	
	$sql = "SELECT COUNT(*)
		FROM INTRANET_CAMPUSMAIL m
		WHERE
		    m.RecordStatus IS NULL AND
		    m.Deleted <> 1 AND
		    m.UserFolderID = 2 AND
		    m.UserID = ".$this->user_id;
		
	return current($this->returnVector($sql));
    }
    public function getDraftMailCount(){
	
	$sql = "SELECT COUNT(*)
		FROM INTRANET_CAMPUSMAIL m
		WHERE
		    m.Deleted <> 1 AND
		    m.UserFolderID = 1 AND
		    m.UserID = ".$this->user_id;
		
	return current($this->returnVector($sql));
    }
    public function getUserTotalQuota(){
	
	if (!$_SESSION['kis']['mail_quota']){
	   
	    $sql = "SELECT Quota
		    FROM INTRANET_CAMPUSMAIL_USERQUOTA
		    WHERE UserID = ".$this->user_id;
	    
	    $_SESSION['kis']['mail_quota'] = (int)current($this->returnVector($sql));
	}
	
	return $_SESSION['kis']['mail_quota'];
	
    }
    public function getUserUsedQuota(){
	
	$sql = "SELECT SUM(AttachmentSize)
		FROM INTRANET_CAMPUSMAIL
		WHERE UserID = ".$this->user_id ;
	
	return current($this->returnVector($sql));
    }
    public function cleanTrash(){
	
	if (!$_SESSION['kis']['mail_trash_cleaned']){
	    
	    $days = $this->settings['days_in_trash'];
	    
	    $sql = "DELETE FROM INTRANET_CAMPUSMAIL
		    WHERE
			Deleted=1 AND
			DATEDIFF(CURDATE(), DateInFolder) > $days AND
			UserID = ".$this->user_id;
	    
	    $this->db_db_query($sql);
	    $_SESSION['kis']['mail_trash_cleaned'] = true;
	}
	
    }
    
    public function removeMail($mail_id){

	$attachments = $this->getMailAttachments($mail_id);
	$attachment_paths = array();
	foreach($attachments as $attachment){
	    $attachment_paths[] = $this->removeMailAttachment($mail_id, $attachment['id']);
	}
	
	$sql = "DELETE FROM INTRANET_CAMPUSMAIL
		WHERE
		    CampusMailID = $mail_id AND
		    UserID = ".$this->user_id;
		    
	$this->db_db_query($sql);
	
	return $attachment_paths;
    }
    
    public function moveMailToFolder($mail_id, $folder_id){
	
	$set = $folder_id==self::$folder_types['trash']?'Deleted=1':"Deleted=0, UserFolderID = $folder_id";
	
	$sql = "UPDATE INTRANET_CAMPUSMAIL
		SET $set,
		    DateInFolder = now()
		WHERE
		    CampusMailID = $mail_id AND
		    UserID = ".$this->user_id;
	
	$this->db_db_query($sql);
	
	return $this;
    }
    
    public function getUserFolders(){
	
	global $kis_lang;
	
	$folders = array();
	foreach (self::$folder_types as $name=>$id){
	    $folder['id'] = $id;
	    $folder['name'] = $kis_lang[$name];
	    $folders[] = $folder;
	}
	//custom folders?
	return $folders;
	
    }
    
    public function setDraft($draft_id, $params=array()){
	
	global $libjson;
	
	extract($params);
	
	$recipients_raw = implode(',',array_unique((array)$recipients));
	$cc_recipients_raw = implode(',',array_unique((array)$cc_recipients));
	$bcc_recipients_raw = implode(',',array_unique((array)$bcc_recipients));
	
	if (!$draft_id){
	    
	    $sql = "INSERT INTO INTRANET_CAMPUSMAIL(
			UserID, UserFolderID, SenderID,
			RecipientID, InternalCC, InternalBCC,
			Subject, Message, AttachmentSize,
			IsImportant, IsNotification,
			RecordStatus, DateInput, DateInFolder
		    ) VALUES (
			".$this->user_id.", 1, ".$this->user_id.",
			'$recipients_raw', '$cc_recipients_raw', '$bcc_recipients_raw',
			'$subject', '$message', 0,
			'$is_important', '$is_notification',
			1, now(), now()
		    )";
	    	
	    $this->db_db_query($sql);
	    return $this->db_insert_id();
	    
	}else{
	    
	    $sql = "UPDATE INTRANET_CAMPUSMAIL SET
			RecipientID = '$recipients_raw',
			InternalCC = '$cc_recipients_raw',
			InternalBCC = '$bcc_recipients_raw',
			Subject = '$subject',
			Message = '$message',
			IsImportant = '$is_important',
			IsNotification = '$is_notification',
			DateInFolder = now(),
			DateModified = now()
		    WHERE
			UserID = ".$this->user_id." AND
			SenderID = ".$this->user_id." AND
			UserFolderID = 1
			AND CampusMailID = $draft_id";
	    
	    $this->db_db_query($sql);
	    return $draft_id;
	}
	
    }
    
    public function updateMailNotification($mail_id, $message=false){
	
	$m = $message? "r.Message = '$message',":"";
	    
	$sql = "UPDATE INTRANET_CAMPUSMAIL_REPLY r 
		INNER JOIN INTRANET_CAMPUSMAIL m ON m.CampusMailFromID = r.CampusMailID
		SET
		    $m
		    r.IsRead = 1,
		    r.DateModified = now()
		WHERE m.CampusMailID = $mail_id AND
		    r.UserID = ".$this->user_id;

	$this->db_db_query($sql);
	
	return $this;
	
    }
    public function getMailNotificationReplyCount($mail_id){
	
	$sql = "SELECT COUNT(*) FROM INTRANET_CAMPUSMAIL_REPLY
		WHERE Message IS NOT NULL AND CampusMailID = $mail_id";
		
	return current($this->returnVector($sql));
	
    }
    
    public function sendMail($mail_id){
	
	$mail = $this->getMail($mail_id);
	
	$mail['recipients'] = explode(',', $mail['recipients_raw']);
	$mail['cc_recipients'] = explode(',', $mail['bcc_recipient_raw']);
	$mail['bcc_recipient'] = explode(',', $mail['bcc_recipient_raw']);
	
	self::service_sendMail($mail);
	
	$this->moveMailToFolder($mail_id, 0);	
    }
    
    public function getSelectRecipientUsers($params, $exclude_list=false){
	
	extract($params);
	
	$_s = $_SESSION['SSV_USER_TARGET'];
	$academic_year_id = $_SESSION['CurrentSchoolYearID'];
	
	if (!$_s["All-Yes"]){
	    
	    $groups  = "SELECT DISTINCT(GroupID) FROM INTRANET_USERGROUP WHERE UserID = ".$this->user_id;
	    $classes = "SELECT DISTINCT(YearClassID) FROM YEAR_CLASS_USER WHERE UserID = ".$this->user_id;
	    $levels  = "SELECT DISTINCT(c.YearID) FROM YEAR_CLASS_USER cu INNER JOIN YEAR_CLASS c ON cu.YearClassID = c.YearClassID WHERE cu.UserID = ".$this->user_id;
	    
	    $groups = $groups? $groups: '0';
	    $classes = $classes? $classes: '0';
	    $levels = $levels? $levels: '0';
	    $types_all = "0";

	    if (!$_s["Staff-AllTeaching"] && $user_type==1){
		
		$t_classes = "SELECT DISTINCT(YearClassID) FROM YEAR_CLASS_TEACHER WHERE UserID = ".$this->user_id;
		$t_levels  = "SELECT DISTINCT(c.YearID) FROM YEAR_CLASS_TEACHER cu INNER JOIN YEAR_CLASS c ON cu.YearClassID = c.YearClassID WHERE cu.UserID = ".$this->user_id;
				
		$sc  .= $_s["Staff-MyForm"] ? " OR ((sc.YearID IN ($levels) OR sc.YearID IN ($t_levels)) AND ug1.GroupID = 1) ": "";
		$scu .= $_s["Staff-MyClass"]? " OR ((scu.YearClassID IN ($classes) OR scu.YearClassID IN ($t_classes))AND ug1.GroupID = 1) ": "";
		$sug .= $_s["Staff-MyGroup"]? " OR (ug2.GroupID IN ($groups) AND ug1.GroupID = 1) ": "";
		
		$t = " OR scu.YearClassID IN ($t_classes)";
		
	    }else{
		$types_all .= ",1";
	    }
	    if (!$_s["Staff-AllNonTeaching"] && $user_type==2){
		
		$sug .= $_s["NonTeaching-MyGroup"]? " OR (ug2.GroupID IN ($groups) AND ug1.GroupID = 2) ": "";
		
	    }else{
		$types_all .= ",2";
	    }
	    if (!$_s["Student-All"] && $user_type==3){
		
		$sc  .= $_s["Student-MyForm"] ? " OR (sc.YearID IN ($levels) AND ug1.GroupID = 3) ": "";
		$scu .= $_s["Student-MyClass"]? " OR (scu.YearClassID IN ($classes) AND ug1.GroupID = 3) ": "";
		$sug .= $_s["Student-MyGroup"]? " OR (ug2.GroupID IN ($groups) AND ug1.GroupID = 3) ": "";
		
	    }else{
		$types_all .= ",3";
	    }
	    if (!$_s["Parent-All"] && $user_type==4){
		
		$sc  .= $_s["Parent-MyForm"] ? " OR (sc.YearID IN ($levels) AND ug1.GroupID = 4) ": "";
		$scu .= $_s["Parent-MyClass"]? " OR (scu.YearClassID IN ($classes) AND ug1.GroupID = 4) ": "";
		$sug .= $_s["Parent-MyGroup"]? " OR (ug2.GroupID IN ($groups) AND ug1.GroupID = 4) ": "";
		
		
	    }else{
		$types_all .= ",4";
	    }
	    
	    $cond .= " AND (ug1.GroupID IN ($types_all) $sc $scu $sug)";
	    
	}
	
	if ($user_type==4){
	    
	    $p_table = "INNER JOIN INTRANET_PARENTRELATION p ON p.ParentID = u.UserID";
	    $p_users = ",p.StudentID";
	    
	}
	if ($sug || $user_group){
	    
	    $g_table = " INNER JOIN INTRANET_USERGROUP ug2 ON ug2.UserID IN (u.UserID $p_users) ";
	    $g_table .= $user_group? " AND ug2.GroupID = $user_group ":"";
	}
	if ($sc){
	    $s_table = "INNER JOIN YEAR_CLASS sc ON sc.YearClassID = scu.YearClassID AND sc.AcademicYearID = $academic_year_id";
	}
	
	
	$user_type = $user_type? "AND ug1.GroupID = $user_type ":"";
	$exclude_list = $exclude_list? " AND u.UserID NOT IN ($exclude_list) ":"";

	$sql = "SELECT DISTINCT(u.UserID) user_id,
		    u.EnglishName user_name_en,
		    u.ChineseName user_name_b5,
		    u.ClassName user_class_name,
		    u.PhotoLink user_photo,
		    u.RecordType user_type
		FROM INTRANET_USER u
		INNER JOIN INTRANET_USERGROUP ug1 ON
		    u.UserID = ug1.UserID $user_type $exclude_list
		    AND (u.EnglishName LIKE '%$keyword%' OR u.ChineseName LIKE '%$keyword%' OR u.ClassName LIKE '%$keyword%')
		    AND (u.RecordStatus > 0 OR u.RecordStatus IS NULL)
		$p_table
		$g_table
		INNER JOIN YEAR_CLASS_USER scu ON scu.UserID IN (u.UserID $p_users) $t
		$s_table
		WHERE 1 $cond
		ORDER BY u.EnglishName";
		    
	return $this->returnArray($sql);
    }
    
}
?>