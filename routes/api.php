<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("openai/test", [App\Http\Controllers\ApiController::class,'test']);

Route::post("openai/ielts/write_task_2", [App\Http\Controllers\ApiController::class,'ieltsWriteTask2']);

Route::post("openai/sentence_1", [App\Http\Controllers\ApiController::class,'test']);
//c?c 2: introduction
Route::post("openai/test/introduction", [App\Http\Controllers\ApiController::class,'introductionTest']);

Route::post("openai/test/introduction", [App\Http\Controllers\ApiController::class,'introductionTest']);
//topic sentence
Route::post("openai/topic_sentence", [App\Http\Controllers\ApiController::class,'topicSentence']);
//band
Route::post("openai/test/band/task_response", [App\Http\Controllers\ApiController::class,'bandTaskResponseTest']);