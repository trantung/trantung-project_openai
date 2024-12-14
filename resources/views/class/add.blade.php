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
                            <h3>Thêm lớp</h3>
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
                                        <a href="#tab_info" class="nav-link active" data-tab="info" data-toggle="tab" role="tab">
                                            Thông tin chung
                                        </a>
                                    </li>
                                </ul>
                                <div id="tabs-content">
                                    <div class="tab-content mt-3">
                                        <div class="tab-pane active" id="tab_info" role="tabpanel">
                                            <div class="tab-pane-content">
                                                <form action="{{ route('class.store') }}" id="form_tab_info" method="post">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label>Tên lớp <span class="asterisk">(*)</span></label>
                                                        <input type="text" name="class_name" value="" class="js-required" required="">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Trạng thái</label>
                                                        <select name="status">
                                                            <option value="0">Đang vận hành</option>
                                                            <option value="1">Đã kết thúc khóa học</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Năm học</label>
                                                        <select id="year" name="year">
                                                            <option value="">Chọn năm học</option>
                                                                <option value="2023" selected="">2023-2024</option>
                                                                <option value="2022">2022-2023</option>
                                                                <option value="2021">2021-2022</option>
                                                                <option value="2020">2020-2021</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Khóa học <span class="asterisk">(*)</span></label>
                                                        <select name="course_ids[]" id="course_ids" required="" multiple>
                                                            <!-- <option value="" disabled selected>-- Chọn khóa học --</option> -->
                                                            @foreach($courses as $course)
                                                                <option value="{{ $course->id }}">{{ $course->moodle_name }}</option>
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