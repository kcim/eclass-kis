<form class="filter_form">
                      
    <div id="table_filter">
	<?=$kis_lang['categories']?> : 
	<select name="category">
	    <option value=""> <?=$kis_lang['all']?> </option>
	    <? foreach ($categories as $cat): ?>
	    <option <?=$category==$cat['id']?'selected="selected"':''?> value="<?=$cat['id']?>"><?=$cat['name']?></option>
	    <? endforeach; ?>
	</select>
	<?=$kis_lang['status']?> : 
	<select name="status">
	    <option value=""><?=$kis_lang['all']?> <?=$kis_lang['status']?></option>
	    <option <?=$status==1?'selected="selected"':''?> value="1"><?=$kis_lang['paid']?></option>
	    <option <?=$status==-1?'selected="selected"':''?> value="-1"><?=$kis_lang['unpaid']?></option>
	</select>
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
		<th><? kis_ui::loadSortButton('item_name','item', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('category_name','category', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('amount','amount', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('subsidy','subsidy', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('deadline','deadline', $sortby, $order)?></th>
		<th><? kis_ui::loadSortButton('status','status', $sortby, $order)?></th>
	    </tr>
	</thead>
	<tbody>
	    <? if ($records): ?>
		<? foreach ($records as $i=>$record): ?>
		<tr>
		    <td><?=($page-1)*$amount+$i+1?></td>
		    <td><?=$record['item_name']?></td>
		    <td><?=$record['category_name']?></td>
		    <td>$<?=$record['amount']?></td>
		    <td><?=$record['subsidy']?'$'.$record['subsidy']:'--'?></td>
		    <td><?=$record['deadline']?></td>
		    <td><?=$record['status']?$kis_lang['paid']:$kis_lang['unpaid']?></td>
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
                    
                    