<span class="content"><?=$question['title']?></span>
<input name="question_titles[]" type="text" size="65" class="input form_check" value="<?=$question['title']?>"/>
<input type="hidden" name="question_types[]" class="input" value="na"/><br/>
<input type="hidden" name="question_choice_count[]" class="input" value="0"/>
<input type="hidden" <?=$attr?> value="na" name="answers[<?=$question['seq']?>][]"/>