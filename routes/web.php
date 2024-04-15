<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api', function () {
    return view('welcome');
});

Route::post("api/openai/test", [App\Http\Controllers\ApiController::class,'test']);
Route::post("api/openai/sentence_1", [App\Http\Controllers\ApiController::class,'test']);
//cục 2: introduction
Route::post("api/openai/introduction", [App\Http\Controllers\ApiController::class,'introduction']);
//topic sentence
Route::post("api/openai/topic_sentence", [App\Http\Controllers\ApiController::class,'topicSentence']);
// Route::post("api/openai/test", [App\Http\Controllers\ApiController::class,'test']);