@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/classes.css') }}"rel="stylesheet">
<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div id="box-content">
                            <h3>Thông tin lớp học - {{ $class->name }}</h3>
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
                            <div id="tabs">
                                <ul class="nav nav-tabs" id="nav_tab_list" role="tablist">
                                    <li class="nav-item active">
                                        <a href="#tab_info" class="nav-link active" data-tab="info" data-toggle="tab" role="tab" aria-selected="true" tabindex="0">
                                            Thông tin chung
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="#tab_student" class="nav-link" data-tab="student" data-toggle="tab" role="tab" aria-selected="false" tabindex="-1">
                                            Học sinh
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="#tab_schedule" class="nav-link " data-tab="schedule" data-toggle="tab" role="tab" tabindex="-1">
                                            Lịch học
                                        </a>
                                    </li>
                                    <li class="nav-item ">
                                        <a href="#tab_teacher" class="nav-link " data-tab="teacher" data-toggle="tab" role="tab" tabindex="-1">
                                            Giáo viên
                                        </a>
                                    </li>
                                </ul>
                                <div id="tabs-content">
                                    <div class="tab-content mt-3">
                                        <div class="tab-pane active" id="tab_info" role="tabpanel">
                                            <div class="tab-pane-content">
                                                <form action="{{ route('class.update', ['id' => $class->id]) }}" id="form_tab_info" method="post">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label>Tên lớp <span class="asterisk">(*)</span></label>
                                                        <input type="text" name="class_name" value="{{ $class->name }}" class="js-required" required="">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Trạng thái</label>
                                                        <select name="status">
                                                            <option {{ ($class->status == 0 ? "selected":"") }} value="0">Đang vận hành</option>
                                                            <option {{ ($class->status == 1 ? "selected":"") }} value="1">Đã kết thúc khóa học</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Năm học</label>
                                                        <select id="year" name="year">
                                                            <option value="">Chọn năm học</option>
                                                                <option {{ ($class->year == '2023' ? "selected":"") }} value="2023" selected="">2023-2024</option>
                                                                <option {{ ($class->year == '2022' ? "selected":"") }} value="2022">2022-2023</option>
                                                                <option {{ ($class->year == '2021' ? "selected":"") }} value="2021">2021-2022</option>
                                                                <option {{ ($class->year == '2020' ? "selected":"") }} value="2020">2020-2021</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Khóa học <span class="asterisk">(*)</span></label>
                                                        <select name="course_ids[]" id="course_ids" required multiple>
                                                            <!-- Duyệt qua danh sách khóa học -->
                                                            @foreach($courses as $course)
                                                                <!-- Kiểm tra xem khóa học có trong danh sách 'course_ids' không -->
                                                                <option value="{{ $course->id }}" 
                                                                    {{ in_array($course->id, $selectedCourseIds) ? 'selected' : '' }}>
                                                                    {{ $course->moodle_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div id="box-button" class="text-center">
                                                        <button type="submit" id="btn-save" class="btn btn-primary">Lưu</button>
                                                        <a href="{{ route('class.index') }}" id="btn-cancel" class="btn btn-secondary">Huỷ</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab_student" role="tabpanel">
                                            @include('class.tab-content.student')
                                        </div>
                                        <div class="tab-pane" id="tab_schedule" role="tabpanel">
                                            @include('class.tab-content.schedule')
                                        </div>
                                        <div class="tab-pane" id="tab_teacher" role="tabpanel">
                                            @include('class.tab-content.teacher')
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

<script src="{{ URL::asset('js/classes/classes.js') }}"></script>
<script>
    function showModalEditTeacher(button) {
        const teacherId = $(button).attr('data-teacher-id');
        const classId = $(button).attr('data-class-id');
        $('#upsertTeacherModal').modal('show');
        $.ajax({
            url: '/api/class/teacher/get_infor_teacher_class',
            type: 'GET',
            data: {
                'teacherId': teacherId,
                'classId': classId
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
                    var allCourses = userData.allCourses;
                    var selectedCourseIds = userData.selected_course_ids ?? [];

                    // Gán giá trị vào các trường input
                    $('form#info_teacher input[name="firstname"]').val(userFirstname);
                    $('form#info_teacher input[name="lastname"]').val(userLastname);
                    $('form#info_teacher input[name="email"]').val(userEmail);
                    $('form#info_teacher input[name="phone2"]').val(phone2);
                    $('form#info_teacher input[name="city"]').val(city);
                    $('form#info_teacher input[name="username"]').val(userName);

                    $('.btn-save-teacher').attr('data-userMoodleId', userMoodleId);
                    $('.btn-save-teacher').attr('data-userTeacherId', userData.teacher.id);
                    $('.btn-save-teacher').attr('data-class-id', classId);

                    // Gán giá trị vào select country
                    $('form#info_teacher select[name="country"]').val(country).trigger('change'); // trigger 'change' nếu cần cập nhật giao diện

                    var courseSelect = $('#course_ids_teacher');
                    courseSelect.empty(); // Xóa các option cũ
                    allCourses.forEach(function(course) {
                        var isSelected = selectedCourseIds.includes(course.id) ? 'selected' : '';
                        courseSelect.append(
                            `<option value="${course.id}" ${isSelected}>${course.moodle_name}</option>`
                        );
                    });
                    courseSelect.trigger('change'); // Nếu sử dụng plugin như Select2
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    $('.btn-save-teacher').on('click', function () {
        var userMoodleId = $(this).attr('data-userMoodleId');
        var userTeacherId = $(this).attr('data-userTeacherId');
        var class_id = $(this).attr('data-class-id');

        var userFirstname = $('form#info_teacher input[name="firstname"]').val().trim();
        var userLastname = $('form#info_teacher input[name="lastname"]').val().trim();
        var userEmail = $('form#info_teacher input[name="email"]').val().trim();
        // var userName = $('form#info_teacher input[name="username"]').val().trim();
        var country = $('form#info_teacher select[name="country"]').val(); // Country code or name
        var city = $('form#info_teacher input[name="city"]').val().trim();
        var phone2 = $('form#info_teacher input[name="phone2"]').val().trim();

        // Lấy danh sách course ids từ select
        var courseIds = $('#course_ids_teacher').val(); // Đây sẽ là mảng chứa các ID của khóa học đã chọn

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

        // if (!userName) {
        //     errors.push('Username is required.');
        // }

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
        formData.append('class_id', class_id);
        // Thêm các course ids vào formData
        courseIds.forEach(function(courseId) {
            formData.append('course_ids_teacher[]', courseId);
        });

        $.ajax({
            url: '/api/class/teacher/update_infor_teacher_class',
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

    // Hàm validate email
    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showModalDeleteTeacher(button) {
        const classId = $(button).attr('data-class-id');
        const className = $(button).attr('data-class-name');
        const teacherId = $(button).attr('data-teacher-id');
        const teacherName = $(button).attr('data-teacher-name');
        
        $('#removeTeacherModal #modal_teacher_name').text(teacherName);

        // $('#removeTeacherModal #teacher_id_remove_teacher').val(teacherId);
        
        // $('#removeTeacherModal #class_id_remove_teacher').val(classId);

        $('#removeTeacherModal .remove-teacher').attr('data-teacher-id', teacherId);

        $('#removeTeacherModal .remove-teacher').attr('data-class-id', classId);

        $('#removeTeacherModal').modal('show');
    }

    $('#removeTeacherModal .remove-teacher').on('click', function () {
        const classId = $(this).attr('data-class-id');
        const teacherId = $(this).attr('data-teacher-id');

        var formData = new FormData();
        formData.append('classId', classId);
        formData.append('teacherId', teacherId);

        $.ajax({
            url: '/api/class/teacher/unenrol',
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
</script>
@endsection