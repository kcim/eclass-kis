<?
class kis_ui extends libdb{
    
    private static $request_params, $page_form_loaded;

    public static function loadModuleTab($tabs, $current_tab, $app_url, $default_tab_index = 0){
    
	global $intranet_root, $kis_lang;
	
	$current_tab = $current_tab? $current_tab: $tabs[$default_tab_index];

	include("$intranet_root/templates/kis/layout/moduletab.php");
	
    }
    public static function loadLeftMenu($tabs, $current_tab, $app_url, $top_image = '', $default_tab_index = 0){
    
	global $intranet_root, $kis_lang;
	
	$current_tab = $current_tab? $current_tab: $tabs[$default_tab_index];

	include("$intranet_root/templates/kis/layout/leftmenu.php");
	
    }
    
    public static function loadPageBar($page, $amount, $total){
	
	global $intranet_root, $kis_lang;
	
	$amount_options = array(10, 20, 50, 100);
	
	$amount      = $amount? $amount: 20;
	$total_pages = $total? ceil($total/$amount): 1;
	$page	     = $page? $page: 1;
	$page	     = $page<=$total_pages? $page: $total_pages;
	$from        = $total? ($page-1)*$amount + 1: 0;
	$to          = $page*$amount > $total? $total: $page*$amount;
	
	$page_form_loaded = self::$page_form_loaded;
	
	include("$intranet_root/templates/kis/layout/pagebar.php");
	self::$page_form_loaded = true;
	   
    }
    public static function loadTemplate($template_name, $kis_data=array(), $format=''){
	
	global $q, $kis_lang, $kis_user, $kis_config, $intranet_session_language, $request_ts, $libjson;
	
	if ($format=='json'){
	    
	    header('Content-Type: application/json; charset=utf-8');
	    echo $libjson->encode($kis_data);
	    
	}else{
	    
	    $lang = $intranet_session_language;

	    if (!self::$request_params){
		self::$request_params = array_map('htmlspecialchars', array_map('stripslashes', $_REQUEST));
		self::$request_params['q'] = $q;
	    }
	    extract(self::$request_params);
	    extract((array)$kis_data);
		    
	    include("templates/$template_name.php");
	}
	
    }
    public static function loadSortButton($field, $title, $sortby, $order, $default_order='asc'){
	
	global $kis_lang, $intranet_root;
	
	if ($sortby == $field){
	    
	    $torder = $order == 'asc'?'desc':'asc';
	    $corder = 'sort_'.$order;
	    
	}else{
	    
	    $torder = $default_order;
	    $corder = '';
	    
	}
	
	include("$intranet_root/templates/kis/layout/sortbutton.php");
	
    }
    public static function loadNoRecord($style=""){
		
	global $kis_lang;
	
	$style = $style? ' style="'.$style.'" ': ' ';
	
	echo '<div'.$style.'class="no_record">'.$kis_lang['norecord'].'</div>';
	
    }
    public static function getDaysAgoWord($to_ts, $from_ts = false){
	
	global $kis_lang, $request_ts;
	
	$ts_diff = ($from_ts?$from_ts:$request_ts)-$to_ts;
	
	if ($ts_diff > 86400){
	    return floor($ts_diff/86400).' '.$kis_lang["daysago"];
	}else if ($ts_diff > 3600){
	    return floor($ts_diff/3600).' '.$kis_lang["hoursago"];
	}else{
	    return ceil($ts_diff/60).' '.$kis_lang["minutesago"];
	}
	
    }
    
    public static function getSimpleDateFormat($ts, $current_ts = false){
	
	global $request_ts;
	
	$current_ts = $current_ts?$current_ts:$request_ts;
	
	if (date('Y', $ts) != date('Y', $current_ts)){
	    
	    return ('Y-m-d');
	}else if(date('md', $ts) != date('md', $current_ts)){
	    return date('M j', $ts);
	}else{
	    return date('g:i a', $ts);
	}
	
    }

}
?>