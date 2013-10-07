<script>
kis.album.albums_init();
</script>

<div class="main_content">
    <? if ($can_create_album): ?>
    <div class="Content_tool"><a class="new" href="#/apps/album/edit/"><?=$kis_lang['createalbum']?></a></div>
    <? endif; ?>
    
    <p class="spacer"></p>

    <form class="filter_form">
	<div id="table_filter">
	    <? if ($kis_user['type']==kis::$user_types['teacher']): ?>
	    <?=$kis_lang['groups']?>: 
	    <select name="group_id" class="auto_submit">
		
		<option value=""><?=$kis_lang['all']?></option>
		<? foreach ($groups as $group): ?>
		<option <?=$group_id==$group['group_id']?'selected':''?> value="<?=$group['group_id']?>"><?=$group['group_name_'.$lang]?></option>
		<? endforeach; ?>
	    </select>
	    <? endif; ?>
	    <? if ($can_create_album): ?>
		<input type="checkbox" id="my_album_only" name="my_album_only" <?=$my_album_only?'checked':''?> value="1"/><label for="my_album_only"><?=$kis_lang['onlyalbumscreatedbyme']?></label>
	    <? endif; ?>
	    <span class="date_time" style="padding-left:20px;"><?=$kis_lang['total']?> <?=sizeof($albums)?> <?=$kis_lang['albums']?></span>
	</div>
	<div class="search"><!--<a href="#">Advanced</a>-->
	    
	    <input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
	</div>
    </form>
    
    <p class="spacer"></p>
    
    
	
    <? if ($albums): ?>
	<ul class="album_list">
	    <? foreach ($albums as $album): ?>
	    <li><a href="#/apps/album/<?=$album['id']?>">
		<span><?=$album['title']?$album['title']:'('.$kis_lang['untitledalbum'].')'?><i><?=$album['photo_count']?> <?=$kis_lang['photos']?> (<?=date('Y-m-d', $album['date'])?>)</i> </span>
		<? if ($album['first_photo_id']): ?>
		<div class="thumbnail" style="background-image: url(<?=kis_album::getPhotoFileName('thumbnail', $album, $album['cover_photo'])?>)"></div>
		<? endif; ?>
	    
	    </a></li>
	
	<? endforeach; ?>
	</ul>
    <? else: ?>
	<div class="no_record"><?=$kis_lang['norecord']?></div>
    <? endif; ?>
    
    <p class="spacer"></p>
</div>                  