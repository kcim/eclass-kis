<div id="table_filter">
    <select class="year">
	
	<? for ($i = $current_date['year']-2; $i <= $current_date['year']; $i++): ?>
	    <option value="/apps/eattendance/<?=$i?>/" <?=$i==$year?'selected="selected"':''?>><?=$i?></option>
	<? endfor; ?>

    </select>
    
</div>
                      

<p class="spacer"></p>
<div class="table_board">
  <table class="common_table_list edit_table_list">
	<colgroup><col nowrap="nowrap">
	</colgroup><thead>
		<tr>
		  <th><?=$kis_lang['month']?></th>
		  <th><?=$kis_lang['present']?></th>
		  <th><?=$kis_lang['late']?></th>
		  <th><?=$kis_lang['earlyleave']?></th>
		  <th><?=$kis_lang['absent']?></th>
		  <th><?=$kis_lang['outing']?></th>
	  </tr>
	</thead>
	<tbody>
	    <? for ($i = 1; $i <= ($year==$current_date['year']? $current_date['mon']: 12); $i++): ?>
	    <tr>
                <td><a href="#/apps/eattendance/<?=$year?>/<?=$i?>/">
		     <?=$year?> <?=$kis_lang['month_'.$i]?>
		</a></td>
		<td class="row_present"><div align="center"><?=$summary_months[$i]['present']?></div></td>
		<td class="row_present_sub"><div align="center"><?=$summary_months[$i]['late']?></div></td>
		<td class="row_present_earlyleave"><div align="center"><?=$summary_months[$i]['early_leave']?></div></td>
		<td class="row_absent"><div align="center"><?=$summary_months[$i]['absent']?></div></td>
		<td class="row_outing"><div align="center"><?=$summary_months[$i]['outing']?></div></td>
	    </tr>
	    <?
		$total['present'] += $summary_months[$i]['present'];
		$total['late'] += $summary_months[$i]['late'];
		$total['early_leave'] += $summary_months[$i]['early_leave'];
		$total['absent'] += $summary_months[$i]['absent'];
		$total['outing'] += $summary_months[$i]['outing'];
	    ?>
	    <? endfor; ?>
	    <tr>
		<td style="background:#cccccc">&nbsp;</td>
		<td><div align="center"><strong><?=$total['present']?></strong></div></td>
		<td><div align="center"><strong><?=$total['late']?></strong></div></td>
		<td><div align="center"><strong><?=$total['early_leave']?></strong></div></td>
		<td><div align="center"><strong style="color:red;"><?=$total['absent']?></strong></div></td>
		<td><div align="center"><strong><?=$total['outing']?></strong></div></td>
	</tr>
	</tbody>
    </table>
    <p class="spacer"></p>
    <p class="spacer"></p><br>

</div>
                    
                    