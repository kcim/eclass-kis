<div class="main_content_detail">

    <div class="navigation_bar"><a href="#/apps/admission/applicantslist/2013/">2013-2014</a><span><?=$kis_lang['applications']?></span></div>
                       
                        
    <p class="spacer"></p>
    <div class="Content_tool"><a class="new" href="#/apps/admission/applicantslist/2013/1/edit/"><?=$kis_lang['createnew']?></a><a class="export" href="#"><?=$kis_lang['export']?></a></div>
    <div class="table_board">
    
	<form class="filter_form">
	    <div id="table_filter">
		<select name="status" class="auto_submit">
		    <option value=""><?=$kis_lang['all']?></option>
		    <option <?=$status=='pending'?'selected="selected"':''?> value="pending"><?=$kis_lang['pending']?></option>
		    <option <?=$status=='settled'?'selected="selected"':''?> value="settled"><?=$kis_lang['paymentsettled']?></option>
		    <option <?=$status=='waiting'?'selected="selected"':''?> value="waiting"><?=$kis_lang['waitingforinterview']?></option>
		    <option <?=$status=='confirmed'?'selected="selected"':''?> value="confirmed"><?=$kis_lang['confirmed']?></option>
		</select>
	    </div>
	    
			    
	    <div class="search"><!--<a href="#">Advanced</a>-->
		<input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
	    </div>
	</form>
	<p class="spacer"></p>
                          <div class="common_table_tool">
                          	 <a class="tool_set fancybox fancybox_small fancybox.iframe" href="#"><?=$kis_lang['changestatus']?></a>
                          </div>
                          <p class="spacer"></p>
                      	    <table class="common_table_list edit_table_list">
							<colgroup><col nowrap="nowrap">
							</colgroup><thead>
								<tr>
								  <th width="20">#</th>
								  <th><?=$kis_lang['application']?> #</th>
								  <th><?=$kis_lang['studentname']?></th>
								  <th><?=$kis_lang['parent']?></th>
								  <th><?=$kis_lang['phone']?></th>
								  <th><?=$kis_lang['status']?></th>
								  <th width="20"><label>
								    <input type="checkbox" id="checkbox" name="checkbox">
								  </label></th>
							  </tr>
							</thead>
							<tbody>
								<tr class="absent">
								  <td>1</td>
								  <td><a href="#/apps/admission/applicantslist/2013/1/1/">A00120</a><br></td>
								  <td>Chan Siu Ming<br>								    <br></td>
								  <td>Chan Ming Ming<br></td>
								  <td nowrap="nowrap">8745 9789<br></td>
								  <td>Pending</td>
								  <td><input type="checkbox" id="checkbox2" name="checkbox2"></td>
							  </tr>
								<tr class="absent">
								  <td>2</td>
								  <td><a href="#">A00119<br>
								  </a></td>
								  <td>Chan Tai Man<br></td>
								  <td>Lee Ka Yan<br></td>
								  <td>5656 7567<br></td>
								  <td>Pending</td>
								  <td><input type="checkbox" id="checkbox3" name="checkbox3"></td>
							    </tr>
								<tr>
								  <td>3</td>
								  <td><a href="#">B00045<br>
								  </a></td>
								  <td>Chan Ka Yat<br></td>
								  <td>Li On<br></td>
								  <td>8435 4524</td>
								  <td>Payment Settled</td>
								  <td><input type="checkbox" id="checkbox4" name="checkbox4"></td>
							    </tr>
								<tr>
								  <td>4</td>
								  <td><a href="#">B00044<br>
								  </a> </td>
								  <td>Chan Ka Yee<br></td>
								  <td>Li On<br>								    <br></td>
								  <td>8435 4524</td>
								  <td>Payment Settled</td>
								  <td><input type="checkbox" id="checkbox5" name="checkbox5"></td>
							  </tr>
								<tr class="waiting">
                                  <td>5</td>
								  <td><a href="#">B00043<br>
								  </a></td>
								  <td>Chan Ka San<br></td>
								  <td>Li On</td>
								  <td>8435 4524</td>

								  <td>Waiting for Interview</td>
								  <td><input type="checkbox" id="checkbox6" name="checkbox6"></td>
							  </tr>
								<tr class="waiting">
								  <td>6</td>
								  <td><a href="#">A00118<br>
								  </a></td>
								  <td>Cheung Ming Ming<br></td>
								  <td>Cheung Ho Yau</td>
								  <td>98624567<br></td>
								  <td>Waiting for Interview</td>
								  <td><input type="checkbox" id="checkbox7" name="checkbox7"></td>
							  </tr>
								<tr class="draft">
								  <td>7</td>
								  <td><a href="#">A00117<br>
								  </a></td>
								  <td>Cheung Yau Yee<br></td>
								  <td> Lee Sze Ki<br></td>
								  <td>34564321<br></td>
								  <td>Out</td>
								  <td><input type="checkbox" id="checkbox8" name="checkbox8"></td>
							  </tr>
								<tr class="done">
								  <td>8</td>
								  <td><a href="#">B00042<br>
								  </a></td>
								  <td>Kwok Ka Yan<br></td>
								  <td>Wong Yuen Yi<br></td>
								  <td>56367889<br></td>
								  <td>Confirmed</td>
								  <td><input type="checkbox" id="checkbox9" name="checkbox9"></td>
							  </tr>
								<tr class="done">
								  <td>9</td>
								  <td><a href="#">A00116<br>
								  </a></td>
								  <td>Ng Chun Kai<br></td>
								  <td>Ng Chun Wai<br></td>
								  <td>84567632<br></td>
								  <td>Confirmed</td>
								  <td><input type="checkbox" id="checkbox10" name="checkbox10"></td>
							  </tr>
								<tr class="done">
								  <td>10</td>
								  <td><a href="#">A00115</a></td>
								  <td>Wong Chow Yi</td>
								  <td>Fong Lik Sun</td>
								  <td>98745321</td>
								  <td>Confirmed</td>
								  <td><input type="checkbox" id="checkbox11" name="checkbox11"></td>
							  </tr>
							</tbody>
						</table>
          <p class="spacer"></p>
	   <? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
       
        </div>
                    </div>