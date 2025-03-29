<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Application For New Internet Service Provider (ISP) License Issue</h3>
    </div>
    <form id="quickForm" method="POST" action="{{route('isp-license.update',\App\Libraries\Encryption::encodeId($ispLicenseData->id))}}">
        @csrf
        @method('PUT')
        <div class="card-body border border-success">
            <div class="card card-success">
                @php
                    $shareholder = $ispLicenseData->shareHolder;
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="shareholder_name"
                                   class="col-md-4 required-star">Name</label>
                            <div class="col-md-8 ">
                                <input class="form-control shareholder_name required"
                                       placeholder="Enter Name" id="shareholder_name"
                                       name="shareholder_name" type="text"
                                       value="{{old('shareholder_name',$shareholder->name)}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shareholder_designation"
                                   class="col-md-4 required-star">Designation</label>
                            <div class="col-md-8 ">
                                <input class="form-control shareholder_designation required"
                                       placeholder="Enter Designation "
                                       id="shareholder_designation"
                                       name="shareholder_designation" type="text"
                                       value="{{old('shareholder_designation',$shareholder->designation)}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shareholder_email"
                                   class="col-md-4 required-star">Email</label>
                            <div class="col-md-8 ">
                                <input class="form-control shareholder_email required email"
                                       placeholder="Enter Email" id="shareholder_email"
                                       name="shareholder_email" type="text"
                                       value="{{old('shareholder_email',$shareholder->email)}}">

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shareholder_mobile" class="col-md-4 required-star">Mobile
                                Number</label>
                            <div class="col-md-8  ">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="iti-flag bd"></span>
                                                        <span>+88</span>
                                                    </span>
                                    </div>
                                    <input
                                        class="form-control shareholder_mobile required bd_mobile"
                                        placeholder="Enter Mobile Number"
                                        id="shareholder_mobile"
                                        name="shareholder_mobile" type="text"
                                        value="{{old('shareholder_mobile',$shareholder->mobile)}}">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shareholder_share_of" class="col-md-4 required-star">%
                                Of
                                Share</label>
                            <div class="col-md-8 ">
                                <input class="form-control shareholder_share_of required digits"
                                       placeholder="" id="shareholder_share_of"
                                       name="shareholder_share_of" type="number"
                                       value="{{old('shareholder_share_of',$shareholder->share_percent)}}">

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_of_share_1" class="col-md-4 required-star">No. of
                                Share</label>
                            <div class="col-md-8 ">
                                <input class="form-control no-of-share required"
                                       placeholder="Enter % Of Share" id="no_of_share_1"
                                       name="no_of_share" type="number"
                                       value="{{old('no_of_share',$shareholder->no_of_share)}}">

                            </div>
                        </div>
                        <div class="form-group row" id="nidBlock_1" style="display: none;">
                            <label for="shareholder_nid" class="col-md-4 ">NID No</label>
                            <div class="col-md-8 ">
                                <input class="form-control shareholder_nid"
                                       placeholder="Enter National ID No" id="shareholder_nid_1"
                                       name="shareholder_nid" type="text"
                                       value="{{old('shareholder_nid',$shareholder->nid)}}">
                            </div>
                        </div>
                        <div class="form-group row" id="passportBlock_1" style="display: none;">
                            <label for="shareholder_passport" class="col-md-4 ">Passport
                                No.</label>
                            <div class="col-md-8 ">
                                <input class="form-control shareholder_passport"
                                       placeholder="Enter Passport No"
                                       id="shareholder_passport_1"
                                       name="shareholder_passport" type="text"
                                       value="{{old('shareholder_passport',$shareholder->passport)}}">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row" style="margin-top:10px;">
                            <label for="shareholder_dob" class="col-md-4 required-star">Date of
                                Birth</label>
                            <div class="col-md-8 ">
                                <div class="input-group date datetimepicker4" id="datepicker0"
                                     data-target-input="nearest">
                                    <input class="form-control shareholder_dob required date"
                                           id="shareholder_dob"
                                           placeholder="Enter Date Of Birth"
                                           name="shareholder_dob" type="date"
                                           value="{{old('shareholder_dob',$shareholder->dob)}}">
                                    <div class="input-group-append"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text"><i
                                                class="fa fa-calendar"></i></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shareholder_nationality"
                                   class="col-md-4 required-star">Nationality</label>
                            <div class="col-md-8 ">
                                <select class="form-control shareholder_nationality required"
                                        id="shareholder_nationality"
                                        name="shareholder_nationality">
                                    <option value="" selected="selected">Select</option>
                                    <option value="1">Afghan</option>
                                    <option value="2">Albanian</option>
                                    <option value="3">Algerian</option>
                                    <option value="226">American</option>
                                    <option value="5">Andorran</option>
                                    <option value="6">Angolan</option>
                                    <option value="8">ANTARCTICA</option>
                                    <option value="7">Antiguans</option>
                                    <option value="10">Argentinean</option>
                                    <option value="11">Armenian</option>
                                    <option value="12">Aruban</option>
                                    <option value="13">Australian</option>
                                    <option value="14">Austrian</option>
                                    <option value="15">Azerbaijani</option>
                                    <option value="16">Bahamian</option>
                                    <option value="17">Bahraini</option>
                                    <option value="18">Bangladeshi</option>
                                    <option value="19">Barbadian</option>
                                    <option value="20">Belarusian</option>
                                    <option value="21">Belgian</option>
                                    <option value="23">Beninese</option>
                                    <option value="24">Bermuda</option>
                                    <option value="25">Bhutanese</option>
                                    <option value="27">Bolivian</option>
                                    <option value="29">Bosnian</option>
                                    <option value="30">Botswanan</option>
                                    <option value="32">Brazilian</option>
                                    <option value="33">Bruneian</option>
                                    <option value="34">Bulgarian</option>
                                    <option value="160">Burmese</option>
                                    <option value="36">Burundian</option>
                                    <option value="37">Cambodian</option>
                                    <option value="38">Cameroonian</option>
                                    <option value="39">Canadian</option>
                                    <option value="43">Chadian</option>
                                    <option value="44">Chilean</option>
                                    <option value="45">Chinese</option>
                                    <option value="48">Colombian</option>
                                    <option value="58">Congolese</option>
                                    <option value="51">Costa Rican</option>
                                    <option value="52">Cote D'Ivoire</option>
                                    <option value="53">Croatian</option>
                                    <option value="56">Cyprus</option>
                                    <option value="59">Danish</option>
                                    <option value="61">Dominican</option>
                                    <option value="62">Dominican</option>
                                    <option value="124">DPR Korea</option>
                                    <option value="164">Dutch</option>
                                    <option value="63">East Timor</option>
                                    <option value="64">Ecuadorean</option>
                                    <option value="65">Egyptian</option>
                                    <option value="67">English</option>
                                    <option value="225">English</option>
                                    <option value="68">Eritrean</option>
                                    <option value="69">Estonian</option>
                                    <option value="70">Ethiopian</option>
                                    <option value="74">Fijian</option>
                                    <option value="182">Filipino</option>
                                    <option value="75">Finnish</option>
                                    <option value="76">French</option>
                                    <option value="78">French Guiana</option>
                                    <option value="81">Gabonese</option>
                                    <option value="82">Gambian</option>
                                    <option value="83">Georgian</option>
                                    <option value="84">German</option>
                                    <option value="85">Ghanaian</option>
                                    <option value="86">Gibraltar</option>
                                    <option value="87">Greek</option>
                                    <option value="89">Grenadian</option>
                                    <option value="97">Haitian</option>
                                    <option value="101">Honduran</option>
                                    <option value="102">Hong Kong</option>
                                    <option value="103">Hungarian</option>
                                    <option value="104">Icelandic</option>
                                    <option value="99">Indian</option>
                                    <option value="105">Indian</option>
                                    <option value="106">Indonesian</option>
                                    <option value="107">Iranian</option>
                                    <option value="108">Iraqi</option>
                                    <option value="109">Irish</option>
                                    <option value="111">Italian</option>
                                    <option value="113">Jamaican</option>
                                    <option value="114">Japanese</option>
                                    <option value="117">Jordanian</option>
                                    <option value="118">Kazakhstan</option>
                                    <option value="119">Kenyan</option>
                                    <option value="126">Kuwaiti</option>
                                    <option value="4">Lao</option>
                                    <option value="128">Latvian</option>
                                    <option value="129">Lebanese</option>
                                    <option value="131">Liberian</option>
                                    <option value="132">Libyan</option>
                                    <option value="133">Liechtenstein</option>
                                    <option value="134">Lithuanian</option>
                                    <option value="135">Luxembourgian</option>
                                    <option value="136">Macedonian</option>
                                    <option value="137">Madagascan</option>
                                    <option value="139">Malawian</option>
                                    <option value="140">Malaysian</option>
                                    <option value="141">Maldivian</option>
                                    <option value="142">Malian</option>
                                    <option value="143">Maltese</option>
                                    <option value="146">Mauritanian</option>
                                    <option value="147">Mauritian</option>
                                    <option value="149">Mexican</option>
                                    <option value="153">Moldovan</option>
                                    <option value="154">Monacan</option>
                                    <option value="155">Mongolian</option>
                                    <option value="156">Montenegrin</option>
                                    <option value="158">Morocaine</option>
                                    <option value="159">Mozambican</option>
                                    <option value="161">Namibian</option>
                                    <option value="163">Nepalese</option>
                                    <option value="22">Netherlands</option>
                                    <option value="166">New Zealanders</option>
                                    <option value="167">Nicaraguan</option>
                                    <option value="169">Nigerian</option>
                                    <option value="168">Nigerien</option>
                                    <option value="173">Norwegian</option>
                                    <option value="174">Omani</option>
                                    <option value="175">Pakistani</option>
                                    <option value="177">Palestinian</option>
                                    <option value="178">Panamanian</option>
                                    <option value="179">Papua New Guinean</option>
                                    <option value="180">Paraguayan</option>
                                    <option value="181">Peruvian</option>
                                    <option value="184">Polish</option>
                                    <option value="185">Portuguese</option>
                                    <option value="187">Qatari</option>
                                    <option value="194">Romanian</option>
                                    <option value="196">Russian</option>
                                    <option value="197">Rwandan</option>
                                    <option value="66">Salvadorean</option>
                                    <option value="206">Saudi Arabian</option>
                                    <option value="207">Scottish</option>
                                    <option value="208">Senegalese</option>
                                    <option value="209">Serbian</option>
                                    <option value="210">Seychelles</option>
                                    <option value="211">Sierra Leonia</option>
                                    <option value="212">Singaporean</option>
                                    <option value="214">Slovak</option>
                                    <option value="215">Slovenian</option>
                                    <option value="216">Solomon Islander</option>
                                    <option value="217">Somali</option>
                                    <option value="218">South African</option>
                                    <option value="123">South Korean</option>
                                    <option value="220">Spanish</option>
                                    <option value="200">Sri Lankan</option>
                                    <option value="221">Sri Lankan</option>
                                    <option value="201">Sudanese</option>
                                    <option value="205">Swedish</option>
                                    <option value="227">Swiss</option>
                                    <option value="228">Syrian</option>
                                    <option value="229">Taiwanese</option>
                                    <option value="231">Tanzanian</option>
                                    <option value="232">Thai</option>
                                    <option value="239">Tunisian</option>
                                    <option value="222">Ugandan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="share_value_1" class="col-md-4 required-star">Share
                                Value</label>
                            <div class="col-md-8 ">
                                <input class="form-control share-value required"
                                       placeholder="Enter Share Value" id="share_value_1"
                                       name="share_value" type="number"
                                       value="{{old('share_value',$shareholder->share_value)}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>


