<script>
$(function(){    
    kis.myaccount.changepassword_init({
	passwordchanged: '<?=$kis_lang['passwordchanged']?>'
    });
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <form id="change_pw_form">
    <div class="table_board">
	<table class="form_table">
	    <colgroup><col class="field_title"><col class="field_c"></colgroup>
	    <tr>
		<td class="field_title"><?=$kis_lang['oldpassword']?></td>
		<td>
		    <input name="old_password" size="15" type="password">
		    <span class="tabletextrequire" id="incorrect_password" style="display:none"><?=$kis_lang['incorrectpassword']?>!</span>

		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['newpassword']?> </td>
		<td><input name="password" size="15" type="password">
		    <span class="text_remark">(<?=$kis_lang['atleast6characters']?>)</span>
		    <span class="tabletextrequire" id="password_too_short" style="display:none"><?=$kis_lang['passwordtooshort']?>!</span>
		    <br/>
		    <span class="text_remark" style="display:none"><?=$kis_lang['passwordsecurity']?>:
			<em id="pw_low" style="color:red"><?=$kis_lang['low'] ?></em>
			<em id="pw_mid" style="color:brown"><?=$kis_lang['medium']?></em>
			<em id="pw_high" style="color:green"><?=$kis_lang['high']?></em>
		     
		    </span>
		      
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['retypepassword']?></td>
		<td>
		    <input name="password2" size="15" type="password">
		    <span class="tabletextrequire" id="password_not_match" style="display:none"><?=$kis_lang['passwordnotmatch']?>!</span>
		</td>
	    </tr>
	</table>
	<p class="spacer"></p><br>
    </div>
    <div class="edit_bottom">
	<input class="formbutton" value="<?=$kis_lang['submit']?>" type="button">
	<!--<input class="formsubbutton" onclick="history.go(-1)" value="<?=$kis_lang['cancel']?>" type="button">-->
    </div>
    <input name="token" value="<?=$token?>" type="hidden">
    </form>
</div>                 