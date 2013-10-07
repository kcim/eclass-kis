<?
/**
 * LPv2 Manage Page - ajax request controller
 *
 *
 * @author Mick Chiu
 * @since 2012-03-27
 * 
 */
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT."includes/global.php");
include_once($PATH_WRT_ROOT."lang/lang.$intranet_session_language.php");
include_once($PATH_WRT_ROOT."lang/iportfolio_lang.$intranet_session_language.php");
include_once($PATH_WRT_ROOT."includes/libdb.php");
include_once($PATH_WRT_ROOT."includes/libpf-lp2.php");
include_once($PATH_WRT_ROOT."includes/libuser.php");
include_once($PATH_WRT_ROOT."includes/libfilesystem.php");

intranet_opendb();

if ($portfolio) list($portfolio_id, $user_id)=libpf_lp2::decryptPortfolioKey($portfolio);
if($_SESSION["platform"]=="KIS"&&$ck_memberType=='S'&&isset($ck_intranet_user_id)){ 
	$liblp2 = new libpf_lp2($user_id, $portfolio_id, $ck_intranet_user_id, 'publish');
}else{
	$liblp2 = new libpf_lp2($user_id, $portfolio_id, $UserID, 'publish');
}
$portfolio_config 	= $liblp2->getPortfolioConfig();
$portfolio_info 	= $liblp2->getPortfolioInfo();
$version 		= $portfolio_info['version'];

$is_owner 		= $liblp2->user_permission==0;
$is_teacher 		= $liblp2->memberType=='T';
$is_competition 	= libpf_lp2::$is_competition;

$request_ts		= time();

