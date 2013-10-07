<script>
kis.sms.messagetemplates_init({message:'<?=$kis_lang['areyousureto']?> <?=$kis_lang['remove']?>?'});
</script>

<div class="main_content_detail">
    <p class="spacer"></p>
    <div class="Content_tool"><a href="#" class="new"><?=$kis_lang['new']?></a></div>
    <p class="spacer"></p>
    <form class="filter_form">
	<div class="table_filter">
            <select name="type" class="auto_submit">
                <option value="1" selected=""><?=$kis_lang['normaleventtemplate']?></option>
		<option <?=$type==2? 'selected':''?> value="2"><?=$kis_lang['preseteventtemplate']?></option>
	    </select>
            <div class="search"><!--<a href="#">Advanced</a>-->
		<input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	    </div>                                                         
        </div>
	
    </form>
    <p class="spacer"></p>
                      
    <div class="table_board">
	<table class="common_table_list edit_table_list">
	    <tr>
		<th> # </th>
		<th><?=$kis_lang['templatename']?></th>
		<th><?=$kis_lang['content']?></th>
		<th><?=$kis_lang['lastmodifed']?></th>
		<th>&nbsp;</th>
	    </tr>
	    <tr>
		<td>1</td>
		<td><a href="#">Template 1</a></td>
		<td>Student Guardian UK is one of the most experienced school placement   consultants.We are experienced in taking care of overseas students from   Asia: Taiwan&nbsp;</td>
		<td>2013-03-10</td>
		<td><div class="table_row_tool"><a href="#" class="edit_dim" title="Delete"></a><a href="#" class="copy_dim" title="Copy"></a><a href="#" class="delete_dim" title="Delete"></a></div></td>
	    </tr>
									
	</table>
	<p class="spacer"></p>
	<form class="filter_form">
	    <? kis_ui::loadPageBar($page, $amount, $total) ?>
	</form>	
	<p class="spacer"></p><br>
    </div>
</div>
                    