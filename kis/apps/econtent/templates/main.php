<p class="spacer"></p>

<form id="filter_form">                 
    <div class="search"><!--<a href="#">Advanced</a>-->
	<input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
    </div>
</form>
      
 <p class="spacer"></p>
                      	
<div class="subject_list" align="center">
    <? foreach (array_keys($kis_config['econtent']) as $subject): ?>
    <a href="#/apps/econtent/<?=$subject?>/"><img src="resources/econtent/<?=$subject?>/title.png" border="0"></a>
    <? endforeach ;?>
</div>
              