if ($liblp2->memberType=='T'){
    
    switch($action){
     
        case 'getAllPortfolios':	    

	    $sortby_choices 	= $langpf_lp2['admin']['navi']['order_choice'];
	    $sortby 		= in_array($sortby,array_keys($sortby_choices))? $sortby: 'modified';
	    $order 		= in_array($order,array('asc','desc'))? $order : 'desc';
	    $keyword 		= trim($keyword);
	    $amount_choices	= array(10,20,50,100);
	    $amount 		= in_array($amount,$amount_choices)||$amount=='all'? $amount: 20;
	    
	    list($total, $lps)  = $liblp2->getAllPortfolios($keyword, $sortby, $order, $page*$amount, $amount);
	  
	    $data['student_lps'] = array();
	    
	    foreach ($lps as $lp){
	    
		$lp['key'] 		= libpf_lp2::encryptPortfolioKey( $lp['web_portfolio_id'], $UserID);
		$lp['modified_days'] 	= libpf_lp2::getDaysWord($lp['modified']);
		$lp['published_days'] 	= libpf_lp2::getDaysWord($lp['published']);
		$lp['share_url'] 	= libpf_lp2::getPortfolioUrl($lp['key']);
		$data['student_lps'][] 	= $lp;
		
	    }
		    
	    $max_page 	= $amount == 'all'? 1: ceil($total/$amount);
	    $hash 	= $page.'/'.$amount.'/'.$keyword.'/'.$sortby.'/'.$order;
	   	    
	    $from 	= $total?$page*$amount+1:0;
	    $to 	= $page*$amount+sizeof($data['student_lps']);
	
            include_once('templates/teacher_portfolio_list.php');
        break;
    
	case 'getAllPublishedPortfolios':
	   
	    $keyword 		= trim($keyword);
	    $amount_choices	= array(10,20,50,100);
	    $amount 		= in_array($amount,$amount_choices)||$amount=='all'? $amount: 20;
	    $group_id		= $keyword? '': $group_id;
		    
	    list($total, $lps)  = $liblp2->getAllPublishedPortfolios($keyword, $group_id, $page*$amount, $amount);
	       
	    $data['student_lps']	= array();
	    $data['portfolios']		= array();
	    $data['groups']		= $liblp2->getAllPortfolioGroups($is_competition);
	    
	    foreach ($lps as $lp){
	    
		$lp 			= array_merge($lp, $liblp2->getUserInfo($lp['user_intranet_id']));
		$lp['key'] 		= libpf_lp2::encryptPortfolioKey( $lp['web_portfolio_id'], $lp['user_intranet_id']);
		$lp['published_days'] 	= libpf_lp2::getDaysWord($lp['published']);
		$lp['share_url'] 	= libpf_lp2::getPortfolioUrl($lp['key']);
		$lp['unread'] 		= $lp['readflag']&&!$liblp2->isUserInReadflag($lp['readflag']);

		$data['student_lps'][] = $lp;
	
	    }

	    $max_page 	= $amount == 'all'? 1: ceil($total/$amount);
	    $hash 	= $page.'/'.$amount.'/'.$keyword.'/'.$group_id;
	   
	    $from 	= $total?$page*$amount+1:0;
	    $to 	= $page*$amount+sizeof($data['student_lps']);
	
            include_once('templates/judge_portfolio_list.php');
	    
	break;
	
	case 'getPortfolioUsers':
	    
	    $keyword 		= trim($keyword);
	    $amount_choices	= array(10,20,50,100);
	    $amount 		= in_array($amount,$amount_choices)||$amount=='all'? $amount: 20;
	    
	    list($total, $lps)  = $liblp2->getPortfolioUsers($keyword, $published, $page*$amount, $amount);
	    
	    $data['student_lps']=array();
	    foreach ($lps as $lp){
	
		$lp['user_intranet_id'] = $liblp2->EC_USER_ID_TO_IP_USER_ID($lp['user_id']);	
		$lp = array_merge($lp, $liblp2->getUserInfo($lp['user_intranet_id']));
		
		$lp['key'] 		= libpf_lp2::encryptPortfolioKey( $lp['web_portfolio_id'], $lp['user_intranet_id']);
		$lp['modified_days'] 	= libpf_lp2::getDaysWord($lp['modified']);
		$lp['published_days'] 	= libpf_lp2::getDaysWord($lp['published']);
		$lp['share_url'] 	= libpf_lp2::getPortfolioUrl($lp['key']);
		$lp['unread'] 		= $lp['readflag']&&!$liblp2->isUserInReadflag($lp['readflag']);
		
		$data['student_lps'][]	= $lp;
	    }
	    
	    $max_page 	= $amount == 'all'? 1: ceil($total/$amount);
	    	    
	    $from 	= $total?$page*$amount+1:0;
	    $to 	= $page*$amount+sizeof($data['student_lps']);
	    
	    $data['title'] 		= $portfolio_info['title'];
	   
            include_once('templates/teacher_portfolio_progress_list.php');
        break;
	
	case 'getSettings':
	    
	    include_once($eclass_filepath."/src/includes/php/lib-groups.php");
	    
	    $lo = new libgroups($liblp2->db);
	    
	    if ($portfolio_id){
		
		$data['group_out_list'] = $lo->getGroupsListOut($portfolio_id,"PORTw");
		$data['group_in_list'] 	= $lo->getGroupsListIn($portfolio_id,"PORTw");
		
	    }else{
		$data['group_out_list'] = $lo->getGroupsList();
	    }

	    $data['portfolio_info'] = $portfolio_info;
	    
	    include_once('templates/teacher_portfolio_settings.php');
	break;
    
	case 'setPortfolio':
	    
	    $data = array(
		
		'title' 		=> HTMLtoDB($title),
		'instruction' 		=> HTMLtoDB($instruction),
		'starttime' 		=> (trim($starttime)!="") ? "'".$starttime." $sh:$sm:00'" : "NULL",
		'endtime' 		=> (trim($endtime)!="") ? "'".$endtime." $eh.$em:59'" : "NULL",
		'user_intranet_id' 	=> $UserID,
		'sizeMax' 		=> $sizeMax*1024,
		'status'		=> $status?1:0
		
	    );

	    if ($portfolio_id){
		
		if ($is_clone){
		    $liblp2 = $liblp2->clonePortfolio($data);
		}else{
		    $liblp2->updatePortfolioInfo($data);
		}
		
	    }else{
		$liblp2 = libpf_lp2::createPortfolio($data);
	    }
	    
	    $liblp2->createPortfolioGroups($groups);		
	    
	break;
    
	case 'setPortfolioStatuses':
	    
	    $liblp2->setPortfolioStatuses($status);
	    
	break;
	
	case 'removePortfolios':
	   
	    $liblp2->removePortfolios();
	    	    
	break;

    }
}
if ($liblp2->memberType=='S'){
    
    switch($action){
	 
	    case 'getUserPortfolios':
		
		$keyword 		= trim($keyword);
		$amount_choices	 	= array(10,20,50,100);
		$amount 		= in_array($amount,$amount_choices)||$amount=='all'? $amount: 20;
		
		list($total, $lps) 	= $liblp2->getUserPortfolios($keyword, $page*$amount, $amount);
		
		$data['student_lps']	= array();
		
		foreach ($lps as $lp){
		
		    $lp['key'] 			= libpf_lp2::encryptPortfolioKey( $lp['web_portfolio_id'], $UserID);
		    $lp['modified_days'] 	= libpf_lp2::getDaysWord($lp['modified']);
		    $lp['published_days'] 	= libpf_lp2::getDaysWord($lp['published']);
		    $lp['share_url'] 		= libpf_lp2::getPortfolioUrl($lp['key']);
		    $data['student_lps'][]	= $lp;
		    
		}

		$max_page 	= $amount == 'all'? 1: ceil($total/$amount);
		$hash 		= $page.'/'.$hash.'/'.$keyword;
	      
		$from 		= $page*$amount+1;
		$to 		= $page*$amount+sizeof($data['student_lps']);
		
		include_once('templates/student_portfolio_list.php');
		
	    break;
	
	    case 'getFriendPortfolios':
		
		$amount = 3;
		
		list($total, $lps) = $liblp2->getFriendPortfolios($page*$amount, $amount);
		
		$data['friend_lps']=array();
		foreach ($lps as $lp){
		    
		    $friend_intranet_id 	= $liblp2->EC_USER_ID_TO_IP_USER_ID($lp['user_id']);
		    $lp 			= array_merge($lp, $liblp2->getUserInfo($friend_intranet_id));
		    $lp['key'] 			= libpf_lp2::encryptPortfolioKey($lp['web_portfolio_id'], $friend_intranet_id);
		    $lp['share_url'] 		= libpf_lp2::getPortfolioUrl($lp['key']);
		    $data['friend_lps'][]	= $lp;
		    
		}

		$max_page 	= ceil($total/$amount);
		
		$from 		= $total?$page*$amount+1:0;
		$to 		= $page*$amount+sizeof($data['friend_lps']);
		
		include_once('templates/student_portfolio_friend_list.php');
		
	    break;
	    
    }

}

