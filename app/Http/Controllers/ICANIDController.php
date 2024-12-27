<?php

namespace App\Http\Controllers;

use Auth0\SDK\Auth0; // Thay đổi namespace
use ICANID\SDK\ICANID;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Students;
use App\Models\Common;
use Illuminate\Support\Facades\Auth;
use App\ApiService\FeService;

class ICANIDController extends Controller
{
    protected $apiService;

    public function __construct(FeService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function login(Request $request)
    {
        $auth0 = new ICANID([
            'domain'        => config('services.icanid.domain'),
            'redirect_url'  => config('services.icanid.redirect'),
            'client_id'     => config('services.icanid.client_id'),
            'client_secret' => config('services.icanid.client_secret'),
            // 'client_secret_authentication_method' => 'client_secret_basic',
            // 'cookieSecret' => 'client_secret_basic',
            // 'skip_userinfo' => false,
        ]);
        // Đăng nhập và redirect đến Auth0
        return $auth0->login();
    }

    public function callback(Request $request)
    {
        $icanid = new ICANID([
            'domain'        => config('services.icanid.domain'),
            'redirect_url'  => config('services.icanid.redirect'),
            'client_id'     => config('services.icanid.client_id'),
            'client_secret' => config('services.icanid.client_secret'),
            // 'client_secret_authentication_method' => 'client_secret_basic',
            // 'cookieSecret' => 'client_secret_basic',
            // 'skip_userinfo' => false,
        ]);


        // Lấy thông tin user và token sau khi icanid redirect về
        $user = $icanid->getUser();
        // $accessToken = $icanid->getAccessToken();
        // $refreshToken = $icanid->getRefreshToken();
        // $idToken = $icanid->getIdToken();
        // dd($user, $accessToken, $refreshToken, $idToken);
        $uid = $user['uid'];
        $email = $user['email'];
        $username = explode('@', $email)[0];

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $username,
                'username' => $username,
                'password' => bcrypt('Hocmai@1234'),
            ]
        );

        if ($user) {
            // Tìm hoặc tạo teacher dựa trên user_id
            $existingStudent = Students::withTrashed()
                ->where('email', $user->email)
                ->first();

            if ($existingStudent) {
                // Nếu tồn tại bản ghi bị xóa mềm, khôi phục nó
                $existingStudent->restore();
            } else {
                // Nếu không tồn tại bản ghi bị xóa mềm, tạo mới
                $teacher = Students::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'sso_id' => $uid,
                        'sso_name' => 'icanid'
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

        // Lưu thông tin user vào session hoặc database
        session(['icanid_user' => $user]);
        session(['access_token' => $accessToken]);
        session(['refresh_token' => $refreshToken]);
        session(['id_token' => $idToken]);

        // Chuyển hướng người dùng đến trang chính của ứng dụng
        return redirect('/home'); // Hoặc trang bạn muốn redirect
    }

    public function logout()
    {
        // $auth0 = new Auth0([
        //     'domain'        => config('services.icanid.domain'),
        //     'redirectUri'  => config('services.icanid.redirect'),
        //     'clientId'     => config('services.icanid.client_id'),
        //     'client_secret' => config('services.icanid.client_secret'),
        //     // 'client_secret_authentication_method' => 'client_secret_basic',
        //     'cookieSecret' => 'client_secret_basic',
        //     'skip_userinfo' => false,
        // ]);
        $icanid = new ICANID([
            'domain'        => config('services.icanid.domain'),
            'redirect_url'  => config('services.icanid.redirect'),
            'client_id'     => config('services.icanid.client_id'),
            'client_secret' => config('services.icanid.client_secret'),
            // 'client_secret_authentication_method' => 'client_secret_basic',
            // 'cookieSecret' => 'client_secret_basic',
            // 'skip_userinfo' => false,
        ]);

        // Xoá thông tin session khi logout
        $icanid->logout();
        session()->flush();

        return redirect('/');
    }

    public function getInfoUserByToken(Request $request)
    {
        $jsonData = $this->checkAccess($request, ['access_token', 'api_lms_key']);
        if(!$jsonData) {
            return $this->responseError();
        }
        if($jsonData['api_lms_key'] != env('API_LMS_FE')) {
            return $this->responseError();
        }
        return $this->responseSuccess(200, $this->apiService->getDataUserInfo($data));
    }
}
