<div class="tab-pane-content">
    <div class="container course-class-student">
        <div class="row-search-add">
            <form action="{{ route('class.edit', ['id' => $class->id]) }}#tab_teacher" class="form-inline mb-2 search-form" method="get">
                <!-- Select for Class Filter -->
                <select id="filter-teacher-name" name="teacher" style="width: 30%;" class="select2-hidden-accessible" aria-hidden="true">
                    <!-- <option value="" disabled selected>Nhập tên giáo viên</option> -->
                    @if(request('teacher'))
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                @if(request('teacher') == $teacher->id) selected @endif>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled selected>Nhập thông tin giáo viên</option>
                    @endif
                </select>
                
                <!-- Buttons -->
                <button type="submit" class="btn btn-primary ml-2 btn-search">Tìm kiếm</button>
                <a href="javascript:void(0);" data-toggle="modal" data-target="#chooseTeacherModal" class="btn btn-primary btn-popup-add-teacher ml-2">Chọn giáo viên</a>
                <button type="button" class="btn btn-secondary ml-2 btn-clear">Xóa tìm kiếm</button>
            </form>
        </div>
        <div class="row-total-add my-2">
            <div class="box-total-item">Tổng số GV: <span id="total_teacher">{{ $totalTeachersCount }}</span></div>
        </div>
        <div class="wrapper-table-scroll">
            <table class="table-scroll table-course-class-teacher">
                <thead>
                    <tr>
                        <th><div>STT</div></th>
                        <th><div>Tên giáo viên</div></th>
                        <th><div>Tài khoản</div></th>
                        <th><div>Email</div></th>
                        <th><div>Khóa học tham gia</div></th>
                        <th><div>Hành động</div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $index => $teacher)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->username ?? 'N/A' }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                {{ implode(', ', $teacher->courses) }}
                            </td>
                            <td>
                                <!-- <a href="#" 
                                title="Phân quyền học liệu">
                                    <i class="fas fa fa-lg fa-eye"></i>
                                </a>
                                <a href="#" 
                                title="Thêm học sinh">
                                    <i class="fas fa fa-lg fa-user-plus text-info"></i>
                                </a> -->
                                <a href="javascript:void(0);" 
                                    data-teacher-id="{{ $teacher->user_id }}" 
                                    data-class-id="{{ $class->id }}" 
                                    class="btn-popup-update-teacher"
                                    onclick=showModalEditTeacher(this)
                                    title="Chỉnh sửa giáo viên">
                                    <i class="fas fa fa-lg fa-edit text-success"></i>
                                </a>
                                <a href="javascript:void(0)" 
                                    class="item-delete" 
                                    data-teacher-id="{{ $teacher->user_id }}" 
                                    data-class-id="{{ $class->id }}" 
                                    data-class-name="{{ $class->name }}" 
                                    data-teacher-name="{{ $teacher->name }}" 
                                    onclick="showModalDeleteTeacher(this)"
                                    title="Xóa giáo viên">
                                    <i class="fas fa fa-lg fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center">Không có giáo viên</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Hiển thị phân trang -->
            <div class="pagination">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
    <div class="modal choose-teacher-modal" id="chooseTeacherModal" tabindex="-1" aria-labelledby="chooseTeacherModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseTeacherModalLabel">Chọn giáo viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="classIdTeacher" id="classIdTeacher" value="{{ $classId }}">
                        <div class="mb-4 box-search w-25">
                            <label>Chọn khóa học</label>
                            <select id="course-teacher-enrol" class="form-control">
                                <option value="">Chọn khóa học</option>
                                @foreach($coursesEnrol as $course)
                                    <option value="{{ $course->id }}" >
                                        {{ $course->moodle_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ml-2 mb-4 box-search search-teacher-other-box w-50" style="display: none;">
                            <label>Nhập tên giáo viên</label>
                            <select id="search-teacher-other" name="class" class="form-control" aria-hidden="true">
                                <!-- <option value="" disabled selected>Nhập tên giáo viên</option> -->
                                <!-- <option value="10153">Hoàng  Khánh Linh (teacher.linhhk)</option>
                                <option value="1772">Nguyễn Khánh Linh (gv.linhnk2022)</option>
                                <option value="2500">Hoàng Diệu Linh (gv.linhhd2022)</option>
                                <option value="3971">Lê Phương Linh (gv.linhlp2023)</option> -->
                            </select>
                        </div>
                        <!-- <a href="javascript:void(0);" data-toggle="modal" data-target="#upsertTeacherModal" data-teacher-id="-1" class="btn btn-primary btn-popup-create-teacher">Thêm giáo viên</a> -->
                    </div>
                    <div class="wrapper-table-scroll">
                        <table class="table-scroll table-course-class-chosen-teacher">
                            <thead>
                                <tr>
                                    <th><div>STT</div></th>
                                    <th><div>Tên giáo viên</div></th>
                                    <th><div>Email</div></th>
                                    <th><div>Chọn</div></th>
                                </tr>
                            </thead>
                            <tbody class="chosen-teachers">
                                <!-- <tr id="teacher-other-2830">
                                    <td class="text-center">1</td>
                                    <td class="text-center">Nguyễn Thị Bảo Linh</td>
                                    <td class="text-center">gv.linhntb@hocmai.edu.vn</td>
                                    <td class="text-center">
                                        <input type="checkbox" class="checkbox-chosen-teachers" value="2830">
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="input-chosen-teachers" value="">
                </div>
                <div class="modal-footer" style="justify-content: center">
                    <button type="button" class="btn btn-primary add-teacher mr-2" data-teacher-id="">Xác nhận</button>
                    <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="upsertTeacherModal" tabindex="-1" aria-labelledby="upsertTeacherModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document" id="yui_3_17_2_1_1733315613262_94">
            <div class="modal-content" id="yui_3_17_2_1_1733315613262_93">
                <div class="modal-header" id="yui_3_17_2_1_1733315613262_92">
                    <h5 class="modal-title" id="upsertTeacherModalLabel">Thêm giáo viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="yui_3_17_2_1_1733315613262_91">
                        <span aria-hidden="true" id="yui_3_17_2_1_1733315613262_90">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="info_teacher">
                        <fieldset>
                            <div class="section-info">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label col-form-label-sm"><h6 class="m-0"><b>Thông tin cá nhân</b></h6></label>
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
                                        <select class="form-control" name="country" id="id_country" data-initial-value="">
                                            <option value="" selected="">Select a country...</option>
                                            <option value="AF">Afghanistan</option>
                                            <option value="AX">Åland Islands</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia (Plurinational State of)</option>
                                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="BA">Bosnia and Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="CV">Cabo Verde</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CX">Christmas Island</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo (the Democratic Republic of the)</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CW">Curaçao</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czechia</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="SZ">Eswatini</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FK">Falkland Islands (Malvinas)</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="TF">French Southern Territories</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GG">Guernsey</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HM">Heard Island and McDonald Islands</option>
                                            <option value="VA">Holy See</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran (Islamic Republic of)</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IM">Isle of Man</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JE">Jersey</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KP">Korea (the Democratic People's Republic of)</option>
                                            <option value="KR">Korea (the Republic of)</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macao</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia (Federated States of)</option>
                                            <option value="MD">Moldova (the Republic of)</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NU">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="MK">North Macedonia</option>
                                            <option value="MP">Northern Mariana Islands</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PS">Palestine, State of</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RE">Réunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="BL">Saint Barthélemy</option>
                                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                            <option value="KN">Saint Kitts and Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="MF">Saint Martin (French part)</option>
                                            <option value="PM">Saint Pierre and Miquelon</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SX">Sint Maarten (Dutch part)</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                                            <option value="SS">South Sudan</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SJ">Svalbard and Jan Mayen</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania, the United Republic of</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TL">Timor-Leste</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks and Caicos Islands</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela (Bolivarian Republic of)</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="VG">Virgin Islands (British)</option>
                                            <option value="VI">Virgin Islands (U.S.)</option>
                                            <option value="WF">Wallis and Futuna</option>
                                            <option value="EH">Western Sahara</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
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
                                    <label class="col-sm-12 col-form-label col-form-label-sm"><h6 class="m-0"><b>Thông tin tài khoản</b></h6></label>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Tài khoản <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">

                                        <input type="text" name="username" disabled class="form-control form-control-sm" placeholder="Nhập tên tài khoản">
                                    </div>
                                </div>

                                <div class="form-group row box-course">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Khóa học đã tham gia </label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-2 mr-sm-2">
                                            <select name="course_ids_teacher[]" id="course_ids_teacher" style="width: 100%;" multiple>
                                                <!-- Duyệt qua danh sách khóa học -->
                                                @foreach($courses as $course)
                                                    <!-- Kiểm tra xem khóa học có trong danh sách 'course_ids' không -->
                                                    <option value="{{ $course->id }}">
                                                        {{ $course->moodle_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="form-group row box-password">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Mật khẩu <span class="asterisk">(*)</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-2 mr-sm-2">
                                            <input type="password" name="password" class="form-control form-control-sm" placeholder="Nhập mật khẩu">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="btn-show-password fa fa-fw fa-eye-slash" data-form-id="info_teacher" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="font-size: small">Mật khẩu phải có ít nhất 8 ký tự, ít nhất 1 con số, ít nhất 1 ký tự viết thường, ít nhất 1 ký tự viết hoa, ít nhất 1 ký tự không phải số</div>
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-sm-4 col-form-label col-form-label-sm">Loại tài khoản</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm" value="Galaxy Education" placeholder="" disabled="">
                                        <input type="hidden" name="user_type" value="1">
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
                                </div> -->
                            </div>
                        </fieldset>
                        <input type="hidden" name="id" value="-1">
                    </form>
                </div>
                <div class="modal-footer" style="justify-content: center">
                    <button type="button" class="btn btn-primary btn-save-teacher mr-2" data-action="">Xác nhận</button>
                    <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="removeTeacherModal" tabindex="-1" role="dialog" aria-labelledby="removeTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeTeacherModalLabel">Rút tên giáo viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <form action="{{ route('class.delete') }}" id="deleteClassForm" method="post">
                        @csrf
                        <input type="hidden" name="teacher_id_remove_teacher" id="teacher_id_remove_teacher">
                        <input type="hidden" name="class_id_remove_teacher" id="class_id_remove_teacher">
                        
                    </form> -->
                    <div style="text-align: center">
                        Bạn chắc chắn muốn rút tên giáo viên "<b id="modal_teacher_name"></b>" ra khỏi lớp "<b>{{ $class->name }}</b>"?
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: center">
                    <button type="button" class="btn btn-primary remove-teacher mr-2" data-teacher-id="">Xác nhận</button>
                    <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>
</div>