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
					<input type="hidden" name="view" id="view" value="cover">
			</div>
		</form>	
            <div class="toggle_tool">
            	<ul>
	                <li><a id="a_list" href="#" class="view_list" title="<?=$kis_lang['StudentAccount']['StutdentList']?>"></a></li>
                    <li class="selected"><a id="a_cover" href="#" class="view_cover" title="<?=$kis_lang['StudentAccount']['StudentPhoto']?>"></a></li>
                      <li> <span><?=$kis_lang['StudentAccount']['ViewBy']?>:</span> </li>
                </ul>
            </div>	
			 <p class="spacer"></p>
				<!---->
				<?php if($total>0){ ?>
				<div class="ipf_student_list">
					<ul>
					<?php 
						for($i=0;$i<count($kis_data['ClassStudentList']);$i++){
							$_record = $kis_data['ClassStudentList'][$i];
							$_userId = $_record['UserID'];
							$_photoLink = kis::getUserPhoto($_record['UserLogin']);
					?>
						<li id="li_<?=$_userId?>">
							<a href="#/apps/iportfolio/studentaccount/studentinfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>" class="ipf_student_list_photo" style="background-image:url(<?=$_photoLink?>)"></a>
							<span><?=$_record['UserName']?></span>
							<div class="btn_ipf_tool">
								<a href="#/apps/iportfolio/studentaccount/studentInfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>" class="btn_ipf_info" title="<?=$kis_lang['studentinfo']?>"></a>
								<a href="#/apps/iportfolio/studentaccount/studentInfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>&showPage=schoolrecord" class="btn_ipf_schoolrecord" title="<?=$kis_lang['schoolrecords']?>"></a>
								<a href="#/apps/iportfolio/studentaccount/studentInfo/?classId=<?=$kis_data['classId']?>&studentId=<?=$_userId?>&showPage=assessment" class="btn_ipf_assessment"  title="<?=$kis_lang['assessmentreport']?>"></a>
								<a href="/home/portfolio/profile/sbs/index.php?StudentID=<?=$_userId?>" class="btn_ipf_sbs" title="<?=$kis_lang['sbs']?>" target="_blank"></a>
								<a href="/home/portfolio/profile/learning_portfolio_teacher_v2.php?StudentID=<?=$_userId?>" class="btn_ipf_lp" title="<?=$kis_lang['learningportfolio']?>" target="_blank"></a>
							</div>
						</li>
					<?php } ?>
					</ul>
				
				</div>
				<!---->        
				<p class="spacer"></p>
				<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>
				<p class="spacer"></p><br />
				<?php }else{ ?>
					<p class="spacer"></p>
						<div style="text-align:center;">
							<?=$kis_lang['norecord']?>
						</div>          
					<p class="spacer"></p><br />
				<?php } ?>
				
        </div>
    </div>
</div>