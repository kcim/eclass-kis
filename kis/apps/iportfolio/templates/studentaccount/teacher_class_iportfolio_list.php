<script>
$(function(){
    kis.iportfolio.teacher_class_iportfolio_init();
	
});
</script>
<?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
<div class="main_content">
    <div class="main_content_detail">
		<?=$kis_data['NavigationBar']?>
		<form class="filter_form">
		<div class="search"> 
			<input type="text" name="keyword" id="keyword" placeholder="<?=$kis_lang['Assessment']['Search']?>" value="<?=$kis_data['keyword']?>"/>
		</div>
		<div class="table_board">
			<p class="spacer"></p>
			<div id="table_filter">
				<?=$kis_data['ClassList']?>
				<input type="hidden" name="view" id="view" value="list">
			</div>
		</form>	
            <div class="toggle_tool">
            	<ul>
	                <li class="selected"><a id="a_list" href="#" class="view_list" title="<?=$kis_lang['StudentAccount']['StutdentList']?>"></a></li>
                    <li><a id="a_cover" href="#" class="view_cover" title="<?=$kis_lang['StudentAccount']['StudentPhoto']?>"></a></li>
                      <li> <span><?=$kis_lang['StudentAccount']['ViewBy']?>:</span> </li>
                </ul>
            </div>	
			 <p class="spacer"></p>
				<!---->
				 <table class="common_table_list edit_table_list">
					<col   nowrap="nowrap"/>
					<thead>
						<tr>
						  <th width="20">#</th>
						  <th><?=$kis_lang['class']?></th>
						  <th><?=$kis_lang['student']?></th>
						  <th><?=$kis_lang['StudentAccount']['PersonalInformation']?></th>
						  <th><?=$kis_lang['StudentAccount']['schoolrecord']?></th>
						  <th><?=$kis_lang['assessmentreport']?></th>
						  <th><?=$kis_lang['sbs']?></th>
						  <th><?=$kis_lang['learningportfolio']?></th>
					  </tr>
					</thead>
					<tbody>
				<?php if($total>0){ ?>
					<?php 
						for($i=0;$i<count($kis_data['ClassStudentList']);$i++){
							$_record = $kis_data['ClassStudentList'][$i];
							$_userId = $_record['UserID'];
							$_photoLink = kis::getUserPhoto($_record['UserLogin']);
					?>
						<tr>
							<td><?=($i+1)?></td>
							<td><?=$_record['ClassName']?></td>
							<td><?=$_record['UserName']?></td>
							<td><div class="btn_ipf_tool"><a href="#/apps/iportfolio/studentaccount/studentinfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>" class="btn_ipf_info"></a></div></td>
							<td><div class="btn_ipf_tool"><a href="#/apps/iportfolio/studentaccount/studentInfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>&showPage=schoolrecord" class="btn_ipf_schoolrecord"></a></div></td>
							<td><div class="btn_ipf_tool"><a href="#/apps/iportfolio/studentaccount/studentInfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>&showPage=assessment" class="btn_ipf_assessment"></a></div></td>
							<td><div class="btn_ipf_tool"><a href="/home/portfolio/profile/sbs/index.php?StudentID=<?=$_userId?>" class="btn_ipf_sbs" target="_blank"></a></div></td>
							<td><div class="btn_ipf_tool"><a href="/home/portfolio/profile/learning_portfolio_teacher_v2.php?StudentID=<?=$_userId?>" class="btn_ipf_lp" target="_blank"></a></div></td>
						</tr>
					<?php } ?>
				<?php }else{ ?>
					<tr>
						<td colspan="7"><?=$kis_lang['norecord']?></td>
					</tr>
				<?php } ?>	
					</tbody>
				</table>
				<!---->        
				<p class="spacer"></p>
				<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
				<p class="spacer"></p><br />
				
				
        </div>
    </div>
</div>