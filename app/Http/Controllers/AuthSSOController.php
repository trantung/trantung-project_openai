<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Teachers;
use App\Models\Common;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthSSOController extends Controller
{
    public function keycloakCallback()
    {
        try {
            // Lấy thông tin người dùng từ Keycloak
            $keycloakUser = Socialite::driver('keycloak');
            // dd($keycloakUser, $keycloakUser->user()->email);

            $user = User::firstOrCreate(
                ['email' => $keycloakUser->user()->email],
                [
                    'name' => $keycloakUser->user()->name,
                    'username' => $keycloakUser->user()->nickname,
                    'password' => bcrypt('Hocmai@1234'),
                ]
            );

            if ($user) {
                // Tìm hoặc tạo teacher dựa trên user_id
                $existingTeacher = Teachers::withTrashed()
                    ->where('email', $user->email)
                    ->first();

                if ($existingTeacher) {
                    // Nếu tồn tại bản ghi bị xóa mềm, khôi phục nó
                    $existingTeacher->restore();
                    // $existingTeacher->update([
                    //     'user_id' => 7,
                    //     'name' => 'Teacher 1',
                    //     'username' => 'teacher1',
                    //     'sso_id' => '79c516a8-e2f1-4ed5-a0e4-47519c39d729',
                    //     'sso_name' => 'keycloak',
                    // ]);
                    // $teacher = $existingTeacher; // Gán bản ghi khôi phục vào biến $teacher
                } else {
                    // Nếu không tồn tại bản ghi bị xóa mềm, tạo mới
                    $teacher = Teachers::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'name' => $user->name,
                            'username' => $user->username,
                            'email' => $user->email,
                            'sso_id' => $keycloakUser->user()->id,
                            'sso_name' => 'keycloak'
                        ]
                    );
                }
                // Đăng nhập user
                Auth::login($user);
        
                // Chuyển hướng tới dashboard
                return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
            } else {
                // Log lỗi nếu user không được tạo
                \Log::error('Không thể tạo hoặc tìm thấy user với email: teacher1@gmail.com');
                return redirect('/ssologin')->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập.');
            }
        
            // Lấy ID của user mới tạo hoặc tìm được
            $userId = $user->id;
            // Đăng nhập người dùng
            Auth::login($user);
        
            // Chuyển hướng sau khi đăng nhập thành công
            return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
        } catch (\Throwable $e) {
            // Ghi log lỗi nếu cần
            \Log::error('Lỗi đăng nhập Keycloak: ' . $e->getMessage());
        
            // Chuyển hướng tới trang lỗi hoặc thông báo lỗi
            return redirect('/ssologin')->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập.');
        }

        // try {
        //     // Tìm hoặc tạo người dùng
        //     $user = User::firstOrCreate(
        //         ['email' => 'teacher2@gmail.com'],
        //         [
        //             'name' => 'Teacher 2',
        //             'username' => 'teacher2',
        //             'password' => bcrypt('Hocmai@1234'),
        //         ]
        //     );
        
        //     // Kiểm tra nếu tạo hoặc tìm thấy user thành công
        //     if ($user) {
        //         // Tìm hoặc tạo teacher dựa trên user_id
        //         $teacher = Teachers::firstOrCreate(
        //             ['user_id' => $user->id],
        //             [
        //                 'name' => $user->name,
        //                 'username' => $user->username,
        //                 'email' => $user->email,
        //                 'sso_id' => '79c516a8-e2f1-4ed5-a0e4-47519c39d729',
        //                 'sso_name' => 'keycloak'
        //             ]
        //         );
        
        //         // Đăng nhập user
        //         Auth::login($user);
        
        //         // Chuyển hướng tới dashboard
        //         return redirect('/dashboard')->with('success', 'Đăng nhập thành công!');
        //     } else {
        //         // Log lỗi nếu user không được tạo
        //         \Log::error('Không thể tạo hoặc tìm thấy user với email: teacher1@gmail.com');
        //         return redirect('/login')->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập.');
        //     }
        // } catch (\Throwable $e) {
        //     // Log lỗi chi tiết
        //     \Log::error('Lỗi xảy ra trong quá trình xử lý đăng nhập: ' . $e->getMessage());
        
        //     // Chuyển hướng với thông báo lỗi
        //     return redirect('/login')->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập.');
        // }
    }

    public function sso_teacher(Request $request)
    {
        $teacherId = $request->input('teacher'); // Lấy teacherId từ request
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('class.index'),
                'text' => 'Danh sách giáo viên',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        // Sử dụng các scope tìm kiếm giáo viên theo ID, tên, email, hoặc username
        $teachersQuery = Teachers::query();

        if ($teacherId) {
            // Tìm giáo viên theo ID nếu có teacherId
            $teachersQuery->where('id', $teacherId);
        }

        // Nếu có tên, email hoặc username trong request, tìm theo các trường này
        if ($searchTerm = $request->input('searchTerm')) {
            $teachersQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('username', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Phân trang kết quả
        $teachers = $teachersQuery->paginate(10);

        // Lấy tổng số giáo viên tìm thấy
        $totalTeacher = $teachers->total();

        return view('ssologin.teacher', compact('breadcrumbs', 'totalTeacher', 'teachers', 'currentEmail'));
    }

    public function sso_teacher_search(Request $request)
    {
        $teacherId = $request->input('teacher'); // Lấy teacherId từ request
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('class.index'),
                'text' => 'Danh sách giáo viên',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        // Sử dụng các scope tìm kiếm giáo viên theo ID, tên, email, hoặc username
        $teachersQuery = Teachers::query();

        if ($teacherId) {
            // Tìm giáo viên theo ID nếu có teacherId
            $teachersQuery->where('id', $teacherId);
        }

        // Nếu có tên, email hoặc username trong request, tìm theo các trường này
        if ($searchTerm = $request->input('searchTerm')) {
            $teachersQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('username', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Phân trang kết quả
        $teachers = $teachersQuery->paginate(10);

        // Lấy tổng số giáo viên tìm thấy
        $totalTeacher = $teachers->total();

        return view('ssologin.teacher', compact('breadcrumbs', 'totalTeacher', 'teachers', 'currentEmail'));
    }

}
