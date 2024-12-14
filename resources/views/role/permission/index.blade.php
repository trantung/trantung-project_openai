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
                        <div class="">
                            <h2>Danh sách quyền</h2>
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
                            <form action="{{ route('permission.index') }}" class="form-inline mb-2 search-form" method="get">
                                <input type="text" name="permission_search" id="permission_search" placeholder="Nhập thông tin quyền" class="form-control" value="{{ request('permission_search') }}" style="width: 350px;">
                                <!-- Buttons -->
                                <button type="submit" class="btn btn-primary ml-2 btn-search">Tìm kiếm</button>
                                <button type="button" class="btn btn-secondary ml-2 btn-clear">Xóa tìm kiếm</button>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="box-total-item">
                                    Tổng số quyền: {{ $totalPermissionsCount }}
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('permission.add') }}" class="btn btn-primary mb-2">+ Quyền</a>
                            </div>
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-role">
                                <thead>
                                    <tr>
                                        <th><div>STT</div></th>
                                        <th><div>Name</div></th>
                                        <th><div>Route Name</div></th>
                                        <th><div>Action</div></th>
                                        <th><div>Method</div></th>
                                        <th><div>Roles</div></th>
                                        <th><div>Hành động</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $index => $permission)
                                        <tr id="permission-{{ $permission->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-left">{{ $permission->name }}</td>
                                            <td>{{ $permission->route_name }}</td>
                                            <td>{{ $permission->action }}</td>
                                            <td>{{ $permission->method }}</td>
                                            <td>
                                                @if($permission->roles->isNotEmpty())
                                                    {{ $permission->roles->pluck('name')->implode(', ') }}
                                                @else
                                                    <em>Chưa có vai trò</em>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('permission.edit', ['id' => $permission->id]) }}" 
                                                title="Chỉnh sửa vai trò">
                                                    <i class="fas fa fa-lg fa-edit text-success"></i>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                    class="item-delete btnDeleteRole" 
                                                    data-permission-id="{{ $permission->id }}" 
                                                    data-permission-name="{{ $permission->name }}" 
                                                    onclick="showModalDeletePermission(this)"
                                                    title="Xóa vai trò">
                                                    <i class="fas fa fa-lg fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination mt-4 justify-content-center">
                            {{ $permissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal moodle-has-zindex deletePermissionModal" data-region="modal-container" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" role="document" data-region="modal" aria-labelledby="0-modal-title" tabindex="0">
        <div class="modal-content">
            <div class="modal-header " data-region="header">
                <h5 id="0-modal-title" class="modal-title" data-region="title">Xóa lớp học</h5>
                <button type="button" class="close" data-action="hide" data-dismiss="modal" aria-label="Đóng">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center" data-region="body">
                <form action="{{ route('permission.delete') }}" id="deletePermissionForm" method="post">
                    @csrf
                    <input type="hidden" name="permission_id" id="permission_id">
                    Bạn có chắc chắn xoá "<b class="this_item_name">test</b>" khỏi hệ thống? <br>
                </form>
            </div>
            <div class="modal-footer justify-content-center" data-region="footer">
                <button type="button" class="btn btn-primary btn-action-delete-permission" data-action="save">Xoá</button>
                <button type="button" class="btn btn-secondary" data-action="cancel" data-dismiss="modal">Huỷ bỏ</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="{{ URL::asset('js/classes/classes.js') }}"></script> -->
<script>
    function showModalDeletePermission(button) {
        const permissionId = $(button).attr('data-permission-id');
        const permissionName = $(button).attr('data-permission-name');
        $('.deletePermissionModal .this_item_name').text(permissionName);

        $('.deletePermissionModal #permission_id').val(permissionId);

        $('.deletePermissionModal').modal('show');
    }

    $('.btn-action-delete-permission').on('click', function() {
        $('#deletePermissionForm').submit();
    });

    $('.btn-clear').on('click', function() {
        // Reset all select options to empty
        $('#permission_search').val('');

        // Submit the form after clearing
        $('.search-form').submit();
    });
</script>
@endsection