<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');
$libkis_message = $libkis->loadApp('shop');

switch ($action){
    
    default:
	include('templates/main.php');
    break;
    
}

?>