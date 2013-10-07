<script>
$(function(){
    kis.enotice.notice_result_class_init();
});
</script>
<div class="main_content_detail">
    <span style="display:none;" class="notice_title"><?=$notice['number']?> - <?=$notice['title']?></span>

    <? if ($has_read_rights): ?>
	   <? kis_ui::loadModuleTab(array('notice','signresult'), 'signresult', '#/apps/enotice/noticelist/'.$notice['id'].'/' )?>
	    <p class="spacer"></p>
    <? endif; ?>
        
    <div class="table_board">
    
	<table class="common_table_list edit_table_list">

	    <tr>
		<th><?=$kis_lang['class']?></th>
		<th><?=$kis_lang['studentname']?></th>
		<? foreach ($notice['questions'] as $i=>$question): ?>
		<th><strong><?=$i+1?>. <?=$question['title']?></strong></th>
		<? endforeach ?>
		<th><?=$kis_lang['signedby']?></th>
		<th><?=$kis_lang['signedat']?></th>
	    </tr>
	<? if ($class_results): ?>
	    <? foreach ($class_results as $i=>$result): ?>
	    <tr>
		<td><?=$class['class_name_'.$lang]?></td>
		<td><a href="#/apps/enotice/noticelist/<?=$notice['id']?>/editreply/?student_id=<?=$result['student_user_id']?>">
		<?=$result['student_user_name_'.$lang]?>
		</a></td>
		
		<? foreach ($notice['questions'] as $i=>$question): ?>
		<td>
		    <? if ($result['status']<2): ?>
			--
		    <? else: ?>
			<? if ($question['options']): ?>
			    <? foreach ($result['answers'][$i] as $option): ?>
				<?=$question['options'][$option]?><br/>
				
			    <? endforeach; ?>
			
			<? else: ?>
			    <?=$result['answers'][$i]?>
			<? endif; ?>
		    <? endif; ?>
		</td>
		<? endforeach ?>
		<td><?=$result['signer_user_id']?$result['signer_user_name_'.$lang]:'--'?></td>
		<td><?=$result['signer_user_id']?$result['modified']:'--'?></td>
	    </tr>
	    <? endforeach; ?>
	    		
	 </table>
	<? else: ?>
	</table><div class="no_record"><?=$kis_lang['norecord']?> </div>
	<? endif; ?>
	
    </div>
             <p class="spacer"></p>&nbsp;
</div>
                    