<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('openai/test', [ApiController::class, 'test']);
Route::post('openai/test', [App\Http\Controllers\ApiController::class,'test']);
Route::post("openai/sentence_1", [App\Http\Controllers\ApiController::class,'test']);
//c?c 2: introduction
Route::post("openai/introduction", [App\Http\Controllers\ApiController::class,'introduction']);
//topic sentence
Route::post("openai/topic_sentence", [App\Http\Controllers\ApiController::class,'topicSentence']);