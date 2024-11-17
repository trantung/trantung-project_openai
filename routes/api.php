<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("lms/course/list", [App\Http\Controllers\ProductController::class,'core_course_get_courses']);
Route::get("lms/category/list", [App\Http\Controllers\ProductController::class,'getCategoryMoodles']);

Route::post("lms/category/create", [App\Http\Controllers\ProductController::class,'createCategoryMoodles']);
Route::post("lms/category/update", [App\Http\Controllers\ProductController::class,'updateCategoryMoodles']);
Route::post("lms/category/delete", [App\Http\Controllers\ProductController::class,'deleteCategoryMoodles']);
Route::post("lms/category/detail", [App\Http\Controllers\ProductController::class,'detailCategoryMoodles']);

Route::post("lms/course/create", [App\Http\Controllers\ProductController::class,'createCourseMoodles']);
Route::post("lms/course/detail", [App\Http\Controllers\ProductController::class,'detailCourseMoodles']);
Route::post("lms/course/update", [App\Http\Controllers\ProductController::class,'updateCourseMoodles']);
Route::post("lms/course/delete", [App\Http\Controllers\ProductController::class,'deleteCourseMoodles']);

Route::post("lms/section/create", [App\Http\Controllers\ProductController::class,'createSectionCourse']);
Route::post("lms/section/update", [App\Http\Controllers\ProductController::class,'updateSectionCourse']);
Route::post("lms/section/list", [App\Http\Controllers\ProductController::class,'getSectionCourse']);
Route::post("lms/section/detail", [App\Http\Controllers\ProductController::class,'detailSectionCourse']);

Route::post("lms/activity/create", [App\Http\Controllers\ProductController::class,'createActivityMoodles']);
Route::post("lms/activity/update", [App\Http\Controllers\ProductController::class,'updateActivityMoodles']);
Route::post("lms/activity/detail", [App\Http\Controllers\ProductController::class,'detailActivityMoodles']);

Route::post("lms/availablity/detail", [App\Http\Controllers\ProductController::class,'detailAvailabilityMoodles']);

Route::post("products/search", [App\Http\Controllers\ProductController::class,'search']);

Route::get("ielts/exam/list", [App\Http\Controllers\ProductController::class,'listContestIelts']);