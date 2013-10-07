<?
/**
 * KIS Init Script
 *
 * Intialize commonly required elements
 *
 * @author Mick Chiu
 * @since 2012-03-27
 * 
 */

include_once($PATH_WRT_ROOT."includes/global.php");
include_once($PATH_WRT_ROOT."includes/libdb.php");
include_once($PATH_WRT_ROOT."includes/kis/libkis.php");
include_once($PATH_WRT_ROOT."includes/kis/libkis_ui.php");
include_once($PATH_WRT_ROOT."includes/kis/libkis_utility.php");
include_once($PATH_WRT_ROOT."includes/kis/libkis_apps.php");
include_once($PATH_WRT_ROOT."includes/json.php");
include_once($PATH_WRT_ROOT."lang/kis/lang_common_".$intranet_session_language.".php");

include_once("config.php");

if ($_SESSION["platform"]!="KIS" || !$_SESSION["UserID"]){

    header('HTTP/1.0 403 Forbidden');
    echo 'stop_'.$a;
    die;
}

intranet_opendb();

$libjson 	= new JSON_obj();
$libkis 	= new kis($UserID);

$lang		= $intranet_session_language;
$kis_user 	= $libkis->getUserInfo();
$q 		= explode('/',strtolower($q));
$search		= trim($search);
$request_ts	= $_SERVER['REQUEST_TIME'];

?>