<?php

namespace App\ApiService;

use App\Models\Students;
use App\Models\Common;
use App\Models\ApiMoodle;
use App\Models\User;
use App\Models\Teachers;
use App\Models\CourseTeacher;
use App\Models\CourseStudent;
use App\Models\CourseTeacherStudent;

class FeService
{
    protected const MOODLE_ROLE_TEACHER = 3;
    protected const MOODLE_ROLE_STUDENT = 5;

    public function getMoodleRoleTeacher()
    {
        return self::MOODLE_ROLE_TEACHER;
    }

    public function getDataUserInfo($jsonData)
    {
        $url = env('ICANID_DOMAIN_GET_USER_INFO');
        $bearerToken = $jsonData['access_token'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $bearerToken
        ]);
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            return false;
        }
        $data = json_decode($response, true);

        return  $this->createStudent($data);
    }

    public function createStudent($data)
    {
        $email = $data['email'];
        $sso_id = $data['sub'];
        $sso_name = 'icanid';
        $phone = $data['phone'];
        $displayName = $data['displayName'];
        $username = explode('@', $email)[0];
        //check student
        $student = Students::where('email', $email)->first();
        if($student) {
            return $student->toArray();
        }

        $student = Students::create(
            [
                'email' => $email,
                'name' => $displayName,
                'username' => $username,
                'password' => bcrypt('Hocmai@1234'),
                'sso_id' => $sso_id,
                'sso_name' => 'icanid'
            ]
        );
        return $student->toArray();
    }

    public function createUserMoodle($username, $email, $firstName = null, $lastName = null)
    {
        $dataJsonCreateUserMoodle = [
            'users[0][username]' => $username,
            'users[0][auth]' => 'manual',
            'users[0][password]' => 'Hocmai@1234',
            'users[0][firstname]' => $firstName,
            'users[0][lastname]' => $lastName,
            'users[0][email]' => $email,
        ];
        $checkUserExistMoodle = Common::core_user_get_users_by_field($email);
        $userIdMoodle = $checkUserExistMoodle[0]['id'] ?? 0;
        // dd($dataJsonCreateUserMoodle);
        if ($userIdMoodle == 0) {
            $createUserMoodle = Common::core_user_create_users($dataJsonCreateUserMoodle);
            if (isset($createUserMoodle['errorcode'])) {
                $userIdMoodle = false;
            } else {
                $userIdMoodle = $createUserMoodle[0]['id'];
            }
            return $userIdMoodle;
        }
        return $userIdMoodle;
    }

    public function enrollStudentToCourse($userIdMoodle, $moodleRoleId, $courseId)
    {
        $courseData = ApiMoodle::byId($courseId)->moodleType('course')->first();
        if(!$courseData) {
            return false;
        }
        $dataJsonEnrolUserMoodle = [
            'enrolments[0][roleid]' => $moodleRoleId,
            'enrolments[0][userid]' => $userIdMoodle,
            'enrolments[0][courseid]' => $courseData->moodle_id,
            'enrolments[0][suspend]' => 0,
        ];
        $enrolUserMoodle = Common::enrol_manual_enrol_users($dataJsonEnrolUserMoodle);
        if (empty($enrolUserMoodle)) {
            return true;
        }

        return false;
    }

    public function createOrUpdateTeacherStudent($email, $moodleRoleId)
    {
        if($moodleRoleId == self::MOODLE_ROLE_TEACHER) {
            $user = Teachers::where('email', $email)->first();
            if(!$user) {
                $user = Teachers::create(['email' => $email]);
            }
        }
        if($moodleRoleId == self::MOODLE_ROLE_STUDENT) {
            $user = Students::where('email', $email)->first();
            if(!$user) {
                $user = Students::create(['email' => $email]);
            }
        }
        
        return $user;
    }

    public function createOrUpdateUser($userIdMoodle, $teacherStudent)
    {
        $user = User::where('moodle_user_id', $userIdMoodle)->first();
        
        if(!$user) {
            // $user = User::create([
            //     'moodle_user_id' => $userIdMoodle,
            //     'email' => $teacherStudent->email,
            //     'name' => '',
            //     'password' => bcrypt('Hocmai@1234'),
            // ]);

            $user = User::updateOrCreate(
                ['email' => $teacherStudent->email], // Điều kiện để tìm bản ghi
                [
                    'moodle_user_id' => $userIdMoodle,
                    'email' => $teacherStudent->email,
                    'name' => $teacherStudent->name ?? '',
                    'password' => bcrypt('Hocmai@1234'),
                ] // Dữ liệu để cập nhật hoặc tạo mới
            );
        }

        return $user->update(['user_id' => $user->id, 'moodle_id' => $userIdMoodle]);
    }

    public function addStudentCourse($teacherStudent, $jsonData, $moodleRoleId, $courseIds)
    {
        $arrayCourseIds = explode(',', $courseIds);
        if($moodleRoleId == self::MOODLE_ROLE_TEACHER) {
            $classId = $jsonData['class_id'];
            $teacherId = $teacherStudent['id'];
            foreach($arrayCourseIds as $courseId)
            {
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

                $studentId = CourseStudent::where('course_id', $courseId)->value('student_id');

                if($studentId){
                    CourseTeacherStudent::create([
                        'course_student_id' => $studentId,
                        'course_teacher_id' => $teacherId,
                    ]);
                }
            }
            //update course_teacher table
            //find course_student with course_id = courseId-->get student_id
            //create new record in course_teacher_student
        }
        if($moodleRoleId == self::MOODLE_ROLE_STUDENT) {
            $studentId = $teacherStudent['id'];
            foreach($arrayCourseIds as $courseId)
            {
                $checkDataExistInCourseStudent = CourseStudent::where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->first();

                if (empty($checkDataExistInCourseStudent)) {
                    CourseStudent::create([
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                    ]);
                }

                $teacherId = CourseTeacher::where('course_id', $courseId)->value('teacher_id');

                if($teacherId){
                    CourseTeacherStudent::create([
                        'course_student_id' => $studentId,
                        'course_teacher_id' => $teacherId,
                    ]);
                }
            }
            //update course_student table
            //find course_teacher with course_id = courseId-->get teacher_id
            //create new record in course_teacher_student
        }

        return true;
    }

    public function createWithCourse($jsonData)
    {
        $success = $fail = [];
        $email = $jsonData['email'];
        $courseIds = $jsonData['course_ids'];
        if(!isset($jsonData['moodle_role_id'])) {
            $moodleRoleId = self::MOODLE_ROLE_STUDENT;
        } else {
            $moodleRoleId = $jsonData['moodle_role_id'];
        }
        if(!isset($jsonData['first_name'])) {
            $firstName = 'firstname';
        } else {
            $firstName = $jsonData['first_name'] ?? 'firstname';
        }
        if(!isset($jsonData['last_name'])) {
            $lastName = 'lastname';
        } else {
            $lastName = $jsonData['last_name'] ?? 'lastname';
        }
        //create new student
        $teacherStudent = $this->createOrUpdateTeacherStudent($email, $moodleRoleId);
        //create student in moodle
        if(!isset($jsonData['username'])) {
            $username = explode('@', $email)[0];
        } else {
            $username = $jsonData['username'];
        }
        $userIdMoodle = $this->createUserMoodle($username, $email, $firstName, $lastName);
        if(!$userIdMoodle) {
            if($moodleRoleId == self::MOODLE_ROLE_TEACHER) {
                $res = [
                    'teacher_id' => $teacherStudent['id'],
                    'teacher_mail' => $teacherStudent['email'],
                    'status' => false,
                    'message' => 'create user moodle fail'
                ];
                return $res;
            }
            if($moodleRoleId == self::MOODLE_ROLE_STUDENT) {
                return [
                    'status_code' => 500,
                    'message' => 'create user moodle fail'
                ];
            }
        }
        //create or update users table
        $updateTeacherStudent = $this->createOrUpdateUser($userIdMoodle, $teacherStudent);
        if(!$updateTeacherStudent) {
            if($moodleRoleId == self::MOODLE_ROLE_TEACHER) {
                $res = [
                    'teacher_id' => $teacherStudent['id'],
                    'teacher_mail' => $teacherStudent['email'],
                    'status' => false,
                    'message' => 'update user fail'
                ];
                return $res;
            }
            if($moodleRoleId == self::MOODLE_ROLE_STUDENT) {
                return [
                    'status_code' => 500,
                    'message' => 'update user fail'
                ];
            }
        }
        //get list course by courseIds
        $arrayCourseIds = explode(',', $courseIds);
        //enroll student to course
        foreach($arrayCourseIds as $courseId)
        {
            if($this->enrollStudentToCourse($userIdMoodle, $moodleRoleId, $courseId)) {
                $success[] = $courseId;
            } else {
                $fail[] = $courseId;
            }
        }
        
        //add student_id and course_id to course_students
        $this->addStudentCourse($teacherStudent, $jsonData, $moodleRoleId, $courseIds);

        if($moodleRoleId == self::MOODLE_ROLE_TEACHER) {
            $res = [
                'teacher_id' => $teacherStudent['id'],
                'teacher_mail' => $teacherStudent['email'],
                'status' => true,
                'message' => 'Enrolled successfully'
            ];
            return $res;
        }
        if($moodleRoleId == self::MOODLE_ROLE_STUDENT) {
            $res = [
                'success' => $success,
                'fail' => $fail,
            ];
            
            return $res;
        }
    }
}
