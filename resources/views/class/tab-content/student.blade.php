<div class="tab-pane-content">
    <div class="container course-class-student">
        <div class="row-search-add">
            <select id="filter-student-name" name="class" style="width: 30%;" class="select2-hidden-accessible" aria-hidden="true">
                <option value="" disabled selected>Nhập tên học sinh</option>
            </select>
            <a href="javascript:void(0);" class="btn btn-primary ml-2" data-toggle="modal" data-target="#chooseClassStudentModal">Chọn học sinh</a>
            <a href="#" class="btn btn-primary ml-2">Import file</a>
            <a href="#" class="btn btn-secondary ml-2">Tải file mẫu</a>
        </div>
        <div class="row-total-add my-2">
            <div class="box-total-item">Tổng số học sinh: <span id="total_student">1</span></div>
        </div>
        <div class="wrapper-table-scroll table-course-class-student">
            <table class="table-scroll">
                <thead>
                    <tr>
                        <th><div>STT</div></th>
                        <th><div>Tên học sinh</div></th>
                        <th><div>Tài khoản</div></th>
                        <th><div>Email</div></th>
                        <th><div>Điện thoại</div></th>
                        <th><div>Hành động</div></th>
                    </tr>
                </thead>
                <tbody id="table_body_students">
                    <tr id="student_4291">
                        <td class="text-center student-stt">1</td>
                        <td class="text-left student-fullname">Nguyen Dieu Linh</td>
                        <td class="text-left student-username">cr.linhnd</td>
                        <td class="text-left student-email">Linhnd3@hocmai.vn</td>
                        <td class="text-center student-phone2"></td>
                        <td class="text-center">
                            <a href="javascript:void(0)" 
                            class="item-edit mr-2" 
                            data-toggle="modal" 
                            data-target="#upsertStudentModal" 
                            data-student-id="4291" 
                            data-student-firstname="Dieu Linh" 
                            data-student-lastname="Nguyen" 
                            data-student-username="cr.linhnd" 
                            data-student-email="Linhnd3@hocmai.vn" 
                            data-student-phone2="" data-student-phone="" 
                            data-student-country="VN" data-student-city="" 
                            data-student-lang="vi" data-student-auth="manual" 
                            data-student-classid="879" data-student-fullname="Nguyen Dieu Linh" title="Chỉnh sửa học sinh">
                                <i class="fas fa fa-lg fa-edit text-success"></i>
                            </a>
                            <a href="javascript:void(0)" class="item-delete" 
                            data-toggle="modal" 
                            data-target="#deleteStudentModal" 
                            data-student-id="4291" 
                            data-student-name="Nguyen Dieu Linh" title="Rút tên">
                                <i class="fas fa fa-lg fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="chooseClassStudentModal" tabindex="-1" role="dialog" aria-labelledby="chooseStudentModalLabel" aria-modal="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseStudentModalLabel">Chọn học sinh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row-search-create">
                        <div class="mb-2 box-search">
                            <select id="search-student-other" name="class" style="width: 200%;" class="select2-hidden-accessible" aria-hidden="true">
                                <option value="" disabled selected>Nhập tên học sinh</option>
                            </select>
                        </div>
                        <input type="hidden" id="temp_choose_class_student_ids" value="4291">
                        <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" data-target="#upsertStudentModal" data-student-id="-1">Thêm học sinh</a>
                    </div>
                    <div class="wrapper-table-scroll">
                        <form id="info_class_student">
                            <table class="table-scroll">
                                <thead>
                                <tr>
                                    <th><div>STT</div></th>
                                    <th><div>Tên học sinh</div></th>
                                    <th><div>Email</div></th>
                                    <th><div>Chọn</div></th>
                                </tr>
                                </thead>
                                <tbody id="table_body_choose_class_student"></tbody>
                            </table>
                            <input type="hidden" name="class_id" value="879">
                        </form>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-content-center">
                    <button type="button" class="btn btn-primary btn-choose-class-student">Xác nhận</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upsertStudentModal" tabindex="-1" role="dialog" aria-labelledby="upsertStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upsertStudentModalLabel">Thêm học sinh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="info_student">
                        <fieldset>
                            <div class="section-info">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label col-form-label-sm"><b>Thông tin cá nhân</b></label>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Tên <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="firstname" class="form-control form-control-sm" placeholder="Nhập tên">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Họ <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="lastname" class="form-control form-control-sm" placeholder="Nhập họ">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Email <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="email" class="form-control form-control-sm" placeholder="Nhập email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Điện thoại</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="phone2" class="form-control form-control-sm" placeholder="Nhập số điện thoại">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Quốc gia</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="country">
                                            <option value="">Chọn quốc gia</option>
                                            <option value="AF">Afghanistan</option>
                                            <option value="EG">Ai Cập</option>
                                            <option value="IE">Ai Len</option>
                                            <option value="AT">Áo</option>
                                            <option value="AU">Australia</option>
                                            <option value="AO">Ăng gô la</option>
                                            <option value="IN">Ấn Độ</option>
                                            <option value="PL">Ba Lan</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BE">Bỉ</option>
                                            <option value="BO">Bolivia (Plurinational State of)</option>
                                            <option value="BA">Bosnia và  Herzegovina</option>
                                            <option value="PT">Bồ Đào Nha</option>
                                            <option value="CI">Bờ Biển Ngà</option>
                                            <option value="BR">Bra xin</option>
                                            <option value="BG">Bun ga ri</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="CA">Ca na đa</option>
                                            <option value="KZ">Ca-dắc-xtan</option>
                                            <option value="AE">Các Tiểu Vương quốc Ả Rập Thống nhất</option>
                                            <option value="BQ">Caribe Hà Lan</option>
                                            <option value="KH">Căm pu chia</option>
                                            <option value="AQ">Châu Nam Cực</option>
                                            <option value="CL">Chi Lê</option>
                                            <option value="SY">Cộng hòa Ả Rập Syria</option>
                                            <option value="AL">Cộng hòa Albania</option>
                                            <option value="AR">Cộng hòa Argentina</option>
                                            <option value="AM">Cộng hòa Armenia</option>
                                            <option value="AZ">Cộng hòa Azerbaijan</option>
                                            <option value="MK">Cộng hòa Bắc Macedonia</option>
                                            <option value="BY">Cộng hòa Belarus</option>
                                            <option value="BJ">Cộng hòa Benin</option>
                                            <option value="VE">Cộng hòa Bolivar Venezuela</option>
                                            <option value="BW">Cộng hòa Botswana</option>
                                            <option value="BI">Cộng hòa Burundi</option>
                                            <option value="CV">Cộng hòa Cabo Verde</option>
                                            <option value="CM">Cộng hòa Cameroon</option>
                                            <option value="TD">Cộng hòa Chad</option>
                                            <option value="CO">Cộng hòa Colombia</option>
                                            <option value="CR">Cộng hòa Costa Rica</option>
                                            <option value="HR">Cộng hòa Croatia</option>
                                            <option value="CG">Cộng hòa dân chủ Congo</option>
                                            <option value="CD">Cộng hoà Dân chủ Công gô</option>
                                            <option value="ET">Cộng hòa Dân chủ Liên bang Ethiopia</option>
                                            <option value="NP">Cộng hoà Dân chủ Liên bang Nepal</option>
                                            <option value="DZ">Cộng hòa Dân chủ Nhân dân Algeria</option>
                                            <option value="ST">Cộng hòa Dân chủ Sao Tome and Principe</option>
                                            <option value="DJ">Cộng hòa Djibouti</option>
                                            <option value="DO">Cộng Hoà Dominican</option>
                                            <option value="UY">Cộng hòa Đông Uruguay</option>
                                            <option value="EC">Cộng hòa Ecuador</option>
                                            <option value="SV">Cộng hòa El Salvador</option>
                                            <option value="EE">Cộng hòa Estonia</option>
                                            <option value="FJ">Cộng hòa Fijji</option>
                                            <option value="GA">Cộng hòa Gabon</option>
                                            <option value="GM">Cộng hòa Gambia</option>
                                            <option value="GH">Cộng hòa Ghana</option>
                                            <option value="GT">Cộng hòa Guatemala</option>
                                            <option value="GN">Cộng hòa Guinea</option>
                                            <option value="GW">Cộng hòa Guinea-Bissau</option>
                                            <option value="HT">Cộng hòa Haiti</option>
                                            <option value="MR">Cộng hòa Hồi giáo Mô - ri - ta - ni</option>
                                            <option value="PK">Cộng hoà Hồi giáo Pakistan</option>
                                            <option value="GY">Cộng hoà Hợp tác Guyana</option>
                                            <option value="ID">Cộng hòa Indonesia</option>
                                            <option value="KE">Cộng hòa Kenya</option>
                                            <option value="KI">Cộng hòa Kiribati</option>
                                            <option value="KG">Cộng hòa Kyrgyzstan</option>
                                            <option value="LV">Cộng hòa Latvia</option>
                                            <option value="LB">Cộng hòa Li - băng</option>
                                            <option value="LR">Cộng hòa Liberia</option>
                                            <option value="NG">Cộng hòa Liên bang Nigeria</option>
                                            <option value="SO">Cộng hòa Liên bang Somalia</option>
                                            <option value="LT">Cộng hòa Lithuania</option>
                                            <option value="MG">Cộng hòa Madagascar</option>
                                            <option value="MW">Cộng hòa Malawi</option>
                                            <option value="MV">Cộng hòa Maldives</option>
                                            <option value="ML">Cộng hòa Mali</option>
                                            <option value="MT">Cộng hòa Malta</option>
                                            <option value="MU">Cộng hòa Mauritius</option>
                                            <option value="MD">Cộng hoà Moldova</option>
                                            <option value="MZ">Cộng hòa Mozambique</option>
                                            <option value="ZA">Cộng hòa Nam Phi</option>
                                            <option value="SS">Cộng hòa Nam Sudan</option>
                                            <option value="NA">Cộng hòa Namibia</option>
                                            <option value="NR">Cộng hòa Nauru</option>
                                            <option value="BD">Cộng hòa nhân dân Bangladesh</option>
                                            <option value="NI">Cộng hòa Nicaragua</option>
                                            <option value="NE">Cộng hòa Niger</option>
                                            <option value="PW">Cộng hòa Palau</option>
                                            <option value="PA">Cộng hòa Panama</option>
                                            <option value="PY">Cộng hòa Paraguay</option>
                                            <option value="PE">Cộng hòa Peru</option>
                                            <option value="RW">Cộng hòa Ru-an-đa</option>
                                            <option value="SM">Cộng hòa San Marino</option>
                                            <option value="CZ">Cộng Hoà Séc</option>
                                            <option value="SN">Cộng hòa Senegal</option>
                                            <option value="SC">Cộng hòa Seychelles</option>
                                            <option value="SL">Cộng hòa Sierra Leone</option>
                                            <option value="CY">Cộng hòa Síp</option>
                                            <option value="SK">Cộng hòa Slovakia</option>
                                            <option value="SI">Cộng hòa Slovenia</option>
                                            <option value="SD">Cộng hòa Sudan</option>
                                            <option value="SR">Cộng hòa Suriname</option>
                                            <option value="TJ">Cộng hòa Tajikistan</option>
                                            <option value="TZ">Cộng hòa Thống nhất Tanzania</option>
                                            <option value="TT">Cộng hòa Trinidad và Tobago</option>
                                            <option value="CF">Cộng hoà Trung Phi</option>
                                            <option value="TN">Cộng hòa Tunisia</option>
                                            <option value="UG">Cộng hòa Uganda</option>
                                            <option value="UZ">Cộng hòa Uzbekistan</option>
                                            <option value="VU">Cộng hòa Vanuatu</option>
                                            <option value="LK">Cộng hoà Xã hội chủ nghĩa Dân chủ Sri Lanka</option>
                                            <option value="YE">Cộng hòa Yemen</option>
                                            <option value="ZM">Cộng hòa Zambia</option>
                                            <option value="ZW">Cộng hòa Zimbabwe</option>
                                            <option value="HN">Cộng hoafa Honduras</option>
                                            <option value="TG">Cộng hoafa Togo</option>
                                            <option value="AD">Công quốc Andorra</option>
                                            <option value="MC">Công quốc Monaco</option>
                                            <option value="CU">Cu Ba</option>
                                            <option value="CW">Curaçao</option>
                                            <option value="LU">Đại công quốc Luxembourg</option>
                                            <option value="TW">Đài Loan</option>
                                            <option value="DK">Đan Mạch</option>
                                            <option value="AI">Đảo Anguilla</option>
                                            <option value="AW">Đảo Aruba</option>
                                            <option value="BB">Đảo Barbados</option>
                                            <option value="BV">Đảo Bouvet</option>
                                            <option value="DM">Đảo Dominica</option>
                                            <option value="CX">Đảo Giáng sinh</option>
                                            <option value="GL">Đảo Greenland</option>
                                            <option value="GD">Đảo Grenada</option>
                                            <option value="GP">Đảo Guadeloupe</option>
                                            <option value="JM">Đảo Jamaica</option>
                                            <option value="MQ">Đảo Martinique</option>
                                            <option value="NU">Đảo Niue</option>
                                            <option value="NF">Đảo Norfolk</option>
                                            <option value="RE">Đảo Réunion</option>
                                            <option value="SH">Đảo Saint Helena, Ascension and Tristan da Cunha</option>
                                            <option value="SJ">Đảo Svalbard và  Jan Mayen</option>
                                            <option value="TK">Đảo Tokelau</option>
                                            <option value="TL">Đông Timor</option>
                                            <option value="DE">Đức</option>
                                            <option value="GE">Georgia</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GG">Guernsey</option>
                                            <option value="GF">Guiana Pháp</option>
                                            <option value="GQ">Guinea Xích đạo</option>
                                            <option value="NL">Hà Lan</option>
                                            <option value="KR">Hàn Quốc</option>
                                            <option value="GR">Hi Lạp</option>
                                            <option value="HK">Hồng Công</option>
                                            <option value="HU">Hung ga ri</option>
                                            <option value="IR">I - ran</option>
                                            <option value="IQ">I rắc</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IM">Isle Of Man</option>
                                            <option value="JE">Jersey</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="IO">Lãnh thổ Anh - Ấn trên biển</option>
                                            <option value="SX">Lãnh thổ Đảo Sint Maarten</option>
                                            <option value="GU">Lãnh thổ Guam</option>
                                            <option value="MS">Lãnh thổ hải ngoại thuộc Anh Montserrat</option>
                                            <option value="WF">Lãnh thổ quần đảo Wallis và Futuna[</option>
                                            <option value="LA">Lào</option>
                                            <option value="KM">Liên bang Comoros</option>
                                            <option value="FM">Liên bang Micronesia</option>
                                            <option value="RU">Liên bang Nga</option>
                                            <option value="KN">Liên bang Saint Kitts và Nevis</option>
                                            <option value="MO">Ma Cao</option>
                                            <option value="MY">Mã Lai</option>
                                            <option value="MX">Mê Hi Cô</option>
                                            <option value="MM">Miến Điện</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MN">Mông cổ</option>
                                            <option value="US">Mỹ</option>
                                            <option value="NO">Na Uy</option>
                                            <option value="GS">Nam Georgia và Quần đảo South Sandwich</option>
                                            <option value="BN">Nhà nước Brunei Darussalam</option>
                                            <option value="PG">Nhà nước Độc lập Papua New Guinea</option>
                                            <option value="WS">Nhà nước Độc lập Samoa</option>
                                            <option value="ER">Nhà nước Eritrea</option>
                                            <option value="IL">Nhà nước Israel</option>
                                            <option value="LY">Nhà nước Li - bi</option>
                                            <option value="PS">Nhà nước Palestine</option>
                                            <option value="QA">Nhà nước Qatar</option>
                                            <option value="JP">Nhật Bản</option>
                                            <option value="FR">Pháp</option>
                                            <option value="FI">Phần Lan</option>
                                            <option value="PH">Phi líp pin</option>
                                            <option value="PF">Polynésie thuộc Pháp</option>
                                            <option value="AX">Quần đảo Ai Len</option>
                                            <option value="BS">Quần đảo Bahamas</option>
                                            <option value="KY">Quần đảo Cayman</option>
                                            <option value="CC">Quần đảo Cocos (Keeling)</option>
                                            <option value="CK">Quần đảo Cook</option>
                                            <option value="TV">Quần đảo Ellice</option>
                                            <option value="FK">Quần đảo Falkland (Malvinas)</option>
                                            <option value="FO">Quần đảo Faroe</option>
                                            <option value="HM">Quần đảo Heard và Mc Donald</option>
                                            <option value="MH">Quần đảo Marshall</option>
                                            <option value="MP">Quần đảo Northern Mariana</option>
                                            <option value="PN">Quần đảo Pitcairn</option>
                                            <option value="SB">Quần đảo Solomon</option>
                                            <option value="TC">Quần đảo Turks và Caicos</option>
                                            <option value="VG">Quần đảo Virgin thuộc Anh</option>
                                            <option value="VI">Quần đảo Virgin thuộc Mỹ</option>
                                            <option value="AG">Quốc đảo Antigua and Barbuda</option>
                                            <option value="RO">Rumani</option>
                                            <option value="BL">Saint Barthélemy</option>
                                            <option value="MF">Saint Martin</option>
                                            <option value="VC">Saint Vincent và Grenadines</option>
                                            <option value="AS">Samoa thuộc Mỹ</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SG">Sinh ga po</option>
                                            <option value="NC">Tân Caledonia</option>
                                            <option value="NZ">Tân Tây Lan</option>
                                            <option value="ES">Tây Ban Nha</option>
                                            <option value="EH">Tây Sahara</option>
                                            <option value="TH">Thái Lan</option>
                                            <option value="LI">Thân vương quốc Liechtenstein</option>
                                            <option value="PR">Thịnh vượng chung Puerto Rico</option>
                                            <option value="LC">Thịnh vượng chung Saint Lucia</option>
                                            <option value="TR">Thổ Nhĩ Kì</option>
                                            <option value="SE">Thuỵ Điển</option>
                                            <option value="CH">Thụy Sĩ</option>
                                            <option value="YT">Tỉnh Mayotte</option>
                                            <option value="VA">Tòa Thánh</option>
                                            <option value="KP">Triều Tiên</option>
                                            <option value="CN">Trung Quốc</option>
                                            <option value="TM">Tuốc-mê-ni-xtan</option>
                                            <option value="UA">U-crai-na</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="VN">Việt Nam</option>
                                            <option value="TF">Vùng đất phía Nam và châu Nam Cực thuộc Pháp</option>
                                            <option value="PM">Vùng lãnh thổ cộng đồng Saint-Pierre và Miquelon</option>
                                            <option value="GB">Vương Quốc Anh</option>
                                            <option value="BH">Vương quốc Bahrain</option>
                                            <option value="BT">Vương quốc Bhutan</option>
                                            <option value="SZ">Vương quốc Eswatini</option>
                                            <option value="OM">Vương quốc Hồi giáo Oman</option>
                                            <option value="JO">Vương quốc Jordan</option>
                                            <option value="LS">Vương quốc Lesotho</option>
                                            <option value="MA">Vương quốc Ma - rốc</option>
                                            <option value="TO">Vương quốc Tonga</option>
                                            <option value="IT">Ý</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Tỉnh/Thành phố</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="city" class="form-control form-control-sm" placeholder="Nhập tỉnh/thành phố">
                                    </div>
                                </div>
                            </div>

                            <div class="section-info">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label col-form-label-sm"><b>Thông tin tài khoản</b></label>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Tài khoản <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="username" class="form-control form-control-sm" placeholder="Nhập tên tài khoản">
                                    </div>
                                </div>

                                <div class="form-group row box-password">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Mật khẩu <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-2 mr-sm-2">
                                            <input type="password" name="password" class="form-control form-control-sm" placeholder="Nhập mật khẩu">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="btn-show-password fa fa-fw fa-eye-slash" data-form-id="info_student" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="font-size: small">Mật khẩu phải có ít nhất 8 ký tự, ít nhất 1 con số, ít nhất 1 ký tự viết thường, ít nhất 1 ký tự viết hoa, ít nhất 1 ký tự không phải số</div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Loại tài khoản</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" value="School" placeholder="" disabled="">
                                        <input type="hidden" name="user_type" value="2">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Phương thức xác thực</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="auth">
                                            <optgroup label="Enabled">
                                                <option value="manual">Manual accounts</option>
                                                <option value="nologin">No login</option>
                                            </optgroup>
                                            <optgroup label="Disabled">
                                                <option value="cas">CAS server (SSO)</option>
                                                <option value="db">Sử dụng một cơ sở dữ liệu bên ngoài</option>
                                                <option value="email">Chứng thực dựa trên Email</option>
                                                <option value="icanconnect">Auth Icanconnect</option>
                                                <option value="ldap">Sử dụng một máy chủ LDAP</option>
                                                <option value="lti">LTI</option>
                                                <option value="mnet">MNet authentication</option>
                                                <option value="none">Không chứng thực</option>
                                                <option value="oauth2">OAuth 2</option>
                                                <option value="shibboleth">Shibboleth</option>
                                                <option value="webservice">Web services authentication</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Ngôn ngữ</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="lang">
                                            <option value="en">English ‎(en)‎</option>
                                            <option value="vi">Tiếng Việt ‎(vi)‎</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <input type="hidden" name="id" value="-1">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-save-student">Xác nhận</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>
</div>