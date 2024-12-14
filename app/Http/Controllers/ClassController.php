<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Teachers;
use Illuminate\Support\Facades\Auth;
use App\Models\Common;
use App\Models\ApiMoodle;
use App\Models\ClassCourse;
use App\Models\StudentClass;
use App\Models\CourseTeacher;
use App\Models\Roles;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\RolePermission;
use DB;

class ClassController extends Controller
{
    protected $common;

    public function __construct(Common $common, Classes $class)
    {
        $this->classes = $class;
        $this->common = $common;
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
        // dd(UserRole::withTrashed()->get(), CourseTeacher::withTrashed()->where('teacher_id', 1)->get());
        // dd(Teachers::withTrashed()->get(), ClassCourse::withTrashed()->get());
        // dd(CourseTeacher::truncate(), RolePermission::truncate(), UserRole::truncate(), Permission::truncate(), Roles::truncate(), Classes::truncate(), ClassCourse::truncate(), StudentClass::truncate());
        $routeName = 'class.index';
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
                'url' => route('class.index'),
                'text' => 'Danh sách lớp học',
            ]
        ];

        $classes = Classes::with('courses')->paginate(10);

        $currentEmail = Common::getCurrentUser()->email;

        // Lấy danh sách các khóa học từ API Moodle
        // $courses = ApiMoodle::where('moodle_type', 'course')->get();
        $courses = ApiMoodle::moodleType('course')->get();

        $classesAll = Classes::all();
        
        return view('class.index', compact('breadcrumbs', 'classes', 'courses', 'currentEmail', 'classesAll'));
    }

    public function add()
    {
        $routeName = 'class.add';
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
                'url' => route('class.add'),
                'text' => 'Quản lý lớp học',
            ]
        ];

        $currentEmail = Common::getCurrentUser()->email;

        // $courses = ApiMoodle::where('moodle_type', 'course')
        // ->get();
        $courses = ApiMoodle::moodleType('course')->get();

        return view('class.add', compact('breadcrumbs', 'courses', 'currentEmail'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['class_name', 'year', 'status']);
        $course_ids = $request->input('course_ids', []);

        // Tạo lớp học mới và xử lý trả về
        $result = $this->common->createClass($data, $course_ids);

        if (!$result['status']) {
            return redirect()
                ->route('class.add')
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('class.add')
            ->with('success', $result['message']);
    }

    public function search(Request $request)
    {
        $classId = $request->input('class');
        $status = $request->input('status');
        $courseId = $request->input('course');

        // Sử dụng Eloquent để xây dựng truy vấn
        $classes = Classes::query()
            ->when($classId, fn($query) => $query->where('id', $classId))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->when($courseId, fn($query) => $query->whereHas('courses', fn($q) => $q->where('api_moodle.id', $courseId)))
            ->with('courses') // Eager load quan hệ
            ->paginate(10);

        // Breadcrumb
        $breadcrumbs = [
            ['url' => route('dashboard'), 'text' => 'Tổng quan'],
            ['url' => route('class.index'), 'text' => 'Danh sách lớp học']
        ];

        $currentEmail = Common::getCurrentUser()->email;

        // Lấy danh sách khóa học từ Moodle
        // $courses = ApiMoodle::where('moodle_type', 'course')->get();
        $courses = ApiMoodle::moodleType('course')->get();

        $classesAll = Classes::all();

        return view('class.index', compact('breadcrumbs', 'classes', 'courses', 'currentEmail', 'classesAll'));
    }

    public function edit(Request $request, $id)
    {
        $routeName = 'class.edit';
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
                'url' => '',
                'text' => 'Quản lý lớp học',
            ]
        ];

        $classId = $id;

        // Tìm lớp học theo ID, sử dụng findOrFail để ném lỗi nếu không tìm thấy
        $class = Classes::findOrFail($classId);

        // Lấy danh sách course_ids từ bảng ClassCourse thông qua mối quan hệ
        $selectedCourseIds = $class->courses->pluck('id')->toArray(); // Sử dụng quan hệ courses đã định nghĩa trong model Classes
        
        $currentEmail = Common::getCurrentUser()->email;

        // Lấy danh sách khóa học liên quan đến class_id
        // $coursesEnrol = ApiMoodle::whereIn('id', $selectedCourseIds)
        //                     ->where('moodle_type', 'course')
        //                     ->get();
        $coursesEnrol = ApiMoodle::inIds($selectedCourseIds)->moodleType('course')->get();

        // $courses = ApiMoodle::where('moodle_type', 'course')->get();
        $courses = ApiMoodle::moodleType('course')->get();

        // Lấy danh sách giáo viên trong lớp học, tối ưu hóa việc join
        $teacherId = $request->query('teacher');

        if ($teacherId) {
            // Nếu có từ khóa tìm kiếm, tìm giáo viên qua scope searchTeachers
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
        } else {
            // Nếu không có từ khóa, lấy danh sách giáo viên mặc định
            $teachers = $class->teachers()->distinct('teachers.id')->paginate(10);
        }

        // $teachers = $class->teachers()->distinct()->paginate(10); // Sử dụng mối quan hệ teachers đã định nghĩa trong model Classes

        // Lấy danh sách khóa học của mỗi giáo viên
        $teachersWithCourses = $teachers->map(function ($teacher) use ($classId) {
            // Lấy các khóa học của giáo viên qua mối quan hệ với CourseTeacher
            $teacher->courses = $teacher->courses()->where('class_id', $classId)
                                                ->whereNull('course_teacher.deleted_at')
                                                ->pluck('moodle_name')
                                                ->toArray();
            return $teacher;
        });

        // Đếm số lượng giáo viên duy nhất
        $totalTeachersCount = $class->teachers()->distinct('teachers.id')->count();

        return view('class.edit', compact('breadcrumbs', 'totalTeachersCount', 'teachers', 'coursesEnrol', 'class', 'courses', 'currentEmail', 'selectedCourseIds', 'classId'));
    }

    public function update(Request $request, $id)
    {
        $courseIds = $request->input('course_ids', []);
        $className = $request->input('class_name');
        $year = $request->input('year');
        $status = $request->input('status');
        $data = $request->only(['class_name', 'year', 'status']);

        // Gọi phương thức cập nhật trong CommonService
        $result = $this->common->updateClass($id, $data, $courseIds);

        // Kiểm tra kết quả và trả về thông báo
        if ($result['status']) {
            return redirect()
                ->route('class.edit', $id)
                ->with('success', $result['message']);
        } else {
            return redirect()
                ->route('class.edit', $id)
                ->with('error', $result['message']);
        }
    }

    public function delete(Request $request)
    {
        $classId = $request->input('class_id');

        try {
            // Tìm lớp học cần xóa, sử dụng findOrFail để tự động xử lý lỗi nếu không tìm thấy
            $class = Classes::findOrFail($classId);

            $teachers = $class->teachers()->distinct('teachers.id')->get();

            foreach($teachers as $teacher){
                $courseIds = CourseTeacher::where('teacher_id', $teacher->id)
                    ->where('class_id', $classId)
                    ->pluck('course_id')
                    ->toArray();

                $user = User::find($teacher->user_id);

                $moodle_user_id = $user->moodle_user_id;

                foreach($courseIds as $courseId){
                    $courseData = ApiMoodle::byId($courseId)->moodleType('course')->first();
        
                    $dataJsonEnrolUserMoodle = [
                        'enrolments[0][roleid]' => 3, // role teacher lms
                        'enrolments[0][userid]' => $moodle_user_id,
                        'enrolments[0][courseid]' => $courseData->moodle_id,
                    ];
        
                    $unenrolUserMoodle = Common::enrol_manual_unenrol_users($dataJsonEnrolUserMoodle);
        
                    if (isset($unenrolUserMoodle['errorcode'])) {
                        return response()->json(['status' => false, 'message' => $unenrolUserMoodle['message']]);
                    }
                }

                CourseTeacher::where('teacher_id', $teacher->id)
                    ->where('class_id', $classId)
                    ->whereIn('course_id', $courseIds)
                    ->delete();
            }
            // Xóa lớp học cùng với các liên kết trong ClassCourse
            // $class->classCourses()->delete(); // xóa mềm
            ClassCourse::where('class_id', $classId)->delete();
            // $class->courses()->detach(); // Xóa tất cả các khóa học liên kết
            $class->delete(); // Xóa lớp học

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

    public function searchTeacher(Request $request)
    {
        $searchTerm = $request->query('searchTerm'); // Lấy query từ URL

        // $teachers = Teachers::query();

        // if ($searchTerm) {
        //     // Áp dụng scope tìm theo tên nếu có searchTerm
        //     $teachers = $teachers->byName($searchTerm);
        // }

        $teachers = Teachers::searchTeachers($searchTerm);

        $teachers = $teachers->limit(10)->get();

        return response()->json($teachers); // Trả về JSON
    }

    public function enrolTeacher(Request $request)
    {
        $teacherIds = $request->input('teacherIds');
        $courseId = $request->input('courseId');
        $classId = $request->input('classId');

        $courseData = ApiMoodle::byId($courseId)->moodleType('course')->first();

        if (!$courseData) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy thông tin khóa học']);
        }

        $results = []; // Mảng để lưu kết quả từng giáo viên

        foreach ($teacherIds as $teacherId) {
            $teacher = Teachers::find($teacherId);

            if (!$teacher) {
                $results[] = [
                    'teacher_id' => $teacherId,
                    'status' => false,
                    'message' => "Không tìm thấy giáo viên với ID: $teacherId"
                ];
                continue; // Bỏ qua giáo viên này và tiếp tục vòng lặp
            }

            $teacherUserId = $teacher->user_id;
            $teacherName = $teacher->name;

            $nameParts = explode(' ', $teacherName);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';

            $teacherUsername = $teacher->username;
            $teacherEmail = $teacher->email;

            $dataJsonCreateUserMoodle = [
                'users[0][username]' => $teacherUsername,
                'users[0][auth]' => 'manual',
                'users[0][password]' => 'Hocmai@1234',
                'users[0][firstname]' => $firstName,
                'users[0][lastname]' => $lastName,
                'users[0][email]' => $teacherEmail,
            ];

            $checkUserExistMoodle = Common::core_user_get_users_by_field($teacherEmail);
            $userIdMoodle = $checkUserExistMoodle[0]['id'] ?? 0;

            if (!$userIdMoodle) {
                $createUserMoodle = Common::core_user_create_users($dataJsonCreateUserMoodle);

                if (isset($createUserMoodle['errorcode'])) {
                    $results[] = [
                        'teacher_id' => $teacherId,
                        'status' => false,
                        'message' => $createUserMoodle['message']
                    ];
                    continue;
                }

                $userIdMoodle = $createUserMoodle[0]['id'];
            }

            if ($userIdMoodle) {
                $updateData = [
                    'moodle_user_id' => $userIdMoodle,
                ];

                User::find($teacherUserId)->update($updateData);

                $checkDataExistInCourseTeacher = CourseTeacher::byTeacherId($teacherId)
                    ->byClassId($classId)
                    ->byCourseId($courseId)
                    ->first();

                if (empty($checkDataExistInCourseTeacher)) {
                    CourseTeacher::create([
                        'teacher_id' => $teacherId,
                        'class_id' => $classId,
                        'course_id' => $courseId,
                    ]);
                }

                $dataJsonEnrolUserMoodle = [
                    'enrolments[0][roleid]' => 3,
                    'enrolments[0][userid]' => $userIdMoodle,
                    'enrolments[0][courseid]' => $courseData->moodle_id,
                    'enrolments[0][suspend]' => 0,
                ];

                $enrolUserMoodle = Common::enrol_manual_enrol_users($dataJsonEnrolUserMoodle);

                if (empty($enrolUserMoodle)) {
                    $results[] = [
                        'teacher_id' => $teacherId,
                        'status' => true,
                        'message' => 'Enrolled successfully'
                    ];
                } else {
                    $results[] = [
                        'teacher_id' => $teacherId,
                        'status' => false,
                        'message' => 'Có lỗi xảy ra khi lấy thông tin user LMS'
                    ];
                }
            } else {
                $results[] = [
                    'teacher_id' => $teacherId,
                    'status' => false,
                    'message' => 'Có lỗi xảy ra khi lấy thông tin user LMS'
                ];
            }
        }

        // Trả về tất cả kết quả sau vòng lặp
        return response()->json(['status' => true, 'results' => $results]);
    }

    public function getInforTeacherInClass(Request $request)
    {
        try {
            // Lấy teacherId từ query string
            $teacherId = $request->query('teacherId');
            $classId = $request->query('classId');

            // Tìm lớp học
            $class = Classes::findOrFail($classId);

            // Kiểm tra nếu teacherId không được cung cấp
            if (!$teacherId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher ID is required.',
                ], 400);
            }

            // Lấy thông tin giáo viên
            $teacher = User::find($teacherId);

            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found.',
                ], 404);
            }

            // Lấy các giáo viên liên quan đến lớp học
            $teacherData = $class->teachers()
                ->where('teachers.user_id', $teacherId)
                ->with(['courses' => function ($query) use ($classId) {
                    $query->where('class_id', $classId)
                        ->whereNull('course_teacher.deleted_at')
                        ->select('moodle_name', 'api_moodle.id'); // Lấy tên khóa học
                }])
                ->first();

            if (!$teacherData) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not associated with this class.',
                ], 404);
            }

            // Lấy danh sách khóa học
            $courses = $teacherData->courses->pluck('moodle_name', 'id')->toArray();

            // Lấy dữ liệu từ Moodle
            $getDataMoodleByField = Common::core_user_get_users_by_field($teacher->email);

            if (empty($getDataMoodleByField)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found from Moodle for the given teacher.',
                ], 404);
            }

            // Lấy danh sách course_ids từ bảng ClassCourse thông qua mối quan hệ
            $selectedCourseIds = $class->courses->pluck('id')->toArray(); // Sử dụng quan hệ courses đã định nghĩa trong model Classes
            
            // Lấy danh sách khóa học liên quan đến class_id
            $coursesEnrol = ApiMoodle::inIds($selectedCourseIds)->moodleType('course')->get();

            // Trả về dữ liệu JSON
            return response()->json([
                'status' => true,
                'data' => [
                    'teacher' => $teacher->only(['id', 'name', 'email']),
                    'courses' => $courses,
                    'allCourses' => $coursesEnrol,
                    'selected_course_ids' => $teacherData->courses->pluck('id')->toArray(),
                    'moodleData' => $getDataMoodleByField[0],
                ],
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi chung và trả về phản hồi lỗi
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateInforTeacherInClass(Request $request)
    {
        $course_ids = $request->course_ids_teacher; // Course IDs for the teacher
        try {
            // Prepare the data for Moodle update
            $dataJson = [
                'users[0][id]' => $request->userMoodleId,
                // 'users[0][username]' => $request->username,
                'users[0][firstname]' => $request->firstname,
                'users[0][lastname]' => $request->lastname,
                'users[0][email]' => $request->email,
                'users[0][phone2]' => $request->phone2 ?? '',
                'users[0][city]' => $request->city ?? '',
                'users[0][country]' => $request->country ?? '',
                'users[0][auth]' => 'manual'
            ];

            // Send the update request to Moodle
            $core_user_update_users = Common::core_user_update_users($dataJson);

            // Check if there is an error from Moodle
            if (isset($core_user_update_users['errorcode'])) {
                return response()->json([
                    'status' => false,
                    'message' => $core_user_update_users['message']
                ]);
            }

            // Retrieve the teacher by user ID
            $teacherId = $request->userTeacherId;
            $teacher = Teachers::where('user_id', $teacherId)->first();

            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found.'
                ]);
            }

            // Update the teacher's personal information
            $teacher->update([
                'name' => $request->firstname . ' ' . $request->lastname,
                // 'username' => $request->username,
                'email' => $request->email,
            ]);

            // Update user details in the User table
            $user = User::find($teacherId);
            if ($user) {
                $user->update([
                    'name' => $request->firstname . ' ' . $request->lastname,
                    // 'username' => $request->username,
                    'email' => $request->email,
                ]);
            }

            // Update course assignments (soft delete previous courses and associate new ones)
            if ($course_ids) {
                // Ensure class_id is provided
                $class_id = $request->class_id ?? null;

                // Lấy danh sách course_id cũ từ bảng CourseTeacher
                $existingCourseIds = CourseTeacher::where('teacher_id', $teacher->id)
                    ->where('class_id', $class_id)
                    ->pluck('course_id')
                    ->toArray();

                // So sánh danh sách course_id
                $courseIdsToAdd = array_diff($course_ids, $existingCourseIds); // Các khóa học cần thêm mới
                $courseIdsToRemove = array_diff($existingCourseIds, $course_ids); // Các khóa học cần xóa
    
                // First, delete existing course-teacher assignments with the same teacher_id
                // CourseTeacher::where('teacher_id', $teacher->id)->where('class_id', $class_id)->delete();
    
                // // Then, assign new courses with class_id to the teacher
                // foreach ($course_ids as $course_id) {
                //     // Create or update the pivot table entry with class_id
                //     CourseTeacher::updateOrCreate(
                //         ['course_id' => $course_id, 'teacher_id' => $teacher->id],
                //         ['class_id' => $class_id]  // Ensure class_id is included
                //     );
                // }

                // Xóa các khóa học không còn liên kết
                CourseTeacher::where('teacher_id', $teacher->id)
                    ->where('class_id', $class_id)
                    ->whereIn('course_id', $courseIdsToRemove)
                    ->delete();

                foreach ($courseIdsToRemove as $course_id) {
                    $courseData = ApiMoodle::byId($course_id)->moodleType('course')->first();

                    $dataJsonEnrolUserMoodle = [
                        'enrolments[0][roleid]' => 3, // role teacher lms
                        'enrolments[0][userid]' => $request->userMoodleId,
                        'enrolments[0][courseid]' => $courseData->moodle_id,
                    ];
    
                    $unenrolUserMoodle = Common::enrol_manual_unenrol_users($dataJsonEnrolUserMoodle);
                }

                // Thêm các khóa học mới
                foreach ($courseIdsToAdd as $course_id) {

                    $courseData = ApiMoodle::byId($course_id)->moodleType('course')->first();

                    $dataJsonEnrolUserMoodle = [
                        'enrolments[0][roleid]' => 3, // role teacher lms
                        'enrolments[0][userid]' => $request->userMoodleId,
                        'enrolments[0][courseid]' => $courseData->moodle_id,
                        'enrolments[0][suspend]' => 0,
                    ];
    
                    $enrolUserMoodle = Common::enrol_manual_enrol_users($dataJsonEnrolUserMoodle);

                    if($enrolUserMoodle == null){
                        CourseTeacher::create([
                            'course_id' => $course_id,
                            'teacher_id' => $teacher->id,
                            'class_id' => $class_id,
                        ]);
                    }
                }
            }

            // Respond with success
            return response()->json([
                'status' => true,
                'message' => 'Teacher and course assignments updated successfully.'
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function unenrolTeacher(Request $request)
    {
        $teacherId = $request->teacherId;
        $classId = $request->classId;
        $user = User::find($teacherId);

        $moodle_user_id = $user->moodle_user_id;

        if(!$moodle_user_id){
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy giáo viên này trên LMS'
            ]);
        }

        $teacher = Teachers::where('user_id', $teacherId)->first();

        $courseIds = CourseTeacher::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->pluck('course_id')
            ->toArray();

        foreach($courseIds as $courseId){
            $courseData = ApiMoodle::byId($courseId)->moodleType('course')->first();

            $dataJsonEnrolUserMoodle = [
                'enrolments[0][roleid]' => 3, // role teacher lms
                'enrolments[0][userid]' => $moodle_user_id,
                'enrolments[0][courseid]' => $courseData->moodle_id,
            ];

            $unenrolUserMoodle = Common::enrol_manual_unenrol_users($dataJsonEnrolUserMoodle);

            if (isset($unenrolUserMoodle['errorcode'])) {
                return response()->json(['status' => false, 'message' => $unenrolUserMoodle['message']]);
            }

            CourseTeacher::where('teacher_id', $teacher->id)
                ->where('class_id', $classId)
                ->whereIn('course_id', $courseIds)
                ->delete();
        }

        return response()->json(['status' => true, 'message' => 'Rút giáo viên khỏi lớp thành công']);
    }
}
