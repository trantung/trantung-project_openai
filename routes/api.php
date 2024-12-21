<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("openai/test", [App\Http\Controllers\ApiTestController::class,'test']);
//all
Route::post("/ielts/write_task_2", [App\Http\Controllers\ApiController::class,'ieltsWriteTask2']);

//cuc 1: introduction
Route::post("openai/test/introduction", [App\Http\Controllers\ApiTestController::class,'introductionTest']);
Route::post("openai/test/task2IdentifyErrors", [App\Http\Controllers\ApiTestController::class,'task2IdentifyErrors']);
//cuc 2: topic sentence
Route::post("openai/test/topic_sentence", [App\Http\Controllers\ApiTestController::class,'topicSentence']);
Route::post("openai/test/conclusion", [App\Http\Controllers\ApiTestController::class,'conclusion']);
//cuc 3: band
Route::post("openai/test/band/task_response", [App\Http\Controllers\ApiTestController::class,'bandTaskResponse']);
//cuc 4 Coherence & Cohesion
Route::post("openai/test/band/coherence_cohesion", [App\Http\Controllers\ApiTestController::class,'coherenceCohesion']);
//cuc 5: Lexical resource
Route::post("openai/test/band/lexical_resource", [App\Http\Controllers\ApiTestController::class,'lexicalResource']);
//cuc 6: Grammatical range & accuracy
Route::post("openai/test/band/gramma", [App\Http\Controllers\ApiTestController::class,'gramma']);
//test phan tich anh
Route::post("openai/test/image", [App\Http\Controllers\ApiTestController::class,'image']);
Route::post("openai/test/task1/lexical_gramma", [App\Http\Controllers\ApiTestController::class,'task1LexicalGramma']);
Route::post("openai/test/task2/lexical_gramma", [App\Http\Controllers\ApiTestController::class,'task2LexicalGramma']);

Route::post("openai/test/task1/task_achievement", [App\Http\Controllers\ApiTestController::class,'task1TaskAchievement']);

//app
//login
Route::post("app/register", [App\Http\Controllers\ApiAppController::class,'register']);
Route::post("openai/test/job", [App\Http\Controllers\ApiTestController::class,'newApiTestJob']);
//api audio
Route::post("openai/test/audio", [App\Http\Controllers\ApiTestController::class,'audio']);
// Route::post("lms/course/activity/complention", [App\Http\Controllers\ApiTestController::class,'audio']);
// Route::post("lms/course/complention/detail", [App\Http\Controllers\ApiTestController::class,'audio']);

// api cms task 2
Route::post("cms/ielts/write_task_2/create", [App\Http\Controllers\ApiController::class,'writeTask2Create']);
Route::post("cms/ielts/write_task_2/detail", [App\Http\Controllers\ApiController::class,'writeTask2Detail']);
// api cms task 1
Route::post("cms/ielts/write_task_1/create", [App\Http\Controllers\ApiController::class,'writeTask1Create']);
Route::post("cms/ielts/write_task_1/detail", [App\Http\Controllers\ApiController::class,'writeTask1Detail']);
Route::post("cms/ielts/test/write_task_1/write_task", [App\Http\Controllers\ApiTestController::class,'writeTask1CreateWrite']);

//lms
Route::post("lms/course/activity/complention", [App\Http\Controllers\LmsCompletionActivityController::class,'createActivityCompletion']);
Route::post("lms/course/complention/detail", [App\Http\Controllers\LmsCompletionActivityController::class,'detail']);

//lms: update video status(1: hoan thanh, 0: khong hoan thanh)
Route::post("lms/update_video", [App\Http\Controllers\LmsCompletionActivityController::class,'updateVideo']);
//lms: update exercise(1: hoan thanh, 0: khong hoan thanh)
Route::post("lms/update_exercise", [App\Http\Controllers\LmsCompletionActivityController::class,'updateExercise']);
//lms: update quiz test(1: hoan thanh, 0: khong hoan thanh)
Route::post("lms/update_quiz", [App\Http\Controllers\LmsCompletionActivityController::class,'updateQuiz']);
//lms: get process course = average of list video(1 video has many exercise) and quiz
Route::post("lms/process_course", [App\Http\Controllers\LmsCompletionActivityController::class,'processCourse']);
//lms: update course information
Route::post("lms/update_course", [App\Http\Controllers\LmsCompletionActivityController::class,'updateCourse']);
//lms: get status video, quiz, excersie of student. Video = average of list excersie(1 video has many exercise) of student.
Route::post("lms/activity/status", [App\Http\Controllers\LmsCompletionActivityController::class,'getStatusActivity']);


//api task 1 2 all
Route::post("openai/task1Test", [App\Http\Controllers\ApiTestController::class,'task1Test']);
Route::post("openai/test/task1/8", [App\Http\Controllers\ApiTestController::class,'task1Test8']);

Route::post("openai/task1/all", [App\Http\Controllers\ApiController::class,'task1All']);
Route::post("openai/task2/all", [App\Http\Controllers\ApiController::class,'task2All']);
Route::post("openai/task1/analyze", [App\Http\Controllers\ApiController::class,'imageTask1']);
//api tinh chinh anh
Route::post("openai/task1/analyze/chat", [App\Http\Controllers\ApiController::class,'imageTask1Chat']);

//api hocmai
//Vocabulary & Grammar Correction
Route::post("openai/hocmai/task1/vocabulary_grammar", [App\Http\Controllers\ApiTestController::class,'hocmaiTask1VocabularyGramma']);
//task_achiement
Route::post("openai/hocmai/task1/task_achiement", [App\Http\Controllers\ApiTestController::class,'hocmaiTask1TaskAchiement']);
//coherence_cohesion
Route::post("openai/hocmai/task1/coherence_cohesion", [App\Http\Controllers\ApiTestController::class,'hocmaiTask1CoherenceCohesion']);
//lexical_resource
Route::post("openai/hocmai/task1/lexical_resource", [App\Http\Controllers\ApiTestController::class,'hocmaiTask1LexicalResource']);
//grammatical_range_accuracy
Route::post("openai/hocmai/task1/grammatical_range_accuracy", [App\Http\Controllers\ApiTestController::class,'hocmaiTask1GrammaRange']);


Route::get('example', [ExampleController::class, 'index']);
Route::get('example/{id}', [ExampleController::class, 'show']);
