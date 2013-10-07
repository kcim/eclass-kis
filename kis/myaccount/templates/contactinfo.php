<script>
$(function(){
    kis.myaccount.contactinfo_init({
	recordsupdated: '<?=$kis_lang['recordsupdated']?>'
    });
});
</script>
<div class="main_content_detail">
    <p class="spacer"></p>
    <form>
    <div class="table_board">
	<table class="form_table">
	    <colgroup><col class="field_title"/><col class="field_c"/></colgroup>
	    <tr>
		<td class="field_title"><?=$kis_lang['hometel']?></td>
		<td>
		<input name="home_tel" size="15" type="text" value="<?=$user_detail['home_tel']?>" <?=$permission["CanUpdateHomeTel"]?'':"disabled ='disabled'"?>/>
		<input type="hidden" name="home_tel" value="<?=$user_detail['home_tel']?>" <?=$permission["CanUpdateHomeTel"]?"disabled ='disabled'":""?>/>
		</td>
		</tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['officetel']?></td>
		<td>
		<input name="office_tel" size="15" type="text" value="<?=$user_detail['office_tel']?>" <?=$permission["CanUpdateOfficeTel"]?'':"disabled ='disabled'"?>/>
		<input type="hidden" name="office_tel" value="<?=$user_detail['office_tel']?>" <?=$permission["CanUpdateOfficeTel"]?"disabled ='disabled'":""?>/>
		</td>
		</tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['mobiletel']?></td>
		<td>
		<input name="mobile_tel" size="15" type="text" value="<?=$user_detail['mobile_tel']?>" <?=$permission["CanUpdateMobile"]?'':"disabled ='disabled'"?>/>
		<input type="hidden" name="mobile_tel" value="<?=$user_detail['mobile_tel']?>" <?=$permission["CanUpdateMobile"]?"disabled ='disabled'":""?>/>
		</td>
		</tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['fax']?></td>
		<td>
		<input name="fax" size="15" type="text" value="<?=$user_detail['fax']?>" <?=$permission["CanUpdateFax"]?'':"disabled ='disabled'"?>/>
		<input type="hidden" name="fax" value="<?=$user_detail['fax']?>" <?=$permission["CanUpdateFax"]?"disabled ='disabled'":""?>/>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['address']?></td>
		<td>
		<textarea name="address" rows="4" wrap="virtual" class="textboxtext" <?=$permission["CanUpdateAddress"]?'':"disabled ='disabled'"?>><?=$user_detail['address']?></textarea>
		<input type="hidden" name="address" value="<?=$user_detail['address']?>" <?=$permission["CanUpdateAddress"]?"disabled ='disabled'":""?>/>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['country']?></td>
		<td><select name="country" <?=$permission["CanUpdateCountry"]?'':"disabled ='disabled'"?>>
		    <option value=""><?=$kis_lang['select']?> <?=$kis_lang['country']?>...</option>
		    <option <?=$user_detail['country']=='China'?'selected':''?>  value="China"><?=$kis_lang['china']?></option>
		    <option <?=$user_detail['country']=='Hong Kong'?'selected':''?>  value="Hong Kong"><?=$kis_lang['hongkong']?></option>
		    <option <?=$user_detail['country']=='Macau'?'selected':''?>  value="Macau"><?=$kis_lang['macau']?></option>
		    <option <?=$user_detail['country']=='Malaysia'?'selected':''?>  value="Malaysia"><?=$kis_lang['malaysia']?></option>
		    <option <?=$user_detail['country']=='Taiwan'?'selected':''?>  value="Taiwan"><?=$kis_lang['taiwan']?></option>			
		    
		    <optgroup label="<?=$kis_lang['othercountries']?>">
			<option <?=$user_detail['country']=='Afganistan'?'selected':''?>  value="Afganistan">Afghanistan</option>
			<option <?=$user_detail['country']=='Albania'?'selected':''?>  value="Albania">Albania</option>
			<option <?=$user_detail['country']=='Algeria'?'selected':''?>  value="Algeria">Algeria</option>
			<option <?=$user_detail['country']=='American Samoa'?'selected':''?>  value="American Samoa">American Samoa</option>
			<option <?=$user_detail['country']=='Andorra'?'selected':''?>  value="Andorra">Andorra</option>
			<option <?=$user_detail['country']=='Angola'?'selected':''?>  value="Angola">Angola</option>
			<option <?=$user_detail['country']=='Anguilla'?'selected':''?>  value="Anguilla">Anguilla</option>
			<option <?=$user_detail['country']=='Antigua &amp; Barbuda'?'selected':''?>  value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
			<option <?=$user_detail['country']=='Argentina'?'selected':''?>  value="Argentina">Argentina</option>
			<option <?=$user_detail['country']=='Armenia'?'selected':''?>  value="Armenia">Armenia</option>
			<option <?=$user_detail['country']=='Aruba'?'selected':''?>  value="Aruba">Aruba</option>
			<option <?=$user_detail['country']=='Australia'?'selected':''?>  value="Australia">Australia</option>
			<option <?=$user_detail['country']=='Austria'?'selected':''?>  value="Austria">Austria</option>
			<option <?=$user_detail['country']=='Azerbaijan'?'selected':''?>  value="Azerbaijan">Azerbaijan</option>
			<option <?=$user_detail['country']=='Bahamas'?'selected':''?>  value="Bahamas">Bahamas</option>
			<option <?=$user_detail['country']=='Bahrain'?'selected':''?>  value="Bahrain">Bahrain</option>
			<option <?=$user_detail['country']=='Bangladesh'?'selected':''?>  value="Bangladesh">Bangladesh</option>
			<option <?=$user_detail['country']=='Barbados'?'selected':''?>  value="Barbados">Barbados</option>
			<option <?=$user_detail['country']=='Belarus'?'selected':''?>  value="Belarus">Belarus</option>
			<option <?=$user_detail['country']=='Belgium'?'selected':''?>  value="Belgium">Belgium</option>
			<option <?=$user_detail['country']=='Belize'?'selected':''?>  value="Belize">Belize</option>
			<option <?=$user_detail['country']=='Benin'?'selected':''?>  value="Benin">Benin</option>
			<option <?=$user_detail['country']=='Bermuda'?'selected':''?>  value="Bermuda">Bermuda</option>
			<option <?=$user_detail['country']=='Bhutan'?'selected':''?>  value="Bhutan">Bhutan</option>
			<option <?=$user_detail['country']=='Bolivia'?'selected':''?>  value="Bolivia">Bolivia</option>
			<option <?=$user_detail['country']=='Bonaire'?'selected':''?>  value="Bonaire">Bonaire</option>
			<option <?=$user_detail['country']=='Bosnia &amp; Herzegovina'?'selected':''?>  value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
			<option <?=$user_detail['country']=='Botswana'?'selected':''?>  value="Botswana">Botswana</option>
			<option <?=$user_detail['country']=='Brazil'?'selected':''?>  value="Brazil">Brazil</option>
			<option <?=$user_detail['country']=='British Indian Ocean Ter'?'selected':''?>  value="British Indian Ocean Ter">British Indian Ocean Ter</option>
			<option <?=$user_detail['country']=='Brunei'?'selected':''?>  value="Brunei">Brunei</option>
			<option <?=$user_detail['country']=='Bulgaria'?'selected':''?>  value="Bulgaria">Bulgaria</option>
			<option <?=$user_detail['country']=='Burkina Faso'?'selected':''?>  value="Burkina Faso">Burkina Faso</option>
			<option <?=$user_detail['country']=='Burundi'?'selected':''?>  value="Burundi">Burundi</option>
			<option <?=$user_detail['country']=='Cambodia'?'selected':''?>  value="Cambodia">Cambodia</option>
			<option <?=$user_detail['country']=='Cameroon'?'selected':''?>  value="Cameroon">Cameroon</option>
			<option <?=$user_detail['country']=='Canada'?'selected':''?>  value="Canada">Canada</option>
			<option <?=$user_detail['country']=='Canary Islands'?'selected':''?>  value="Canary Islands">Canary Islands</option>
			<option <?=$user_detail['country']=='Cape Verde'?'selected':''?>  value="Cape Verde">Cape Verde</option>
			<option <?=$user_detail['country']=='Cayman Islands'?'selected':''?>  value="Cayman Islands">Cayman Islands</option>
			<option <?=$user_detail['country']=='Central African Republic'?'selected':''?>  value="Central African Republic">Central African Republic</option>
			<option <?=$user_detail['country']=='Chad'?'selected':''?>  value="Chad">Chad</option>
			<option <?=$user_detail['country']=='Channel Islands'?'selected':''?>  value="Channel Islands">Channel Islands</option>
			<option <?=$user_detail['country']=='Chile'?'selected':''?>  value="Chile">Chile</option>
			<option <?=$user_detail['country']=='Christmas Island'?'selected':''?>  value="Christmas Island">Christmas Island</option>
			<option <?=$user_detail['country']=='Cocos Island'?'selected':''?>  value="Cocos Island">Cocos Island</option>
			<option <?=$user_detail['country']=='Colombia'?'selected':''?>  value="Colombia">Colombia</option>
			<option <?=$user_detail['country']=='Comoros'?'selected':''?>  value="Comoros">Comoros</option>
			<option <?=$user_detail['country']=='Congo'?'selected':''?>  value="Congo">Congo</option>
			<option <?=$user_detail['country']=='Cook Islands'?'selected':''?>  value="Cook Islands">Cook Islands</option>
			<option <?=$user_detail['country']=='Costa Rica'?'selected':''?>  value="Costa Rica">Costa Rica</option>
			<option <?=$user_detail['country']=='Cote DIvoire'?'selected':''?>  value="Cote DIvoire">Cote D'Ivoire</option>
			<option <?=$user_detail['country']=='Croatia'?'selected':''?>  value="Croatia">Croatia</option>
			<option <?=$user_detail['country']=='Cuba'?'selected':''?>  value="Cuba">Cuba</option>
			<option <?=$user_detail['country']=='Curaco'?'selected':''?>  value="Curaco">Curacao</option>
			<option <?=$user_detail['country']=='Cyprus'?'selected':''?>  value="Cyprus">Cyprus</option>
			<option <?=$user_detail['country']=='Czech Republic'?'selected':''?>  value="Czech Republic">Czech Republic</option>
			<option <?=$user_detail['country']=='Denmark'?'selected':''?>  value="Denmark">Denmark</option>
			<option <?=$user_detail['country']=='Djibouti'?'selected':''?>  value="Djibouti">Djibouti</option>
			<option <?=$user_detail['country']=='Dominica'?'selected':''?>  value="Dominica">Dominica</option>
			<option <?=$user_detail['country']=='Dominican Republic'?'selected':''?>  value="Dominican Republic">Dominican Republic</option>
			<option <?=$user_detail['country']=='East Timor'?'selected':''?>  value="East Timor">East Timor</option>
			<option <?=$user_detail['country']=='Ecuador'?'selected':''?>  value="Ecuador">Ecuador</option>
			<option <?=$user_detail['country']=='Egypt'?'selected':''?>  value="Egypt">Egypt</option>
			<option <?=$user_detail['country']=='El Salvador'?'selected':''?>  value="El Salvador">El Salvador</option>
			<option <?=$user_detail['country']=='Equatorial Guinea'?'selected':''?>  value="Equatorial Guinea">Equatorial Guinea</option>
			<option <?=$user_detail['country']=='Eritrea'?'selected':''?>  value="Eritrea">Eritrea</option>
			<option <?=$user_detail['country']=='Estonia'?'selected':''?>  value="Estonia">Estonia</option>
			<option <?=$user_detail['country']=='Ethiopia'?'selected':''?>  value="Ethiopia">Ethiopia</option>
			<option <?=$user_detail['country']=='Falkland Islands'?'selected':''?>  value="Falkland Islands">Falkland Islands</option>
			<option <?=$user_detail['country']=='Faroe Islands'?'selected':''?>  value="Faroe Islands">Faroe Islands</option>
			<option <?=$user_detail['country']=='Fiji'?'selected':''?>  value="Fiji">Fiji</option>
			<option <?=$user_detail['country']=='Finland'?'selected':''?>  value="Finland">Finland</option>
			<option <?=$user_detail['country']=='France'?'selected':''?>  value="France">France</option>
			<option <?=$user_detail['country']=='French Guiana'?'selected':''?>  value="French Guiana">French Guiana</option>
			<option <?=$user_detail['country']=='French Polynesia'?'selected':''?>  value="French Polynesia">French Polynesia</option>
			<option <?=$user_detail['country']=='French Southern Ter'?'selected':''?>  value="French Southern Ter">French Southern Ter</option>
			<option <?=$user_detail['country']=='Gabon'?'selected':''?>  value="Gabon">Gabon</option>
			<option <?=$user_detail['country']=='Gambia'?'selected':''?>  value="Gambia">Gambia</option>
			<option <?=$user_detail['country']=='Georgia'?'selected':''?>  value="Georgia">Georgia</option>
			<option <?=$user_detail['country']=='Germany'?'selected':''?>  value="Germany">Germany</option>
			<option <?=$user_detail['country']=='Ghana'?'selected':''?>  value="Ghana">Ghana</option>
			<option <?=$user_detail['country']=='Gibraltar'?'selected':''?>  value="Gibraltar">Gibraltar</option>
			<option <?=$user_detail['country']=='Great Britain'?'selected':''?>  value="Great Britain">Great Britain</option>
			<option <?=$user_detail['country']=='Greece'?'selected':''?>  value="Greece">Greece</option>
			<option <?=$user_detail['country']=='Greenland'?'selected':''?>  value="Greenland">Greenland</option>
			<option <?=$user_detail['country']=='Grenada'?'selected':''?>  value="Grenada">Grenada</option>
			<option <?=$user_detail['country']=='Guadeloupe'?'selected':''?>  value="Guadeloupe">Guadeloupe</option>
			<option <?=$user_detail['country']=='Guam'?'selected':''?>  value="Guam">Guam</option>
			<option <?=$user_detail['country']=='Guatemala'?'selected':''?>  value="Guatemala">Guatemala</option>
			<option <?=$user_detail['country']=='Guinea'?'selected':''?>  value="Guinea">Guinea</option>
			<option <?=$user_detail['country']=='Guyana'?'selected':''?>  value="Guyana">Guyana</option>
			<option <?=$user_detail['country']=='Haiti'?'selected':''?>  value="Haiti">Haiti</option>
			<option <?=$user_detail['country']=='Hawaii'?'selected':''?>  value="Hawaii">Hawaii</option>
			<option <?=$user_detail['country']=='Honduras'?'selected':''?>  value="Honduras">Honduras</option>
			<option <?=$user_detail['country']=='Hungary'?'selected':''?>  value="Hungary">Hungary</option>
			<option <?=$user_detail['country']=='Iceland'?'selected':''?>  value="Iceland">Iceland</option>
			<option <?=$user_detail['country']=='India'?'selected':''?>  value="India">India</option>
			<option <?=$user_detail['country']=='Indonesia'?'selected':''?>  value="Indonesia">Indonesia</option>
			<option <?=$user_detail['country']=='Iran'?'selected':''?>  value="Iran">Iran</option>
			<option <?=$user_detail['country']=='Iraq'?'selected':''?>  value="Iraq">Iraq</option>
			<option <?=$user_detail['country']=='Ireland'?'selected':''?>  value="Ireland">Ireland</option>
			<option <?=$user_detail['country']=='Isle of Man'?'selected':''?>  value="Isle of Man">Isle of Man</option>
			<option <?=$user_detail['country']=='Israel'?'selected':''?>  value="Israel">Israel</option>
			<option <?=$user_detail['country']=='Italy'?'selected':''?>  value="Italy">Italy</option>
			<option <?=$user_detail['country']=='Jamaica'?'selected':''?>  value="Jamaica">Jamaica</option>
			<option <?=$user_detail['country']=='Japan'?'selected':''?>  value="Japan">Japan</option>
			<option <?=$user_detail['country']=='Jordan'?'selected':''?>  value="Jordan">Jordan</option>
			<option <?=$user_detail['country']=='Kazakhstan'?'selected':''?>  value="Kazakhstan">Kazakhstan</option>
			<option <?=$user_detail['country']=='Kenya'?'selected':''?>  value="Kenya">Kenya</option>
			<option <?=$user_detail['country']=='Kiribati'?'selected':''?>  value="Kiribati">Kiribati</option>
			<option <?=$user_detail['country']=='Korea North'?'selected':''?>  value="Korea North">Korea North</option>
			<option <?=$user_detail['country']=='Korea Sout'?'selected':''?>  value="Korea Sout">Korea South</option>
			<option <?=$user_detail['country']=='Kuwait'?'selected':''?>  value="Kuwait">Kuwait</option>
			<option <?=$user_detail['country']=='Kyrgyzstan'?'selected':''?>  value="Kyrgyzstan">Kyrgyzstan</option>
			<option <?=$user_detail['country']=='Laos'?'selected':''?>  value="Laos">Laos</option>
			<option <?=$user_detail['country']=='Latvia'?'selected':''?>  value="Latvia">Latvia</option>
			<option <?=$user_detail['country']=='Lebanon'?'selected':''?>  value="Lebanon">Lebanon</option>
			<option <?=$user_detail['country']=='Lesotho'?'selected':''?>  value="Lesotho">Lesotho</option>
			<option <?=$user_detail['country']=='Liberia'?'selected':''?>  value="Liberia">Liberia</option>
			<option <?=$user_detail['country']=='Libya'?'selected':''?>  value="Libya">Libya</option>
			<option <?=$user_detail['country']=='Liechtenstein'?'selected':''?>  value="Liechtenstein">Liechtenstein</option>
			<option <?=$user_detail['country']=='Lithuania'?'selected':''?>  value="Lithuania">Lithuania</option>
			<option <?=$user_detail['country']=='Luxembourg'?'selected':''?>  value="Luxembourg">Luxembourg</option>
			<option <?=$user_detail['country']=='Macedonia'?'selected':''?>  value="Macedonia">Macedonia</option>
			<option <?=$user_detail['country']=='Madagascar'?'selected':''?>  value="Madagascar">Madagascar</option>
			<option <?=$user_detail['country']=='Malawi'?'selected':''?>  value="Malawi">Malawi</option>
			<option <?=$user_detail['country']=='Maldives'?'selected':''?>  value="Maldives">Maldives</option>
			<option <?=$user_detail['country']=='Mali'?'selected':''?>  value="Mali">Mali</option>
			<option <?=$user_detail['country']=='Malta'?'selected':''?>  value="Malta">Malta</option>
			<option <?=$user_detail['country']=='Marshall Islands'?'selected':''?>  value="Marshall Islands">Marshall Islands</option>
			<option <?=$user_detail['country']=='Martinique'?'selected':''?>  value="Martinique">Martinique</option>
			<option <?=$user_detail['country']=='Mauritania'?'selected':''?>  value="Mauritania">Mauritania</option>
			<option <?=$user_detail['country']=='Mauritius'?'selected':''?>  value="Mauritius">Mauritius</option>
			<option <?=$user_detail['country']=='Mayotte'?'selected':''?>  value="Mayotte">Mayotte</option>
			<option <?=$user_detail['country']=='Mexico'?'selected':''?>  value="Mexico">Mexico</option>
			<option <?=$user_detail['country']=='Midway Islands'?'selected':''?>  value="Midway Islands">Midway Islands</option>
			<option <?=$user_detail['country']=='Moldova'?'selected':''?>  value="Moldova">Moldova</option>
			<option <?=$user_detail['country']=='Monaco'?'selected':''?>  value="Monaco">Monaco</option>
			<option <?=$user_detail['country']=='Mongolia'?'selected':''?>  value="Mongolia">Mongolia</option>
			<option <?=$user_detail['country']=='Montserrat'?'selected':''?>  value="Montserrat">Montserrat</option>
			<option <?=$user_detail['country']=='Morocco'?'selected':''?>  value="Morocco">Morocco</option>
			<option <?=$user_detail['country']=='Mozambique'?'selected':''?>  value="Mozambique">Mozambique</option>
			<option <?=$user_detail['country']=='Myanmar'?'selected':''?>  value="Myanmar">Myanmar</option>
			<option <?=$user_detail['country']=='Nambia'?'selected':''?>  value="Nambia">Nambia</option>
			<option <?=$user_detail['country']=='Nauru'?'selected':''?>  value="Nauru">Nauru</option>
			<option <?=$user_detail['country']=='Nepal'?'selected':''?>  value="Nepal">Nepal</option>
			<option <?=$user_detail['country']=='Netherland Antilles'?'selected':''?>  value="Netherland Antilles">Netherland Antilles</option>
			<option <?=$user_detail['country']=='Netherlands'?'selected':''?>  value="Netherlands">Netherlands (Holland, Europe)</option>
			<option <?=$user_detail['country']=='Nevis'?'selected':''?>  value="Nevis">Nevis</option>
			<option <?=$user_detail['country']=='New Caledonia'?'selected':''?>  value="New Caledonia">New Caledonia</option>
			<option <?=$user_detail['country']=='New Zealand'?'selected':''?>  value="New Zealand">New Zealand</option>
			<option <?=$user_detail['country']=='Nicaragua'?'selected':''?>  value="Nicaragua">Nicaragua</option>
			<option <?=$user_detail['country']=='Niger'?'selected':''?>  value="Niger">Niger</option>
			<option <?=$user_detail['country']=='Nigeria'?'selected':''?>  value="Nigeria">Nigeria</option>
			<option <?=$user_detail['country']=='Niue'?'selected':''?>  value="Niue">Niue</option>
			<option <?=$user_detail['country']=='Norfolk Island'?'selected':''?>  value="Norfolk Island">Norfolk Island</option>
			<option <?=$user_detail['country']=='Norway'?'selected':''?>  value="Norway">Norway</option>
			<option <?=$user_detail['country']=='Oman'?'selected':''?>  value="Oman">Oman</option>
			<option <?=$user_detail['country']=='Pakistan'?'selected':''?>  value="Pakistan">Pakistan</option>
			<option <?=$user_detail['country']=='Palau Island'?'selected':''?>  value="Palau Island">Palau Island</option>
			<option <?=$user_detail['country']=='Palestine'?'selected':''?>  value="Palestine">Palestine</option>
			<option <?=$user_detail['country']=='Panama'?'selected':''?>  value="Panama">Panama</option>
			<option <?=$user_detail['country']=='Papua New Guinea'?'selected':''?>  value="Papua New Guinea">Papua New Guinea</option>
			<option <?=$user_detail['country']=='Paraguay'?'selected':''?>  value="Paraguay">Paraguay</option>
			<option <?=$user_detail['country']=='Peru'?'selected':''?>  value="Peru">Peru</option>
			<option <?=$user_detail['country']=='Phillipines'?'selected':''?>  value="Phillipines">Philippines</option>
			<option <?=$user_detail['country']=='Pitcairn Island'?'selected':''?>  value="Pitcairn Island">Pitcairn Island</option>
			<option <?=$user_detail['country']=='Poland'?'selected':''?>  value="Poland">Poland</option>
			<option <?=$user_detail['country']=='Portugal'?'selected':''?>  value="Portugal">Portugal</option>
			<option <?=$user_detail['country']=='Puerto Rico'?'selected':''?>  value="Puerto Rico">Puerto Rico</option>
			<option <?=$user_detail['country']=='Qatar'?'selected':''?>  value="Qatar">Qatar</option>
			<option <?=$user_detail['country']=='Republic of Montenegro'?'selected':''?>  value="Republic of Montenegro">Republic of Montenegro</option>
			<option <?=$user_detail['country']=='Republic of Serbia'?'selected':''?>  value="Republic of Serbia">Republic of Serbia</option>
			<option <?=$user_detail['country']=='Reunion'?'selected':''?>  value="Reunion">Reunion</option>
			<option <?=$user_detail['country']=='Romania'?'selected':''?>  value="Romania">Romania</option>
			<option <?=$user_detail['country']=='Russia'?'selected':''?>  value="Russia">Russia</option>
			<option <?=$user_detail['country']=='Rwanda'?'selected':''?>  value="Rwanda">Rwanda</option>
			<option <?=$user_detail['country']=='St Barthelemy'?'selected':''?>  value="St Barthelemy">St Barthelemy</option>
			<option <?=$user_detail['country']=='St Eustatius'?'selected':''?>  value="St Eustatius">St Eustatius</option>
			<option <?=$user_detail['country']=='St Helena'?'selected':''?>  value="St Helena">St Helena</option>
			<option <?=$user_detail['country']=='St Kitts-Nevis'?'selected':''?>  value="St Kitts-Nevis">St Kitts-Nevis</option>
			<option <?=$user_detail['country']=='St Lucia'?'selected':''?>  value="St Lucia">St Lucia</option>
			<option <?=$user_detail['country']=='St Maarten'?'selected':''?>  value="St Maarten">St Maarten</option>
			<option <?=$user_detail['country']=='St Pierre &amp; Miquelon'?'selected':''?>  value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
			<option <?=$user_detail['country']=='St Vincent &amp; Grenadines'?'selected':''?>  value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
			<option <?=$user_detail['country']=='Saipan'?'selected':''?>  value="Saipan">Saipan</option>
			<option <?=$user_detail['country']=='Samoa'?'selected':''?>  value="Samoa">Samoa</option>
			<option <?=$user_detail['country']=='Samoa American'?'selected':''?>  value="Samoa American">Samoa American</option>
			<option <?=$user_detail['country']=='San Marino'?'selected':''?>  value="San Marino">San Marino</option>
			<option <?=$user_detail['country']=='Sao Tome & Principe'?'selected':''?>  value="Sao Tome & Principe">Sao Tome &amp; Principe</option>
			<option <?=$user_detail['country']=='Saudi Arabia'?'selected':''?>  value="Saudi Arabia">Saudi Arabia</option>
			<option <?=$user_detail['country']=='Senegal'?'selected':''?>  value="Senegal">Senegal</option>
			<option <?=$user_detail['country']=='Seychelles'?'selected':''?>  value="Seychelles">Seychelles</option>
			<option <?=$user_detail['country']=='Sierra Leone'?'selected':''?>  value="Sierra Leone">Sierra Leone</option>
			<option <?=$user_detail['country']=='Singapore'?'selected':''?>  value="Singapore">Singapore</option>
			<option <?=$user_detail['country']=='Slovakia'?'selected':''?>  value="Slovakia">Slovakia</option>
			<option <?=$user_detail['country']=='Slovenia'?'selected':''?>  value="Slovenia">Slovenia</option>
			<option <?=$user_detail['country']=='Solomon Islands'?'selected':''?>  value="Solomon Islands">Solomon Islands</option>
			<option <?=$user_detail['country']=='Somalia'?'selected':''?>  value="Somalia">Somalia</option>
			<option <?=$user_detail['country']=='South Africa'?'selected':''?>  value="South Africa">South Africa</option>
			<option <?=$user_detail['country']=='Spain'?'selected':''?>  value="Spain">Spain</option>
			<option <?=$user_detail['country']=='Sri Lanka'?'selected':''?>  value="Sri Lanka">Sri Lanka</option>
			<option <?=$user_detail['country']=='Sudan'?'selected':''?>  value="Sudan">Sudan</option>
			<option <?=$user_detail['country']=='Suriname'?'selected':''?>  value="Suriname">Suriname</option>
			<option <?=$user_detail['country']=='Swaziland'?'selected':''?>  value="Swaziland">Swaziland</option>
			<option <?=$user_detail['country']=='Sweden'?'selected':''?>  value="Sweden">Sweden</option>
			<option <?=$user_detail['country']=='Switzerland'?'selected':''?>  value="Switzerland">Switzerland</option>
			<option <?=$user_detail['country']=='Syria'?'selected':''?>  value="Syria">Syria</option>
			<option <?=$user_detail['country']=='Tahiti'?'selected':''?>  value="Tahiti">Tahiti</option>
			<option <?=$user_detail['country']=='Tajikistan'?'selected':''?>  value="Tajikistan">Tajikistan</option>
			<option <?=$user_detail['country']=='Tanzania'?'selected':''?>  value="Tanzania">Tanzania</option>
			<option <?=$user_detail['country']=='Thailand'?'selected':''?>  value="Thailand">Thailand</option>
			<option <?=$user_detail['country']=='Togo'?'selected':''?>  value="Togo">Togo</option>
			<option <?=$user_detail['country']=='Tokelau'?'selected':''?>  value="Tokelau">Tokelau</option>
			<option <?=$user_detail['country']=='Tonga'?'selected':''?>  value="Tonga">Tonga</option>
			<option <?=$user_detail['country']=='Trinidad &amp; Tobago'?'selected':''?>  value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
			<option <?=$user_detail['country']=='Tunisia'?'selected':''?>  value="Tunisia">Tunisia</option>
			<option <?=$user_detail['country']=='Turkey'?'selected':''?>  value="Turkey">Turkey</option>
			<option <?=$user_detail['country']=='Turkmenistan'?'selected':''?>  value="Turkmenistan">Turkmenistan</option>
			<option <?=$user_detail['country']=='Turks &amp; Caicos Is'?'selected':''?>  value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
			<option <?=$user_detail['country']=='Tuvalu'?'selected':''?>  value="Tuvalu">Tuvalu</option>
			<option <?=$user_detail['country']=='Uganda'?'selected':''?>  value="Uganda">Uganda</option>
			<option <?=$user_detail['country']=='Ukraine'?'selected':''?>  value="Ukraine">Ukraine</option>
			<option <?=$user_detail['country']=='United Arab Erimates'?'selected':''?>  value="United Arab Erimates">United Arab Emirates</option>
			<option <?=$user_detail['country']=='United Kingdom'?'selected':''?>  value="United Kingdom">United Kingdom</option>
			<option <?=$user_detail['country']=='United States of America'?'selected':''?>  value="United States of America">United States of America</option>
			<option <?=$user_detail['country']=='Uraguay'?'selected':''?>  value="Uraguay">Uruguay</option>
			<option <?=$user_detail['country']=='Uzbekistan'?'selected':''?>  value="Uzbekistan">Uzbekistan</option>
			<option <?=$user_detail['country']=='Vanuatu'?'selected':''?>  value="Vanuatu">Vanuatu</option>
			<option <?=$user_detail['country']=='Vatican City State'?'selected':''?>  value="Vatican City State">Vatican City State</option>
			<option <?=$user_detail['country']=='Venezuela'?'selected':''?>  value="Venezuela">Venezuela</option>
			<option <?=$user_detail['country']=='Vietnam'?'selected':''?>  value="Vietnam">Vietnam</option>
			<option <?=$user_detail['country']=='Virgin Islands (Brit)'?'selected':''?>  value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
			<option <?=$user_detail['country']=='Virgin Islands (USA)'?'selected':''?>  value="Virgin Islands (USA)">Virgin Islands (USA)</option>
			<option <?=$user_detail['country']=='Wake Island'?'selected':''?>  value="Wake Island">Wake Island</option>
			<option <?=$user_detail['country']=='Wallis &amp; Futana Is'?'selected':''?>  value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
			<option <?=$user_detail['country']=='Yemen'?'selected':''?>  value="Yemen">Yemen</option>
			<option <?=$user_detail['country']=='Zaire'?'selected':''?>  value="Zaire">Zaire</option>
			<option <?=$user_detail['country']=='Zambia'?'selected':''?>  value="Zambia">Zambia</option>
			<option <?=$user_detail['country']=='Zimbabwe'?'selected':''?>  value="Zimbabwe">Zimbabwe</option>
		    </optgroup>
		</select>
		<input type="hidden" name="country" value="<?=$user_detail['country']?>" <?=$permission["CanUpdateCountry"]?"disabled ='disabled'":""?>/>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['homepageurl']?></td>
		<td>
		<input name="url" class="textboxtext" type="url" value="<?=$user_detail['url']?>" <?=$permission["CanUpdateURL"]?'':"disabled ='disabled'"?>/>
		<input type="hidden" name="url" value="<?=$user_detail['url']?>" <?=$permission["CanUpdateURL"]?"disabled ='disabled'":""?>/>
		</td>
	    </tr>
	    <tr>
		<td class="field_title"><?=$kis_lang['email']?></td>
		<td>
		<input name="email" class="textboxtext" type="text" value="<?=$user_detail['email']?>" <?=$permission["CanUpdateEmail"]?'':"disabled ='disabled'"?>/>
		<span class="tabletextrequire" id="invalid_email" style="display:none"><?=$kis_lang['invalidemail']?>!</span>
		<input type="hidden" name="email" value="<?=$user_detail['email']?>" <?=$permission["CanUpdateEmail"]?"disabled ='disabled'":""?>/>
		</td>
	    </tr>
	</table>
	<p class="spacer"></p>
    </div>
    <?if ($permission["CanUpdateHomeTel"]||$permission["CanUpdateOfficeTel"]||$permission["CanUpdateMobile"]||$permission["CanUpdateFax"]||$permission["CanUpdateAddress"]||$permission["CanUpdateCountry"]||$permission["CanUpdateURL"]||$permission["CanUpdateEmail"]):?>
    <div class="edit_bottom">
        <input class="formbutton" value="<?=$kis_lang['submit']?>" type="button">
    <!--    <input class="formsubbutton" onclick="history.go(-1)" value="<?=$kis_lang['cancel']?>" type="button">-->
    </div>
    <?endif;?>
    <input type="hidden" name="nick_name" value="<?=$user_detail['nick_name']?>"/>
    <input type="hidden" name="gender" value="<?=$user_detail['gender']?>"/>
    </form>
</div>
                    