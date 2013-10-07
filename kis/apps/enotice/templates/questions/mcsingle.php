<span class="content"><?=$question['title']?></span>

<input name="question_titles[]" type="text" size="65" class="input form_check" value="<?=$question['title']?>"/>
<input type="hidden" name="question_types[]" class="input" value="mcsingle"/>
<input type="hidden" name="question_choice_count[]" class="input" value="<?=sizeof($question['options'])?>"/>


<? foreach ($question['options'] as $i=>$option): ?>
    <div>
    <input type="radio" <?=$attr?> <?=in_array($i, $answers)? 'checked="checked"': ''?> value="<?=$i?>" name="answers[<?=$question['seq']?>][]">
    <span class="content"><?=$option?></span>
    <input type="text" name="question_choices[]" class="input form_check" value="<?=$option?>"/>
  
    <div class="table_row_tool input" <?=$i>1?'':'style="display:none"'?>>
	<a href="#" title="<?=$kis_lang['remove']?>" class="delete"></a>
    </div>

    </div>
<? endforeach; ?>

<a class="add_choice_single input" href="#"><?=$kis_lang['addchoice']?></a>

                   