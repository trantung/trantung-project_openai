@extends('layouts.app')

@section('content')
<link href="{{ URL::asset('css/rubric-templates/rubric_template.css') }}"rel="stylesheet">

<link href="{{ URL::asset('css/rubric-templates/popup/index.css')}}"rel="stylesheet">
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
                            <h2>Danh sách bộ mẫu điểm số</h2>
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
                                    Tổng số bộ mẫu điểm số: {{ $rubricTemplates->total() }}
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('rubric_templates.create') }}" class="btn btn-primary mb-2">+ Bộ mẫu điểm số</a>
                            </div>
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-sso-teacher">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Tên bộ mẫu điểm</th>
                                        <th>Mẫu điểm</th>
                                        <th>Bộ đề áp dụng</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rubricTemplates as $key => $item)
                                        <tr>
                                            <td align="center">{{ $item->id }}</td>
                                            <td align="center">{{ $item->name }}</td>
                                            <td align="center">
                                                <a href="{{route('rubric_scores.index', ['rubric_template_id' => $item->id])}}" target="_blank">
                                                    <i class="fas fa fa-lg fa-list"></i>
                                                </a>
                                            </td>
                                            <td align="center">
                                                <a href="{{route('api_ems.index', ['rubric_template_id' => $item->id])}}" target="_blank">
                                                    <i class="fas fa fa-lg fa-list"></i>
                                                </a>
                                            </td>
                                            <td class="action-table">
                                                <a href="{{ route('rubric_templates.edit', $item->id) }}"> <i class="fas fa fa-lg fa-edit text-success"></i> </a>
                                                <div>
                                                    <form action="{{ route('rubric_templates.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-button">
                                                             <i class="fas fa fa-lg fa-trash text-danger"></i> </a>
                                                        </button>
                                                    </form>
                                                </div>
                                                <a href="#" class="setting-ems-type" data-toggle="modal" data-target="#staticBackdrop" data-id="{{ $item->id }}">
                                                    <i class="fa-solid fa-gear" style="color: #df3076;"></i>
                                                <a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-pagination :pagination='$rubricTemplates'/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('js/rubric-template/main.js') }}"></script>
@include('rubric-templates.popup.index');
@endsection
