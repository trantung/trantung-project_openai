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
Route::post("openai/test/introduction", [App\Http\Controllers\ApiController::class,'introductionTest']);
//cuc 2: topic sentence
Route::post("openai/test/topic_sentence", [App\Http\Controllers\ApiTestController::class,'topicSentence']);
Route::post("openai/test/conclusion", [App\Http\Controllers\ApiTestController::class,'conclusion']);
//cuc 3: band
Route::post("openai/test/band/task_response", [App\Http\Controllers\ApiTestController::class,'bandTaskResponse']);
//cuc 4 Coherence & Cohesion
Route::post("openai/test/band/coherence_cohesion", [App\Http\Controllers\ApiTestController::class,'coherenceCohesion']);
