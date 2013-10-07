<p class="spacer"></p>
<div class="ipf_board">     
    <h1><?=$kis_lang['basicinformation']?></h1>
    <div class="ipf_info_board">
	<div class="ipf_info_detail">                     
	    <table>
		<tbody>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['englishname']?></td>
			<td class="form_field_content"><?=$student_info['user_name_en']?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['chinesename']?></td>
			<td class="form_field_content"><?=$student_info['user_name_b5']?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['gender']?></td>
			<td class="form_field_content"><?=$kis_lang['gender_'.$student_info['gender']]?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['dateofbirth']?></td>
			<td class="form_field_content"><?=$student_info['birth_date']?$student_info['birth_date']:'--'?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['placeofbirth']?></td>
			<td class="form_field_content"><?=$student_info['birth_place']?$student_info['birth_place']:'--'?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['nationality']?></td>
			<td class="form_field_content"><?=$student_info['nationality']?$student_info['nationality']:'--'?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['homephoneno']?></td>
			<td class="form_field_content"><?=$student_info['home_tel']?$student_info['home_tel']:'--'?></td>
		    </tr>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['homeaddress']?></td>
			<td class="form_field_content"><?=$student_info['address']?nl2br($student_info['address']):'--'?>
		    </tr>
		</tbody>
	    </table>
        </div>
	<div class="ipf_info_class">
	    <table>
		<tbody>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['schoolyear']?></td>
			<td class="form_field_name"><?=$kis_lang['class']?></td>
			
		    </tr>
		    <? foreach ($admitted_classes as $admitted_class): ?>
		    <tr>
			<td class="form_field_content"><?=$admitted_class['year_name_'.$lang]?></td>
			<td class="form_field_content"><?=$admitted_class['class_name_'.$lang]?></td>
			
		    </tr>
		    <? endforeach; ?>

		</tbody></table>
	    <p class="spacer"></p>
	    <table>
		<tbody>
		    <tr>
			<td class="form_field_name"><?=$kis_lang['admissiondate']?></td>
			<td class="form_field_content"><?=$student_info['admission_date']?$student_info['admission_date']:'--'?></td>
		    </tr>
		</tbody>
	    </table>
	</div>
	<p class="spacer"></p>
	</div>
    <p class="spacer"></p>
    <p class="spacer"></p>
    
    <h1><?=$kis_lang['guardianinformation']?></h1>
    
    <div class="ipf_info_board_g">
	
	<? foreach ($guardians as $i=>$guardian): ?>
	<div class="ipf_info_g_detail">

	    <h2> (<?=$i==0?$kis_lang['mainguardian']: $kis_lang['guardian'].' '.($i+1)?>)</h2>

	    <table>
	    <tbody>
		<tr>
		    <td class="form_field_name"><?=$kis_lang['englishname']?></td>
		    <td class="form_field_content"><?=$guardian['name_en']?></td>
		</tr>
		<tr>
		    <td class="form_field_name"><?=$kis_lang['chinesename']?></td>
		    <td class="form_field_content"><?=$guardian['name_b5']?$guardian['name_b5']:'--'?></td>
		</tr>
		<tr>
		    <td class="form_field_name"><?=$kis_lang['relationship']?></td>
		    <td class="form_field_content"><?=$kis_lang['StudentAccount']['Relation'][$guardian['relation']]?></td>
		</tr>
		
		<tr>
		    <td class="form_field_name"><?=$kis_lang['phoneno']?></td>
		    <td class="form_field_content"><?=$guardian['tel']?$guardian['tel']:'--'?></td>
		</tr>
		<tr>
		    <td class="form_field_name"><?=$kis_lang['emergencycontactno']?></td>
		    <td class="form_field_content"><?=$guardian['emergency_tel']?></td>
		</tr>
		</tbody>
	    </table> 
	</div>

	<? endforeach; ?>
	 <p class="spacer"></p>                         
    </div>
</div>
<br>