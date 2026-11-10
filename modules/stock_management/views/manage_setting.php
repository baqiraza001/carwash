<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div>
                        <div class="tab-content">
                            <h4 class="right-side-group-heading"><?= _l('settings'); ?></h4>
                            <div class="row hide">
                                <div class="col-md-6">
                                    <div class="form-group" app-field-wrapper="instance_id"><label for="instance_id" class="control-label"><?= _l('id'); ?></label><input type="" id="instance_id" name="instance_id" class="form-control" disabled="disabled" value=""></div>
                                    <input type="hidden" name="id" value="65">

                                </div>
                            </div>
                            <div class="row">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane" id="instance_general">
                                        <div class="col-md-12" style="padding-top: 17px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="first_name"><label for="first_name" class="control-label"><?= _l('first_name'); ?></label><input type="text" id="first_name" name="first_name" class="form-control" value="pizza"></div>                                                        </div>

                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="last_name"><label for="last_name" class="control-label"><?= _l('last_name'); ?></label><input type="text" id="last_name" name="last_name" class="form-control" value="khan"></div>                                                        </div>

                                                <div class="col-md-6">

                                                    <div class="form-group" app-field-wrapper="company"><label for="company" class="control-label"><?= _l('company'); ?> <a href="http://perfex244.test/admin/clients/client/65" target="_blank">( <?= _l('click_here'); ?> )</a></label><input type="text" id="company" name="company" class="form-control" value="pizza"></div>                                                        </div>


                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="sub_domain"><label for="sub_domain" class="control-label"><?= _l('sub_domain'); ?></label><input type="" id="sub_domain" name="sub_domain" class="form-control" disabled="1" value="pizza"></div>                                                        </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="email_address"><label for="email_address" class="control-label"><?= _l('email_address'); ?></label><input type="" id="email_address" name="email_address" class="form-control" disabled="1" value="pizza@gmail.com"></div>                                                        </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password" class="control-label"><small class="req text-danger">* </small><?= _l('password'); ?></label>

                                                        <div class="input-group mb-3">

                                                            <i class="fa-solid fa-key login_icon"></i><input type="password" id="password" name="password" class="form-control" aria-label="passw" aria-describedby="basic-addon1"><div class="line_toggler"></div>
                                                            <div class="input-group-addon">
                                                                <span toggle="#password-field" id="basic-addon1" class="fa-regular fa-fw fa-eye field_icon toggle-password"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="contact_number"><label for="contact_number" class="control-label"><?= _l('contact_number'); ?></label><input type="text" id="contact_number" name="contact_number" class="form-control" value="123123"></div>                                                        </div>

                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="country"><label for="country" class="control-label"><?= _l('country'); ?></label><div class="dropdown bootstrap-select bs3" style="width: 100%;"><select id="country" name="country" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98"><option value=""></option><option value="1">Afghanistan</option><option value="2">Aland Islands</option><option value="3">Albania</option><option value="4">Algeria</option><option value="5">American Samoa</option><option value="6">Andorra</option><option value="7">Angola</option><option value="8">Anguilla</option><option value="9">Antarctica</option><option value="10">Antigua and Barbuda</option><option value="11">Argentina</option><option value="12">Armenia</option><option value="13">Aruba</option><option value="14">Australia</option><option value="15">Austria</option><option value="16">Azerbaijan</option><option value="17">Bahamas</option><option value="18">Bahrain</option><option value="19">Bangladesh</option><option value="20">Barbados</option><option value="21">Belarus</option><option value="22">Belgium</option><option value="23">Belize</option><option value="24">Benin</option><option value="25">Bermuda</option><option value="26">Bhutan</option><option value="27">Bolivia</option><option value="28">Bonaire, Sint Eustatius and Saba</option><option value="29">Bosnia and Herzegovina</option><option value="30">Botswana</option><option value="31">Bouvet Island</option><option value="32">Brazil</option><option value="33">British Indian Ocean Territory</option><option value="34">Brunei</option><option value="35">Bulgaria</option><option value="36">Burkina Faso</option><option value="37">Burundi</option><option value="38">Cambodia</option><option value="39">Cameroon</option><option value="40">Canada</option><option value="41">Cape Verde</option><option value="42">Cayman Islands</option><option value="43">Central African Republic</option><option value="44">Chad</option><option value="45">Chile</option><option value="46">China</option><option value="47">Christmas Island</option><option value="48">Cocos (Keeling) Islands</option><option value="49">Colombia</option><option value="50">Comoros</option><option value="51">Congo</option><option value="52">Cook Islands</option><option value="53">Costa Rica</option><option value="54">Cote d'ivoire (Ivory Coast)</option><option value="55">Croatia</option><option value="56">Cuba</option><option value="57">Curacao</option><option value="58">Cyprus</option><option value="59">Czech Republic</option><option value="60">Democratic Republic of the Congo</option><option value="61">Denmark</option><option value="62">Djibouti</option><option value="63">Dominica</option><option value="64">Dominican Republic</option><option value="65">Ecuador</option><option value="66">Egypt</option><option value="67">El Salvador</option><option value="68">Equatorial Guinea</option><option value="69">Eritrea</option><option value="70">Estonia</option><option value="71">Ethiopia</option><option value="72">Falkland Islands (Malvinas)</option><option value="73">Faroe Islands</option><option value="74">Fiji</option><option value="75">Finland</option><option value="76">France</option><option value="77">French Guiana</option><option value="78">French Polynesia</option><option value="79">French Southern Territories</option><option value="80">Gabon</option><option value="81">Gambia</option><option value="82">Georgia</option><option value="83">Germany</option><option value="84">Ghana</option><option value="85">Gibraltar</option><option value="86">Greece</option><option value="87">Greenland</option><option value="88">Grenada</option><option value="89">Guadaloupe</option><option value="90">Guam</option><option value="91">Guatemala</option><option value="92">Guernsey</option><option value="93">Guinea</option><option value="94">Guinea-Bissau</option><option value="95">Guyana</option><option value="96">Haiti</option><option value="97">Heard Island and McDonald Islands</option><option value="98">Honduras</option><option value="99">Hong Kong</option><option value="100">Hungary</option><option value="101">Iceland</option><option value="102">India</option><option value="103">Indonesia</option><option value="104">Iran</option><option value="105">Iraq</option><option value="106">Ireland</option><option value="107">Isle of Man</option><option value="108">Israel</option><option value="109">Italy</option><option value="110">Jamaica</option><option value="111">Japan</option><option value="112">Jersey</option><option value="113">Jordan</option><option value="114">Kazakhstan</option><option value="115">Kenya</option><option value="116">Kiribati</option><option value="117">Kosovo</option><option value="118">Kuwait</option><option value="119">Kyrgyzstan</option><option value="120">Laos</option><option value="121">Latvia</option><option value="122">Lebanon</option><option value="123">Lesotho</option><option value="124">Liberia</option><option value="125">Libya</option><option value="126">Liechtenstein</option><option value="127">Lithuania</option><option value="128">Luxembourg</option><option value="129">Macao</option><option value="131">Madagascar</option><option value="132">Malawi</option><option value="133">Malaysia</option><option value="134">Maldives</option><option value="135">Mali</option><option value="136">Malta</option><option value="137">Marshall Islands</option><option value="138">Martinique</option><option value="139">Mauritania</option><option value="140">Mauritius</option><option value="141">Mayotte</option><option value="142">Mexico</option><option value="143">Micronesia</option><option value="144">Moldava</option><option value="145">Monaco</option><option value="146">Mongolia</option><option value="147">Montenegro</option><option value="148">Montserrat</option><option value="149">Morocco</option><option value="150">Mozambique</option><option value="151">Myanmar (Burma)</option><option value="152">Namibia</option><option value="153">Nauru</option><option value="154">Nepal</option><option value="155">Netherlands</option><option value="156">New Caledonia</option><option value="157">New Zealand</option><option value="158">Nicaragua</option><option value="159">Niger</option><option value="160">Nigeria</option><option value="161">Niue</option><option value="162">Norfolk Island</option><option value="163">North Korea</option><option value="130">North Macedonia</option><option value="164">Northern Mariana Islands</option><option value="165">Norway</option><option value="166">Oman</option><option value="167" selected="">Pakistan</option><option value="168">Palau</option><option value="169">Palestine</option><option value="170">Panama</option><option value="171">Papua New Guinea</option><option value="172">Paraguay</option><option value="173">Peru</option><option value="174">Phillipines</option><option value="175">Pitcairn</option><option value="176">Poland</option><option value="177">Portugal</option><option value="178">Puerto Rico</option><option value="179">Qatar</option><option value="180">Reunion</option><option value="181">Romania</option><option value="182">Russia</option><option value="183">Rwanda</option><option value="184">Saint Barthelemy</option><option value="185">Saint Helena</option><option value="186">Saint Kitts and Nevis</option><option value="187">Saint Lucia</option><option value="188">Saint Martin</option><option value="189">Saint Pierre and Miquelon</option><option value="190">Saint Vincent and the Grenadines</option><option value="191">Samoa</option><option value="192">San Marino</option><option value="193">Sao Tome and Principe</option><option value="194">Saudi Arabia</option><option value="195">Senegal</option><option value="196">Serbia</option><option value="197">Seychelles</option><option value="198">Sierra Leone</option><option value="199">Singapore</option><option value="200">Sint Maarten</option><option value="201">Slovakia</option><option value="202">Slovenia</option><option value="203">Solomon Islands</option><option value="204">Somalia</option><option value="205">South Africa</option><option value="206">South Georgia and the South Sandwich Islands</option><option value="207">South Korea</option><option value="208">South Sudan</option><option value="209">Spain</option><option value="210">Sri Lanka</option><option value="211">Sudan</option><option value="212">Suriname</option><option value="213">Svalbard and Jan Mayen</option><option value="214">Swaziland</option><option value="215">Sweden</option><option value="216">Switzerland</option><option value="217">Syria</option><option value="218">Taiwan</option><option value="219">Tajikistan</option><option value="220">Tanzania</option><option value="221">Thailand</option><option value="222">Timor-Leste (East Timor)</option><option value="223">Togo</option><option value="224">Tokelau</option><option value="225">Tonga</option><option value="226">Trinidad and Tobago</option><option value="227">Tunisia</option><option value="228">Turkey</option><option value="229">Turkmenistan</option><option value="230">Turks and Caicos Islands</option><option value="231">Tuvalu</option><option value="232">Uganda</option><option value="233">Ukraine</option><option value="234">United Arab Emirates</option><option value="235">United Kingdom</option><option value="236">United States</option><option value="237">United States Minor Outlying Islands</option><option value="238">Uruguay</option><option value="239">Uzbekistan</option><option value="240">Vanuatu</option><option value="241">Vatican City</option><option value="242">Venezuela</option><option value="243">Vietnam</option><option value="244">Virgin Islands, British</option><option value="245">Virgin Islands, US</option><option value="246">Wallis and Futuna</option><option value="247">Western Sahara</option><option value="248">Yemen</option><option value="249">Zambia</option><option value="250">Zimbabwe</option></select><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" role="combobox" aria-owns="bs-select-1" aria-haspopup="listbox" aria-expanded="false" data-id="country" title="Pakistan"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">Pakistan</div></div> </div><span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-1" aria-autocomplete="list"></div><div class="inner open" role="listbox" id="bs-select-1" tabindex="-1"><ul class="dropdown-menu inner " role="presentation"></ul></div></div></div></div>                                                        </div>

                                                <div class="col-md-6">
                                                    <div class="form-group" app-field-wrapper="domain_logo"><label for="domain_logo" class="control-label"><?= _l('logo'); ?></label><input type="file" id="domain_logo" name="domain_logo" class="form-control" value=""></div>                                                        </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="timezones" class="control-label"><?= _l('timezone'); ?></label>
                                                        <div class="dropdown bootstrap-select form-control bs3"><select name="timezones" id="timezones" class="form-control selectpicker" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                                                <optgroup label="EUROPE">
                                                                    <option value="Europe/Amsterdam" selected="">Europe/Amsterdam</option>
                                                                    <option value="Europe/Andorra">Europe/Andorra</option>
                                                                    <option value="Europe/Astrakhan">Europe/Astrakhan</option>
                                                                    <option value="Europe/Athens">Europe/Athens</option>
                                                                    <option value="Europe/Belgrade">Europe/Belgrade</option>
                                                                    <option value="Europe/Berlin">Europe/Berlin</option>
                                                                    <option value="Europe/Bratislava">Europe/Bratislava</option>
                                                                    <option value="Europe/Brussels">Europe/Brussels</option>
                                                                    <option value="Europe/Bucharest">Europe/Bucharest</option>
                                                                    <option value="Europe/Budapest">Europe/Budapest</option>
                                                                    <option value="Europe/Busingen">Europe/Busingen</option>
                                                                    <option value="Europe/Chisinau">Europe/Chisinau</option>
                                                                    <option value="Europe/Copenhagen">Europe/Copenhagen</option>
                                                                    <option value="Europe/Dublin">Europe/Dublin</option>
                                                                    <option value="Europe/Gibraltar">Europe/Gibraltar</option>
                                                                    <option value="Europe/Guernsey">Europe/Guernsey</option>
                                                                    <option value="Europe/Helsinki">Europe/Helsinki</option>
                                                                    <option value="Europe/Isle_of_Man">Europe/Isle_of_Man</option>
                                                                    <option value="Europe/Istanbul">Europe/Istanbul</option>
                                                                    <option value="Europe/Jersey">Europe/Jersey</option>
                                                                    <option value="Europe/Kaliningrad">Europe/Kaliningrad</option>
                                                                    <option value="Europe/Kiev">Europe/Kiev</option>
                                                                    <option value="Europe/Kirov">Europe/Kirov</option>
                                                                    <option value="Europe/Lisbon">Europe/Lisbon</option>
                                                                    <option value="Europe/Ljubljana">Europe/Ljubljana</option>
                                                                    <option value="Europe/London">Europe/London</option>
                                                                    <option value="Europe/Luxembourg">Europe/Luxembourg</option>
                                                                    <option value="Europe/Madrid">Europe/Madrid</option>
                                                                    <option value="Europe/Malta">Europe/Malta</option>
                                                                    <option value="Europe/Mariehamn">Europe/Mariehamn</option>
                                                                    <option value="Europe/Minsk">Europe/Minsk</option>
                                                                    <option value="Europe/Monaco">Europe/Monaco</option>
                                                                    <option value="Europe/Moscow">Europe/Moscow</option>
                                                                    <option value="Europe/Oslo">Europe/Oslo</option>
                                                                    <option value="Europe/Paris">Europe/Paris</option>
                                                                    <option value="Europe/Podgorica">Europe/Podgorica</option>
                                                                    <option value="Europe/Prague">Europe/Prague</option>
                                                                    <option value="Europe/Riga">Europe/Riga</option>
                                                                    <option value="Europe/Rome">Europe/Rome</option>
                                                                    <option value="Europe/Samara">Europe/Samara</option>
                                                                    <option value="Europe/San_Marino">Europe/San_Marino</option>
                                                                    <option value="Europe/Sarajevo">Europe/Sarajevo</option>
                                                                    <option value="Europe/Saratov">Europe/Saratov</option>
                                                                    <option value="Europe/Simferopol">Europe/Simferopol</option>
                                                                    <option value="Europe/Skopje">Europe/Skopje</option>
                                                                    <option value="Europe/Sofia">Europe/Sofia</option>
                                                                    <option value="Europe/Stockholm">Europe/Stockholm</option>
                                                                    <option value="Europe/Tallinn">Europe/Tallinn</option>
                                                                    <option value="Europe/Tirane">Europe/Tirane</option>
                                                                    <option value="Europe/Ulyanovsk">Europe/Ulyanovsk</option>
                                                                    <option value="Europe/Uzhgorod">Europe/Uzhgorod</option>
                                                                    <option value="Europe/Vaduz">Europe/Vaduz</option>
                                                                    <option value="Europe/Vatican">Europe/Vatican</option>
                                                                    <option value="Europe/Vienna">Europe/Vienna</option>
                                                                    <option value="Europe/Vilnius">Europe/Vilnius</option>
                                                                    <option value="Europe/Volgograd">Europe/Volgograd</option>
                                                                    <option value="Europe/Warsaw">Europe/Warsaw</option>
                                                                    <option value="Europe/Zagreb">Europe/Zagreb</option>
                                                                    <option value="Europe/Zaporozhye">Europe/Zaporozhye</option>
                                                                    <option value="Europe/Zurich">Europe/Zurich</option>
                                                                </optgroup>
                                                                <optgroup label="AMERICA">
                                                                    <option value="America/Adak">America/Adak</option>
                                                                    <option value="America/Anchorage">America/Anchorage</option>
                                                                    <option value="America/Anguilla">America/Anguilla</option>
                                                                    <option value="America/Antigua">America/Antigua</option>
                                                                    <option value="America/Araguaina">America/Araguaina</option>
                                                                    <option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires</option>
                                                                    <option value="America/Argentina/Catamarca">America/Argentina/Catamarca</option>
                                                                    <option value="America/Argentina/Cordoba">America/Argentina/Cordoba</option>
                                                                    <option value="America/Argentina/Jujuy">America/Argentina/Jujuy</option>
                                                                    <option value="America/Argentina/La_Rioja">America/Argentina/La_Rioja</option>
                                                                    <option value="America/Argentina/Mendoza">America/Argentina/Mendoza</option>
                                                                    <option value="America/Argentina/Rio_Gallegos">America/Argentina/Rio_Gallegos</option>
                                                                    <option value="America/Argentina/Salta">America/Argentina/Salta</option>
                                                                    <option value="America/Argentina/San_Juan">America/Argentina/San_Juan</option>
                                                                    <option value="America/Argentina/San_Luis">America/Argentina/San_Luis</option>
                                                                    <option value="America/Argentina/Tucuman">America/Argentina/Tucuman</option>
                                                                    <option value="America/Argentina/Ushuaia">America/Argentina/Ushuaia</option>
                                                                    <option value="America/Aruba">America/Aruba</option>
                                                                    <option value="America/Asuncion">America/Asuncion</option>
                                                                    <option value="America/Atikokan">America/Atikokan</option>
                                                                    <option value="America/Bahia">America/Bahia</option>
                                                                    <option value="America/Bahia_Banderas">America/Bahia_Banderas</option>
                                                                    <option value="America/Barbados">America/Barbados</option>
                                                                    <option value="America/Belem">America/Belem</option>
                                                                    <option value="America/Belize">America/Belize</option>
                                                                    <option value="America/Blanc-Sablon">America/Blanc-Sablon</option>
                                                                    <option value="America/Boa_Vista">America/Boa_Vista</option>
                                                                    <option value="America/Bogota">America/Bogota</option>
                                                                    <option value="America/Boise">America/Boise</option>
                                                                    <option value="America/Cambridge_Bay">America/Cambridge_Bay</option>
                                                                    <option value="America/Campo_Grande">America/Campo_Grande</option>
                                                                    <option value="America/Cancun">America/Cancun</option>
                                                                    <option value="America/Caracas">America/Caracas</option>
                                                                    <option value="America/Cayenne">America/Cayenne</option>
                                                                    <option value="America/Cayman">America/Cayman</option>
                                                                    <option value="America/Chicago">America/Chicago</option>
                                                                    <option value="America/Chihuahua">America/Chihuahua</option>
                                                                    <option value="America/Costa_Rica">America/Costa_Rica</option>
                                                                    <option value="America/Creston">America/Creston</option>
                                                                    <option value="America/Cuiaba">America/Cuiaba</option>
                                                                    <option value="America/Curacao">America/Curacao</option>
                                                                    <option value="America/Danmarkshavn">America/Danmarkshavn</option>
                                                                    <option value="America/Dawson">America/Dawson</option>
                                                                    <option value="America/Dawson_Creek">America/Dawson_Creek</option>
                                                                    <option value="America/Denver">America/Denver</option>
                                                                    <option value="America/Detroit">America/Detroit</option>
                                                                    <option value="America/Dominica">America/Dominica</option>
                                                                    <option value="America/Edmonton">America/Edmonton</option>
                                                                    <option value="America/Eirunepe">America/Eirunepe</option>
                                                                    <option value="America/El_Salvador">America/El_Salvador</option>
                                                                    <option value="America/Fort_Nelson">America/Fort_Nelson</option>
                                                                    <option value="America/Fortaleza">America/Fortaleza</option>
                                                                    <option value="America/Glace_Bay">America/Glace_Bay</option>
                                                                    <option value="America/Godthab">America/Godthab</option>
                                                                    <option value="America/Goose_Bay">America/Goose_Bay</option>
                                                                    <option value="America/Grand_Turk">America/Grand_Turk</option>
                                                                    <option value="America/Grenada">America/Grenada</option>
                                                                    <option value="America/Guadeloupe">America/Guadeloupe</option>
                                                                    <option value="America/Guatemala">America/Guatemala</option>
                                                                    <option value="America/Guayaquil">America/Guayaquil</option>
                                                                    <option value="America/Guyana">America/Guyana</option>
                                                                    <option value="America/Halifax">America/Halifax</option>
                                                                    <option value="America/Havana">America/Havana</option>
                                                                    <option value="America/Hermosillo">America/Hermosillo</option>
                                                                    <option value="America/Indiana/Indianapolis">America/Indiana/Indianapolis</option>
                                                                    <option value="America/Indiana/Knox">America/Indiana/Knox</option>
                                                                    <option value="America/Indiana/Marengo">America/Indiana/Marengo</option>
                                                                    <option value="America/Indiana/Petersburg">America/Indiana/Petersburg</option>
                                                                    <option value="America/Indiana/Tell_City">America/Indiana/Tell_City</option>
                                                                    <option value="America/Indiana/Vevay">America/Indiana/Vevay</option>
                                                                    <option value="America/Indiana/Vincennes">America/Indiana/Vincennes</option>
                                                                    <option value="America/Indiana/Winamac">America/Indiana/Winamac</option>
                                                                    <option value="America/Inuvik">America/Inuvik</option>
                                                                    <option value="America/Iqaluit">America/Iqaluit</option>
                                                                    <option value="America/Jamaica">America/Jamaica</option>
                                                                    <option value="America/Juneau">America/Juneau</option>
                                                                    <option value="America/Kentucky/Louisville">America/Kentucky/Louisville</option>
                                                                    <option value="America/Kentucky/Monticello">America/Kentucky/Monticello</option>
                                                                    <option value="America/Kralendijk">America/Kralendijk</option>
                                                                    <option value="America/La_Paz">America/La_Paz</option>
                                                                    <option value="America/Lima">America/Lima</option>
                                                                    <option value="America/Los_Angeles">America/Los_Angeles</option>
                                                                    <option value="America/Lower_Princes">America/Lower_Princes</option>
                                                                    <option value="America/Maceio">America/Maceio</option>
                                                                    <option value="America/Managua">America/Managua</option>
                                                                    <option value="America/Manaus">America/Manaus</option>
                                                                    <option value="America/Marigot">America/Marigot</option>
                                                                    <option value="America/Martinique">America/Martinique</option>
                                                                    <option value="America/Matamoros">America/Matamoros</option>
                                                                    <option value="America/Mazatlan">America/Mazatlan</option>
                                                                    <option value="America/Menominee">America/Menominee</option>
                                                                    <option value="America/Merida">America/Merida</option>
                                                                    <option value="America/Metlakatla">America/Metlakatla</option>
                                                                    <option value="America/Mexico_City">America/Mexico_City</option>
                                                                    <option value="America/Miquelon">America/Miquelon</option>
                                                                    <option value="America/Moncton">America/Moncton</option>
                                                                    <option value="America/Monterrey">America/Monterrey</option>
                                                                    <option value="America/Montevideo">America/Montevideo</option>
                                                                    <option value="America/Montserrat">America/Montserrat</option>
                                                                    <option value="America/Nassau">America/Nassau</option>
                                                                    <option value="America/New_York">America/New_York</option>
                                                                    <option value="America/Nipigon">America/Nipigon</option>
                                                                    <option value="America/Nome">America/Nome</option>
                                                                    <option value="America/Noronha">America/Noronha</option>
                                                                    <option value="America/North_Dakota/Beulah">America/North_Dakota/Beulah</option>
                                                                    <option value="America/North_Dakota/Center">America/North_Dakota/Center</option>
                                                                    <option value="America/North_Dakota/New_Salem">America/North_Dakota/New_Salem</option>
                                                                    <option value="America/Ojinaga">America/Ojinaga</option>
                                                                    <option value="America/Panama">America/Panama</option>
                                                                    <option value="America/Pangnirtung">America/Pangnirtung</option>
                                                                    <option value="America/Paramaribo">America/Paramaribo</option>
                                                                    <option value="America/Phoenix">America/Phoenix</option>
                                                                    <option value="America/Port-au-Prince">America/Port-au-Prince</option>
                                                                    <option value="America/Port_of_Spain">America/Port_of_Spain</option>
                                                                    <option value="America/Porto_Velho">America/Porto_Velho</option>
                                                                    <option value="America/Puerto_Rico">America/Puerto_Rico</option>
                                                                    <option value="America/Punta_Arenas">America/Punta_Arenas</option>
                                                                    <option value="America/Rainy_River">America/Rainy_River</option>
                                                                    <option value="America/Rankin_Inlet">America/Rankin_Inlet</option>
                                                                    <option value="America/Recife">America/Recife</option>
                                                                    <option value="America/Regina">America/Regina</option>
                                                                    <option value="America/Resolute">America/Resolute</option>
                                                                    <option value="America/Rio_Branco">America/Rio_Branco</option>
                                                                    <option value="America/Santarem">America/Santarem</option>
                                                                    <option value="America/Santiago">America/Santiago</option>
                                                                    <option value="America/Santo_Domingo">America/Santo_Domingo</option>
                                                                    <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                                                    <option value="America/Scoresbysund">America/Scoresbysund</option>
                                                                    <option value="America/Sitka">America/Sitka</option>
                                                                    <option value="America/St_Barthelemy">America/St_Barthelemy</option>
                                                                    <option value="America/St_Johns">America/St_Johns</option>
                                                                    <option value="America/St_Kitts">America/St_Kitts</option>
                                                                    <option value="America/St_Lucia">America/St_Lucia</option>
                                                                    <option value="America/St_Thomas">America/St_Thomas</option>
                                                                    <option value="America/St_Vincent">America/St_Vincent</option>
                                                                    <option value="America/Swift_Current">America/Swift_Current</option>
                                                                    <option value="America/Tegucigalpa">America/Tegucigalpa</option>
                                                                    <option value="America/Thule">America/Thule</option>
                                                                    <option value="America/Thunder_Bay">America/Thunder_Bay</option>
                                                                    <option value="America/Tijuana">America/Tijuana</option>
                                                                    <option value="America/Toronto">America/Toronto</option>
                                                                    <option value="America/Tortola">America/Tortola</option>
                                                                    <option value="America/Vancouver">America/Vancouver</option>
                                                                    <option value="America/Whitehorse">America/Whitehorse</option>
                                                                    <option value="America/Winnipeg">America/Winnipeg</option>
                                                                    <option value="America/Yakutat">America/Yakutat</option>
                                                                    <option value="America/Yellowknife">America/Yellowknife</option>
                                                                </optgroup>
                                                                <optgroup label="INDIAN">
                                                                    <option value="Indian/Antananarivo">Indian/Antananarivo</option>
                                                                    <option value="Indian/Chagos">Indian/Chagos</option>
                                                                    <option value="Indian/Christmas">Indian/Christmas</option>
                                                                    <option value="Indian/Cocos">Indian/Cocos</option>
                                                                    <option value="Indian/Comoro">Indian/Comoro</option>
                                                                    <option value="Indian/Kerguelen">Indian/Kerguelen</option>
                                                                    <option value="Indian/Mahe">Indian/Mahe</option>
                                                                    <option value="Indian/Maldives">Indian/Maldives</option>
                                                                    <option value="Indian/Mauritius">Indian/Mauritius</option>
                                                                    <option value="Indian/Mayotte">Indian/Mayotte</option>
                                                                    <option value="Indian/Reunion">Indian/Reunion</option>
                                                                </optgroup>
                                                                <optgroup label="AUSTRALIA">
                                                                    <option value="Australia/Adelaide">Australia/Adelaide</option>
                                                                    <option value="Australia/Brisbane">Australia/Brisbane</option>
                                                                    <option value="Australia/Broken_Hill">Australia/Broken_Hill</option>
                                                                    <option value="Australia/Currie">Australia/Currie</option>
                                                                    <option value="Australia/Darwin">Australia/Darwin</option>
                                                                    <option value="Australia/Eucla">Australia/Eucla</option>
                                                                    <option value="Australia/Hobart">Australia/Hobart</option>
                                                                    <option value="Australia/Lindeman">Australia/Lindeman</option>
                                                                    <option value="Australia/Lord_Howe">Australia/Lord_Howe</option>
                                                                    <option value="Australia/Melbourne">Australia/Melbourne</option>
                                                                    <option value="Australia/Perth">Australia/Perth</option>
                                                                    <option value="Australia/Sydney">Australia/Sydney</option>
                                                                </optgroup>
                                                                <optgroup label="ASIA">
                                                                    <option value="Asia/Aden">Asia/Aden</option>
                                                                    <option value="Asia/Almaty">Asia/Almaty</option>
                                                                    <option value="Asia/Amman">Asia/Amman</option>
                                                                    <option value="Asia/Anadyr">Asia/Anadyr</option>
                                                                    <option value="Asia/Aqtau">Asia/Aqtau</option>
                                                                    <option value="Asia/Aqtobe">Asia/Aqtobe</option>
                                                                    <option value="Asia/Ashgabat">Asia/Ashgabat</option>
                                                                    <option value="Asia/Atyrau">Asia/Atyrau</option>
                                                                    <option value="Asia/Baghdad">Asia/Baghdad</option>
                                                                    <option value="Asia/Bahrain">Asia/Bahrain</option>
                                                                    <option value="Asia/Baku">Asia/Baku</option>
                                                                    <option value="Asia/Bangkok">Asia/Bangkok</option>
                                                                    <option value="Asia/Barnaul">Asia/Barnaul</option>
                                                                    <option value="Asia/Beirut">Asia/Beirut</option>
                                                                    <option value="Asia/Bishkek">Asia/Bishkek</option>
                                                                    <option value="Asia/Brunei">Asia/Brunei</option>
                                                                    <option value="Asia/Chita">Asia/Chita</option>
                                                                    <option value="Asia/Choibalsan">Asia/Choibalsan</option>
                                                                    <option value="Asia/Colombo">Asia/Colombo</option>
                                                                    <option value="Asia/Damascus">Asia/Damascus</option>
                                                                    <option value="Asia/Dhaka">Asia/Dhaka</option>
                                                                    <option value="Asia/Dili">Asia/Dili</option>
                                                                    <option value="Asia/Dubai">Asia/Dubai</option>
                                                                    <option value="Asia/Dushanbe">Asia/Dushanbe</option>
                                                                    <option value="Asia/Famagusta">Asia/Famagusta</option>
                                                                    <option value="Asia/Gaza">Asia/Gaza</option>
                                                                    <option value="Asia/Hebron">Asia/Hebron</option>
                                                                    <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                                                                    <option value="Asia/Hong_Kong">Asia/Hong_Kong</option>
                                                                    <option value="Asia/Hovd">Asia/Hovd</option>
                                                                    <option value="Asia/Irkutsk">Asia/Irkutsk</option>
                                                                    <option value="Asia/Jakarta">Asia/Jakarta</option>
                                                                    <option value="Asia/Jayapura">Asia/Jayapura</option>
                                                                    <option value="Asia/Jerusalem">Asia/Jerusalem</option>
                                                                    <option value="Asia/Kabul">Asia/Kabul</option>
                                                                    <option value="Asia/Kamchatka">Asia/Kamchatka</option>
                                                                    <option value="Asia/Karachi">Asia/Karachi</option>
                                                                    <option value="Asia/Kathmandu">Asia/Kathmandu</option>
                                                                    <option value="Asia/Khandyga">Asia/Khandyga</option>
                                                                    <option value="Asia/Kolkata">Asia/Kolkata</option>
                                                                    <option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk</option>
                                                                    <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur</option>
                                                                    <option value="Asia/Kuching">Asia/Kuching</option>
                                                                    <option value="Asia/Kuwait">Asia/Kuwait</option>
                                                                    <option value="Asia/Macau">Asia/Macau</option>
                                                                    <option value="Asia/Magadan">Asia/Magadan</option>
                                                                    <option value="Asia/Makassar">Asia/Makassar</option>
                                                                    <option value="Asia/Manila">Asia/Manila</option>
                                                                    <option value="Asia/Muscat">Asia/Muscat</option>
                                                                    <option value="Asia/Nicosia">Asia/Nicosia</option>
                                                                    <option value="Asia/Novokuznetsk">Asia/Novokuznetsk</option>
                                                                    <option value="Asia/Novosibirsk">Asia/Novosibirsk</option>
                                                                    <option value="Asia/Omsk">Asia/Omsk</option>
                                                                    <option value="Asia/Oral">Asia/Oral</option>
                                                                    <option value="Asia/Phnom_Penh">Asia/Phnom_Penh</option>
                                                                    <option value="Asia/Pontianak">Asia/Pontianak</option>
                                                                    <option value="Asia/Pyongyang">Asia/Pyongyang</option>
                                                                    <option value="Asia/Qatar">Asia/Qatar</option>
                                                                    <option value="Asia/Qostanay">Asia/Qostanay</option>
                                                                    <option value="Asia/Qyzylorda">Asia/Qyzylorda</option>
                                                                    <option value="Asia/Riyadh">Asia/Riyadh</option>
                                                                    <option value="Asia/Sakhalin">Asia/Sakhalin</option>
                                                                    <option value="Asia/Samarkand">Asia/Samarkand</option>
                                                                    <option value="Asia/Seoul">Asia/Seoul</option>
                                                                    <option value="Asia/Shanghai">Asia/Shanghai</option>
                                                                    <option value="Asia/Singapore">Asia/Singapore</option>
                                                                    <option value="Asia/Srednekolymsk">Asia/Srednekolymsk</option>
                                                                    <option value="Asia/Taipei">Asia/Taipei</option>
                                                                    <option value="Asia/Tashkent">Asia/Tashkent</option>
                                                                    <option value="Asia/Tbilisi">Asia/Tbilisi</option>
                                                                    <option value="Asia/Tehran">Asia/Tehran</option>
                                                                    <option value="Asia/Thimphu">Asia/Thimphu</option>
                                                                    <option value="Asia/Tokyo">Asia/Tokyo</option>
                                                                    <option value="Asia/Tomsk">Asia/Tomsk</option>
                                                                    <option value="Asia/Ulaanbaatar">Asia/Ulaanbaatar</option>
                                                                    <option value="Asia/Urumqi">Asia/Urumqi</option>
                                                                    <option value="Asia/Ust-Nera">Asia/Ust-Nera</option>
                                                                    <option value="Asia/Vientiane">Asia/Vientiane</option>
                                                                    <option value="Asia/Vladivostok">Asia/Vladivostok</option>
                                                                    <option value="Asia/Yakutsk">Asia/Yakutsk</option>
                                                                    <option value="Asia/Yangon">Asia/Yangon</option>
                                                                    <option value="Asia/Yekaterinburg">Asia/Yekaterinburg</option>
                                                                    <option value="Asia/Yerevan">Asia/Yerevan</option>
                                                                </optgroup>
                                                                <optgroup label="AFRICA">
                                                                    <option value="Africa/Abidjan">Africa/Abidjan</option>
                                                                    <option value="Africa/Accra">Africa/Accra</option>
                                                                    <option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
                                                                    <option value="Africa/Algiers">Africa/Algiers</option>
                                                                    <option value="Africa/Asmara">Africa/Asmara</option>
                                                                    <option value="Africa/Bamako">Africa/Bamako</option>
                                                                    <option value="Africa/Bangui">Africa/Bangui</option>
                                                                    <option value="Africa/Banjul">Africa/Banjul</option>
                                                                    <option value="Africa/Bissau">Africa/Bissau</option>
                                                                    <option value="Africa/Blantyre">Africa/Blantyre</option>
                                                                    <option value="Africa/Brazzaville">Africa/Brazzaville</option>
                                                                    <option value="Africa/Bujumbura">Africa/Bujumbura</option>
                                                                    <option value="Africa/Cairo">Africa/Cairo</option>
                                                                    <option value="Africa/Casablanca">Africa/Casablanca</option>
                                                                    <option value="Africa/Ceuta">Africa/Ceuta</option>
                                                                    <option value="Africa/Conakry">Africa/Conakry</option>
                                                                    <option value="Africa/Dakar">Africa/Dakar</option>
                                                                    <option value="Africa/Dar_es_Salaam">Africa/Dar_es_Salaam</option>
                                                                    <option value="Africa/Djibouti">Africa/Djibouti</option>
                                                                    <option value="Africa/Douala">Africa/Douala</option>
                                                                    <option value="Africa/El_Aaiun">Africa/El_Aaiun</option>
                                                                    <option value="Africa/Freetown">Africa/Freetown</option>
                                                                    <option value="Africa/Gaborone">Africa/Gaborone</option>
                                                                    <option value="Africa/Harare">Africa/Harare</option>
                                                                    <option value="Africa/Johannesburg">Africa/Johannesburg</option>
                                                                    <option value="Africa/Juba">Africa/Juba</option>
                                                                    <option value="Africa/Kampala">Africa/Kampala</option>
                                                                    <option value="Africa/Khartoum">Africa/Khartoum</option>
                                                                    <option value="Africa/Kigali">Africa/Kigali</option>
                                                                    <option value="Africa/Kinshasa">Africa/Kinshasa</option>
                                                                    <option value="Africa/Lagos">Africa/Lagos</option>
                                                                    <option value="Africa/Libreville">Africa/Libreville</option>
                                                                    <option value="Africa/Lome">Africa/Lome</option>
                                                                    <option value="Africa/Luanda">Africa/Luanda</option>
                                                                    <option value="Africa/Lubumbashi">Africa/Lubumbashi</option>
                                                                    <option value="Africa/Lusaka">Africa/Lusaka</option>
                                                                    <option value="Africa/Malabo">Africa/Malabo</option>
                                                                    <option value="Africa/Maputo">Africa/Maputo</option>
                                                                    <option value="Africa/Maseru">Africa/Maseru</option>
                                                                    <option value="Africa/Mbabane">Africa/Mbabane</option>
                                                                    <option value="Africa/Mogadishu">Africa/Mogadishu</option>
                                                                    <option value="Africa/Monrovia">Africa/Monrovia</option>
                                                                    <option value="Africa/Nairobi">Africa/Nairobi</option>
                                                                    <option value="Africa/Ndjamena">Africa/Ndjamena</option>
                                                                    <option value="Africa/Niamey">Africa/Niamey</option>
                                                                    <option value="Africa/Nouakchott">Africa/Nouakchott</option>
                                                                    <option value="Africa/Ouagadougou">Africa/Ouagadougou</option>
                                                                    <option value="Africa/Porto-Novo">Africa/Porto-Novo</option>
                                                                    <option value="Africa/Sao_Tome">Africa/Sao_Tome</option>
                                                                    <option value="Africa/Tripoli">Africa/Tripoli</option>
                                                                    <option value="Africa/Tunis">Africa/Tunis</option>
                                                                    <option value="Africa/Windhoek">Africa/Windhoek</option>
                                                                </optgroup>
                                                                <optgroup label="ANTARCTICA">
                                                                    <option value="Antarctica/Casey">Antarctica/Casey</option>
                                                                    <option value="Antarctica/Davis">Antarctica/Davis</option>
                                                                    <option value="Antarctica/DumontDUrville">Antarctica/DumontDUrville</option>
                                                                    <option value="Antarctica/Macquarie">Antarctica/Macquarie</option>
                                                                    <option value="Antarctica/Mawson">Antarctica/Mawson</option>
                                                                    <option value="Antarctica/McMurdo">Antarctica/McMurdo</option>
                                                                    <option value="Antarctica/Palmer">Antarctica/Palmer</option>
                                                                    <option value="Antarctica/Rothera">Antarctica/Rothera</option>
                                                                    <option value="Antarctica/Syowa">Antarctica/Syowa</option>
                                                                    <option value="Antarctica/Troll">Antarctica/Troll</option>
                                                                    <option value="Antarctica/Vostok">Antarctica/Vostok</option>
                                                                </optgroup>
                                                                <optgroup label="ARCTIC">
                                                                    <option value="Arctic/Longyearbyen">Arctic/Longyearbyen</option>
                                                                </optgroup>
                                                                <optgroup label="ATLANTIC">
                                                                    <option value="Atlantic/Azores">Atlantic/Azores</option>
                                                                    <option value="Atlantic/Bermuda">Atlantic/Bermuda</option>
                                                                    <option value="Atlantic/Canary">Atlantic/Canary</option>
                                                                    <option value="Atlantic/Cape_Verde">Atlantic/Cape_Verde</option>
                                                                    <option value="Atlantic/Faroe">Atlantic/Faroe</option>
                                                                    <option value="Atlantic/Madeira">Atlantic/Madeira</option>
                                                                    <option value="Atlantic/Reykjavik">Atlantic/Reykjavik</option>
                                                                    <option value="Atlantic/South_Georgia">Atlantic/South_Georgia</option>
                                                                    <option value="Atlantic/St_Helena">Atlantic/St_Helena</option>
                                                                    <option value="Atlantic/Stanley">Atlantic/Stanley</option>
                                                                </optgroup>
                                                                <optgroup label="PACIFIC">
                                                                    <option value="Pacific/Apia">Pacific/Apia</option>
                                                                    <option value="Pacific/Auckland">Pacific/Auckland</option>
                                                                    <option value="Pacific/Bougainville">Pacific/Bougainville</option>
                                                                    <option value="Pacific/Chatham">Pacific/Chatham</option>
                                                                    <option value="Pacific/Chuuk">Pacific/Chuuk</option>
                                                                    <option value="Pacific/Easter">Pacific/Easter</option>
                                                                    <option value="Pacific/Efate">Pacific/Efate</option>
                                                                    <option value="Pacific/Enderbury">Pacific/Enderbury</option>
                                                                    <option value="Pacific/Fakaofo">Pacific/Fakaofo</option>
                                                                    <option value="Pacific/Fiji">Pacific/Fiji</option>
                                                                    <option value="Pacific/Funafuti">Pacific/Funafuti</option>
                                                                    <option value="Pacific/Galapagos">Pacific/Galapagos</option>
                                                                    <option value="Pacific/Gambier">Pacific/Gambier</option>
                                                                    <option value="Pacific/Guadalcanal">Pacific/Guadalcanal</option>
                                                                    <option value="Pacific/Guam">Pacific/Guam</option>
                                                                    <option value="Pacific/Honolulu">Pacific/Honolulu</option>
                                                                    <option value="Pacific/Kiritimati">Pacific/Kiritimati</option>
                                                                    <option value="Pacific/Kosrae">Pacific/Kosrae</option>
                                                                    <option value="Pacific/Kwajalein">Pacific/Kwajalein</option>
                                                                    <option value="Pacific/Majuro">Pacific/Majuro</option>
                                                                    <option value="Pacific/Marquesas">Pacific/Marquesas</option>
                                                                    <option value="Pacific/Midway">Pacific/Midway</option>
                                                                    <option value="Pacific/Nauru">Pacific/Nauru</option>
                                                                    <option value="Pacific/Niue">Pacific/Niue</option>
                                                                    <option value="Pacific/Norfolk">Pacific/Norfolk</option>
                                                                    <option value="Pacific/Noumea">Pacific/Noumea</option>
                                                                    <option value="Pacific/Pago_Pago">Pacific/Pago_Pago</option>
                                                                    <option value="Pacific/Palau">Pacific/Palau</option>
                                                                    <option value="Pacific/Pitcairn">Pacific/Pitcairn</option>
                                                                    <option value="Pacific/Pohnpei">Pacific/Pohnpei</option>
                                                                    <option value="Pacific/Port_Moresby">Pacific/Port_Moresby</option>
                                                                    <option value="Pacific/Rarotonga">Pacific/Rarotonga</option>
                                                                    <option value="Pacific/Saipan">Pacific/Saipan</option>
                                                                    <option value="Pacific/Tahiti">Pacific/Tahiti</option>
                                                                    <option value="Pacific/Tarawa">Pacific/Tarawa</option>
                                                                    <option value="Pacific/Tongatapu">Pacific/Tongatapu</option>
                                                                    <option value="Pacific/Wake">Pacific/Wake</option>
                                                                    <option value="Pacific/Wallis">Pacific/Wallis</option>
                                                                </optgroup>
                                                                <optgroup label="UTC">
                                                                    <option value="UTC">UTC</option>
                                                                </optgroup>
                                                            </select><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" role="combobox" aria-owns="bs-select-2" aria-haspopup="listbox" aria-expanded="false" data-id="timezones" title="Europe/Amsterdam"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">Europe/Amsterdam</div></div> </div><span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-2" aria-autocomplete="list"></div><div class="inner open" role="listbox" id="bs-select-2" tabindex="-1"><ul class="dropdown-menu inner " role="presentation"></ul></div></div></div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="plan" class="control-label"><?= _l('plan'); ?></label>
                                                        <div class="dropdown bootstrap-select disabled form-control bs3"><select name="plan" id="plan" class="form-control selectpicker" data-none-selected-text="Nothing selected" data-live-search="true" disabled="" tabindex="-98">
                                                                <option value=""></option>
                                                                <option value="1">Bronze Plan</option>
                                                                <option value="2" selected="">silver plan</option>
                                                            </select><button type="button" class="btn dropdown-toggle disabled btn-default" data-toggle="dropdown" role="combobox" aria-owns="bs-select-3" aria-haspopup="listbox" aria-expanded="false" data-id="plan" tabindex="-1" aria-disabled="true" title="silver plan"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">silver plan</div></div> </div><span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open"><div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-3" aria-autocomplete="list"></div><div class="inner open" role="listbox" id="bs-select-3" tabindex="-1"><ul class="dropdown-menu inner " role="presentation"></ul></div></div></div>
                                                    </div>
                                                </div>


                                                <div class="col-md-3" style="padding-top: 33px;">
                                                    <div class="form-group">
                                                        <div class="checkbox-inline checkbox checkbox-primary">
                                                            <input type="checkbox" name="active" id="active" class="" value="1" checked="">
                                                            <label for="active"><?= _l('activate'); ?></label>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-3" style="padding-top: 33px;">
                                                    <div class="form-group">
                                                        <a href="http://perfex244.test/subscription/bba22af48029a447253cc243541a2897" target="_blank">View Subscription</a>

                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="instance_permission">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="staff_limit"><label for="staff_limit" class="control-label">Staff Limit</label><input type="number" id="staff_limit" name="staff_limit" class="form-control" min="0" value="0"></div>                                                </div>
                                        </div>

                                        <div class="col-md-12" style="padding-top: 17px;">

                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[dashboard]" id="dashboard" class="" value="1" checked="">
                                                    <label for="dashboard">Dashboard</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[customers]" id="customers" class="" value="1" checked="">
                                                    <label for="customers">Customers</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[proposals]" id="proposals" class="" value="1">
                                                    <label for="proposals">Proposals</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[estimates]" id="estimates" class="" value="1">
                                                    <label for="estimates">Estimates</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[invoices]" id="invoices" class="" value="1" checked="">
                                                    <label for="invoices">Invoices</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[payments]" id="payments" class="" value="1" checked="">
                                                    <label for="payments">Payments</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[credit_notes]" id="credit_notes" class="" value="1">
                                                    <label for="credit_notes">Credit Notes</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[items]" id="items" class="" value="1">
                                                    <label for="items">Items</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[subscriptions]" id="subscriptions" class="" value="1">
                                                    <label for="subscriptions">Subscriptions</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[expenses]" id="expenses" class="" value="1">
                                                    <label for="expenses">Expenses</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[contracts]" id="contracts" class="" value="1">
                                                    <label for="contracts">Contracts</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[projects]" id="projects" class="" value="1">
                                                    <label for="projects">Projects</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[tasks]" id="tasks" class="" value="1">
                                                    <label for="tasks">Tasks</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[support]" id="support" class="" value="1">
                                                    <label for="support">Support</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[leads]" id="leads" class="" value="1">
                                                    <label for="leads">Leads</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[knowledge_base]" id="knowledge_base" class="" value="1">
                                                    <label for="knowledge_base">Knowledge base</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[media]" id="media" class="" value="1">
                                                    <label for="media">Media</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[bulk_pdf_expoter]" id="bulk_pdf_expoter" class="" value="1">
                                                    <label for="bulk_pdf_expoter">bulk Pdf expoter</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[calendar]" id="calendar" class="" value="1">
                                                    <label for="calendar">Calendar</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[announcements]" id="announcements" class="" value="1">
                                                    <label for="announcements">Announcements</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[activity_log]" id="activity_log" class="" value="1">
                                                    <label for="activity_log">Activity log</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[ticket_pipe_log]" id="ticket_pipe_log" class="" value="1">
                                                    <label for="ticket_pipe_log">Ticket pipe log</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[sales_reports]" id="sales_reports" class="" value="1">
                                                    <label for="sales_reports">Sales reports</label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12" style="padding-top: 17px;">

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[departments]" id="departments" class="" value="1">
                                                    <label for="departments">Departments</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[tickets_predifined_replies]" id="tickets_predifined_replies" class="" value="1">
                                                    <label for="tickets_predifined_replies">Tickets predifined replies</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[tickets_priorities]" id="tickets_priorities" class="" value="1">
                                                    <label for="tickets_priorities">Tickets priorities</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[tickets_statuses]" id="tickets_statuses" class="" value="1">
                                                    <label for="tickets_statuses">Tickets statuses</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[tickets_services]" id="tickets_services" class="" value="1">
                                                    <label for="tickets_services">Tickets services</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[tickets_spam_filters]" id="tickets_spam_filters" class="" value="1">
                                                    <label for="tickets_spam_filters">Tickets spam filters</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[leads_sources]" id="leads_sources" class="" value="1">
                                                    <label for="leads_sources">Leads sources</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[leads_statuses]" id="leads_statuses" class="" value="1">
                                                    <label for="leads_statuses">Leads statuses</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[leads_email_integration]" id="leads_email_integration" class="" value="1">
                                                    <label for="leads_email_integration">Leads email integration</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[web_to_lead]" id="web_to_lead" class="" value="1">
                                                    <label for="web_to_lead">Web to lead</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[taxes]" id="taxes" class="" value="1">
                                                    <label for="taxes">Taxes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[currencies]" id="currencies" class="" value="1">
                                                    <label for="currencies">Currencies</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[payment_modes]" id="payment_modes" class="" value="1">
                                                    <label for="payment_modes">Payment modes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[expenses_categories]" id="expenses_categories" class="" value="1">
                                                    <label for="expenses_categories">Expenses categories</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[contracts_types]" id="contracts_types" class="" value="1">
                                                    <label for="contracts_types">Contracts types</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[modules]" id="modules" class="" value="1">
                                                    <label for="modules">Modules</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[email_templates]" id="email_templates" class="" value="1">
                                                    <label for="email_templates">Email templates</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[custom_fields]" id="custom_fields" class="" value="1">
                                                    <label for="custom_fields">Custom fields</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[gdpr]" id="gdpr" class="" value="1">
                                                    <label for="gdpr">Gdpr</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[roles]" id="roles" class="" value="1">
                                                    <label for="roles">Roles</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[settings]" id="settings" class="" value="1">
                                                    <label for="settings">Settings</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[mailbox]" id="mailbox" class="" value="1">
                                                    <label for="mailbox">Mailbox</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[prchat]" id="prchat" class="" value="1">
                                                    <label for="prchat">Chat</label>
                                                </div>
                                            </div>


                                            <div class="col-md-3" style="padding-top: 15px;display: none !important;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[database_backup]" id="database_backup" class="" value="1">
                                                    <label for="database_backup">Database Backup</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[staff]" id="staff" class="" value="1">
                                                    <label for="staff">Staff</label>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 15px;">
                                                <div class="checkbox-inline checkbox checkbox-primary">
                                                    <input type="checkbox" name="permission[customers_group]" id="customers_group" class="" value="1">
                                                    <label for="customers_group">Customers group</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane active" id="instance_style">

                                        <div class="row col-md-12" style="padding-top: 17px;">

                                            <div class="col-md-3">

                                                <div class="panel_s">
                                                    <div class="panel-body left-sidebar-profile-tabs">
                                                        <ul class="nav navbar-pills nav-tabs nav-stacked product-tabs" role="tablist">
                                                            <!--<li role="presentation" class="active"><a href="#warehouse_tab" aria-controls="warehouse_tab" role="tab" data-toggle="tab" aria-expanded="true" class="warehouse_tab"><?= _l('warehouses'); ?></a></li>-->
                                                             <li role="presentation" class="active" id="categories_tab_li"><a href="#categories_tab" aria-controls="categories_tab" role="tab" data-toggle="tab" aria-expanded="true" class="categories_tab"><?= _l('Settings'); ?></a></li> 
                                                        </ul>
                                                    </div> 
                                                </div>

                                            </div>
                                            <div class="col-md-9">
                                                <div class="panel_s">
                                                    <div class="panel-body">
                                                        <div class="clearfix"></div>
                                                        <div>
                                                            <div class="tab-content">
                                                                <div role="tabpanel" class="tab-pane active" id="categories_tab">

                                                                    <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="row col-md-12">
                                                                                
                                                                                <?php echo form_open(admin_url('stock_management/manage_settings/update_low_stock')); 
                                                                                echo render_input('sm_low_stock',_l('Low Stock Value'),get_option('sm_low_stock'),'number', ['placeholder' => 'Enter low stock value']);
                                                                                ?>
                                                                                
                                                                                <div class="_buttons">
                                                                                    <button type="submit" class="btn btn-info " ><?= _l('Update') ?></button>
                                                                                </div>
                                                                                 <?= form_close(); ?>
                                                                            </div>
                                                                           
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                            </div>

                                                                <!--<div role="tabpanel" class="tab-pane" id="warehouse_tab">
                                                                        <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="row col-md-12">
                                                                                <div class="_buttons">
                                                                                    <button type="button" class="btn btn-info btn-lg warehouse_add" data-toggle="modal" data-target="#warehouse_modal"><?= _l('add').' '._l('new').' '._l('warehouse'); ?></button>
                                                                                </div>
                                                                                <hr>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <?php render_datatable([
                                                                                    _l('id'),
                                                                                    _l('name'),
                                                                                     _l('actions'),
                                                                                ], 'warehouse'); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>-->

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>

