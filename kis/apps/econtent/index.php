<?

$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');
$libkis_message = $libkis->loadApp('econtent');

if ($kis_config['econtent'][$q[0]]){
    
    $subject = $q[0];
    $units = $kis_config['econtent'][$q[0]][$type]? array($type=>$kis_config['econtent'][$q[0]][$type]): $kis_config['econtent'][$q[0]];
    include('templates/units.php');
    
}else{
    include('templates/main.php');
}

    

?>