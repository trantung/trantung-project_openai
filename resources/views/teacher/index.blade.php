@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/ssologin.css') }}"rel="stylesheet">
<div id="main-content">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="">
                            <h2>Danh sách giáo viên</h2>
                            <div class="message-class">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <div id="content_message">{{ session('success') }}</div>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <div id="content_message">{{ session('error') }}</div>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <form action="{{ route('teacher.index') }}" class="form-inline mb-2 search-form" method="get">
                                <!-- Select for Class Filter -->
                                <select id="filter-class" name="class" style="width: 20%;" class="form-control ml-2 mr-sm-2">
                                    <option value="" disabled selected></option>
                                    @foreach($allClass as $class)
                                        <option value="{{ $class->id }}" 
                                            @if(request('class') == $class->id) selected @endif>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <select id="filter-user" name="user" style="width: 20%;" class="select2-hidden-accessible ml-2" aria-hidden="true">
                                    @if(request('user'))
                                        @foreach($allTeacher as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                @if(request('user') == $teacher->id) selected @endif>
                                                {{ $teacher->name }} ({{ $teacher->email }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled selected>Nhập thông tin giáo viên</option>
                                    @endif
                                </select>
                                <!-- Buttons -->
                                <button type="submit" class="btn btn-primary ml-2 btn-search">Tìm kiếm</button>
                                <button type="button" class="btn btn-secondary ml-2 btn-clear">Xóa tìm kiếm</button>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="box-total-item">
                                    Tổng số giáo viên: {{ $totalTeachers }}
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="#" class="btn btn-primary mb-2">+ Giáo viên</a>
                            </div>
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-sso-teacher">
                                <thead>
                                    <tr>
                                        <th><div>STT</div></th>
                                        <th><div>SSO ID</div></th>
                                        <th><div>SSO Name</div></th>
                                        <th><div>Tên giáo viên</div></th>
                                        <th><div>Lớp</div></th>
                                        <th><div>Tài khoản</div></th>
                                        <th><div>Email</div></th>
                                        <th><div>Hành động</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teachers as $index => $teacher)
                                        <tr id="user-{{ $teacher->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $teacher->sso_id }}</td>
                                            <td>{{ $teacher->sso_name }}</td>
                                            <td class="text-left">{{ $teacher->name }}</td>
                                            <td>
                                                @if($teacher->classes->isNotEmpty())
                                                    @php
                                                        $classNames = $teacher->classes->pluck('name');
                                                        $displayedClasses = $classNames->take(2)->implode(', ');
                                                        $remainingClasses = $classNames->slice(2)->implode(', ');
                                                    @endphp
                                                    {{ $displayedClasses }}
                                                    @if($classNames->count() > 2)
                                                        , 
                                                        <a class="more_info_classes" 
                                                        title="{{ $remainingClasses }}" 
                                                        data-fullname="{{ $teacher->name }}" 
                                                        href="javascript:void(0);" 
                                                        data-toggle="modal" 
                                                        data-target="#modal_more_info_classes" 
                                                        data-classes-name="{{ $classNames->implode(', ') }}">
                                                            ...
                                                        </a>
                                                    @endif
                                                @else
                                                    Không có lớp tham gia
                                                @endif
                                            </td>
                                            <td class="text-left">{{ $teacher->username }}</td>
                                            <td class="text-left">{{ $teacher->email }}</td>
                                            <td>
                                                <a href="javascript:void(0);" 
                                                    data-toggle="modal" 
                                                    data-teacher-id="{{ $teacher->user_id }}" 
                                                    onclick=showModalEditTeacher(this)
                                                    title="Chỉnh sửa giáo viên">
                                                    <i class="fas fa fa-lg fa-edit text-success"></i>
                                                </a>
                                                <!-- <a class="login_as" data-fullname="Nguyễn  Bích Ngọc" data-course-id="1" data-user-id="10907" data-sesskey="t5uuyZVtfd" href="javascript:void(0);" data-toggle="modal" data-target="#modal_login_as" title="Đăng nhập với tư cách">
                                                    <i class="fa fa-lg fa-user-secret text-success"></i>
                                                </a> -->
                                                <a href="javascript:void(0);" 
                                                    title="Xoá giáo viên" 
                                                    data-type="" 
                                                    data-teacher-id="{{ $teacher->user_id }}" 
                                                    data-teacher-name="{{ $teacher->name }}"
                                                    data-class-id="{{ $teacher->classes->pluck('id')->implode(', ') }}" 
                                                    onclick="showModalDeleteUser(this)"
                                                    class="item-delete">
                                                    <i class="fas fa fa-lg fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_more_info_classes" tabindex="-1" aria-labelledby="modal_more_info_classes_label" aria-modal="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_more_info_classes_label">Lớp của <b><span class="teacher-name"></span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="class-names"></span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upsert_user" tabindex="-1" aria-labelledby="modal_upsert_user_label" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-md" role="document" id="yui_3_17_2_1_1733992756629_397">
        <div class="modal-content" id="yui_3_17_2_1_1733992756629_396">
            <div class="modal-header" id="yui_3_17_2_1_1733992756629_395">
                <h5 class="modal-title" id="modal_upsert_user_label">Chỉnh sửa giáo viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="modal_upsert_user_body">
                <form id="info_teacher">
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
                            <label class="col-sm-4 col-form-label col-form-label-sm">Lớp </label>
                            <div class="col-sm-8">
                                <div class="input-group mb-2 mr-sm-2">
                                    <select name="division_class[]" id="division_class" style="width: 100%;" multiple>
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
                        </div>

                        <div class="form-group row">
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
                </form>
            </div>
            <div class="modal-footer" style="justify-content: center">
                <button type="button" class="btn btn-primary btn-save-teacher mr-2" data-action="">Xác nhận</button>
                <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Hủy bỏ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="removeUserModal" tabindex="-1" role="dialog" aria-labelledby="removeUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeUserModalLabel">Rút tên giáo viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('teacher.delete') }}" id="deleteUserForm" method="post">
                    @csrf
                    <input type="hidden" name="teacher_id" id="teacher_id">
                    <input type="hidden" name="class_id" id="class_id">
                </form>
                <div style="text-align: center">
                    Bạn có chắc chắn xoá Giáo viên "<b id="modal_user_name"></b>" ?
                    <br>
                    <div class="text-danger"><strong>Lưu ý:</strong> Xoá xong không thể khôi phục lại được</div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center">
                <button type="button" class="btn btn-primary remove-teacher mr-2" data-teacher-id="">Xác nhận</button>
                <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Hủy bỏ</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle click on the "more_info_classes" link
        $('.more_info_classes').on('click', function() {
            // Get the teacher's name and class names from data attributes
            var teacherName = $(this).data('fullname');
            var classesName = $(this).data('classes-name');

            // Set the teacher's name in the modal title
            $('#modal_more_info_classes .teacher-name').text(teacherName);

            // Set the list of classes in the modal body
            $('#modal_more_info_classes .class-names').text(classesName);
        });
    });

    $('.btn-save-teacher').on('click', function () {
        var userMoodleId = $(this).attr('data-userMoodleId');
        var userTeacherId = $(this).attr('data-userTeacherId');

        var userFirstname = $('form#info_teacher input[name="firstname"]').val().trim();
        var userLastname = $('form#info_teacher input[name="lastname"]').val().trim();
        var userEmail = $('form#info_teacher input[name="email"]').val().trim();
        // var userName = $('form#info_teacher input[name="username"]').val().trim();
        var country = $('form#info_teacher select[name="country"]').val(); // Country code or name
        var city = $('form#info_teacher input[name="city"]').val().trim();
        var phone2 = $('form#info_teacher input[name="phone2"]').val().trim();

        // Lấy danh sách course ids từ select
        var classIds = $('#division_class').val(); // Đây sẽ là mảng chứa các ID của khóa học đã chọn

        // Validate dữ liệu
        var errors = [];

        if (!userFirstname) {
            errors.push('First name is required.');
        }

        if (!userLastname) {
            errors.push('Last name is required.');
        }

        if (!userEmail) {
            errors.push('Email is required.');
        } else if (!validateEmail(userEmail)) {
            errors.push('Invalid email format.');
        }

        // Nếu có lỗi, hiển thị thông báo và dừng việc gửi form
        if (errors.length > 0) {
            alert(errors.join('\n'));
            return;
        }

        // Chuẩn bị form data nếu không có lỗi
        var formData = new FormData();
        formData.append('userMoodleId', userMoodleId);
        formData.append('userTeacherId', userTeacherId);
        formData.append('firstname', userFirstname);
        formData.append('lastname', userLastname);
        formData.append('email', userEmail);
        // formData.append('username', userName);
        formData.append('country', country);
        formData.append('city', city);
        formData.append('phone2', phone2);
        // Thêm các course ids vào formData
        classIds.forEach(function(classId) {
            formData.append('class_ids_teacher[]', classId);
        });

        $.ajax({
            url: '/api/teacher/update_infor_user',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status){
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error uploading avatar:', error);
            }
        });
    });

    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showModalEditTeacher(button) {
        const teacherId = $(button).attr('data-teacher-id');
        $('#modal_upsert_user').modal('show');
        $.ajax({
            url: '/api/teacher/get_infor_user',
            type: 'GET',
            data: {
                'teacherId': teacherId
            },
            success: function(response) {
                var userData = response.data;
                console.log(userData);
                if (userData) {
                    var userMoodleId = userData.moodleData.id;
                    var userFirstname = userData.moodleData.firstname;
                    var userLastname = userData.moodleData.lastname;
                    var userEmail = userData.moodleData.email;
                    var userName = userData.moodleData.username;
                    var country = userData.moodleData.country; // Country code or name
                    var city = userData.moodleData.city ?? '';
                    var phone2 = userData.moodleData.phone2 ?? '';
                    var allClass = userData.allClass;
                    var selectedClassIds = userData.selected_class_ids ?? [];

                    // Gán giá trị vào các trường input
                    $('form#info_teacher input[name="firstname"]').val(userFirstname);
                    $('form#info_teacher input[name="lastname"]').val(userLastname);
                    $('form#info_teacher input[name="email"]').val(userEmail);
                    $('form#info_teacher input[name="phone2"]').val(phone2);
                    $('form#info_teacher input[name="city"]').val(city);
                    $('form#info_teacher input[name="username"]').val(userName);

                    $('.btn-save-teacher').attr('data-userMoodleId', userMoodleId);
                    $('.btn-save-teacher').attr('data-userTeacherId', userData.teacher.id);

                    // Gán giá trị vào select country
                    $('form#info_teacher select[name="country"]').val(country).trigger('change'); // trigger 'change' nếu cần cập nhật giao diện

                    var classSelect = $('#division_class');
                    classSelect.empty(); // Xóa các option cũ
                    allClass.forEach(function(classes) {
                        var isSelected = selectedClassIds.includes(classes.id) ? 'selected' : '';
                        classSelect.append(
                            `<option value="${classes.id}" ${isSelected}>${classes.name}</option>`
                        );
                    });
                    classSelect.trigger('change'); // Nếu sử dụng plugin như Select2
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    $('#division_class').select2({
        placeholder: "-- Nhập tên lớp --",
        allowClear: true,
        tags: true
    });

    $('#filter-user').select2({
        placeholder: "-- Nhập thông tin giáo viên --",
        allowClear: true,
        minimumInputLength: 2, // Chỉ bắt đầu tìm kiếm khi người dùng gõ ít nhất 2 ký tự
        ajax: {
            url: '/api/class/teacher/search', // Địa chỉ API trong Laravel
            dataType: 'json',
            delay: 250, // Thời gian trì hoãn để không gửi quá nhiều yêu cầu
            data: function (params) {
                return {
                    searchTerm: params.term, // Truyền từ khóa tìm kiếm
                };
            },
            processResults: function (data) {
                // Dữ liệu trả về từ server phải có định dạng đúng với yêu cầu của Select2
                var results = $.map(data, function (teacher) {
                    return {
                        id: teacher.id,   // ID của giáo viên
                        text: teacher.name + '(' + teacher.email + ')'// Tên giáo viên
                    };
                });

                // Nếu bạn cần phân trang, có thể bổ sung thêm phần này:
                return {
                    results: results
                };
            },
            cache: true // Cache kết quả để giảm tải server
        }
    });

    function showModalDeleteUser(button) {
        const classId = $(button).attr('data-class-id');
        const teacherId = $(button).attr('data-teacher-id');
        const teacherName = $(button).attr('data-teacher-name');
        
        $('#removeUserModal #modal_user_name').text(teacherName);

        $('#removeUserModal #class_id').val(classId);

        $('#removeUserModal #teacher_id').val(teacherId);

        $('#removeUserModal').modal('show');
    }

    $('.remove-teacher').on('click', function() {
        $('#deleteUserForm').submit();
    });
</script>
<!-- <script src="{{ URL::asset('js/ssologin/ssologin.js') }}"></script> -->
@endsection