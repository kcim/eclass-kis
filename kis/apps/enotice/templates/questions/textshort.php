<span class="content"><?=$question['title']?></span>
<input name="question_titles[]" type="text" size="65" class="input form_check" value="<?=$question['title']?>"/>
<input type="hidden" name="question_types[]" class="input" value="textshort"/>
<input type="hidden" name="question_choice_count[]" class="input" value="0"/>
<br/>
<input type="text" size="60" <?=$attr?> value="<?=$answers[0]?>" name="answers[<?=$question['seq']?>][]"/>

                   