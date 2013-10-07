<?

class kis_album extends libdb implements kis_apps {
        
    private $user_id;
    public static $photo_urls = array('photo'=>"/file/album/photos/",'thumbnail'=>"/file/album/thumbnails/",'original'=>"/file/album/originals/");
    public static $photo_max_size = "960";
    public static $thumbnail_max_size = "240";
    
    //not using now
    public static function getAvailability($user_id, $user_type, $student_id){
    
	global $plugin;
	
	if ($plugin['photo_album']){
	    return array('album', 'btn_album', '', '');
	}
	return array('album', 'btn_album', '', '');
	//return array();
    }
      
    public static function getAdminStatus($user_id, $user_type, $student_id){
    
	if ($_SESSION["SSV_USER_ACCESS"]["eAdmin-PhotoAlbum"]){
	    return array('');
	}
	
	return array();
	
    }

    public static function getNotificationCount($user_id, $user_type, $student_id){
    
	return 0;
	
    }
    public static function getResizedImage($image, $sw, $sh, $t){
	$sr = $sw/$sh;
	
	if ($sw <= $t && $sh <= $t) {
	    $rw = $sw;
	    $rh = $sh;
	} else if ($sr < 1) {
	    $rw = floor($t * $sr);
	    $rh = $t;
	} else {
	    $rw = $t;
	    $rh = floor($t / $sr);
	}

	$rimage = imagecreatetruecolor($rw, $rh);
	
	imagecopyresampled($rimage, $image, 0, 0, 0, 0, $rw, $rh, $sw, $sh);
	return $rimage;

    }
    public static function getPhotoFileName($type, $album, $photo){
	
	return self::getAlbumFileName($type, $album).'/'.base64_encode($photo['id'].'_'.$photo['input_date'].'_'.$photo['input_by']).'.jpg';
	
    }
    public static function getAlbumFileName($type, $album){
	
	return self::$photo_urls[$type].base64_encode($album['id'].'_'.$album['input_date'].'_'.$album['input_by']);
	
    }

    public function __construct($user_id, $user_type, $student_id, $params){

	global $intranet_db;

	$this->db = $intranet_db;
	$this->user_id = $user_id;
	$this->student_id = $student_id;
	$this->user_type = $user_type;
	$this->album_id = $params['album_id'];
	$this->is_admin = self::getAdminStatus($user_id, $user_type, $student_id);
	$this->own_albums_editable = $this->user_type == kis::$user_types['teacher'] && true;
    	
    }
    public function hasAlbumCreatePermission(){
				
	return $this->is_admin || $this->own_albums_editable;
	
    }
    public function isAlbumReadable($album_id=false){
		
	$album_id = $album_id? $album_id: $this->album_id;
	
	if ($this->is_admin){ return true; }
	
	$sql = "SELECT InputBy,
		IF (SharedSince <= CURDATE() OR SharedSince IS NULL OR SharedSince = 0, 1, 0),
		IF (SharedUntil >= CURDATE() OR SharedUntil IS NULL OR SharedUntil = 0, 1, 0) 
		FROM INTRANET_ALBUM
		WHERE AlbumID = $album_id";
	
	$settings = current($this->returnArray($sql));echo mysql_error();
		
	if ($settings[0]==$this->user_id){ return true; }
	
	if (!$settings[1] || !$settings[2]){ return false; }
	
	$sql1 = "SELECT COUNT(*)
		FROM INTRANET_ALBUM_USER a
		WHERE a.AlbumID = $album_id AND a.RecordType = 'all'";
	
	$sql2 = "SELECT COUNT(*)
		FROM INTRANET_ALBUM_USER a
		INNER JOIN INTRANET_USERGROUP g ON a.RecordID = g.GroupID AND a.RecordType = 'group'
		WHERE a.AlbumID = $album_id AND (g.UserID = ".$this->user_id." OR g.UserID = '".$this->student_id."')";
	
	$sql3 = "SELECT COUNT(*)
		FROM INTRANET_ALBUM_USER a
		WHERE a.AlbumID = $album_id AND a.RecordID = ".$this->user_id." AND a.RecordType = 'user'";

	return current($this->returnVector($sql1)) || current($this->returnVector($sql2)) || current($this->returnVector($sql3));

    }
    public function isAlbumEditable(){
	
	if ($this->is_admin){ return true; }
	
	if ($this->own_albums_editable){
	    
	    $sql = "SELECT COUNT(*)
		    FROM INTRANET_ALBUM
		    WHERE InputBy = ".$this->user_id." AND AlbumID = ".$this->album_id;
		    
	    return current($this->returnVector($sql));	
	}
		
	return false;
	
    }
    
