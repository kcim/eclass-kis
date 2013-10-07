<script>
$(function(){
    kis.album.album_form_init({
	areyousureto : '<?=$kis_lang['areyousureto']?>',
	allphotoswillberemoved: '<?=$kis_lang['allphotoswillberemoved']?>!',
	stopuploadingphotos: '<?=$kis_lang['stopuploadingphotos']?>'		      
    },{photo_count: <?=sizeof($photos)?>});
});
</script>
<div class="main_content">
    
    <? if ($album): ?>
    <span class="album_title" style="display:none;"><?=$album['title']?$album['title']:'('.$kis_lang['untitledalbum'].')'?></span>
    <? endif; ?>
    <form class="album_form">
	
	<div class="album_form_title" ><?=$kis_lang['albumtitle']?> :</div> 
	
	<input type="text" name="title" maxlength="60" class="album_form_input" value="<?=$album['title']?>" placeholder="<?=$kis_lang['albumtitle']?>..." size="30">
	<div class="album_form_date_access">  <input class="date" name="date" type="text" size="12" value="<?=$album['date']? date('Y-m-d',$album['date']): date('Y-m-d')?>"></div>
	<p class="spacer"></p>
	<div class="album_form_title"><?=$kis_lang['description']?> :</div> 
	
	<input type="text" name="description" value="<?=$album['description']?>" class="album_form_input" placeholder="<?=$kis_lang['description']?>..." size="50"> 
	<input type="hidden" name="album_id" value="<?=$album['id']?>"> 
	
	<div class="album_form_date_access">
	    <a class="btn_select_ppl" href="#"><?=$kis_lang['shareto']?>...</a>
	    <div class="album_form_date_access_select" style="display:none;">
		<input <?=!$shared_all&&!$shared_groups?'checked':''?> type="radio" name="share_to" id="share_to_myself" value=""/><label for="share_to_myself"><?=$kis_lang['onlymyself']?></label><br/>
		<input <?=$shared_all?'checked':''?> type="radio" name="share_to" id="share_to_all" value="all"/><label for="share_to_all"><?=$kis_lang['allusers']?></label><br/>
		<input <?=$shared_groups?'checked':''?> type="radio" name="share_to" id="share_to_groups" value="groups"/><label for="share_to_groups"><?=$kis_lang['specificgroups']?></label><br/>
		<select multiple="multiple" size="8" name="select_groups[]"  class="select_groups" <?=$shared_groups?'':'style="display:none"'?>>
		    <? foreach ($groups as $group): ?>
		    <option <?=in_array($group['group_id'], $shared_groups)?'selected':''?> value="<?=$group['group_id']?>"><?=$group['group_name_'.$lang]?></option>
		    <? endforeach; ?>
		</select>
	      
		<div class="select_date" <?=$shared_groups||$shared_all?'':'style="display:none"'?>><?=$kis_lang['since']?> <input name="since" type="text" class="date" value="<?=$album['since']?date('Y-m-d',$album['since']):''?>"/></div>
		<p class="spacer"></p> 
		<div class="select_date" <?=$shared_groups||$shared_all?'':'style="display:none"'?>><?=$kis_lang['until']?> <input name="until" type="text" class="date" value="<?=$album['until']?date('Y-m-d',$album['until']):''?>"/></div>
	    </div>
	</div>
    </form>
   
                        
    <p class="spacer"></p> 
		      
    <div class="Content_tool">
	<a class="new" id="add_photos" href="#"><?=$kis_lang['addphotos']?></a>
    </div>
    <div class="filter_ordering">
		       
	<a href="#title"><?=$kis_lang['photoname']?></a><a class="int" href="#date_uploaded"><?=$kis_lang['uploaddate']?></a><a class="int" href="#date_taken"><?=$kis_lang['datetaken']?></a><span><?=$kis_lang['reorderphotos']?> :</span>
    </div>
    <p class="spacer"></p>
    <div class="edit_photo_list" id="attach_file_area">
	<ul>
	    <? foreach ((array)$photos as $photo): ?>
	    <li class="uploaded">
		<span title="<?=$photo['title']?>" style="background-image:url(<?=kis_album::getPhotoFileName('thumbnail', $album, $photo)?>)"/></span>
		<p class="spacer"></p>
	    
		<textarea maxlength="255" placeholder="<?=$kis_lang['descriptionhere']?>..." name="descriptions[]" class="input_desc" wrap="virtual" rows="2"><?=$photo['description']?></textarea>
		<input type="hidden" name="photo_ids[]" class="photo_ids" value="<?=$photo['id']?>"/>
		<input type="hidden" class="date_taken" value="<?=$photo['date_taken']?>"/>
		<input type="hidden" class="date_uploaded" value="<?=$photo['date_uploaded']?>"/>
		<input type="hidden" class="title" value="<?=$photo['title']?>"/>
		<p class="spacer"></p>
		<div class="table_row_tool">

		    <a href="#" class="copy_dim" title="<?=$kis_lang['setascoverphoto']?>"></a> 
		    <a href="#" class="delete_dim" title="<?=$kis_lang['removephoto']?>"></a>
		</div>
	    </li>
	    <? endforeach; ?>
	   
	</ul>
	
	<p class="spacer"></p>
    </div>				
    		  
    <div class="edit_bottom">
	<input type="button" value="<?=$kis_lang['finish']?>" class="formbutton">
	<a class="album_form_remove" <?=$album?'':'style="display:none"'?> href="#"><?=$kis_lang['removealbum']?></a>
    </div>
    
    <p class="spacer"></p>
                      	
</div>
<ul id="edit_photo_list_template" style="display:none">
    <li style="display:none">
	
	<span></span>
	<p class="spacer"></p>
	<textarea disabled maxlength="255" placeholder="<?=$kis_lang['descriptionhere']?>..." name="descriptions[]" class="input_desc" wrap="virtual" rows="2"></textarea>
	<input type="hidden" name="photo_ids[]" class="photo_ids" value=""/>
	<input type="hidden" class="date_taken" value=""/>
	<input type="hidden" class="date_uploaded" value=""/>
	<input type="hidden" class="title" value=""/>
	<p class="spacer"></p>
	<div class="table_row_tool" style="display:none">
	    <a href="#" class="copy_dim" title="<?=$kis_lang['setascoverphoto']?>"></a><a href="#" class="delete_dim" title="<?=$kis_lang['removephoto']?>"></a>
	</div>
    </li>
</ul>

