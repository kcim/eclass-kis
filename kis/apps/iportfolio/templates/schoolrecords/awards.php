<? kis_ui::loadModuleTab(array('awards','activities'/* ,'readingrecord' */), 'awards', '#apps/iportfolio/schoolrecords/') ?>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
                        <!--tab end-->
                   <div class="table_board">
          <table class="common_table_list edit_table_list">
							<colgroup><col nowrap="nowrap">
							</colgroup><thead>
								<tr>
								  <th width="5%">#</th>
								  <th width="10%"><?kis_ui::loadSortButton('schoolyear','schoolyear', $sortby, $order)?></th>
								  <th width="13%"><?kis_ui::loadSortButton('semester','term', $sortby, $order)?></th>
								  <th width="12%"><?kis_ui::loadSortButton('award_date','award_date', $sortby, $order)?></th>
								  <th width="20%"><?kis_ui::loadSortButton('award_name','award_title', $sortby, $order)?></th>
								  <th width="20%"><?kis_ui::loadSortButton('organization','award_organization', $sortby, $order)?></th>
								  <th width="10%"><?kis_ui::loadSortButton('subject_area','subject_area', $sortby, $order)?></th>
								  <th width="10%"><?kis_ui::loadSortButton('remarks','remarks', $sortby, $order)?></th>
							  </tr>
							</thead>
							<tbody>
						<?php 
							$record_cnt = count($kis_data['award_record']);
							if($record_cnt>0){
						?>
							<?php for($a=0;$a<$record_cnt;$a++){ ?>
								<tr>
								  <td><?=($a+1)?></td>
								  <td><?=$kis_data['award_record'][$a]['schoolyear']?></td>
								  <td><?=$kis_data['award_record'][$a]['semester']?></td>
								  <td><?=$kis_data['award_record'][$a]['award_date']?></td>
								  <td><?=$kis_data['award_record'][$a]['award_name']?></td>
								  <td><?=$kis_data['award_record'][$a]['organization']?></td>
								  <td><?=$kis_data['award_record'][$a]['subject_area']?></td>
								  <td><?=$kis_data['award_record'][$a]['remarks']?></td>
							    </tr>
							<?php } ?>			
						<?php }else{ ?>
								<tr>
								  <td colspan="8"><?=$kis_lang['norecord']?></td>
								</tr>
						<?php } ?>	
							</tbody>
						</table>
          <p class="spacer"></p>

		  <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>      
		  <p class="spacer"></p><br>
        </div>
                 