<? kis_ui::loadModuleTab(array('awards','activities'/* ,'readingrecord' */), 'activities', '#apps/iportfolio/schoolrecords/') ?>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
<div class="table_board">
          <table class="common_table_list edit_table_list">
							<colgroup><col nowrap="nowrap">
							</colgroup><thead>
								<tr>
								  <th width="5%">#</th>
								  <th width="13%"><?kis_ui::loadSortButton('schoolyear','schoolyear', $sortby, $order)?></th>
								  <th width="12%"><?kis_ui::loadSortButton('semester','term', $sortby, $order)?></th>
								  <th width="20%"><?kis_ui::loadSortButton('activity_name','activity_name', $sortby, $order)?></th>
								  <th width="10%"><?kis_ui::loadSortButton('role','role', $sortby, $order)?></th>
								  <th width="20%"><?kis_ui::loadSortButton('performance','performance', $sortby, $order)?></th>
								  <th width="20%"><?kis_ui::loadSortButton('organization','organization', $sortby, $order)?></th>
							  </tr>
							</thead>
							<tbody>
					<?php 
						$record_cnt = count($kis_data['activity_record']);
						if($record_cnt>0){
					?>
						<?php for($a=0;$a<$record_cnt;$a++){ ?>
								<tr>
								  <td><?=($a+1)?></td>
								  <td><?=$kis_data['activity_record'][$a]['schoolyear']?></td>
								  <td><?=$kis_data['activity_record'][$a]['semester']?></td>
								  <td><?=$kis_data['activity_record'][$a]['activity_name']?></td>
								  <td><?=$kis_data['activity_record'][$a]['role']?></td>
								  <td><?=$kis_data['activity_record'][$a]['performance']?></td>
								  <td><?=$kis_data['activity_record'][$a]['organization']?></td>
								</tr>
						<?php } ?>
					<?php }else{ ?>
								<tr>
								  <td colspan="7"><?=$kis_lang['norecord']?></td>
								</tr>
					<?php } ?>	
							</tbody>
						</table>
          <p class="spacer"></p>
		  <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>           	
		  <p class="spacer"></p><br>
        </div>