 <script>
$(function(){
    kis.iportfolio.teacher_class_list_init();

});
</script>
 <div class="main_content">
                    	<div class="main_content_detail">
                    <!--tab-->
                        	<!--<div class="module_tab">
                            	<ul>
                                	<li class="selected"><a href="teacher_enotice.htm"><span>Notice List</span></a></li>
                                  <li><a href="#"><span>Setting</span></a></li>
                                </ul>
                            </div>-->
                        <!--tab end-->
                        <p class="spacer"></p>
         
			<form class="filter_form" method="post">   
			       <div class="search"><!--<a href="#">Advanced</a>-->
				       <input type="text" name="keyword" class="auto_submit" id="keyword" placeholder="<?=$kis_lang['Assessment']['Search']?>" value="<?=$keyword?>"/>
			       </div>
			</form>	
					
      
                     <!---->
                    	
                      <p class="spacer"></p>
                      	  <div class="table_board">
                      	    <table class="common_table_list edit_table_list">
							<col  />
							<thead>
								<tr>
								  <th width="20">#</th>
								  <th><? kis_ui::loadSortButton('SortClassName','class', $sortby, $order)?></th>
								  <th><? kis_ui::loadSortButton('NewSRCount','school_record_updated', $sortby, $order)?></th>
								  <th><? kis_ui::loadSortButton('AssessmentCount','assessment_report_updated', $sortby, $order)?></th>
								  <th><? kis_ui::loadSortButton('NewLPCount','lpf_updated', $sortby, $order)?></th>
								  <th><? kis_ui::loadSortButton('StudentCount','no_of_students', $sortby, $order)?></th>
							  </tr>
							</thead>
							<tbody>			
							<?php for($i=0;$i<count($kis_data['ClassList']);$i++){ ?>
								<tr>
								  <td><?=($i+1)?></td>
								  <td><?=$kis_data['ClassList'][$i]['ClassName']?></td>
								  <td><?=$kis_data['ClassList'][$i]['NewSRCount']?></td>
								  <td><?=$kis_data['ClassList'][$i]['AssessmentCount']?></td>
								  <td><?=$kis_data['ClassList'][$i]['NewLPCount']?></td>
								  <td><?=$kis_data['ClassList'][$i]['StudentCount']?></td>
								</tr>
							<?php } ?>	
							</tbody>
						</table>
          <p class="spacer"></p>
		  <?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
		  <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
		  <p class="spacer"></p><br />
        </div></div>
                    </div>