<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api', function () {
    return view('welcome');
});

Route::post("openai/test", [App\Http\Controllers\ApiController::class,'test']);