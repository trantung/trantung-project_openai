<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use App\Models\Common;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\RolePermission;

class RoleController extends Controller
{
    public function index()
    {
        // dd(UserRole::withTrashed()->get());
        // Roles::truncate();
        // UserRole::truncate();
        // RolePermission::truncate();
        // Permission::truncate();

        $routeName = 'role.index';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('role.index'),
                'text' => 'Danh sách vai trò',
            ]
        ];

        // Lấy danh sách vai trò với số lượng user được gán
        $roles = Roles::paginate(10);

        // Đếm số người dùng chưa bị xóa mềm cho mỗi vai trò
        foreach ($roles as $role) {
            $role->user_count = $role->users()->whereNull('user_role.deleted_at')->count();
        }

        $currentEmail = Common::getCurrentUser()->email;

        $totalRolesCount = Roles::count();

        return view('role.index', compact('breadcrumbs', 'roles', 'currentEmail' , 'totalRolesCount'));
    }

    public function add()
    {
        $routeName = 'role.add';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('role.add'),
                'text' => 'Quản lý vai trò',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        return view('role.add', compact('breadcrumbs', 'currentEmail'));
    }

    public function store(Request $request)
    {
        $role = Common::storeRole($request->only(['name', 'description']));

        if (!$role['status']) {
            return redirect()
                ->route('role.add')
                ->with('error', $role['message']);
        }

        return redirect()
            ->route('role.index')
            ->with('success', $role['message']);
    }

    public function delete(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);
        
        try {
            // Gọi Common để xóa vai trò
            $result = Common::deleteRole($request->role_id);

            if ($result) {
                return redirect()->route('role.index')->with('success', 'Vai trò đã được xóa thành công.');
            }

            return redirect()->route('role.index')->with('error', 'Không thể xóa vai trò này.');
        } catch (\Exception $e) {
            return redirect()->route('role.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $routeName = 'role.edit';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('role.edit', $id),
                'text' => 'Quản lý vai trò',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        $role = Roles::find($id);

        return view('role.edit', compact('breadcrumbs', 'currentEmail', 'role'));
    }

    public function update(Request $request, $id)
    {
        // // Validate dữ liệu đầu vào
        $validatedData = [
            'name' => $request->name,
            'description' => $request->description ?? '',
        ];

        // Gọi hàm updateRole từ Common
        $result = Common::updateRole($validatedData, $id);

        if (!$result['status']) {
            return redirect()
                ->route('role.edit', $id)
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('role.edit', $id)
            ->with('success', $result['message']);
    }

    public function assignsIndex(Request $request, $id)
    {
        $routeName = 'role.assigns';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        // Tạo breadcrumbs
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('role.assigns', $id),
                'text' => 'Phân quyền người dùng',
            ]
        ];

        // Lấy email người dùng hiện tại
        $currentEmail = Common::getCurrentUser()->email;

        // Lấy danh sách user_id đã được gán vai trò với role_id là $id
        $existingUserIds = UserRole::where('role_id', $id)->pluck('user_id')->toArray();

        // Trạng thái để kiểm tra số lượng người dùng chưa được gán vai trò
        $status = false;

        // Danh sách người dùng chưa được gán vai trò
        $users = [];
        $countUsers = User::whereNotIn('id', $existingUserIds)->count();

        if ($countUsers <= 50) {
            $status = true;
            $users = User::whereNotIn('id', $existingUserIds)->get();
        }

        // Lấy danh sách người dùng đã được gán vai trò
        $assignedUsers = [];

        $countAssignedUsers = User::whereIn('id', $existingUserIds)->count();

        $statusAssignedUsers = false;

        if ($countAssignedUsers <= 50) {
            $statusAssignedUsers = true;
            $assignedUsers = User::whereIn('id', $existingUserIds)->get();
        }

        $role_id = $id;

        $role_name = Roles::find($role_id)->name;

        return view('role.assigns', compact(
            'breadcrumbs',
            'currentEmail',
            'users',
            'status',
            'countUsers',
            'assignedUsers',
            'statusAssignedUsers',
            'countAssignedUsers',
            'role_id',
            'role_name'
        ));
    }

    public function searchUserAssign(Request $request)
    {
        $existingUserIds = UserRole::where('role_id', $request['roleId'])->pluck('user_id')->toArray();
        if ($request['type'] == 'addselect_searchtext') {
            // Trạng thái để kiểm tra số lượng người dùng chưa được gán vai trò
            $status = false;

            // Danh sách người dùng chưa được gán vai trò
            $users = [];
            $countUsers = User::whereNotIn('id', $existingUserIds)
                ->where('email', 'like', '%' . $request['text'] . '%')
                ->count();

            if ($countUsers <= 50) {
                $status = true;
                $users = User::whereNotIn('id', $existingUserIds)
                    ->where('email', 'like', '%' . $request['text'] . '%')
                    ->get();
            }

            return response()->json([
                'status' => $status,
                'countUsers' => $countUsers,
                'users' => $users,
            ]);
        }

        if ($request['type'] == 'removeselect_searchtext') {
            $countAssignedUsers = User::whereIn('id', $existingUserIds)
                ->where('email', 'like', '%' . $request['text'] . '%')
                ->count();

            $assignedUsers = User::whereIn('id', $existingUserIds)
                ->where('email', 'like', '%' . $request['text'] . '%')
                ->get();

            return response()->json([
                'countUsers' => $countAssignedUsers,
                'users' => $assignedUsers,
            ]);
        }
    }

    public function assignUsers(Request $request)
    {
        $role_id = $request->input('role_id'); // Lấy role_id từ request

        if (!empty($request->input('add'))) {
            // Thêm người dùng vào vai trò
            foreach ($request->input('addselect', []) as $user_id) {
                UserRole::firstOrCreate([
                    'role_id' => $role_id,
                    'user_id' => $user_id,
                ]);
            }
        }

        if (!empty($request->input('remove'))) {
            // Gỡ bỏ người dùng khỏi vai trò
            foreach ($request->input('removeselect', []) as $user_id) {
                UserRole::where('role_id', $role_id)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        }

        // Tạo thông báo lỗi (nếu có) để hiển thị ở giao diện
        $msgError = '';
        if (empty($request->input('addselect')) && empty($request->input('removeselect'))) {
            $msgError = 'Không có người dùng nào được chọn để thêm hoặc xóa.';
        }
        $request->session()->put('msgError', $msgError);

        // Chuyển hướng trở lại trang trước đó
        return redirect()->back()->with('success', 'Cập nhật vai trò thành công.');
    }

    public function permissionIndex(Request $request)
    {
        $routeName = 'permission.index';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        // Lấy giá trị tìm kiếm từ request
        $permission_search = $request->query('permission_search');
        
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('role.index'),
                'text' => 'Danh sách quyền',
            ]
        ];

        // Kiểm tra nếu có tìm kiếm, áp dụng điều kiện vào query
        $permissions = Permission::with(['roles' => function ($query) {
            $query->whereNull('role_permission.deleted_at'); // Loại bỏ các liên kết bị xóa mềm trong bảng pivot
        }])
        ->when($permission_search, function ($query, $permission_search) {
            return $query->where(function ($query) use ($permission_search) {
                $query->where('name', 'like', '%' . $permission_search . '%')
                    ->orWhere('route_name', 'like', '%' . $permission_search . '%')
                    ->orWhere('action', 'like', '%' . $permission_search . '%')
                    ->orWhereHas('roles', function ($query) use ($permission_search) {
                        $query->where('roles.name', 'like', '%' . $permission_search . '%');
                    });
            });
        })
        ->paginate(10);

        $currentEmail = Common::getCurrentUser()->email;

        $totalPermissionsCount = Permission::count();

        return view('role.permission.index', compact('breadcrumbs', 'permissions', 'currentEmail' , 'totalPermissionsCount'));
    }

    public function permissionAdd()
    {
        $routeName = 'permission.add';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('permission.add'),
                'text' => 'Quản lý quyền',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        $roles = Roles::all();

        return view('role.permission.add', compact('breadcrumbs', 'roles', 'currentEmail'));
    }

    public function permissionStore(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'permission_name' => 'required|string|max:255',
            'permission_route_name' => 'required|string|max:255',
            'permission_action' => 'required|string|max:255',
            'permission_method' => 'required|string|max:10',
            'permission_description' => 'nullable|string',
            'permission_roleIds' => 'nullable|array',
        ]);

        try {
            // Kiểm tra sự tồn tại của route_name, action và method
            $existingPermission = Permission::where('route_name', $request->permission_route_name)
                ->where('action', $request->permission_action)
                ->where('method', $request->permission_method)
                ->first();

            if ($existingPermission) {
                return redirect()
                    ->route('permission.add')
                    ->with('error', 'Permission với Route Name, Action và Method này đã tồn tại.');
            }

            // Tạo mới một quyền (permission)
            $permission = Permission::create([
                'name' => $request->permission_name,
                'route_name' => $request->permission_route_name,
                'action' => $request->permission_action,
                'method' => $request->permission_method,
                'description' => $request->permission_description,
            ]);

            // Kiểm tra nếu có role IDs được gửi trong request
            if (!empty($request->permission_roleIds)) {
                // Gắn quyền vào các vai trò được chỉ định
                $permission->roles()->attach($request->permission_roleIds);
            }

            return redirect()
                ->route('permission.index')
                ->with('success', 'Permission đã được tạo thành công');
        } catch (\Exception $e) {
            return redirect()
                ->route('permission.add')
                ->with('error', 'Đã xảy ra lỗi khi tạo permission: ' . $e->getMessage());
        }
    }

    public function permissionEdit($id)
    {
        $routeName = 'permission.edit';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('permission.edit', $id),
                'text' => 'Quản lý quyền',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        $permission = Permission::findOrFail($id);

        $selectedRoleIds = $permission->roles()->whereNull('role_permission.deleted_at')->pluck('roles.id')->toArray();
        
        $roles = Roles::all();

        return view('role.permission.edit', compact('breadcrumbs', 'permission', 'selectedRoleIds', 'currentEmail', 'roles'));
    }

    public function permissionUpdate(Request $request, $id)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'permission_name' => 'required|string|max:255',
            'permission_route_name' => 'required|string|max:255',
            'permission_action' => 'required|string|max:255',
            'permission_method' => 'required|string|max:10',
            'permission_description' => 'nullable|string',
            'permission_roleIds' => 'nullable|array',
        ]);

        try {
            // Lấy permission cần cập nhật
            $permission = Permission::findOrFail($id);

            // Kiểm tra trùng lặp route_name, action và method (ngoại trừ chính nó)
            $existingPermission = Permission::where('route_name', $request->permission_route_name)
                ->where('action', $request->permission_action)
                ->where('method', $request->permission_method)
                ->where('id', '!=', $id)
                ->first();

            if ($existingPermission) {
                return redirect()
                    ->route('permission.edit', ['id' => $id])
                    ->with('error', 'Permission với Route Name, Action và Method này đã tồn tại.');
            }

            // Cập nhật thông tin permission
            $permission->update([
                'name' => $request->permission_name,
                'route_name' => $request->permission_route_name,
                'action' => $request->permission_action,
                'method' => $request->permission_method,
                'description' => $request->permission_description,
            ]);

            if (!empty($request->permission_roleIds)) {
                // Gỡ bỏ các vai trò không nằm trong danh sách mới bằng cách xóa mềm
                $existingRoles = $permission->roles()
                    ->wherePivotNull('deleted_at')
                    ->select('roles.id') // Chỉ định rõ bảng chứa cột `id`
                    ->pluck('id')
                    ->toArray();

                $rolesToRemove = array_diff($existingRoles, $request->permission_roleIds);

                foreach ($rolesToRemove as $roleId) {
                    $permission->roles()->updateExistingPivot($roleId, ['deleted_at' => now()]);
                }

                // Thêm mới các vai trò chưa được gắn
                $rolesToAdd = array_diff($request->permission_roleIds, $existingRoles);
                $permission->roles()->attach($rolesToAdd);
            } else {
                // Xóa mềm tất cả các vai trò nếu không có vai trò nào được gửi
                foreach ($permission->roles as $role) {
                    $permission->roles()->updateExistingPivot($role->id, ['deleted_at' => now()]);
                }
            }

            return redirect()
                ->route('permission.index')
                ->with('success', 'Permission đã được cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()
                ->route('permission.edit', ['id' => $id])
                ->with('error', 'Đã xảy ra lỗi khi cập nhật permission: ' . $e->getMessage());
        }
    }

    public function permissionDelete(Request $request)
    {
        $permission_id = $request->permission_id;

        try {
            // Tìm permission theo ID
            $permission = Permission::findOrFail($permission_id);

            // Xóa mềm tất cả các liên kết roles với permission trong bảng pivot
            foreach ($permission->roles as $role) {
                $permission->roles()->updateExistingPivot($role->id, ['deleted_at' => now()]);
            }

            // Xóa hoàn toàn permission
            $permission->delete();

            return redirect()
                ->route('permission.index')
                ->with('success', 'Permission và các liên kết của nó đã được xóa thành công');
        } catch (\Exception $e) {
            return redirect()
                ->route('permission.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa permission: ' . $e->getMessage());
        }
    }
}
