<script>
$(function(){
    kis.album.album_init();
});
</script>
<div class="main_content">
    
    <span class="album_title" style="display:none;"><?=$album['title']?$album['title']:'('.$kis_lang['untitledalbum'].')'?></span>
    
    <div>
    <?=$album['description']?>
      
      <? if ($album['editable']): ?>
      <div class="common_table_tool">
	  <a class="edit" href="#/apps/album/edit/?id=<?=$album['id']?>"><?=$kis_lang['edit']?></a>
      </div>
   	  <p class="spacer"></p>
    <? endif; ?>
    </div>
    
    <div class="navigation_bar">
	<span><?=$kis_lang['total']?> <?=sizeof($photos)?> <?=$kis_lang['photos']?> <em> (<?=date('Y-m-d', $album['date'])?>)</em></span>
    </div>
    <p class="spacer"></p>
  
	  
    <div class="photo_thumb_list">
	<? if ($photos): ?>
	<ul>
	    <? foreach ($photos as $photo): ?>
	    <li>
		<a title="<?=$photo['title']?>" href="<?=kis_album::getPhotoFileName('photo', $album, $photo)?>" rel="fancybox-thumb" class="fancybox-thumb"
		    style="background-image: url(<?=kis_album::getPhotoFileName('thumbnail', $album, $photo)?>)">
		</a>
		<div class="photo_thumb_detail"> 
		    <div class="description"><?=$photo['description']?$photo['description']:$photo['title']?></div>    
		</div>
		<p class="spacer"></p>
	    </li>
	    <? endforeach; ?>
	</ul>
	<? else: ?>
	<div class="no_record"><?=$kis_lang['norecord']?></div>
	<? endif; ?>

	<p class="spacer"></p>
    </div>
</div>