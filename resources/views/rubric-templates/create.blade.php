@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/rubric-templates/rubric_template.css') }}"rel="stylesheet">
@if(session('error'))
    <div class="alert alert-danger" id="error-alert">
        {{ session('error') }}
    </div>
@endif
<div id="main-content">
    {{-- <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}"> --}}
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div id="box-content">
                            <h3>Thêm bộ mẫu điểm</h3>
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
                                                <form action="{{ route('rubric_templates.store') }}" id="" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Tên mẫu điểm <span class="asterisk">(*)</span></label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" name="name" value="{{old('name')}}" class="js-required form-control" placeholder="Nhập tên" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Mô tả</label>
                                                        </div>
                                                        <div class="col-5">
                                                            <textarea id="description" class="form-control" name="description" placeholder="Nhập mô tả" rows="5">{{old('description')}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="rubric-score">
                                                        <div class="mb-2 row">
                                                            <div class="col-2">Bảng điểm</div>
                                                            <div class="col-4">
                                                                <input type="text" name="rubric_scores[create][0][lms_score]" value="{{old('rubric_scores[create][0][lms_score]')}}" class="js-required form-control" placeholder="lms_score" required>
                                                            </div>
                                                            <span> - </span>
                                                            <div class="col-4">
                                                                <input type="text" name="rubric_scores[create][0][rule_score]" value="{{old('rubric_scores[create][0][rule_score]')}}" class="js-required form-control" placeholder="rule_score" required>
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2"></div>
                                                        <div class="col-5">
                                                            <button type="button" class="btn btn-info add-rubric-score">Thêm bảng điểm</button>
                                                        </div>
                                                    </div>
                                                    <div id="box-button" class="text-center">
                                                        <button type="submit" id="btn-save" class="btn btn-primary">Lưu</button>
                                                        <a href="{{ route('rubric_templates.index') }}" id="btn-cancel" class="btn btn-secondary">Huỷ</a>
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

{{-- @include('rubric-templates.rubric-score.row'); --}}
<script src="{{ URL::asset('js/rubric-template/main.js') }}"></script>
@endsection
