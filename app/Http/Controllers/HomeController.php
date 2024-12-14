<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\OpenAiJob;
use App\Models\User;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use App\Models\ApiMoodle;

class HomeController extends Controller
{
    public function __construct()
    {
    
    }
    
    public function index()
    {
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ]
        ];
        return view('admin.dashboard', compact('breadcrumbs'));
    }

    public function index1()
    {
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ]
        ];

        $courses = ApiMoodle::where('moodle_type', 'course')->get();

        $menus = [
            [
                'title' => 'Khoá của tôi',
                'type' => 'dynamic',
                'items' => $courses->map(function ($course) {
                    return [
                        'name' => $course->moodle_name,
                        'url' => env('URL_LMS') . '/course/view.php?id=' . $course->moodle_id,
                    ];
                })->toArray(),
            ],
            [
                'title' => 'Quản lí vận hành',
                'items' => [
                    ['name' => 'Danh sách lớp', 'route' => 'class.index'],
                    ['name' => 'Danh sách giáo viên', 'route' => 'teacher.index'],
                    ['name' => 'Danh sách giáo viên SSO', 'route' => 'ssouser.teacher.index'],
                ],
            ],
            [
                'title' => 'Quản lí nội dung',
                'items' => [
                    ['name' => 'Quản lí sản phẩm', 'route' => 'course.index'],
                ],
            ],
            [
                'title' => 'Hệ thống',
                'items' => [
                    ['name' => 'Danh sách vai trò', 'route' => 'role.index'],
                ],
            ],
            [
                'title' => 'Báo cáo',
                'items' => [], // Không có item nào nên sẽ bị ẩn
            ],
        ];

        // Lọc các menu không hợp lệ
        foreach ($menus as &$menu) {
            // Kiểm tra item động (ví dụ: khóa học)
            if (isset($menu['type']) && $menu['type'] === 'dynamic') {
                $menu['items'] = array_filter($menu['items']);
            } else {
                // Kiểm tra các route bằng `checkPermissionUser`
                $menu['items'] = array_filter($menu['items'], function ($item) {
                    return isset($item['route']) ? checkPermissionUser($item['route']) : true;
                });
            }

            // Loại bỏ menu nếu không có item
            if (empty($menu['items'])) {
                unset($menu);
            }
        }

        return view('dashboard', compact('breadcrumbs'));
    }

    public function testQueue(Request $request)
    {
        $id = $request->input('id');
        DB::beginTransaction();
        try {
            $checkData = CustomerId::where('id', $id)->first();
            
            if (empty($checkData)) {
                // Nếu id chưa có trong bảng, insert dữ liệu mới và chạy queue
                $data = [
                    'question' => 'question',
                    'topic' => '1',
                    'user_id' => Auth::id(),
                    'status' => 0
                ];
                $questionTable = CustomerId::create($data);
                
                // Chạy queue
                dispatch(new OpenAiJob($questionTable->id, Auth::id()));
                
                DB::commit();
                return response()->json(['message' => 'Data inserted and job dispatched']);
            } else {
                if (empty($checkData->answer)) {
                    // Nếu id có trong bảng nhưng không có answer, chạy queue
                    dispatch(new OpenAiJob($id, Auth::id()));
                    
                    DB::commit();
                    return response()->json(['message' => 'Job dispatched to update answer']);
                } else {
                    DB::commit();
                    // Nếu có id và có answer, trả về answer
                    return response()->json(['answer' => $checkData->answer]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function testStreaming()
    {
        return view('testStreaming');
    }

    public function testChat()
    {
        return view('testChat');
    }

    public function sendMessage(Request $request)
    {
        return response()->json(['status' => true, 'message' => 'Hi']);
    }
}