    public function getAlbums($params=array()){
	
	extract($params);
	$group = $group_id? "INNER JOIN INTRANET_ALBUM_USER u ON a.AlbumID = u.AlbumID AND u.RecordID = $group_id AND u.RecordType = 'group'": "";
	$mine = $my_album_only? "a.InputBy = ".$this->user_id." AND":'';
	
	$sql = "SELECT
		    a.AlbumID id,
		    a.Title title,
		    a.Description description,
		    a.PlaceTaken place,
		    UNIX_TIMESTAMP(a.DateTaken) date,
		    a.InputBy input_by,
		    a.CoverPhotoID cover_photo_id,
		    p.AlbumPhotoID first_photo_id,
		    UNIX_TIMESTAMP(a.DateInput) input_date,
		    a.InputBy input_by,
		    COUNT(p.AlbumPhotoID) as photo_count
		FROM INTRANET_ALBUM a
		LEFT JOIN INTRANET_ALBUM_PHOTO p ON a.AlbumID = p.AlbumID
		$group
		WHERE $mine (a.Title LIKE '%$keyword%' OR a.Description LIKE '%$keyword%' OR a.PlaceTaken LIKE '%$keyword%' )
		GROUP BY a.AlbumID";
		
	return $this->returnArray($sql);
	
    }
    
    public function getAlbum(){
	
	
	$sql = "SELECT
		    a.AlbumID id,
		    a.Title title,
		    a.Description description,
		    a.CoverPhotoID cover_photo_id,
		    a.PlaceTaken place,
		    UNIX_TIMESTAMP(a.SharedSince) since,
		    UNIX_TIMESTAMP(a.SharedUntil) until,
		    UNIX_TIMESTAMP(a.DateTaken) date,
		    UNIX_TIMESTAMP(a.DateInput) input_date,
		    a.InputBy input_by
		FROM INTRANET_ALBUM a
		WHERE a.AlbumID = ".$this->album_id;
		
	return current($this->returnArray($sql));
	
    }
    public function getAlbumUsers(){
	
	$sql = "SELECT
		    u.UserID user_id.
		    u.EnglishName user_name_en.
		    u.ChineseName user_name_b5
		FROM INTRANET_ALBUM_USER a
		INNER JOIN INTRANET_USER u ON a.RecordType = 'user' AND a.RecordID = u.UserID";
		
	return $this->returnArray($sql);
    }
    public function getAlbumGroups(){
	
	$sql = "SELECT
		    g.GroupID group_id,
		    g.Title group_name_en,
		    g.TitleChinese group_name_b5
		FROM INTRANET_ALBUM_USER a
		INNER JOIN INTRANET_GROUP g ON a.RecordType = 'group' AND a.RecordID = g.GroupID";
		
	return $this->returnArray($sql);
    }
    
    public function getAlbumPhotos(){
	
	$sql = "SELECT
		    p.AlbumID as album_id,
		    p.AlbumPhotoID id,
		    p.Title title,
		    p.Description description,
		    p.Size as size,
		    UNIX_TIMESTAMP(p.DateTaken) date_taken,
		    UNIX_TIMESTAMP(p.DateInput) date_uploaded,
		    UNIX_TIMESTAMP(p.DateInput) input_date,
		    p.InputBy input_by
		FROM INTRANET_ALBUM_PHOTO p
		WHERE p.AlbumID = ".$this->album_id."
		ORDER BY $sort IF(Sequence IS NULL, -1, Sequence), DateInput";
		
	return $this->returnArray($sql);
	
    }
    
    public function getAlbumPhoto($photo_id){
	
	$sql = "SELECT
		    p.AlbumID as album_id,
		    p.AlbumPhotoID id,
		    p.Title title,
		    p.Description description,
		    p.Size as size,
		    UNIX_TIMESTAMP(p.DateTaken) date_taken,
		    UNIX_TIMESTAMP(p.DateInput) date_uploaded,
		    UNIX_TIMESTAMP(p.DateInput) input_date,
		    p.InputBy input_by
		FROM INTRANET_ALBUM_PHOTO p
		WHERE AlbumPhotoID = $photo_id";
		
	return current($this->returnArray($sql));
	
    }
    
    public function createAlbum($params){
	
	extract($params);
	
	$sql = "INSERT INTO INTRANET_ALBUM (Title, Description, PlaceTaken, DateTaken, SharedSince, SharedUntil, InputBy, DateInput)
		VALUES ('$title','$description','$place','$date','$since','$until',".$this->user_id.",now())";
		
	$this->db_db_query($sql);
	$this->album_id = $this->db_insert_id();
		
	return $this->album_id;

    }
    
    public function updateAlbum($params){
	
	extract($params);
	
	$sql = "UPDATE INTRANET_ALBUM SET
		    Title = '$title',
		    Description = '$description',
		    PlaceTaken = '$place',
		    DateTaken = '$date',
		    SharedSince = '$since',
		    SharedUntil = '$until',
		    ModifyBy = '".$this->user_id."'
		WHERE AlbumID = ".$this->album_id;
		
	$this->db_db_query($sql);
	
    }
    
