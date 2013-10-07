<?
$PATH_WRT_ROOT = "../../../";
include_once($PATH_WRT_ROOT.'kis/init.php');

if ($q[0]=='edit'){
    
    $libkis_album = $libkis->loadApp('album',array('album_id'=>$id));
    
    if ($id){
	
	$kis_data['album'] = $libkis_album->getAlbum();

	if ($kis_data['album'] && $libkis_album->isAlbumReadable()){
	    
	    $kis_data['photos'] = $libkis_album->getAlbumPhotos();
	    $kis_data['album']['editable'] = $libkis_album->isAlbumEditable();
	    
	    if ($kis_data['album']['editable']){//album editable
		
		$kis_data['groups'] = kis_utility::getAcademicYearGroups();
		list($kis_data['shared_all'], $kis_data['shared_groups'], ) = $libkis_album->getAlbumUsersGroups();
		$kis_data['main_template'] = 'album_form';
		
	    }else{//album exists but not editable
		
		$kis_data['main_template'] = 'album';
	    }
		    
	}else{//album not found or not visible, show all albums list
	    
	    $show_album_list = true;
	}
	
    }else if ($libkis_album->hasAlbumCreatePermission()){//can create new albums
	
	$kis_data['groups'] = kis_utility::getAcademicYearGroups();
	list($kis_data['shared_all'], $kis_data['shared_groups'], ) = $libkis_album->getAlbumUsersGroups();
 	$kis_data['main_template'] = 'album_form';
	
    }else{//nothing can be done, show all albums list
	
	$show_album_list = true;
    }

	
}else{
    
    $libkis_album = $libkis->loadApp('album',array('album_id'=>$q[0]));
    
    $kis_data['album'] = $libkis_album->getAlbum();
    
    if ($kis_data['album'] && $libkis_album->isAlbumReadable()){
	
	$kis_data['photos'] = $libkis_album->getAlbumPhotos();
	$kis_data['album']['editable'] = $libkis_album->isAlbumEditable();
	$kis_data['main_template'] = 'album';
	
    }else{//album not found or not visible, show all albums list
	
	$show_album_list = true;
    }

}

if ($show_album_list){
    
    $albums = $libkis_album->getAlbums(array('group_id'=>$group_id,'keyword'=>$search, 'my_album_only'=>$my_album_only));
    $kis_data['albums'] = array();
    
    foreach ($albums as $album){

	if ($libkis_album->isAlbumReadable($album['id'])){
	    
	    $album['cover_photo'] = $libkis_album->getAlbumPhoto($album['cover_photo_id']?$album['cover_photo_id']:$album['first_photo_id']);
	    $kis_data['albums'][] = $album;
	}
	
    }
    $kis_data['can_create_album'] = $libkis_album->hasAlbumCreatePermission();
    $kis_data['groups'] = kis_utility::getAcademicYearGroups();
    $kis_data['main_template'] = 'albums';
    
}

kis_ui::loadTemplate($kis_data['main_template'], $kis_data, $format);
?>