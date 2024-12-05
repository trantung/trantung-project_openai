<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Common;
use App\Models\ApiMoodle;
use DB;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->classes = new Classes();
    }

    public function getCurrentUser()
    {
        $user = Auth::user();

        $arr = [
            'user' => [],
        ];
        if ($user) {
            $arr['user'] = $user;
        }
        return $arr['user'];
    }

    public function index()
    {
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('class.index'),
                'text' => 'Danh sách lớp học',
            ]
        ];

        $classes = DB::table('classes')
        ->join('api_moodle', function ($join) {
            $join->on('classes.course_id', '=', 'api_moodle.id')
                ->where('api_moodle.moodle_type', '=', 'course');
        })
        ->select('classes.*', 'api_moodle.moodle_name as course_name')
        ->paginate(10);
        
        $currentEmail = Common::getCurrentUser()->email;

        $courses = ApiMoodle::where('moodle_type', 'course')
        ->get();

        $classesAll = Classes::all();

        return view('class.index', compact('breadcrumbs', 'classes', 'courses', 'currentEmail', 'classesAll'));
    }

    public function add()
    {
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('class.add'),
                'text' => 'Quản lý lớp học',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        $courses = ApiMoodle::where('moodle_type', 'course')
        ->get();

        return view('class.add', compact('breadcrumbs', 'courses', 'currentEmail'));
    }

    public function store(Request $request)
    {
        // Kiểm tra xem tên lớp có bị trùng trong cùng một khóa học không
        $existingClass = Classes::where('name', $request->input('class_name'))
            ->where('course_id', $request->input('course_id'))
            ->first();

        if ($existingClass) {
            return redirect()
                ->route('class.add')
                ->with('error', 'Tên lớp đã tồn tại trong khóa học này.');
        }

        // Tạo lớp học mới
        $newClass = Classes::create([
            'name' => $request->input('class_name'),
            'course_id' => $request->input('course_id'),
            'year' => $request->input('year'),
            'status' => $request->input('status')
        ]);

        if ($newClass) {
            return redirect()
                ->route('class.edit', $newClass->id)
                ->with('success', 'Lớp học đã được tạo thành công.');
        } else {
            return redirect()
                ->route('class.add')
                ->with('error', 'Không thể tạo lớp học. Vui lòng thử lại.');
        }
    }

    public function search(Request $request)
    {
        // Lấy các tham số tìm kiếm từ request
        $classId = $request->input('class');
        $status = $request->input('status');
        $courseId = $request->input('course');
    
        // Xây dựng query để lọc
        $query = DB::table('classes')
            ->join('api_moodle', function ($join) {
                $join->on('classes.course_id', '=', 'api_moodle.id')
                    ->where('api_moodle.moodle_type', '=', 'course');
            })
            ->select('classes.*', 'api_moodle.moodle_name as course_name');
    
        // Tìm kiếm theo lớp học
        if ($classId) {
            $query->where('classes.id', $classId);
        }
    
        // Tìm kiếm theo trạng thái
        if ($status !== null) {
            $query->where('classes.status', $status);
        }
    
        // Tìm kiếm theo khóa học
        if ($courseId) {
            $query->where('classes.course_id', $courseId);
        }
    
        // Lấy kết quả tìm kiếm và phân trang
        $classes = $query->paginate(10);
    
        // Gửi dữ liệu vào view
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('class.index'),
                'text' => 'Danh sách lớp học',
            ]
        ];
    
        $currentEmail = Common::getCurrentUser()->email;
    
        // Lấy danh sách khóa học
        $courses = ApiMoodle::where('moodle_type', 'course')->get();
    
        // Lấy tất cả lớp học
        $classesAll = Classes::all();
    
        return view('class.index', compact('breadcrumbs', 'classes', 'courses', 'currentEmail', 'classesAll'));
    }
    

    public function edit($id)
    {
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => '',
                'text' => 'Quản lý lớp học',
            ]
        ];

        $classId = $id;
    
        // Xây dựng query để lọc
        $query = DB::table('classes')
            ->join('api_moodle', function ($join) {
                $join->on('classes.course_id', '=', 'api_moodle.id')
                    ->where('api_moodle.moodle_type', '=', 'course');
            })
            ->select('classes.*', 'api_moodle.moodle_name as course_name');
    
        // Tìm kiếm theo lớp học
        if ($classId) {
            $query->where('classes.id', $classId);
        }
        // Lấy kết quả tìm kiếm và phân trang
        $classes = $query->first();

        $currentEmail = Common::getCurrentUser()->email;

        $courses = ApiMoodle::where('moodle_type', 'course')
        ->get();

        return view('class.edit', compact('breadcrumbs', 'classes', 'courses', 'currentEmail'));
    }

    public function update(Request $request, $id)
    {
        // Tìm lớp học cần cập nhật
        $class = Classes::find($id);

        // Kiểm tra xem lớp học có tồn tại không
        if (!$class) {
            return redirect()
                ->route('class.edit', $id)
                ->with('error', 'Lớp học không tồn tại.');
        }

        // Kiểm tra xem tên lớp có bị trùng trong cùng một khóa học không
        $existingClass = Classes::where('name', $request->input('class_name'))
            ->where('course_id', $request->input('course_id'))
            ->where('id', '!=', $id) // Loại trừ chính lớp học hiện tại
            ->first();

        if ($existingClass) {
            return redirect()
                ->route('class.edit', $id)
                ->with('error', 'Tên lớp đã tồn tại trong khóa học này.');
        }

        // Cập nhật thông tin lớp học
        $updated = $class->update([
            'name' => $request->input('class_name'),
            'course_id' => $request->input('course_id'),
            'year' => $request->input('year'),
            'status' => $request->input('status'),
        ]);

        if ($updated) {
            return redirect()
                ->route('class.edit', $id)
                ->with('success', 'Lớp học đã được cập nhật thành công.');
        } else {
            return redirect()
                ->route('class.edit', $id)
                ->with('error', 'Không thể cập nhật lớp học. Vui lòng thử lại.');
        }
    }

    public function delete(Request $request)
    {
        $classId = $request->input('class_id');
        
        try {
            // Kiểm tra xem lớp học có tồn tại hay không
            $class = Classes::find($classId);
            if (!$class) {
                return redirect()
                    ->route('class.index')
                    ->with('error', 'Lớp học không tồn tại.');
            }

            // Xóa lớp học
            $class->delete();

            return redirect()
                ->route('class.index')
                ->with('success', 'Lớp học đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu xảy ra
            return redirect()
                ->route('class.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa lớp học. Vui lòng thử lại.');
        }
    }


}