<div class="modal fade" id="product_categories_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('stock_management/manage_settings/add_category')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?= _l('add').' '._l('category'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                            <?php
                            echo render_input('name',_l('category').' '._l('name')); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                        echo render_textarea('description',_l('category').' '._l('description')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= htmlspecialchars(_l('close')); ?></button>
                <button type="submit" class="btn btn-info"><?= htmlspecialchars(_l('submit')); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?= form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="category_modal_edit" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('stock_management/manage_settings/edit_category')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?= _l('edit').' '._l('category'); ?></span>
                </h4>
            </div>
            <input type="hidden" name="id" id="categoryid" value="">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo render_input('name',_l('category').' '._l('name'),'','',[],[],'','category_name_v'); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                        echo render_textarea('description',_l('category').' '._l('description'),'',[],[],'','description_v'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= htmlspecialchars(_l('close')); ?></button>
                <button type="submit" class="btn btn-info"><?= htmlspecialchars(_l('submit')); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?= form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="warehouse_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('stock_management/manage_settings/add_warehouse')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?= _l('add').' '._l('warehouse'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo render_input('name',_l('warehouse').' '._l('name')); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                        echo render_textarea('address',_l('address')); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                        echo render_textarea('description',_l('description')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= htmlspecialchars(_l('close')); ?></button>
                <button type="submit" class="btn btn-info"><?= htmlspecialchars(_l('submit')); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?= form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="warehouse_modal_edit" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('stock_management/manage_settings/edit_warehouse')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?= _l('edit').' '._l('warehouse'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id" id="warehouseid" value="">
                        <?php
                        echo render_input('name',_l('warehouse').' '._l('name'),'','',[],[],'','warehouse_name'); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                        echo render_textarea('address',_l('address'),'',[],[],'','warehouse_address'); ?>
                    </div>

                    <div class="col-md-12">
                        <?php
                        echo render_textarea('description',_l('description'),'',[],[],'','warehouse_description'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= htmlspecialchars(_l('close')); ?></button>
                <button type="submit" class="btn btn-info"><?= htmlspecialchars(_l('submit')); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?= form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script src="<?= module_dir_url('stock_management', 'assets/js/settings_js.js?v='.uniqid()); ?>"></script>


</body>
</html>
