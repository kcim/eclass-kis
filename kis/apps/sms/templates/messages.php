<script>
kis.sms.messages_init();
</script>

<div class="main_content_detail">
    <p class="spacer"></p>
        <div class="Content_tool">
	    <a href="#/apps/sms/messages/new/" class="new"><?=$kis_lang['new']?></a>
	    <a href="#" class="export"><?=$kis_lang['export']?></a>
	    <a href="#" class="refresh"><?=$kis_lang['refreshallastatus']?></a>
	</div>
	
    <form class="filter_form">                 
	<div class="search"><!--<a href="#">Advanced</a>-->
	    <input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	</div>
    </form>
    <p class="spacer"></p>
                      
    
    <fieldset class="instruction_box">
	<legend><?=$kis_lang['sms_legend']?></legend>
	<ul>
	    <li><?=$kis_lang['sms_legend_1']?></li>
	    <li><?=$kis_lang['sms_legend_2']?></li>
	</ul>
    </fieldset>
    
    <p class="spacer"></p>
    <div class="table_board">
	<div class="table_filter">
	    <div class="selectbox_group selectbox_group_filter">
		<a><?=$kis_lang['select']?> <?=$kis_lang['status']?></a>
	    
	    <p class="spacer"></p>
	    <form id="status_option" class="filter_form selectbox_layer selectbox_group_layer">
		<input name="status" <?=!$status || in_array(1, (array)$status)? 'checked':''?> value="1" id="status_1" type="checkbox"/>
		<label for="status_1"><?=$kis_lang['pending']?></label><br>
		<input name="status" <?=!$status || in_array(2, (array)$status)? 'checked':''?> value="2" id="status_2" type="checkbox"/>
		<label for="status_2"><?=$kis_lang['delivered']?></label><br>
		<input name="status" <?=!$status || in_array(3, (array)$status)? 'checked':''?> value="3" id="status_3" type="checkbox"/>
		<label for="status_3"><?=$kis_lang['failed']?></label><br>
		<input name="status" <?=!$status || in_array(4, (array)$status)? 'checked':''?> value="4" id="status_4" type="checkbox"/>
		<label for="status_4"><?=$kis_lang['multiplestatus']?></label>
					    
		<p class="spacer"></p>
		<div class="edit_bottom"> 
		    <input class="formsmallbutton" value="<?=$kis_lang['view']?>" type="submit">
		    <input class="formsmallbutton select_all" value="<?=$kis_lang['selectall']?>" type="button">
		    <input class="formsmallbutton unselect_all" value="<?=$kis_lang['unselectall']?>" type="button">
		</div>
	    </form></div>
	</div>
	<table class="common_table_list edit_table_list">
	    <tr>
		<th> # </th>
		<th><?=$kis_lang['message']?></th>
		<th><?=$kis_lang['recipients']?></th>
		<th><?=$kis_lang['createdby']?></th>
		<th><?=$kis_lang['createdtime']?></th>
		<th><?=$kis_lang['status']?></th>
	    </tr>
	    <tr>
		<td>1</td>
		<td><a href="#/apps/sms/messages/1/">Message Content</a></td>
	     <td>1</td>
		<td>Miss Chan</td>
		<td>2013-03-10</td>
		<td>Delivered&nbsp;</td>
	    </tr>
	    <tr>
		<td>2</td>
		<td><a href="#">[Multi-message]</a></td>
		<td>200</td>
		<td>Miss Chan</td>
		<td>2013-03-10</td>
		<td class="tabletextrequire">Fail to Send SMS</td>
	    </tr>
									
	</table>
	<p class="spacer"></p><p class="spacer"></p><br>
    </div>
</div>
                    