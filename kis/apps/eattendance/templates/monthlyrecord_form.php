<script>
$(function(){
    kis.eattendance.monthlyrecord_form_init({
	pleaseentervalid: '<?=$kis_lang['pleaseentervalid']?>',
	displayinred: '<?=$kis_lang['displayinred']?>'			    
    });
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <form action="/home/eAdmin/StudentMgmt/attendance/report/class_month_full_report.php" target="class_month_full_report">
    <div class="table_board">
	<table class="form_table">
	    <tbody><tr>
		<td class="field_title"><?=$kis_lang['selectclassname']?></td>
		<td><select name="ClassID">
		    <option value=""><?=$kis_lang['all']?></option>
		    <? foreach ($classes as $class): ?>
			<option value="<?=$class['class_id']?>"><?=$class['class_name_'.$lang]?></option>
		    <? endforeach?>
		</select></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['year']?></td>
		<td><select name="Year">
		<? foreach ($years as $year): ?>
		    <option value="<?=$year?>" <?=$year==date('Y')?'selected':''?>><?=$year?></option>
		<? endforeach?>
		</select></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['month']?></td>
		<td><select name="Month">
		  <? for ($i=1; $i <= 12; $i++): ?>
		    <option value="<?=$i?>" <?=date('n')==$i?'selected':''?>><?=$kis_lang['month_'.$i]?></option>
		  <? endfor; ?>
		</select></td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['format']?></td>
		<td>
		    <input name="format" value="0" id="status10" checked="checked" type="radio"/>
		    <label for="status10"><?=$kis_lang['web']?></label>
		    <input name="format" value="1" id="status11" type="radio"/>
		    <label for="status11"><?=$kis_lang['excel']?></label>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['hidedatewithoutdata']?></td>
		<td><input name="HideNoData" value="1" id="status8" type="radio">
		    <label for="status8"><?=$kis_lang['yes']?></label>
		    <input name="HideNoData" value="0" id="status9" checked="checked" type="radio">
		    <label for="status9"><?=$kis_lang['no']?></label>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['studentstatus']?></td>
		<td>
		    <input name="StudentStatus" value="0,1,2,3" id="status6" checked="checked" type="radio">
		    <label for="status6"><?=$kis_lang['all']?></label>
		    <input name="StudentStatus" value="0,1,2" id="status7" type="radio">
		    <label for="status7"><?=$kis_lang['active']?> + <?=$kis_lang['suspended']?></label>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['displayinred']?></td>
		<td>
		    <input name="AbsentDay" id="textfield4" size="10" value="0" type="text">
		    <label><?=$kis_lang['days']?>  (<?=$kis_lang['input0todisable']?>)</label>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['showallcloumns']?></td>
		<td>
		    <input name="ShowAllColumns" value="1" id="status4" type="radio">
		    <label for="status4"><?=$kis_lang['yes']?></label>
		    <input name="ShowAllColumns" value="0" id="status5" checked="checked" type="radio">
		    <label for="status5"><?=$kis_lang['no']?> </label>
		</td>
	    </tr>
	    <tr id="OptionsLayer">
		<td class="field_title"></td>	
		<td>
		    <div>
			<input type="checkbox" value="1" id="ChineseName" name="ColumnChineseName"><label for="ChineseName"><?=$kis_lang['chinesename']?></label><br>
			<input type="checkbox" value="1" id="EnglishName" name="ColumnEnglishName"><label for="EnglishName"><?=$kis_lang['englishname']?></label><br>
			<input type="checkbox" value="1" id="Gender" name="ColumnGender"><label for="Gender"><?=$kis_lang['gender']?></label><br>
			<input type="checkbox" value="1" id="Data" name="ColumnData"><label for="Data"><?=$kis_lang['datadistribution']?></label><br>
			<input type="checkbox" value="1" id="DailyStat" name="ColumnDailyStat"><label for="DailyStat"><?=$kis_lang['dailystatistics']?></label><br>
			<input type="checkbox" value="1" id="SchoolDays" name="ColumnSchoolDays"><label for="SchoolDays"><?=$kis_lang['schooldaysstatistics']?></label><br>
			<input type="checkbox" value="1" id="MonthlyStat" name="ColumnMonthlyStat"><label for="MonthlyStat"><?=$kis_lang['monthlystatistics']?></label><br>
			<input type="checkbox" value="1" id="Remark" name="ColumnRemark"><label for="Remark"><?=$kis_lang['remark']?></label><br>
			<input type="checkbox" value="1" id="ReasonStat" name="ColumnReasonStat"><label for="ReasonStat"><?=$kis_lang['customizedreasonstatistics']?></label><br>
			<input type="checkbox" value="1" id="Session" name="ColumnSession"><label for="Session"><?=$kis_lang['sessionstatistics']?></label><br>
		    </div>
		</td>
	    </tr>
	    
	    </tbody>
	    <colgroup>
		<col class="field_title">
		<col class="field_c">
	    </colgroup>
	</table>
	<p class="spacer"></p>
    </div>
    <input type="hidden" name="AcademicYearID" value="<?=$current_academic_year_id?>"/>
    <div class="edit_bottom">
        <input class="formbutton" value="<?=$kis_lang['submit']?>" type="submit">
        <input class="formsubbutton" value="<?=$kis_lang['reset']?>" type="reset">
    </div>
    </form>
</div>
                    