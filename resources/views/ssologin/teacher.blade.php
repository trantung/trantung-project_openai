@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/ssologin.css') }}"rel="stylesheet">
<div id="main-content">
    <input type="hidden" id="currentUserEmail" value="{{ $currentEmail }}">
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
                            <form action="{{ route('ssouser.teacher.index') }}" class="form-inline mb-2 search-form" method="get">
                                <!-- Select for Class Filter -->
                                <select id="search-teacher-sso" name="teacher" style="width: 20%;" class="select2-hidden-accessible" aria-hidden="true">
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
                                <button type="button" class="btn btn-secondary ml-2 btn-clear">Xóa tìm kiếm</button>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="box-total-item">
                                    Tổng số giáo viên: {{ $totalTeacher }}
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
                                        <th><div>Tên giáo viên</div></th>
                                        <th><div>Tài khoản</div></th>
                                        <th><div>Email</div></th>
                                        <!-- <th><div>Hành động</div></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teachers as $index => $teacher)
                                        <tr id="user-{{ $teacher->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-left">{{ $teacher->name }}</td>
                                            <td class="text-left">{{ $teacher->username }}</td>
                                            <td class="text-left">{{ $teacher->email }}</td>
                                            <!-- <td>
                                                <a href="#" 
                                                title="Phân quyền học liệu">
                                                    <i class="fas fa fa-lg fa-eye"></i>
                                                </a>
                                                <a href="#" 
                                                title="Thêm học sinh">
                                                    <i class="fas fa fa-lg fa-user-plus text-info"></i>
                                                </a>
                                                <a href="#" 
                                                title="Chỉnh sửa lớp học">
                                                    <i class="fas fa fa-lg fa-edit text-success"></i>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                    class="item-delete btnDeleteClass" 
                                                    data-class-id="{{ $teacher->id }}" 
                                                    data-class-name="{{ $teacher->name }}" 
                                                    title="Xóa lớp học">
                                                    <i class="fas fa fa-lg fa-trash text-danger"></i>
                                                </a>
                                            </td> -->
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


<script src="{{ URL::asset('js/ssologin/ssologin.js') }}"></script>
@endsection