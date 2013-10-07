<form class="filter_form">
        <div class="search"><!--<a href="#">Advanced</a>-->
	    <input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
	</div>
</form>
<div class="table_board">
    <table class="common_table_list display_table_list">
	<thead>
	    <tr>
		<th width="20">#</th>
		<th><? kis_ui::loadSortButton('input_date','posttime', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('time','transactiontime', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('amount','creditamount', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('type','creditmethod', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('ref_code','refcode', $sortby, $order)?></th>
	    </tr>
	</thead>
	<tbody>
	    <? if ($records): ?>
		<? foreach ($records as $i=>$record): ?>
		<tr>
		    <td><?=($page-1)*$amount+$i+1?></td>
		    <td><?=$record['input_date']?></td>
		    <td><?=$record['time']?></td>
		    <td>$<?=$record['amount']?></td>
		    <td><?=kis_epayment::$addvalue_types[$record['type']]?$kis_lang[kis_epayment::$addvalue_types[$record['type']]]:$kis_lang['others']?></td>
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
    <p class="spacer"></p><br>
</div>