/****Owner Action****/
if ($is_owner && $version>=1){//owner
    switch($action){

	case 'setTheme':
	    
	    $liblp2->setPortfolioMode('draft'); 
	    
	    $banner_editable 	= $liblp2->hasSuperPermission('banner');
	    
	    if (!$banner_uploaded  && $banner_editable){//user disabled banner
		
		$banner_url 	= "";
	    
	    }else if($_SESSION['iPortfolio_uploaded_banner_url'][$portfolio]){//user just uploaded a banner
		
		$banner_url 	= $fm->copy_fck_flash_image_upload($portfolio, $_SESSION['iPortfolio_uploaded_banner_url'][$portfolio], '../../', $cfg['fck_image']['iPortfolio']);
		
		unset($_SESSION['iPortfolio_uploaded_banner_url'][$portfolio]);
	    
	    }else{//nothing happened, use old banner
		
		$banner_url 	= $portfolio_config['banner_url'];
		$banner_status 	= $banner_editable?$banner_status:$portfolio_config['banner_status'];
		
	    }

	    $liblp2->updatePortfolioConfig(array(
		
		'template_selected'	=> $theme,
		'banner_status'		=> $banner_status,
		'custom_root'		=> $custom_root,
		'banner_url'		=> $banner_url
		
	    ));
	    
        break;
    
	case 'getExportDoc':

	    $fs = new libfilesystem();

	    $student = $liblp2->getUserInfo();
	     
	    $export_basedir 	= "/tmp/iPortfolioBurnCD";
	    $export_filename 	= "student_u".$liblp2->user_id."_wp".$portfolio_id.".zip";
	    $export_zipname 	= $student['name']."_".$portfolio_info['title'].".zip";
	    $export_filepath 	= $export_basedir."/student_u".$liblp2->user_id."_wp".$portfolio_id;
	    
	    if(!file_exists($export_basedir)) $fs->folder_new($export_basedir);
	    if(file_exists($export_basedir.'/'.$export_filename)) $fs->file_remove($export_basedir.'/'.$export_filename);

	    $liblp2->exportPortfolio($export_filepath, '');
	    $liblp2->exportPortfolioSharedFiles($export_filepath);
	   
	    $fs->file_zip(basename($export_filepath), $export_basedir.'/'.$export_filename, dirname($export_filepath));

	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="'.$export_zipname.'"');
	    header('Content-Transfer-Encoding: binary');
	    header('Content-Length: '. filesize($export_basedir.'/'.$export_filename));
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    

	    readfile($export_basedir.'/'.$export_filename);
    
	
	    
	break;
    
    }
}
if ($is_owner){
    switch($action){
	case 'setTheme':
	    
	    if (!in_array($mode, array('prototype','draft'))){
		$liblp2->setPortfolioMode('draft');
	    }
	    
	    $banner_editable = $liblp2->hasSuperPermission('banner');	   
	    if (!$banner_uploaded  && $banner_editable){//user disabled banner
		
		$banner_url = "";
	    
	    }else if($_SESSION['iPortfolio_uploaded_banner_url'][$portfolio]){//user just uploaded a banner
		
		$banner_url = $fm->copy_fck_flash_image_upload($portfolio, $_SESSION['iPortfolio_uploaded_banner_url'][$portfolio], '../../', $cfg['fck_image']['iPortfolio']);
		unset($_SESSION['iPortfolio_uploaded_banner_url'][$portfolio]);
	    
	    }else{//nothing happened, use old banner
		
		$banner_url = $portfolio_config['banner_url'];
		$banner_status = $banner_editable?$banner_status:$portfolio_config['banner_status'];
	    }

	    $liblp2->updatePortfolioConfig(array(
				    'template_selected'	=> $theme,
				    'banner_status'	=> $banner_status,
				    'custom_root'	=> $custom_root,
				    'banner_url'	=> $banner_url
				    ));
	    
        break;	
	case 'publishPortfolio':
	  
	    $liblp2->publishPortfolioDraft($force_status ,$allow_like);
	    echo libpf_lp2::getPortfolioUrl($portfolio);
        break;
	case 'getPublish':
	    
	    $liblp2->setPortfolioMode('draft');

	    $data['elements'] = $liblp2->getAllElements();
	    include("templates/publish.php");
	    
        break;
	
        case 'setShareFriends':
	    $liblp2->createFriends($friend_ids); 
	    
	break;
	case 'getShareSearchResult':
	    $students = $liblp2->getClassStudents($class, $keyword, $exclude);
	    foreach ($students as $student){
		echo "<option value='".$student['user_id']."'>".$student['name'].' ('.$student['class'].')</option>';
	    }
	break;
	
	case 'getThemeSettings':
	    
	    if (!in_array($mode, array('prototype','draft'))){
		$liblp2->setPortfolioMode('draft');
	    }
	    $portfolio_config 	= $liblp2->getPortfolioConfig();
	    $title 		= $portfolio_info['title'];
	    $themes 		= libpf_lp2::$themes;
	    $banner_editable 	= $liblp2->hasSuperPermission('banner');    
	    $current_theme 	= $portfolio_config['template_selected']? $portfolio_config['template_selected']: $themes[0];
	    $banner_path 	= explode('/',$portfolio_config['banner_url']);
	    $banner_path 	= strpos($portfolio_config['banner_url'],'http://')===0? $portfolio_config['banner_url']:array_pop($banner_path);
	    
	    $data=array(
		'banner_status' 	=> $portfolio_config['banner_status'],
		'banner' 		=> $portfolio_config['banner_url'],
		'banner_filename' 	=> $banner_path,
		'custom_root' 		=> $portfolio_config['custom_root']
	    );
	    
	    include ("templates/theme_settings.php");
        break;
    }
}
switch($action){
    
	case 'getShare':
	  
	    $student = $liblp2->getUserInfo();
	    $portfolio_url = $version>=1?libpf_lp2::getPortfolioUrl($portfolio):'';
	    $show_sharepeer = $is_owner && !$is_teacher && $ck_current_academic_year_id && !$is_competition &&!libpf_lp2::isFromKIS();
	    
	    if ($show_sharepeer){//no need to get friends list
		$data['friends'] = $liblp2->getUserFriends();
		$data['classes'] = $liblp2->getClasses(); 
	    }
	    
	    include("templates/share.php");
	    
	break;
}

intranet_closedb();

?>