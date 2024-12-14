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
                            <h2>Danh sách vai trò</h2>
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
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="box-total-item">
                                    Tổng số vai trò: {{ $totalRolesCount }}
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('permission.index') }}" class="btn btn-primary mb-2">Permission</a>
                                <a href="{{ route('role.add') }}" class="btn btn-primary mb-2">+ Vai trò</a>
                            </div>
                        </div>
                        <div class="wrapper-table-scroll">
                            <table class="table-scroll table-role">
                                <thead>
                                    <tr>
                                        <th><div>STT</div></th>
                                        <th><div>Tên</div></th>
                                        <th><div>Mô tả</div></th>
                                        <th><div>Tên ngắn</div></th>
                                        <th><div>Users with role</div></th>
                                        <th><div>Hành động</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $index => $role)
                                        <tr id="role-{{ $role->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-left">{{ $role->name }}</td>
                                            <td>{{ $role->description }}</td>
                                            <td>{{ $role->short_name }}</td>
                                            <td>{{ $role->user_count }}</td>
                                            <td>
                                                <a href="{{ route('role.assigns', ['id' => $role->id]) }}" 
                                                    title="Phân quyền người dùng">
                                                    <i class="fas fa fa-lg fa-eye"></i>
                                                </a>
                                                <a href="{{ route('role.edit', ['id' => $role->id]) }}" 
                                                title="Chỉnh sửa vai trò">
                                                    <i class="fas fa fa-lg fa-edit text-success"></i>
                                                </a>
                                                <a href="javascript:void(0)" 
                                                    class="item-delete btnDeleteRole" 
                                                    data-role-id="{{ $role->id }}" 
                                                    data-role-name="{{ $role->name }}" 
                                                    onclick="showModalDeleteRole(this)"
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
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal moodle-has-zindex deleteRoleModal" data-region="modal-container" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" role="document" data-region="modal" aria-labelledby="0-modal-title" tabindex="0">
        <div class="modal-content">
            <div class="modal-header " data-region="header">
                <h5 id="0-modal-title" class="modal-title" data-region="title">Xóa lớp học</h5>
                <button type="button" class="close" data-action="hide" data-dismiss="modal" aria-label="Đóng">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center" data-region="body">
                <form action="{{ route('role.delete') }}" id="deleteRoleForm" method="post">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    Bạn có chắc chắn xoá "<b class="this_item_name">test</b>" khỏi hệ thống? <br>
                </form>
            </div>
            <div class="modal-footer justify-content-center" data-region="footer">
                <button type="button" class="btn btn-primary btn-action-delete-role" data-action="save">Xoá</button>
                <button type="button" class="btn btn-secondary" data-action="cancel" data-dismiss="modal">Huỷ bỏ</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="{{ URL::asset('js/classes/classes.js') }}"></script> -->
<script>
    function showModalDeleteRole(button) {
        const roleId = $(button).attr('data-role-id');
        const roleName = $(button).attr('data-role-name');
        $('.deleteRoleModal .this_item_name').text(roleName);

        $('.deleteRoleModal #role_id').val(roleId);

        $('.deleteRoleModal').modal('show');
    }

    $('.btn-action-delete-role').on('click', function() {
        $('#deleteRoleForm').submit();
    });
</script>
@endsection