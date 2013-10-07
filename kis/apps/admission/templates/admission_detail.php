<div class="main_content">
                            <div class="navigation_bar">
                	<a href="#/apps/admission">2013-2014</a><span>Applications</span>                </div>
                       
                        
                      <p class="spacer"></p>
                        <!--tab end-->
                      <p class="spacer"></p>
		      
<form id="filter_form">                 
    <div id="table_filter">
	<select name="status">
	    <option value="">All Status</option>
	    <option <?=$status=='pending'?'selected':''?> value="pending">Pending</option>
	    <option <?=$status=='processing'?'selected':''?> value="processing">Processing</option>
	</select>
    </div>
    <div class="search"><!--<a href="#">Advanced</a>-->
	<input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text">
    </div>
</form>
                   	
                      <p class="spacer"></p>
                      	  <div class="table_board">
                      	    <table class="common_table_list edit_table_list">
							<colgroup><col nowrap="nowrap">
							</colgroup><thead>
								<tr>
								  <th width="20">#</th>
								  <th>Application #</th>
								  <th>Student Name</th>
								  <th>Parent</th>
								  <th>Phone </th>
								  <th>Payment </th>
								  <th>Status</th>
							  </tr>
							</thead>
							<tbody>
								<tr class="absent">
								  <td>1</td>
								  <td><a href="#">A00120</a><br></td>
								  <td>Chan Siu Ming<br>								    <br></td>
								  <td>Chan Ming Ming<br></td>
								  <td nowrap="nowrap">8745 9789<br></td>
								  <td>Pending</td>
								  <td>Pending</td>
							  </tr>
								<tr class="absent">
								  <td>2</td>
								  <td><a href="#">A00119<br>
								  </a></td>
								  <td>Chan Tai Man<br></td>
								  <td>Lee Ka Yan<br></td>
								  <td>5656 7567<br></td>
								  <td>Pending</td>
								  <td>Pending</td>
							    </tr>
								<tr>
								  <td>3</td>
								  <td><a href="#">B00045<br>
								  </a></td>
								  <td>Chan Ka Yat<br></td>
								  <td>Li On<br></td>
								  <td>8435 4524</td>
								  <td>Confimred</td>
								  <td>Processing (missing doc.)</td>
							    </tr>
								<tr>
								  <td>4</td>
								  <td><a href="#">B00044<br>
								  </a> </td>
								  <td>Chan Ka Yee<br></td>
								  <td>Li On<br>								    <br></td>
								  <td>8435 4524</td>
								  <td>Confimred</td>
								  <td>Processing (missing doc.)</td>
							  </tr>
								<tr>
                                  <td>5</td>
								  <td><a href="#">B00043<br>
								  </a></td>
								  <td>Chan Ka San<br></td>
								  <td>Li On</td>
								  <td>8435 4524</td>
								  <td>Confimred</td>
								  <td>Processing (missing doc.)</td>
							  </tr>
								<tr class="draft">
								  <td>6</td>
								  <td><a href="#">A00118<br>
								  </a></td>
								  <td>Cheung Ming Ming<br></td>
								  <td>Cheung Ho Yau</td>
								  <td>98624567<br></td>
								  <td>Confimred</td>
								  <td>not consider</td>
							  </tr>
								<tr class="draft">
								  <td>7</td>
								  <td><a href="#">A00117<br>
								  </a></td>
								  <td>Cheung Yau Yee<br></td>
								  <td> Lee Sze Ki<br></td>
								  <td>34564321<br></td>
								  <td>Confimred</td>
								  <td>Invaid Record</td>
							  </tr>
								<tr class="waiting">
								  <td>8</td>
								  <td><a href="#">B00042<br>
								  </a></td>
								  <td>Kwok Ka Yan<br></td>
								  <td>Wong Yuen Yi<br></td>
								  <td>56367889<br></td>
								  <td>Confimred</td>
								  <td>Transfered to Next Stage</td>
							  </tr>
								<tr class="draft">
								  <td>9</td>
								  <td><a href="#">A00116<br>
								  </a></td>
								  <td>Ng Chun Kai<br></td>
								  <td>Ng Chun Wai<br></td>
								  <td>84567632<br></td>
								  <td>Pending</td>
								  <td>Invaid Record</td>
							  </tr>
								<tr class="waiting">
								  <td>10</td>
								  <td><a href="#">A00115</a></td>
								  <td>Wong Chow Yi</td>
								  <td>Fong Lik Sun</td>
								  <td>98745321</td>
								  <td>Confimred</td>
								  <td>Transfered to Next Stage</td>
							  </tr>
							</tbody>
						</table>
          <p class="spacer"></p><img src="/images/kis/temp/table_bottom.png"><p class="spacer"></p><br>
        </div>
                    </div>