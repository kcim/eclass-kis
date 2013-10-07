<div class="main_content_detail">
    <p class="spacer"></p>
    <div class="Content_tool"><a href="#" class="refresh"><?=$kis_lang['refreshallastatus']?></a></div>
    <p class="spacer"></p>
    <form class="filter_form">
	<div class="table_filter">
            <select name="year">
                <option <?=$year==2013? 'selected':''?> value="2013">2013</option>
		<option <?=$year==2012? 'selected':''?> value="2012">2012</option>
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
		<th><?=$kis_lang['month']?></th>
		<th><?=$kis_lang['noofmessage']?></th>
		<th>&nbsp;</th>
	    </tr>
	    <tr>
		<td><a href="#/apps/sms/messages/1/">Message Content</a></td>
		<td>1</td>
		<td><div class="Content_tool"><a href="#" class="refresh"></a></div></td>
	    </tr>
								    
	</table>
	<p class="spacer"></p><p class="spacer"></p><br>
    </div>
</div>
                    