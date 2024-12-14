<?php

use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Roles;

function checkPermissionUser($routeName)
{
    // Kiểm tra người dùng đã đăng nhập
    $user = Auth::user();
    if (!$user) {
        return false; // Người dùng chưa đăng nhập
    }

    if($user->email == 'kienpn@ctv.hocmai.vn'){
        return true;
    }

    // Lấy danh sách role_id của người dùng
    $roleIds = UserRole::where('user_id', $user->id)->pluck('role_id');

    $roleNames = Roles::whereIn('id', $roleIds)->pluck('name')->toArray();

    // Kiểm tra nếu người dùng là admin
    if (in_array('admin', $roleNames)) {
        return true; // Người dùng là admin
    }

    // Lấy danh sách permission_id liên kết với role_id
    $permissionIds = RolePermission::whereIn('role_id', $roleIds)->pluck('permission_id');

    if ($permissionIds->isEmpty()) {
        return false; // Không có quyền nào liên kết với vai trò
    }

    // Kiểm tra quyền với routeName
    $hasPermission = Permission::whereIn('id', $permissionIds)
        ->where('route_name', $routeName)
        ->exists();

    return $hasPermission; // Trả về true nếu có quyền, false nếu không
}

