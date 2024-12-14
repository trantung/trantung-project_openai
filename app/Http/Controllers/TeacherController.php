<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use App\Models\Common;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\RolePermission;
use App\Models\CourseTeacher;
use App\Models\Teachers;
use App\Models\ClassCourse;
use App\Models\Classes;
use App\Models\ApiMoodle;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm từ request
        $classId = $request->query('class');
        $teacherId = $request->query('user');

        // Lấy danh sách giáo viên kèm theo các lớp họ tham gia
        $teachersQuery = Teachers::with('classes')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'teacher'); // Tên vai trò là "teacher"
            });

        // Nếu có classId trong request, lọc theo lớp
        if ($classId) {
            $teachersQuery = $teachersQuery->whereHas('classes', function ($query) use ($classId) {
                $query->where('classes.id', $classId); // Lọc theo classId
            });
        }

        // Nếu có teacherId trong request, lọc theo teacherId
        if ($teacherId) {
            $teachersQuery = $teachersQuery->where('id', $teacherId); // Lọc theo userId (teacher)
        }

        // Đếm tổng số giáo viên sau khi đã áp dụng các bộ lọc
        $totalTeachers = $teachersQuery->count();

        // Lấy danh sách giáo viên phân trang
        $teachers = $teachersQuery->paginate(10);
        // Các breadcrumbs
        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('teacher.index'),
                'text' => 'Danh sách giáo viên',
            ]
        ];

        // Lấy tất cả các lớp học
        $allClass = Classes::all();

        $allTeacher = Teachers::with('classes')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'teacher'); // Tên vai trò là "teacher"
        })->get();

        // Trả về view với dữ liệu đã lọc
        return view('teacher.index', compact('breadcrumbs', 'allTeacher', 'teachers', 'allClass', 'totalTeachers'));
    }

    public function getInforTeacherUser(Request $request)
    {
        try {
            // Lấy teacherId từ query string
            $teacherId = $request->query('teacherId');
            // Tìm lớp học
            $allClass = Classes::all();

            // Kiểm tra nếu teacherId không được cung cấp
            if (!$teacherId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher ID is required.',
                ], 400);
            }

            // Lấy thông tin giáo viên
            $user = User::find($teacherId);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            $teacher = Teachers::where('user_id', $teacherId)->first();

            if (!$teacher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found.',
                ], 404);
            }

            $classes = $teacher->classes;

            $classIds = $classes->pluck('id')->toArray();

            $getDataMoodleByField = Common::core_user_get_users_by_field($teacher->email);

            if (empty($getDataMoodleByField)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found from Moodle for the given teacher.',
                ], 404);
            }
            
            // Trả về dữ liệu JSON
            return response()->json([
                'status' => true,
                'data' => [
                    'teacher' => $user->only(['id', 'name', 'email']),
                    'allClass' => $allClass,
                    'selected_class_ids' => $classIds,
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

    public function updateInforTeacherUser(Request $request)
    {
        $class_ids = $request->class_ids_teacher ?? []; // Class IDs for the teacher
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
            $moodle_user_id = $user->moodle_user_id;

            $classes = $teacher->classes;

            $existingClassIds = $classes->pluck('id')->toArray();

            $classIdsToAdd = array_diff($class_ids, $existingClassIds);
            $classIdsToRemove = array_diff($existingClassIds, $class_ids);

            if($classIdsToRemove){
                foreach ($classIdsToRemove as $class_id_to_remove) {
                    $classId = $class_id_to_remove;

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
                    }

                    CourseTeacher::where('teacher_id', $teacher->id)
                        ->where('class_id', $classId)
                        ->whereIn('course_id', $courseIds)
                        ->delete();
                }
            }
            if($classIdsToAdd){
                foreach($classIdsToAdd as $class_id) {
                    $classId = $class_id;

                    $class = Classes::find($classId);

                    $selectedCourseIds = $class->courses->pluck('id')->toArray();

                    foreach($selectedCourseIds as $courseId){
                        $courseData = ApiMoodle::byId($courseId)->moodleType('course')->first();

                        $checkDataExistInCourseTeacher = CourseTeacher::byTeacherId($teacher->id)
                            ->byClassId($classId)
                            ->byCourseId($courseId)
                            ->first();

                        if (empty($checkDataExistInCourseTeacher)) {
                            CourseTeacher::create([
                                'teacher_id' => $teacher->id,
                                'class_id' => $classId,
                                'course_id' => $courseId,
                            ]);
                        }

                        $dataJsonEnrolUserMoodle = [
                            'enrolments[0][roleid]' => 3,
                            'enrolments[0][userid]' => $moodle_user_id,
                            'enrolments[0][courseid]' => $courseData->moodle_id,
                            'enrolments[0][suspend]' => 0,
                        ];
        
                        $enrolUserMoodle = Common::enrol_manual_enrol_users($dataJsonEnrolUserMoodle);
        
                        if (isset($enrolUserMoodle['errorcode'])) {
                            return response()->json(['status' => false, 'message' => $enrolUserMoodle['message']]);
                        }
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Teacher updated successfully.'
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        // Lấy dữ liệu từ request
        $teacher_id = $request->teacher_id;
        $class_id = $request->class_id;
    
        // Chuyển chuỗi class_id thành mảng
        $class_ids = explode(', ', $class_id);
    
        try {
            // Kiểm tra kết quả
            $user = User::find($teacher_id);

            $teacher = Teachers::where('user_id', $teacher_id)->first();

            $courseIds = CourseTeacher::where('teacher_id', $teacher->id)
                ->whereIn('class_id', $class_ids)
                ->pluck('course_id')
                ->toArray();

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
                ->whereIn('class_id', $class_ids)
                ->whereIn('course_id', $courseIds)
                ->delete();

            $teacher->delete();

            UserRole::where('user_id', $user->id)->delete();

            return redirect()
                ->route('teacher.index')
                ->with('success', 'Giáo viên đã được xóa thành công.');
        }  catch (\Exception $e) {
            // Xử lý lỗi nếu xảy ra
            return redirect()
                ->route('teacher.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa giáo viên. Vui lòng thử lại.');
        }
    }
}
