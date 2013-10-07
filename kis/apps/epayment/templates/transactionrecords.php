<script>kis.epayment.transactionrecord_init();</script>
<form class="filter_form">
    <div id="table_filter">
	<select name="recent_days">
	    <option value=""><?=$kis_lang['all']?></option>
	    <option <?=$recent_days==10?'selected="selected"':''?> value="10"><?=$kis_lang['recent']?> 10 <?=$kis_lang['days']?></option>
	    <option <?=$recent_days==30?'selected="selected"':''?> value="30"><?=$kis_lang['recent']?> 30 <?=$kis_lang['days']?></option>
	    <option <?=$recent_days==60?'selected="selected"':''?> value="60"><?=$kis_lang['recent']?> 60 <?=$kis_lang['days']?></option>
	    <option <?=$recent_days=='custom'?'selected="selected"':''?> value="custom"><?=$kis_lang['selectperiod']?></option>
	</select>
	<span class="select_period" <?=$recent_days!='custom'?'style="display:none;"':''?>>
	<?=$kis_lang['from']?>: <input readonly size="15" name="from_date" value="<?=$from_date?>" type="text"/>
	<?=$kis_lang['to']?>: <input readonly size="15" name="to_date" value="<?=$to_date?>" type="text"/>
	</span>
	<input type="submit" value="<?=$kis_lang['view']?>" class="formsmallbutton">
    </div>
    
		    
    <div class="search"><!--<a href="#">Advanced</a>-->
	<input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
    </div>
</form>
<div class="table_board">
    <table class="common_table_list display_table_list">
	<thead>
	    <tr>
		<th width="20">#</th>
		
		<th><? kis_ui::loadSortButton('time','transactiontime', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('add_value_time','addvaluerecordtime', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('type','trantype', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('credit_amount','credit', $sortby, $order)?></a></th>
		<th><? kis_ui::loadSortButton('debit_amount','debit', $sortby, $order)?></a></th>
		<th><?=$kis_lang['details']?></th>
		<th><?=$kis_lang['balance']?></th>
		<th><? kis_ui::loadSortButton('ref_code','refcode', $sortby, $order)?></a></th>
	    </tr>
	</thead>
	<tbody>
	    <? if ($records): ?>
		<? foreach ($records as $i=>$record): ?>
		<tr>
		    <td><?=($page-1)*$amount+$i+1?></td>
		    <td><?=$record['time']?></td>
		    <td><?=$record['add_value_time']?$record['add_value_time']:'--'?></td>
		    <td><?=kis_epayment::$transaction_types[$record['type']]?$kis_lang[kis_epayment::$transaction_types[$record['type']]]:$kis_lang['others']?></td>
		    <td><?=$record['credit_amount']?'$'.$record['credit_amount']:'--'?></td>
		    <td><?=$record['debit_amount']?'$'.$record['debit_amount']:'--'?></td>
		    <td><?=$record['detail']?></td>
		    <td>$<?=$record['balance_after']?></td>
		    <td><?=$record['ref_code']?></td>
		</tr>
		<? endforeach; ?>
	    </tbody></table>
	    <? else: ?>
	    </tbody></table>
		<div class="no_record">
		   <?=$kis_lang['norecord']?>!
		</div>
	    <? endif; ?>
	
    <p class="spacer"></p>
    <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
</div>
                    
                    