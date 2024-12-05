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
                            <h3>Thông tin lớp học - {{ $classes->name }}</h3>
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
                                                <form action="{{ route('class.update', ['id' => $classes->id]) }}" id="form_tab_info" method="post">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label>Tên lớp <span class="asterisk">(*)</span></label>
                                                        <input type="text" name="class_name" value="{{ $classes->name }}" class="js-required" required="">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Trạng thái</label>
                                                        <select name="status">
                                                            <option {{ ($classes->status == 0 ? "selected":"") }} value="0">Đang vận hành</option>
                                                            <option {{ ($classes->status == 1 ? "selected":"") }} value="1">Đã kết thúc khóa học</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Năm học</label>
                                                        <select id="year" name="year">
                                                            <option value="">Chọn năm học</option>
                                                                <option {{ ($classes->year == '2023' ? "selected":"") }} value="2023" selected="">2023-2024</option>
                                                                <option {{ ($classes->year == '2022' ? "selected":"") }} value="2022">2022-2023</option>
                                                                <option {{ ($classes->year == '2021' ? "selected":"") }} value="2021">2021-2022</option>
                                                                <option {{ ($classes->year == '2020' ? "selected":"") }} value="2020">2020-2021</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Khóa học <span class="asterisk">(*)</span></label>
                                                        <label class="label_course">{{ $classes->course_name }}</label>
                                                        <input type="hidden" name="course_id" value="{{ $classes->course_id }}">
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
@endsection