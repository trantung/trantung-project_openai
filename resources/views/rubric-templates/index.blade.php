@extends('layouts.app')

@section('content')
{{-- <link href="{{ URL::asset('css/rubric_template.css') }}"rel="stylesheet"> --}}
{{-- {{ dd(URL::asset('css/products.css'));}} --}}
@if(session('success'))
    <div class="alert alert-success" id="success-alert">
        {{ session('success') }}
    </div>
@endif
<div id="main-content">
    <div class="container-fluid">
        <div class="row">
            <div id="region-main" class="content-col col-md-12">
                <div id="page-content">
                    <span class="notifications" id="user-notifications"></span>
                    <div role="main">
                        <span id="maincontent"></span>
                        <div class="">
                            <h2>Danh sách mẫu điểm số</h2>
                            <form action="{{ route('rubric_templates.index') }}" class="form-inline mb-2 search-form" method="get">
                                <div class="input-group mb-7">
                                    <input type="text" class="form-control" placeholder="Name" aria-label="name" name="name" aria-describedby="basic-addon1">
                                </div>
                                <button type="submit" class="btn btn-primary ml-2 btn-search">Tìm kiếm</button>
                                {{-- <button type="button" class="btn btn-secondary ml-2 btn-clear">Xóa tìm kiếm</button> --}}
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="box-total-item">
                                    Tổng số mẫu điểm số: {{ $rubricTemplates->total() }}
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('rubric_templates.create') }}" class="btn btn-primary mb-2">+ Mẫu điểm số</a>
                            </div>
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-sso-teacher">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rubricTemplates as $key => $item)
                                        <tr id="user-{{ $item->id }}">
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <a href="{{ route('rubric_templates.edit', $item->id) }}"> <i class="fas fa fa-lg fa-edit text-success"></i> </a>
                                                <a> <i class="fas fa fa-lg fa-trash text-danger"></i> </a>
                                                <!-- <a class="login_as" data-fullname="Nguyễn  Bích Ngọc" data-course-id="1" data-user-id="10907" data-sesskey="t5uuyZVtfd" href="javascript:void(0);" data-toggle="modal" data-target="#modal_login_as" title="Đăng nhập với tư cách">
                                                    <i class="fa fa-lg fa-user-secret text-success"></i>
                                                </a> -->
                                                {{-- <a href="javascript:void(0);"
                                                    title="Xoá giáo viên"
                                                    data-type=""
                                                    data-teacher-id="{{ $teacher->user_id }}"
                                                    data-teacher-name="{{ $teacher->name }}"
                                                    data-class-id="{{ $teacher->classes->pluck('id')->implode(', ') }}"
                                                    onclick="showModalDeleteUser(this)"
                                                    class="item-delete">
                                                    <i class="fas fa fa-lg fa-trash text-danger"></i>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-pagination :pagpagination='$rubricTemplates'/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ URL::asset('js/products/products.js') }}"></script> --}}
@endsection
