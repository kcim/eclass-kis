<script>
kis.eattendance.remainderrecord_init({message: '<?=$kis_lang['areyousureto']?> <?=$kis_lang['remove']?>?'})
</script>

<div class="main_content_detail">
    <p class="spacer"></p>
    <div class="Content_tool"><a href="#" class="new"><?=$kis_lang['new']?></a></div>
    <p class="spacer"></p>
    <form class="filter_form">
	<div id="table_filter">
	    <select name="time" class="auto_submit">
		<option value=""><?=$kis_lang['all']?></option>
		<option value="past" <?=$time=='past'?'selected':''?>><?=$kis_lang['past']?></option>
		<option value="coming" <?=$time=='coming'?'selected':''?>><?=$kis_lang['coming']?></option>
	    </select>
	</div>
	<div class="search">
	    <input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	</div>
    </form>

    <p class="spacer"></p>
    <div class="table_board">
	<table class="common_table_list edit_table_list">
	    <colgroup><col>
	    </colgroup>
		<tr>
		    <th width="20">#</th>
		    <th><?=$kis_lang['duedate']?></th>
		    <th><a title="Sort By Student Name"><?=$kis_lang['studentname']?></a></th>
		    <th><a title="Sort By Reason"><?=$kis_lang['reason']?></a></th>
		    <th>&nbsp;</th>
		</tr>
		
		
	</table>
	<div class="no_record"><?=$kis_lang['norecord']?></div>
	<p class="spacer"></p><p class="spacer"></p><br>
    </div>
</div>
                    