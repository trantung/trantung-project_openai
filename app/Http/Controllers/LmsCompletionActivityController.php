<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\LmsCompletionActivity;
use App\Models\LmsCourse;
use App\Models\LmsSection;
use App\Models\LmsVideo;
use App\Models\LmsExercise;
use App\Models\LmsQuiz;
use App\Models\LmsUserVideo;
use App\Models\LmsUserQuiz;
use App\Models\LmsUserExercise;
use App\Models\LmsUserSection;
use DB;

class LmsCompletionActivityController extends Controller
{
    public function checkParam($jsonData)
    {
        if(empty($jsonData['user_id']) || empty($jsonData['username'])) {
            return false;
        }
        return true;
    }

    public function responseSuccess($statusCode, $message)
    {
        return response()->json(array(
            'code' => $statusCode,
            'data' => $message,
            'message' => 'Success'
        ), 200);
    }
    public function checkParamValid($jsonData)
    {
        $check = $this->checkParam($jsonData);
        if(!$check) {
            return false;
        }
        if(!isset($jsonData['course_id']) || empty($jsonData['course_id'])) {
           return false;
        }
        return true;
    }

    public function createActivityCompletion(Request $request)
    {
        $jsonData = $request->json()->all();
        // Find the existing record or create a new one
        $activity = LmsCompletionActivity::updateOrCreate(
            [
                'username' => $jsonData['username'],
                'course_id' => $jsonData['course_id'],
                'section_id' => $jsonData['section_id'],
                'video_id' => $jsonData['video_id'],
            ],
            [
                'status' => $jsonData['status'],
            ]
        );

        // Return response
        return response()->json(['success' => true, 'activity' => $activity], 200);
    }
    
