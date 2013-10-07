<div class="main_content_detail">

    <? kis_ui::loadModuleTab(array('applicationperiod','applicationform'), '', '#/apps/admission/settings/')?>

    <p class="spacer"></p>
    <div class="Content_tool"><a class="new" href="#"><?=$kis_lang['createnew']?></a></div>
    <p class="spacer"></p>
    <div id="table_filter">
        <select class="year">
            <option value="apps/admission/settings/applicationperiod/2013/">2013-2014</option>
            <option value="apps/admission/settings/applicationperiod/2012/">2012-2013</option>
        </select>
    </div>
                         <!---->
                    	
                      <p class="spacer"></p>
                      	  <div class="table_board">
                      	       <table class="common_table_list edit_table_list">
							<colgroup><col nowrap="nowrap">
							</colgroup><thead>
								<tr>
								  <th width="20">&nbsp;</th>
								  <th><?=$kis_lang['level']?></th>
								  <th><?=$kis_lang['from']?></th>
								  <th><?=$kis_lang['to']?></th>
								  <th width="40">&nbsp;</th>
							  </tr>
								<tr>
                                  <td>1</td>
								  <td><a href="#/apps/admission/applicantslist/2013/1/">N1</a></td>
								  <td>2012-11-30</td>
								  <td>2013-01-30</td>
								  <td><div class="table_row_tool"><a class="tool_edit" href="#"></a></div></td>
							  </tr>
								<tr>
                                  <td>2</td>
								  <td><a href="teacher_admission_list.htm">K1</a></td>
								  <td>2013-02-01</td>
								  <td>2013-03-31</td>
								  <td><div class="table_row_tool"><a class="tool_edit" href="#"></a></div></td>
							  </tr>
								<tr>
                                  <td>3</td>
								  <td><a href="#">K2</a></td>
								  <td>2013-04-01<br></td>
								  <td>2013-04-30</td>
								  <td><div class="table_row_tool"><a class="tool_edit" href="#"></a></div></td>
							  </tr>
							</thead>
							<tbody>
							</tbody>
						</table>
          <p class="spacer"></p><p class="spacer"></p><br>
                      	  </div>
                    	</div>