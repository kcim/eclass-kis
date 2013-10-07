<script>
$(function(){
    kis.iportfolio.sbs_init();
});
</script>
		<?php 
			$schoolBasedSchemeRecordCnt = count($kis_data['schoolbasescheme_record']);
			if($schoolBasedSchemeRecordCnt>0){
				for($r=0;$r<$schoolBasedSchemeRecordCnt;$r++){ 
					$_record = $kis_data['schoolbasescheme_record'][$r];
					$_cnt = count($_record['PhaseData']);
					$_title = $_record['title'];						
		?>
					<div class="table_board sbs_table_board">
							<h1><?=$_title?><!--img src="/images/kis/alert_new2.gif" align="texttop" border="0"--></h1>
							<table class="common_table_list sbs_table">
							<colgroup><col nowrap="nowrap">
							</colgroup>
							<tbody>
								<tr>
									  <th width="5%"><?=$kis_lang['SchoolBasedScheme']['Phase']?></th>
									  <th width="30%"><?=$kis_lang['SchoolBasedScheme']['Title']?></th>
									  <th width="20%"><?=$kis_lang['SchoolBasedScheme']['From']?></th>
									  <th width="20%"><?=$kis_lang['SchoolBasedScheme']['to']?></th>
									  <th width="10%"><?=$kis_lang['SchoolBasedScheme']['Target']?></th>
									  <th width="15%"><?=$kis_lang['SchoolBasedScheme']['Status']?></th>
								</tr>
			<?php
				$__sbsBox = "";
				for($s=0;$s<$_cnt;$s++){ 
					$__phaseRecord = $_record['PhaseData'][$s];
					$__phaseTitle = $__phaseRecord['title'];
					$__assignmentId = $__phaseRecord['assignment_id'];
					$__parentId = $__phaseRecord['parent_id'];
					$__startTime = date('Y-m-d',strtotime($__phaseRecord['starttime']));
					$__endTime = date('Y-m-d',strtotime($__phaseRecord['deadline']));
					$__markingScheme = $__phaseRecord['marking_scheme'];
					$__status = $__phaseRecord['status'];
					$__role = $kis_data['lpf']->GET_ROLE_TYPE($__markingScheme);
					$__trId = 'tr_'.$__parentId.'_'.$__assignmentId;
					switch($__status){
						case "on_edit":
								$__phaseCss = 'class="phase_active"';
								$__displayStatus = $kis_lang['SchoolBasedScheme']['InProgress'];
								$__displayStatus .= '<p class="spacer"></p><div class="common_table_tool"><a href="#" class="tool_edit">'.$kis_lang['LearningPortfolio']['FillIn'].'</a></div>';
								$__showDetail = true;
							break;					
						case "on":
								$__phaseCss = '';
								$__displayStatus = $kis_lang['SchoolBasedScheme']['InProgress'];
								$__showDetail = true;
							break;
						case "done":
								$__phaseCss = 'class="phase_finihsed"';
								$__displayStatus = $kis_lang['SchoolBasedScheme']['Finished'];
								$__showDetail = true;
							break;
						default:	
								$__phaseCss = '';	
								$__displayStatus = $kis_lang['SchoolBasedScheme']['Pending'];
								$__showDetail = false;	
					}
					$__sbsBox .= ($__showDetail)?"<div class='sbs_edit_box_".$__parentId."' id='sbs_edit_box_".$__parentId."_".$__assignmentId."' style='padding:5px;display:none;'></div>":"";					
			?>
								<tr <?=$__phaseCss?> id="<?=$__trId?>">
									  <td><div align="center"><?=($s+1)?></div></td>
								<?php if($__showDetail){ ?>
									  <td><a href="#" class="phaseTitle"><?=$__phaseTitle?></a></td>
								<?php }else{ ?>
									  <td><?=$__phaseTitle?></td>
								<?php } ?>
									  <td><?=$__startTime?></td>
									  <td><?=$__endTime?></td>
									  <td><?=$__role?></td>
									  <td><?=$__displayStatus?><br></td>
								</tr>
			<?php } ?>		
							</tbody></table>
							<p class="spacer"></p><p class="spacer"></p><br>
						</div>
			<?=$__sbsBox?>		
		<?php 	}
			}else{ ?>	
			<p class="spacer"></p>
			<? kis_ui::loadNoRecord() ?>
			<p class="spacer"></p>
			
		<?php } ?>
	
                   
                    
                    