    public function detail(Request $request)
    {
        $jsonData = $request->json()->all();
        if(isset($jsonData['username'])) {
            $username = $jsonData['username'];
        }
        if(isset($jsonData['course_id'])) {
            $course_id = $jsonData['course_id'];
        }
        if(isset($jsonData['user_id'])) {
            $user_id = $jsonData['user_id'];
        }
        $section_id = $request->input('section_id'); // Optional parameter
        if ($section_id) {
            // Retrieve the activities for the given section
            $activities = LmsCompletionActivity::where('username', $username)
                ->where('course_id', $course_id)
                ->where('section_id', $section_id)
                ->get();

            $totalActivities = $activities->count();
            $completedActivities = $activities->where('status', 1)->count();

            $completionPercentage = $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100) : 0;

            return response()->json([
                'totalActivities' => $totalActivities,
                'completedActivities' => $completedActivities,
                'completionPercentage' => $completionPercentage,
            ], 200);
        } else {
            // Retrieve all activities for the given course
            $sections = LmsCompletionActivity::where('username', $username)
                ->where('course_id', $course_id)
                ->select('section_id')
                ->distinct()
                ->get();

            $totalActivities = 0;
            $totalCompletedActivities = 0;

            foreach ($sections as $section) {
                $sectionActivities = LmsCompletionActivity::where('username', $username)
                    ->where('course_id', $course_id)
                    ->where('section_id', $section->section_id)
                    ->get();

                $sectionTotal = $sectionActivities->count();
                $sectionCompleted = $sectionActivities->where('status', 1)->count();

                $totalActivities += $sectionTotal;
                $totalCompletedActivities += $sectionCompleted;
            }

            $completionPercentage = $totalActivities > 0 ? round(($totalCompletedActivities / $totalActivities) * 100) : 0;

            return response()->json([
                'totalActivities' => $totalActivities,
                'completedActivities' => $totalCompletedActivities,
                'completionPercentage' => $completionPercentage,
            ], 200);
        }
    }

    public function updateCourse(Request $request)
    {
        // $default = LmsCompletionActivity::getDefaultCourse();
        // return $this->responseSuccess(403, 'user_id or username or course_id is empty');
        // return response()->json(['success' => true, 'data' => $default], 200);
        // LmsCourse::truncate();
        // LmsSection::truncate();
        // LmsVideo::truncate();
        // LmsExercise::truncate();
        // LmsQuiz::truncate();
        // LmsUserVideo::truncate();
        // LmsUserQuiz::truncate();
        // LmsUserExercise::truncate();
        // LmsUserSection::truncate();
        // dd(11);

        $jsonData = $request->json()->all();
        $courseId = $jsonData['course_id'];
        //update lms_section table(update bai giang)
        $listSection = $jsonData['list_section'];
        foreach($listSection as $section)
        {
            $dataSection = [
                'course_id' => $courseId,
                'section_id' => $section['section_id']
            ];
            // dd(DB::getSchemaBuilder()->getColumnListing('lms_video'));
            $checkSection = LmsSection::where('course_id', $courseId)->where('section_id', $section['section_id'])->first();
            if(!$checkSection) {
                LmsSection::create($dataSection);
            }
            //update lms_video table
            $listVideo = $section['list_video'];
            foreach($listVideo as $video)
            {
                $dataVideo = [
                    'course_id' => $courseId,
                    'video_id' => $video['video_id'],
                    'section_id' => $section['section_id']
                ];
                $checkVideo = LmsVideo::where('course_id', $courseId)
                    ->where('section_id', $section['section_id'])
                    ->where('video_id', $video['video_id'])
                    ->first();
                if(!$checkVideo) {
                    LmsVideo::create($dataVideo);
                }
                //update lms_exercise table
                $listExercise = $video['list_exercise'];
                foreach($listExercise as $exercise) {
                    $dataExercise = [
                        'course_id' => $courseId,
                        'video_id' => $video['video_id'],
                        'exercise_id' => $exercise['exercise_id']
                    ];
                    $checkExercise = LmsExercise::where('course_id', $courseId)
                        ->where('exercise_id', $exercise['exercise_id'])
                        ->where('video_id', $video['video_id'])
                        ->first();
                    if(!$checkExercise) {
                        LmsExercise::create($dataExercise);
                    }
                }
            }
            //update lms_quiz table
            $quizList = $section['quiz_list'];
            foreach($quizList as $quiz)
            {
                $dataQuiz = [
                    'course_id' => $courseId,
                    'quiz_id' => $quiz['quiz_id'],
                    'section_id' => $section['section_id']
                ];
                $checkQuiz = LmsQuiz::where('course_id', $courseId)
                    ->where('quiz_id', $quiz['quiz_id'])
                    ->where('section_id', $section['section_id'])
                    ->first();
                if(!$checkQuiz) {
                    LmsQuiz::create($dataQuiz);
                }
            }
        }
        return $this->responseSuccess(200, 'update success');
    }

    public function updateVideo(Request $request)
    {
        $jsonData = $request->json()->all();
        //check validate param
        $check = $this->checkParamValid($jsonData);
        if(!$check) {
            return $this->responseSuccess(403, 'user_id or username or course_id is empty');
        }
        //Check course_id is exist
        $checkCourse = LmsCourse::where('user_id', $jsonData['user_id'])
            ->where('course_id', $jsonData['course_id'])
            ->first();
        if(!$checkCourse) {
            $user_course_id = LmsCourse::create([
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'course_id' => $jsonData['course_id'],
                'process' => 0,
            ])->id;
        }
        $lmsUserVideo = LmsUserVideo::where('user_id', $jsonData['user_id'])
            ->where('course_id', $jsonData['course_id'])
            ->where('video_id', $jsonData['video_id'])
            ->first();
        if(!$lmsUserVideo) {
            LmsUserVideo::create([
                'user_id' => $jsonData['user_id'],
                'video_id' => $jsonData['video_id'],
                'course_id' => $jsonData['course_id'],
                'status' => $jsonData['status'],
            ]);
        } else {
            LmsUserVideo::where('user_id', $jsonData['user_id'])
                ->where('course_id', $jsonData['course_id'])
                ->where('video_id', $jsonData['video_id'])
                ->update(['status' => $jsonData['status']]);
        }
        //update lms_user_section table: get section_id by video_id from lms_video table
        $sectionIds = LmsVideo::where('video_id', $jsonData['video_id'])
            ->where('course_id', $jsonData['course_id'])
            ->pluck('section_id')->toArray();
        foreach($sectionIds as $sectionId)
        {
            $checkUserSection = LmsUserSection::where('user_id', $jsonData['user_id'])
                ->where('course_id', $jsonData['course_id'])
                ->where('section_id', $sectionId)
                ->first();
            if(!$checkUserSection) {
                LmsUserSection::create([
                    'user_id' => $jsonData['user_id'],
                    'section_id' => $sectionId,
                    'course_id' => $jsonData['course_id'],
                ]);
            }
        }
        return response()->json(['success' => true, 'video_id' => $jsonData['video_id']], 200);
    }

    public function updateQuiz(Request $request)
    {
        $jsonData = $request->json()->all();
        //check validate param
        $check = $this->checkParamValid($jsonData);
        if(!$check) {
            return $this->responseSuccess(403, 'user_id or username or course_id is empty');
        }
        //Check course_id is exist
        $checkCourse = LmsCourse::where('user_id', $jsonData['user_id'])
            ->where('course_id', $jsonData['course_id'])
            ->first();
        if(!$checkCourse) {
            $user_course_id = LmsCourse::create([
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'course_id' => $jsonData['course_id'],
                'process' => 0,
            ])->id;
        }
        $lmsUserQuiz = LmsUserQuiz::where('user_id', $jsonData['user_id'])
            ->where('course_id', $jsonData['course_id'])
            ->where('quiz_id', $jsonData['quiz_id'])
            ->first();
        if(!$lmsUserQuiz) {
            LmsUserQuiz::create([
                'user_id' => $jsonData['user_id'],
                'quiz_id' => $jsonData['quiz_id'],
                'course_id' => $jsonData['course_id'],
                'status' => $jsonData['status'],
            ]);
        } else {
            LmsUserQuiz::where('user_id', $jsonData['user_id'])
                ->where('course_id', $jsonData['course_id'])
                ->where('quiz_id', $jsonData['quiz_id'])
                ->update(['status' => $jsonData['status']]);
        }
        //update lms_user_section table: get section_id by quiz_id from lms_quiz table
        $sectionIds = LmsQuiz::where('quiz_id', $jsonData['quiz_id'])
            ->where('course_id', $jsonData['course_id'])
            ->pluck('section_id')->toArray();
        foreach($sectionIds as $sectionId)
        {
            $checkUserSection = LmsUserSection::where('user_id', $jsonData['user_id'])
                ->where('course_id', $jsonData['course_id'])
                ->where('section_id', $sectionId)
                ->first();
            if(!$checkUserSection) {
                LmsUserSection::create([
                    'user_id' => $jsonData['user_id'],
                    'section_id' => $sectionId,
                    'course_id' => $jsonData['course_id'],
                ]);
            }
        }
        return response()->json(['success' => true, 'quiz_id' => $jsonData['quiz_id']], 200);
    }

    public function updateExercise(Request $request)
    {
        $jsonData = $request->json()->all();
        //check validate param
        $check = $this->checkParamValid($jsonData);
        if(!$check) {
            return $this->responseSuccess(403, 'user_id or username or course_id is empty');
        }
        //Check course_id is exist
        $checkCourse = LmsCourse::where('user_id', $jsonData['user_id'])
            ->where('course_id', $jsonData['course_id'])
            ->first();
        if(!$checkCourse) {
            $user_course_id = LmsCourse::create([
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'course_id' => $jsonData['course_id'],
                'process' => 0,
            ])->id;
        }
        $lmsUserExercise = LmsUserExercise::where('user_id', $jsonData['user_id'])
            ->where('course_id', $jsonData['course_id'])
            ->where('video_id', $jsonData['video_id'])
            ->where('exercise_id', $jsonData['exercise_id'])
            ->first();
        if(!$lmsUserExercise) {
            LmsUserExercise::create([
                'user_id' => $jsonData['user_id'],
                'video_id' => $jsonData['video_id'],
                'course_id' => $jsonData['course_id'],
                'exercise_id' => $jsonData['exercise_id'],
                'status' => $jsonData['status'],
            ]);
        } else {
            LmsUserExercise::where('user_id', $jsonData['user_id'])
                ->where('course_id', $jsonData['course_id'])
                ->where('video_id', $jsonData['video_id'])
                ->where('exercise_id', $jsonData['exercise_id'])
                ->update(['status' => $jsonData['status']]);
        }

        //update lms_user_section table: get section_id by video_id from lms_video table
        $sectionIds = LmsVideo::where('video_id', $jsonData['video_id'])
            ->where('course_id', $jsonData['course_id'])
            ->pluck('section_id')->toArray();
        foreach($sectionIds as $sectionId)
        {
            $checkUserSection = LmsUserSection::where('user_id', $jsonData['user_id'])
                ->where('course_id', $jsonData['course_id'])
                ->where('section_id', $sectionId)
                ->first();
            if(!$checkUserSection) {
                LmsUserSection::create([
                    'user_id' => $jsonData['user_id'],
                    'section_id' => $sectionId,
                    'course_id' => $jsonData['course_id'],
                ]);
            }
        }

        return response()->json(['success' => true, 'exercise_id' => $jsonData['exercise_id']], 200);
    }

    public function processSection($courseId, $sectionId, $userId)
    {
        $test = LmsUserVideo::all();
        
        $listVideoIds = LmsVideo::where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->pluck('video_id')->toArray();
        //get list video with percent complete
        $resVideoListDetail = $data = $resQuizListDetail = [];
        $numberVideoDone = 0;
        foreach($listVideoIds as $videoId)
        {
            $checkVideoDone = $this->processVideo($videoId, $userId, $courseId);
            if($checkVideoDone) {
                $numberVideoDone ++;
            }
            $resVideoListDetail[] = [
                'video_id' => $videoId,
                'status' => $checkVideoDone,
                'list_exercise' => $this->listUserExerciseVideo($courseId, $videoId, $userId)
            ];
        }
        $data['list_videos'] = $resVideoListDetail;
        //get list quiz_id of section
        $numberQuizDone = 0;
        $listQuizIds = LmsQuiz::where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->pluck('quiz_id')->toArray();

        foreach($listQuizIds as $quizDetailId)
        {
            $checkQuizDone = $this->processQuiz($quizDetailId, $courseId, $userId);
            if($checkQuizDone) {
                $numberQuizDone ++;
            }
            $resQuizListDetail[] = [
                'quiz_id' => $quizDetailId,
                'status' => $checkQuizDone
            ];
        }
        $data['list_quizs'] = $resQuizListDetail;

        $userQuiz = LmsUserQuiz::where('course_id', $courseId)
            ->where('status', 1)
            ->where('user_id', $userId)
            ->whereIn('quiz_id', $listQuizIds)
            ->pluck('quiz_id')->toArray();

        //get list quiz with percent complete
        //% hoan thanh quiz = count(quiz_id with status = 1)/count(quiz_id)
        $numberDone = $numberVideoDone + count($userQuiz);
        $numberTotalSection = count($listVideoIds) + count($listQuizIds);
        $data['section_id'] = $sectionId;
        $data['percent'] = round(100 * $numberDone/$numberTotalSection);
        return $data;
    }

    public function processQuiz($quizId, $courseId, $userId)
    {
        $userQuiz = LmsUserQuiz::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->where('status', 1)
            ->first();
        if($userQuiz) {
            return true;
        }
        return false;
    }

    public function processCourse(Request $request)
    {
        $jsonData = $request->json()->all();
        $courseId = $jsonData['course_id'];
        $userId = $jsonData['user_id'];
        //get process of section
        //get list of section
        $sectionIds = LmsSection::where('course_id', $jsonData['course_id'])->pluck('section_id')->toArray();
        // $userSectionIds = LmsUserSection::where('course_id', $courseId)->where('user_id', $userId)->pluck('section_id')->toArray();
        $data = [];
        foreach($sectionIds as $sectionId)
        {
            //get list video_id of section
            $listVideoIds = LmsVideo::where('course_id', $jsonData['course_id'])
                ->where('section_id', $sectionId)
                ->pluck('video_id')->toArray();
            $numberVideoDone = 0;
            foreach($listVideoIds as $videoId)
            {
                $checkVideoDone = $this->processVideo($videoId, $userId, $courseId);
                if($checkVideoDone) {
                    $numberVideoDone ++;
                }
            }
            //get list quiz_id of section
            $listQuizIds = LmsQuiz::where('course_id', $jsonData['course_id'])
                ->where('section_id', $sectionId)
                ->pluck('quiz_id')->toArray();
            $userQuiz = LmsUserQuiz::where('course_id', $jsonData['course_id'])
                ->where('status', 1)
                ->where('user_id', $userId)
                ->whereIn('quiz_id', $listQuizIds)
                ->pluck('quiz_id')->toArray();
            //% hoan thanh quiz = count(quiz_id with status = 1)/count(quiz_id)
            $numberDone = $numberVideoDone + count($userQuiz);
            $numberTotalSection = count($listVideoIds) + count($listQuizIds);
            $data[] = [
                'section_id' => $sectionId,
                'percent' => round(100 * $numberDone/$numberTotalSection)
            ];
        }
        $res = [];
        if(isset($jsonData['section_id'])) {
            $section_id = $jsonData['section_id'];
            foreach($data as $value)
            {
                if($value['section_id'] == $section_id) {
                    $res = $value;
                }
            }
        } else {
            $res = [
                'course_id' => $courseId,
                'percent' => $this->getPercentCourse($data)
            ];
        }
        return response()->json(['success' => true, 'data' => $res], 200);
    }

    public function getPercentCourse($data)
    {
        if (empty($data)) {
            return 0; // Tránh chia cho 0, có thể đặt giá trị mặc định là 0
        }

        $percent = 0;
        foreach ($data as $value) {
            $percent += $value['percent'];
        }
        return $percent / count($data);
    }

    public function processExercise($courseId, $videoId, $exerciseId, $userId)
    {
        $userExercise = LmsUserExercise::where('user_id', $userId)
            ->where('video_id', $videoId)
            ->where('course_id', $courseId)
            ->where('exercise_id', $exerciseId)
            ->where('status', 1)
            ->first();
        if($userExercise) {
            return true;
        }
        return false;
    }

    public function listUserExerciseVideo($courseId, $videoId, $userId)
    {
        $userExercise = LmsUserExercise::where('user_id', $userId)
            ->where('video_id', $videoId)
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->pluck('exercise_id')->toArray();
        $resExercise = [];
        $numberExerciseDone = 0;
        foreach($userExercise as $exerciseId)
        {
            $checkExerciseDone = $this->processExercise($courseId, $videoId, $exerciseId, $userId);
            if($checkExerciseDone) {
                $numberExerciseDone ++;
            }
            $resExercise[] = [
                'exercise_id' => $exerciseId,
                'status' => $checkExerciseDone
            ];
        }
        return $resExercise;
    }

    public function processVideo($videoId, $userId, $courseId)
    {
        $checkUserVideo = LmsUserVideo::where('user_id', $userId)
            ->where('video_id', $videoId)
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->first();
        if($checkUserVideo) {
            return false;
        }
        //check exercise of video
        $exerciseIds = LmsExercise::where('video_id', $videoId)
            ->where('course_id', $courseId)
            ->pluck('exercise_id')->toArray();
        $userExercise = LmsUserExercise::where('user_id', $userId)
            ->where('video_id', $videoId)
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->pluck('exercise_id')->toArray();
        if(count($exerciseIds) > count($userExercise)) {
            return false;
        }
        return true;
    }

    public function getStatusActivity(Request $request)
    {
        $jsonData = $request->json()->all();
        $check = $this->checkParamValid($jsonData);
        if(!$check) {
            return $this->responseSuccess(403, 'user_id or username or course_id is empty');
        }
        $userId = $jsonData['user_id'];
        $courseId = $jsonData['course_id'];
        if(!empty($jsonData['section_id'])) {
            $sectionId = $jsonData['section_id'];
            //check video
            if(!empty($jsonData['video_id'])) {
                $videoId = $jsonData['video_id'];
                $videoStatus = $this->processVideo($videoId, $userId, $courseId);
                if(!empty($jsonData['exercise_id'])) {
                    $exerciseId = $jsonData['exercise_id'];
                    $exerciseStatus = $this->processExercise($courseId, $videoId, $exerciseId, $userId);
                    return response()->json(['success' => true, 'data' => $exerciseStatus], 200);
                }
                $dataVideoResponse = [
                    'status' => $videoStatus,
                    'list_exercise' => $this->listUserExerciseVideo($courseId, $videoId, $userId)
                ];
                return response()->json(['success' => true, 'data' => $dataVideoResponse], 200);
            }
            //check quiz
            if(!empty($jsonData['quiz_id'])) {
                $quizId = $jsonData['quiz_id'];
                $quizStatus = $this->processQuiz($quizId, $courseId, $userId);
                return response()->json(['success' => true, 'data' => $quizStatus], 200);
            }
            $sectionStatus = $this->processSection($courseId, $sectionId, $userId);
            return response()->json(['success' => true, 'data' => $sectionStatus], 200);
        }
        return $this->processCourse($request);
    }
}
