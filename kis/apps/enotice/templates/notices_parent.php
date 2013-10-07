<p class="spacer"></p>

<form class="filter_form">                 
    <div id="table_filter">
	<select class="auto_submit" name="signed">
	     <option value=""><?=$kis_lang['all'] ?></option>
	     <option <?=$signed=='1'?'selected':''?> value="1"><?=$kis_lang['signednotices']?></option>
	     <option <?=$signed=='-1'?'selected':''?> value="-1"><?=$kis_lang['notyetsignednotices']?></option>
	     
	</select>
	<select class="auto_submit" name="past">
	     <option value="1"><?=$kis_lang['currentnotices']?></option>
	     <option <?=$past=='-1'?'selected':''?> value="-1"><?=$kis_lang['pastnotices']?></option>
	</select>
    </div>
    <div class="search"><!--<a href="#">Advanced</a>-->
	<input name="search" placeholder="<?=$kis_lang['search']?>" value="<?=$search?>" type="text">
    </div>
</form>
			
                      <p class="spacer"></p>
<div class="table_board">
    <table class="common_table_list notice_table">
	<colgroup><col nowrap="nowrap">
	
	</colgroup>
	<tbody>
	    <tr>
		      <th><?=$kis_lang['noticenumber']?></th>
		      <th><?=$kis_lang['noticetitle']?></th>
		      <th><?=$kis_lang['issuedate']?></th>
		      <th><?=$kis_lang['duedate']?></th>
		      <th><?=$kis_lang['status']?></th>
	    </tr>
	    <? if ($notices): ?>
		<? foreach ($notices as $notice): ?>
		<tr>
		    <td><?=$notice['number']?></td>
		    <td><a href="#/apps/enotice/<?=$notice['id']?>/"><?=$notice['title']?>
		    </a>
		    <? if ($notice['status']==0): ?>
			<img height="11" width="28" src="/images/kis/alert_new2.gif">
		    <? endif; ?>
		    </td>
		    <td nowrap="nowrap"><?=$notice['issue_date']?></td>
		    <td><?=$notice['due_date']?></td>
		    <td>
		    <? if ($notice['reply_status']==2): ?>
			<span class="date_time"><?=$notice['modified']?><em><?=$kis_lang['signedby']?> <?=$notice['signer_name_'.$lang]?></em></span>
		    <? else: ?>
			<span style="color:red;"><?=$kis_lang['notsignedyet']?></span>
		    <? endif; ?>
		    </td>
		</tr>
		<? endforeach; ?>
	    </tbody></table>
	    <? else: ?>
	    </tbody></table>
		<div class="no_record">
		   <?=$kis_lang['norecord']?>!
		</div>
	    <? endif; ?>
	    
	
<p class="spacer"></p><p class="spacer"></p><br>
</div>

    <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
