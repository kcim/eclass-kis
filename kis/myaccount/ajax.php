<?

$PATH_WRT_ROOT = "../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$libkis_accountmanage = $libkis->loadApp('accountmanage', array('target_user_id'=>$kis_user['id']));
$permission = $libkis_accountmanage->getUserTypePermission($kis_user['type']);

switch ($action){
    
    case 'updatedetail':
	
	$params['nick_name'] = htmlspecialchars($nick_name);
	$params['gender'] = htmlspecialchars($gender);
	$params['address'] = htmlspecialchars($address);
	$params['home_tel'] = htmlspecialchars($home_tel);
	$params['office_tel'] = htmlspecialchars($office_tel);
	$params['mobile_tel'] = htmlspecialchars($mobile_tel);
	$params['url'] = htmlspecialchars($url);
	$params['fax'] = htmlspecialchars($fax);
	$params['country'] = htmlspecialchars($country);
	$params['email'] = htmlspecialchars($email);

	$libkis->updateUserDetail($params);
	
    break;

    case 'updatephoto':
	
	if ($img_path = $_FILES['file']['tmp_name']){
	
	    list($width, $height, $img_type) = getimagesize($img_path);
	    // Maximum size is 1MB for now
		if (filesize($img_path) < 1048576){  
	    switch ($img_type) {
		case IMAGETYPE_GIF:
		    $image = imagecreatefromgif($img_path);
		break;
		case IMAGETYPE_JPEG:
		    $image = imagecreatefromjpeg($img_path);
		break;
		case IMAGETYPE_PNG:
		    $image = imagecreatefrompng($img_path);
		break;
	    }
	   	}
	   	
	}
	    
	if ($image){
	    
	    $image_url = kis::$personal_photo_url.'p'.$kis_user['id'].'.jpg';	    
	    $sr = $width/$height;
	    $rw = kis::$personal_photo_width;
	    $rh = kis::$personal_photo_height;
	
	    if ($sr > 1/1.3) {
		$rw = floor($rh * $sr);
	    } else {
		$rh = floor($rw / $sr);
	    }
	    
	    $rimage = imagecreatetruecolor(kis::$personal_photo_width, kis::$personal_photo_height);
	    imagecopyresampled($rimage, $image, floor(($rw-kis::$personal_photo_width)/-2), floor(($rh-kis::$personal_photo_height)/-2), 0, 0, $rw, $rh, $width, $height);
	    
	    if (!file_exists($file_path.kis::$personal_photo_url)){
		mkdir($file_path.kis::$personal_photo_url ,0755, true);
	    }
	    imagejpeg($rimage, $file_path.$image_url, 100);  
	   
	    $libkis->updateUserPersonalPhoto($image_url);
	    $kis_data['personal_photo'] = $image_url.'?_='.time();
	    
	}else{
		if ($img_type != IMAGETYPE_GIF &&$img_type != IMAGETYPE_JPEG && $img_type !=IMAGETYPE_PNG)
			$kis_data['error'] = $kis_lang['unsupportedformat'];
		else
	  		$kis_data['error'] = $kis_lang['exceedlimit'];
	}

    break;

    case 'removephoto':
	$libkis->updateUserPersonalPhoto('');
    break;

    case 'updatepassword':
	
	if ($token != $_SESSION['kis']['password_token']){ break; }
	if ($password != $password2){ $kis_data['error'] = $kis_lang['passwordnotmatch']; break; }
	if (mb_strlen($password)<6){ $kis_data['error'] = $kis_lang['passwordtooshort']; break; }
    	
	if (!$libkis_accountmanage->getUserInfoSettings("CanUpdatePassword", $kis_user['type'])){ break; }
	if (!$libkis_accountmanage->checkUserPassword($old_password)){ $kis_data['error'] = $kis_lang['incorrectpassword']; break; }	
	
	$libkis_accountmanage->updateUserPassword($password);

	$kis_data['error'] = 0;

	//include_once("../../includes/libeclass.php");
	//$lc = new libeclass();
	//$lc->eClassUserUpdateInfoIP($lu->UserEmail, $lu->Title, $lu->EnglishName,$lu->ChineseName,$lu->FirstName,$lu->LastName, $lu->NickName, $password, $lu->ClassName,$lu->ClassNumber, $newmail,$lu->Gender, $lu->ICQNo,$lu->HomeTelNo,$lu->FaxNo,$lu->DateOfBirth,$lu->Address,$lu->Country,$lu->URL,$lu->Info);
	
    break;

}

echo $libjson->encode($kis_data);

?>