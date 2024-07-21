<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("openai/test", [App\Http\Controllers\ApiController::class,'test']);
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
//app
//login
Route::post("app/register", [App\Http\Controllers\ApiAppController::class,'register']);
Route::post("openai/test/job", [App\Http\Controllers\ApiTestController::class,'newApiTestJob']);

//api audio
Route::post("openai/test/audio", [App\Http\Controllers\ApiTestController::class,'audio']);

// api cms task 2
Route::post("cms/ielts/write_task_2/create", [App\Http\Controllers\ApiController::class,'writeTask2Create']);
Route::post("cms/ielts/write_task_2/detail", [App\Http\Controllers\ApiController::class,'writeTask2Detail']);
// api cms task 1
Route::post("cms/ielts/write_task_1/create", [App\Http\Controllers\ApiController::class,'writeTask1Create']);
Route::post("cms/ielts/write_task_1/detail", [App\Http\Controllers\ApiController::class,'writeTask1Detail']);



Route::post("lms/course/activity/complention", [App\Http\Controllers\LmsCompletionActivityController::class,'create']);
Route::post("lms/course/complention/detail", [App\Http\Controllers\LmsCompletionActivityController::class,'show']);