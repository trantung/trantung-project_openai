@extends('layouts.app')

@section('content')
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
                            <h2>Danh sách mẫu điểm</h2>
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
                                    Tổng số mẫu điểm: {{ $rubricScores->total() }}
                                </div>
                            </div>
                            {{-- <div class="col-6 text-right">
                                <a href="{{ route('rubric_templates.create') }}" class="btn btn-primary mb-2">+ Mẫu điểm số</a>
                            </div> --}}
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-sso-teacher">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Mẫu điểm</th>
                                        <th>lms_score</th>
                                        <th>rule_score</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rubricScores as $key => $item)
                                        <tr>
                                            <td align="center">{{ $item->id }}</td>
                                            <td align="center">{{ $item->rubric_template->name ?? '' }}</td>
                                            <td align="center">{{ $item->lms_score ?? '' }}</td>
                                            <td align="center">{{ $item->rule_score ?? '' }}</td>
                                            <td align="center">
                                                <a href="#"> <i class="fas fa fa-lg fa-edit text-success"></i> </a>
                                                <a> <i class="fas fa fa-lg fa-trash text-danger"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-pagination :pagination='$rubricScores'/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
