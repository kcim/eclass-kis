<script>
$(function(){
    kis.iportfolio.teacher_list_init({
	message: '<?=$kis_lang['areyousureto']?>',
	remove: '<?=strtolower($kis_lang['remove'])?> ',
	please_fill_in: '<?=$kis_lang['please_fill_in']?> ',
	title: '<?=strtolower($kis_lang['assessment_title'])?> ',
	release_date: '<?=strtolower($kis_lang['release_date'])?> ',
	assessment: '<?=strtolower($kis_lang['Assessment']['Assessment'])?> ',
	
	});
	
});
</script>

<p class="spacer"></p>

    <div class="table_board">
        <div class="Content_tool"><a href="#" class="new"><?=$kis_lang['new']?></a></div>
		<!--Filter Form-->
			<form class="filter_form" method="post">   
				<p class="spacer"></p>
				<div id="table_filter">
					<?=$kis_data['ClassList']?>
					<?=$kis_data['StatusSelection']?>
				</div>
				<div class="search"><!--<a href="#">Advanced</a>-->
					<input type="text" name="keyword" class="auto_submit" id="keyword" placeholder="<?=$kis_lang['Assessment']['Search']?>" value="<?=$kis_data['keyword']?>"/>
				</div>
			</form>				
			<p class="spacer"></p>&nbsp;
            <p class="spacer"></p>
			<div id="div_assessment_list"></div>
				<table class="common_table_list">
                        <tr class="sub_table_top">
                          <th><? kis_ui::loadSortButton('title','assessment_title', $sortby, $order)?></th>
                          <th><? kis_ui::loadSortButton('release_date','release_date', $sortby, $order)?></th>
                          <th><? kis_ui::loadSortButton('classname','classname', $sortby, $order)?></th>
                          <th><?=$kis_lang['Assessment']['NoOfUploads']?></th>
						  <th><? kis_ui::loadSortButton('last_update_date','last_update_date', $sortby, $order)?></th>
                          <th>&nbsp;</th>
                        </tr>
				<?php for($i=0;$i<count($kis_data['assessment_list']);$i++){ 
						$_assessmentId = $kis_data['assessment_list'][$i]['id'];
						$_aCnt = $kis_data['uploadCountArr'][$_assessmentId]['assessmentCnt']?$kis_data['uploadCountArr'][$_assessmentId]['assessmentCnt']:0;
						$_sCnt = $kis_data['uploadCountArr'][$_assessmentId]['studentCnt']?$kis_data['uploadCountArr'][$_assessmentId]['studentCnt']:0;
				?>
                        <tr>
                          <td id="title_<?=$_assessmentId?>"><a href="#/apps/iportfolio/assessmentreport/assessment_class/?assessmentId=<?=$_assessmentId?>"><?=$kis_data['assessment_list'][$i]['title']?></a></td>
                          <td><?=$kis_data['assessment_list'][$i]['release_date']?><span class="date_time"><em><?=$kis_data['assessment_list'][$i]['created_user']?></em></span></td>
                          <td><?=$kis_data['assessment_list'][$i]['classname']?></td>
                          <td><span class="common_table_list edit_table_list"><?=$_aCnt?>/<?=$_sCnt?></span></td>
						  <td><?=$kis_data['assessment_list'][$i]['last_update_date']?><span class="date_time"><em><?=$kis_data['assessment_list'][$i]['modified_user']?></em></span></td>
                          <td id="button_<?=$_assessmentId?>"><div class="table_row_tool row_content_tool">
						  <a href="#" class="edit_dim" title="<?=$kis_lang['edit']?>"></a>
						  <a href="#" class="copy_dim" title="<?=$kis_lang['copy']?>"></a>
						  <a href="#" class="delete_dim" title="<?=$kis_lang['delete']?>"></a></div>
                          <div class="table_row_tool row_content_tool"></div></td>
                        </tr>
				<?php } ?>
                      </table>
                    	  <p class="spacer"></p>
                      <?php list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar'];  ?>
						<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?>     
                      	    	
							<p class="spacer"></p><br />
        </div>
	
		<!--FancyBox-->
		
		<div id='create_new_box' style="padding:5px;display:none;" class="pop_edit">
			<div class="pop_title">
				<span><?=$kis_lang['Assessment']['NewAssessment']?></span>
			</div>
			<div class="table_board" style="height:330px">
			<form id="assessmentForm" method="post">
				<table class="form_table">
					<tr>
						<td class="field_title"><?=$kis_lang['Assessment']['Title']?></td>
						<td ><input name="title" type="text" id="title" class="textboxtext" /></td>
					</tr>
					<tr>
						<td class="field_title"><?=$kis_lang['Assessment']['ReleaseDate']?></td>
						<td ><input name="release_date" type="text" id="release_date" size="15" /></td>
					</tr>
				  <tr>
					<td class="field_title"><?=$kis_lang['Assessment']['Target']?></td>
					<td><?=$kis_data['CreateNewClassList']?></td>
				  </tr>
				  <col class="field_title" />
				  <col  class="field_c" />
				</table>
			<input type="hidden" name="type" value="add">
			<input type="hidden" name="assessmentId">
		</form>	
      <p class="spacer"></p>
      </div>
        <div class="edit_bottom">         
           <input id="submitBtn" name="submitBtn" type="button" class="formbutton" value="Submit" />
           <input name="cancelBtn" type="button" class="formsubbutton" onclick="parent.$.fancybox.close();" value="<?=$kis_lang['cancel']?>" />
      </div>
		</div>
