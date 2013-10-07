<script>
$(function(){
    kis.enotice.notices_teacher_init({message: '<?=$kis_lang['areyousureto']?><?=$kis_lang['removethisrecord']?>'});
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <? if ($can_create_notice): ?>
    <div class="Content_tool">
	<a href="#/apps/enotice/noticelist/edit/" class="new"><?=$kis_lang['createnew']?></a>
    </div>
    <? endif; ?>
    <p class="spacer"></p>
    
    <form class="filter_form">  
	<div id="table_filter">
		    
	<select name="status" class="auto_submit">
	    <option value=""><?=$kis_lang['all']?></option>
	    <option <?=$status=='1'?'selected':''?> value="1"><?=$kis_lang['distributednotices']?></option>
	    <option <?=$status=='2'?'selected':''?> value="2"><?=$kis_lang['pendingnotices']?></option>
	    <option <?=$status=='3'?'selected':''?> value="3"><?=$kis_lang['templates']?></option>
	</select>
	</div>
		     
	 <div class="search"><!--<a href="#">Advanced</a>-->
	    <input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
	</div>
    </form>
    
    <p class="spacer"></p>
    <div class="table_board">
	<table class="common_table_list edit_table_list">
	    <colgroup>
		<col style="width:10%"/>
		<col style="width:40%"/>
		<col style="width:8%"/>
		<col style="width:8%"/>
		<col style="width:8%"/>
		<col style="width:8%"/>
		<col style="width:10%"/>
		<col style="width:8%"/>
	    </colgroup>
	    <tr>
		<th><? kis_ui::loadSortButton('number','noticenumber', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('title','noticetitle', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('issue_date','issuedate', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('due_date','duedate', $sortby, $order)?></th>
		<th><?=$kis_lang['signed']?> / <?=$kis_lang['total']?></th>
		<th><?=$kis_lang['replystats']?></th>
		<th><?=$kis_lang['audience']?></th>
		<th>&nbsp;</th>
	    </tr>
		
	    <? if ($notices): ?>
		<? foreach ($notices as $notice): ?>
		<tr>
		    <td class="notice_number" ><?=$notice['number']?></td>
		    <td><a href="#/apps/enotice/noticelist/<?=$notice['id']?>/"><?=$notice['title']?>
		    </a></td>
		    <td nowrap="nowrap"><?=$notice['issue_date']?></td>
		    <td><?=$notice['due_date']?></td>
		    
		    <? if (($notice['is_issuer'] || $has_normal_rights) && $notice['status']==1): ?>
		    <td>
			<?=$notice['total_signed_students']?> / <?=$notice['total_issued_students']?>
		    </td>
		    <td>
			<a href="#/apps/enotice/noticelist/<?=$notice['id']?>/signresult/"><?=$kis_lang['view']?></a>
		    </td>
		    <? else: ?>
		    <td>--</td><td>--</td>
		    <? endif; ?>
		   
		    <td><?=$kis_lang[kis_enotice::$notice_types[$notice['type']]]?>
		    <td width="80">
			<div class="table_row_tool">
			    <? if ($has_full_rights || $notice['is_issuer']): ?>
			    <a title="<?=$kis_lang['edit']?>" class="edit_dim" href="#/apps/enotice/noticelist/edit/?notice_id=<?=$notice['id']?>"></a>
			    <? endif; ?>
			    <? if ($can_create_notice): ?>
			    <a title="<?=$kis_lang['copy']?>" class="copy_dim" href="#/apps/enotice/noticelist/edit/?notice_id=<?=$notice['id']?>&action=copy"></a>
			    <? endif; ?>
			    <? if ($has_full_rights || $notice['is_issuer']): ?>
			    <a class="delete_dim" title='<?=$kis_lang['remove']?> "<?=$notice['title']?>"' href="#">
				<input type="hidden" class="notice_id" value="<?=$notice['id']?>">
			    </a>
			    <? endif; ?>
			</div>
		    </td>
		</tr>
		<? endforeach; ?>
	    </table>
	    <? else: ?>
	    </table>
		<div class="no_record">
		   <?=$kis_lang['norecord']?>!
		</div>
	    <? endif; ?>
	<p class="spacer"></p><p class="spacer"></p><br>
    </div>
    
    <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
    
</div>
                    