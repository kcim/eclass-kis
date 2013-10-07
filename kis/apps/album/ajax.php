<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

$libkis_album = $libkis->loadApp('album',array('album_id'=>$album_id));

switch ($action){
    
    case 'setalbum':
	
	$params = array(
	    'title'=>htmlspecialchars($title),
	    'description'=>htmlspecialchars($description),
	    'cover_photo_id'=>$cover_photo_id,
	    'place'=>htmlspecialchars($place),
	    'date'=>$date,
	    'since'=>$since,
	    'until'=>$until
	);
	
	if ($album_id && $libkis_album->isAlbumEditable()){
	    
	    $kis_data['album_id'] = $album_id;
	    $kis_data['album_title'] = $title? $title: '('.$kis_lang['untitledalbum'].')';
	    $libkis_album->updateAlbum($params);
	    $libkis_album->resetAlbumUsersGroups($share_to=='all', $select_groups);
	    
	}else if ($libkis_album->hasAlbumCreatePermission()){
	    
	    $kis_data['album_id'] = $libkis_album->createAlbum($params);
	    $kis_data['album_title'] = $title? $title: '('.$kis_lang['untitledalbum'].')';
	    $album = $libkis_album->getAlbum();;
	    	    
	    mkdir($file_path.kis_album::getAlbumFileName('photo', $album), 0755, true);
	    mkdir($file_path.kis_album::getAlbumFileName('thumbnail', $album), 0755, true);
	    mkdir($file_path.kis_album::getAlbumFileName('original', $album), 0755, true);
	    
	    $libkis_album->resetAlbumUsersGroups($share_to=='all', $select_groups);
	    
	}else{
	    $kis_data['error'] = 1;
	}
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'addphoto':
	
	$img_path = $_FILES['file']['tmp_name'];
	$size = $_FILES['file']['size'];
	
	list($width, $height, $img_type) = getimagesize($img_path);
	
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
	
	if ($image && $libkis_album->isAlbumEditable()){
	    
	    $exif = @exif_read_data($img_path);
	    
	    $kis_data['title'] = $_FILES['file']['name'];
	    $kis_data['date_taken'] = (int)strtotime($exif['DateTime']);
	    $kis_data['date_uploaded'] = time();
	    
	    $photo = array(
		'title'=>htmlspecialchars($kis_data['title']),
		'date'=>$kis_data['date_taken'],
		'size'=>$size
	    );

	    $photo_id = $libkis_album->createAlbumPhoto($photo);
	
	    $album = $libkis_album->getAlbum();
	    $photo = $libkis_album->getAlbumPhoto($photo_id);
	    
	    $photo_url 		= kis_album::getPhotoFileName('photo', $album, $photo);
	    $thumbnail_url 	= kis_album::getPhotoFileName('thumbnail', $album, $photo);
	    $original_url 	= kis_album::getPhotoFileName('original', $album, $photo);

	    $resized = kis_album::getResizedImage($image, $width, $height, kis_album::$photo_max_size);
	    imagejpeg($resized, $file_path.$photo_url, 100);
	    imagedestroy($resized);
	    
	    $thumbnail = kis_album::getResizedImage($image, $width, $height, kis_album::$thumbnail_max_size);
	    imagejpeg($thumbnail, $file_path.$thumbnail_url, 100);
	    imagedestroy($thumbnail);
	    
	    imagejpeg($image, $file_path.$original_url, 100);
	    imagedestroy($image);
	    
	    $kis_data['thumbnail'] = $thumbnail_url;
	    $kis_data['photo_id']  = $photo_id;
	    
	}else{
	    
	    $kis_data['error'] = $kis_lang['unsupportedformat'].'!';
	}
	
	echo $libjson->encode($kis_data);
	
    break;

    case 'updatephotodescription':
	
	if ($libkis_album->isAlbumEditable()){
	    $libkis_album->updateAlbumPhoto($photo_id, array('description'=>$description));
	}
	
    break;

    case 'reorderphotos':
	
	if ($libkis_album->isAlbumEditable()){
	    $libkis_album->reorderAlbumPhotos($photo_ids);
	}
	
    break;

    case 'updatecoverphoto':
	
	if ($libkis_album->isAlbumEditable()){
	    $libkis_album->updateAlbumCoverPhoto($photo_id);
	}
	
    break;

    case 'removephoto':
	
	$album = $libkis_album->getAlbum();
	$photo = $libkis_album->getAlbumPhoto($photo_id);
	    
	if ($libkis_album->isAlbumEditable() && $libkis_album->removeAlbumPhoto($photo_id)){
	    
	    unlink($file_path.kis_album::getPhotoFileName('photo', $album, $photo));
	    unlink($file_path.kis_album::getPhotoFileName('thumbnail', $album, $photo));
	    unlink($file_path.kis_album::getPhotoFileName('original', $album, $photo));
	    
	}
	
    break;

    case 'removealbum':

	if ($libkis_album->isAlbumEditable()){
	    
	    $album = $libkis_album->getAlbum();
	    $photos = $libkis_album->getAlbumPhotos();
	    
	    foreach ($photos as $photo){
		
		unlink($file_path.kis_album::getPhotoFileName('photo', $album, $photo));
		unlink($file_path.kis_album::getPhotoFileName('thumbnail', $album, $photo));
		unlink($file_path.kis_album::getPhotoFileName('original', $album, $photo));
		
	    }
	    
	    rmdir($file_path.kis_album::getAlbumFileName('photo', $album));
	    rmdir($file_path.kis_album::getAlbumFileName('thumbnail', $album));
	    rmdir($file_path.kis_album::getAlbumFileName('original', $album));
	    
	    $libkis_album->removeAlbum();
	    
	}
	
    break;


}

?>
