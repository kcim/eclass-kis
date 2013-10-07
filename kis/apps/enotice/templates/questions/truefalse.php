<span class="content"><?=$question['title']?></span>
<input name="question_titles[]" type="text" size="65" class="input form_check" value="<?=$question['title']?>"/>
<input type="hidden" name="question_types[]" class="input" value="truefalse"/>
<input type="hidden" name="question_choice_count[]" class="input" value="0"/>
<br/>

<input type="radio" <?=$attr?> <?=$answers=='Y'? 'checked="checked"': ''?> value="Y" name="answers[<?=$question['seq']?>]"><?=$kis_lang['yes']?>
<br/>
<input type="radio" <?=$attr?> <?=$answers=='N'? 'checked="checked"': ''?> value="N" name="answers[<?=$question['seq']?>]"><?=$kis_lang['no']?>
                   