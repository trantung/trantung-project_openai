@extends('layouts.app')

@section('content')
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
                            <h3>Thêm quyền</h3>
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
                                                <form action="{{ route('permission.store') }}" id="form_permission_info" method="post">
                                                    @csrf
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Name <span class="asterisk">(*)</span></label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" name="permission_name" class="form-control" value="" class="js-required" required="">
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Route Name <span class="asterisk">(*)</span></label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" name="permission_route_name" class="form-control" value="" class="js-required" required="">
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Action <span class="asterisk">(*)</span></label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" name="permission_action" class="form-control" value="" class="js-required" required="">
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Method <span class="asterisk">(*)</span></label>
                                                        </div>
                                                        <div class="col-5">
                                                            <input type="text" name="permission_method" class="form-control" value="" class="js-required" required="">
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Mô tả</label>
                                                        </div>
                                                        <div class="col-5">
                                                            <textarea id="permission_description" class="form-control" name="permission_description" placeholder="Nhập mô tả" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2 row">
                                                        <div class="col-2">
                                                            <label>Roles</label>
                                                        </div>
                                                        <div class="col-5">
                                                            <select name="permission_roleIds[]" style="width: 100%;" id="permission_roleIds" multiple>
                                                                <!-- <option value="" disabled selected>-- Chọn khóa học --</option> -->
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
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
<script>
    $('#permission_roleIds').select2({
        placeholder: "-- Chọn vai trò --",
        allowClear: true,
        tags: true
    });
</script>
@endsection