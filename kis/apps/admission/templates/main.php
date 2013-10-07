<div class="main_content">                 
<p class="spacer"></p>  
			
<form id="filter_form">
    <div id="table_filter">
	<select name="year">
	    <option value=""><?=$kis_lang['all'] ?></option>
	    <option <?=$year=='2013'?'selected':''?>  value="2013">2013-2014</option>
	    <option <?=$year=='2012'?'selected':''?>  value="2012">2012-2013</option>
	</select>
    </div>
    <div class="search"><!--<a href="#">Advanced</a>-->
	<input placeholder="<?=$kis_lang['search'] ?>" name="search" value="<?=$search?>" type="text"/>
    </div>
</form>
      
                     <!---->
                    	
                      <p class="spacer"></p>
                      	  <div class="table_board">
                      	    <table class="common_table_list edit_table_list">
							<colgroup><col nowrap="nowrap">
							</colgroup><thead>
								<tr>
								  <th width="20">&nbsp;</th>
								  <th>Stage</th>
								  <th>from </th>
								  <th>to</th>
								  <th>Pending </th>
								  <th>Processing</th>
								  <th>Transfered to <br>
								    next stage</th>
								  <th>Unsuccessful</th>
							  </tr>
							</thead>
							<tbody>
								<tr>
								  <td>1</td>
								  <td><a href="#/apps/admission/1">Applications</a></td>
								  <td>2012-11-30</td>
								  <td>2013-01-30</td>
								  <td><div align="center"><a href="#">110</a></div></td>
								  <td><div align="center"><a href="#">3</a></div></td>
								  <td><div class="style1" align="center">70</div></td>
								  <td><div class="style1" align="center">20</div></td>
							  </tr>
								<tr>
								  <td>2</td>
								  <td><a href="#">1st Interview</a></td>
								  <td>2013-02-01</td>
								  <td>2013-03-31</td>
								  <td><div align="center"><a href="#">48</a></div></td>
								  <td><div align="center"><a href="#">20</a></div></td>
								  <td><div class="style1" align="center">0</div></td>
								  <td><div class="style1" align="center">2</div></td>
								</tr>
								<tr>
								  <td>3</td>
								  <td><a href="#">2nd Interview</a></td>
								  <td>2013-04-01<br></td>
								  <td>2013-04-30</td>
								  <td><div align="center">0</div></td>
								  <td><div align="center">0</div></td>
								  <td><div class="style1" align="center">0</div></td>
								  <td><div class="style1" align="center">0</div></td>
								</tr>
								<tr>
								  <td>4</td>
								  <td><a href="#">Registration</a> </td>
								  <td>2013-05-01<br></td>
								  <td>2013-06-30<br></td>
								  <td><div align="center">0</div></td>
								  <td><div align="center">0</div></td>
								  <td><div class="style1" align="center">0</div></td>
								  <td><div class="style1" align="center">0</div></td>
							  </tr>
								<tr>
                                  <td>5</td>
								  <td><a href="#">Placement</a></td>
								  <td>2013-07-01</td>
								  <td>2013-07-30</td>
								  <td><div align="center">0</div></td>
								  <td><div align="center">0</div></td>
								  <td><div class="style1" align="center">0</div></td>
								  <td><div class="style1" align="center">0</div></td>
							  </tr>
							</tbody>
						</table>
          <p class="spacer"></p><p class="spacer"></p><br>
    </div>
</div>
                    