    public function removeAlbum(){
	
	$sql = "DELETE a,p,u
		FROM INTRANET_ALBUM a
		LEFT JOIN INTRANET_ALBUM_PHOTO p ON a.AlbumID = p.AlbumID
		LEFT JOIN INTRANET_ALBUM_USER u ON a.AlbumID = u.AlbumID
		WHERE a.AlbumID = ".$this->album_id;
	
	$this->db_db_query($sql);
    }
    
    public function updateAlbumCoverPhoto($photo_id){
		
	$sql = "UPDATE INTRANET_ALBUM SET
		    CoverPhotoID = '$photo_id',
		    ModifyBy = '".$this->user_id."'
		WHERE AlbumID = ".$this->album_id;
		
	return $this->db_db_query($sql);
	
    }

    public function createAlbumPhoto($params=array()){
	
	$sql = "SELECT MAX(Sequence)+1 FROM INTRANET_ALBUM_PHOTO WHERE AlbumID=".$this->album_id." GROUP BY AlbumID";
	$sequence = (int)current($this->returnVector($sql));
	
	extract($params);
	
	$sql = "INSERT INTO INTRANET_ALBUM_PHOTO (AlbumID, Title, Size, Sequence, DateTaken, InputBy, DateInput)
		VALUES (".$this->album_id.",'$title', '$size', '$sequence', FROM_UNIXTIME('$date'),".$this->user_id.",now())";
		
	$this->db_db_query($sql);
	
	return $this->db_insert_id();
	
    }
    public function updateAlbumPhoto($photo_id, $params){
	
	extract($params);
	
	$sql = "UPDATE INTRANET_ALBUM_PHOTO SET
		    Description = '$description',
		    ModifyBy = '".$this->user_id."'
		WHERE AlbumPhotoID = $photo_id";
		
	$this->db_db_query($sql);
	
    }
    public function reorderAlbumPhotos($photo_ids){
	
	$count = 0;
	foreach ($photo_ids as $photo_id){
	    
	    $sql = "UPDATE INTRANET_ALBUM_PHOTO SET
		    Sequence = '$count',
		    ModifyBy = '".$this->user_id."'
		WHERE AlbumPhotoID = $photo_id";
		
	    $this->db_db_query($sql);
	
	    $count++;
	}
	
    }
    public function removeAlbumPhoto($photo_id){
	
	$sql = "UPDATE INTRANET_ALBUM SET CoverPhotoID = NULL WHERE CoverPhotoID = $photo_id";
	
	$this->db_db_query($sql);
	
	$sql = "DELETE FROM INTRANET_ALBUM_PHOTO WHERE AlbumPhotoID = $photo_id";
	
	return $this->db_db_query($sql);
	
    }
    
    public function getAlbumUsersGroups(){
	
	$sql = "SELECT COUNT(*)
		FROM INTRANET_ALBUM_USER
		WHERE RecordType = 'all' AND
		    AlbumID = ".$this->album_id;
		    
	
	if (current($this->returnVector($sql))){
	    
	    return array(1, array(), array());
	    
	}else{
	    
	    $sql = "SELECT RecordID
		    FROM INTRANET_ALBUM_USER
		    WHERE RecordType = 'group' AND
			AlbumID = ".$this->album_id;
	    
	    $groups = $this->returnVector($sql);
	    
	    $sql = "SELECT RecordID
		    FROM INTRANET_ALBUM_USER
		    WHERE RecordType = 'user' AND
			AlbumID = ".$this->album_id;
	    
	    $users = $this->returnVector($sql);
	    
	    return array(0, $groups, $users);
	    
	}
	
    }
    public function resetAlbumUsersGroups($all_users, $group_ids=array(), $user_ids=array()){
	
	$sql = "DELETE FROM INTRANET_ALBUM_USER WHERE AlbumID = ".$this->album_id;
		
	$this->db_db_query($sql);
	
	if ($all_users){
	    
	    $sql = "INSERT INTO INTRANET_ALBUM_USER (AlbumID, RecordType, DateInput, InputBy)
		    VALUES (".$this->album_id.", 'all', now(), ".$this->user_id.")";
		    
	    $this->db_db_query($sql);
	    
	}else{
	    
	    foreach ((array) $group_ids as $group_id){
		
		$sql = "INSERT INTO INTRANET_ALBUM_USER (AlbumID, RecordID, RecordType, DateInput, InputBy)
		    VALUES (".$this->album_id.", $group_id, 'group', now(), ".$this->user_id.")";
		    
		$this->db_db_query($sql);
		
	    }
	    
	    foreach ((array) $user_ids as $user_id){
		
		$sql = "INSERT INTO INTRANET_ALBUM_USER (AlbumID, RecordID, RecordType, DateInput, InputBy)
		    VALUES (".$this->album_id.", $user_id, 'user',  now(), ".$this->user_id.")";
		    
		$this->db_db_query($sql);
		
	    }
	    
	}
	
	return $this;
	
    }
    
}
?>