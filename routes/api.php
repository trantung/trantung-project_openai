<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ICANIDController;

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
Route::post("lms/section/delete", [App\Http\Controllers\ProductController::class,'deleteSection']);

Route::post("lms/activity/create", [App\Http\Controllers\ProductController::class,'createActivityMoodles']);
Route::post("lms/activity/update", [App\Http\Controllers\ProductController::class,'updateActivityMoodles']);
Route::post("lms/activity/detail", [App\Http\Controllers\ProductController::class,'detailActivityMoodles']);
Route::post("lms/activity/delete", [App\Http\Controllers\ProductController::class,'deleteModule']);

Route::post("lms/availablity/detail", [App\Http\Controllers\ProductController::class,'detailAvailabilityMoodles']);

Route::post("products/search", [App\Http\Controllers\ProductController::class,'search']);
Route::post("productdetail/search", [App\Http\Controllers\ProductController::class,'searchProductDetail']);

Route::get("ielts/exam/list", [App\Http\Controllers\ProductController::class,'listContestIelts']);

Route::post("chat/sendMessage", [App\Http\Controllers\HomeController::class,'sendMessage']);

Route::get("class/teacher/search", [App\Http\Controllers\ClassController::class,'searchTeacher']);
Route::post("class/teacher/enrol", [App\Http\Controllers\ClassController::class,'enrolTeacher']);
Route::get("class/teacher/get_infor_teacher_class", [App\Http\Controllers\ClassController::class,'getInforTeacherInClass']);
Route::post("class/teacher/update_infor_teacher_class", [App\Http\Controllers\ClassController::class,'updateInforTeacherInClass']);
Route::post("class/teacher/unenrol", [App\Http\Controllers\ClassController::class,'unenrolTeacher']);


Route::post("role/assigns/search", [App\Http\Controllers\RoleController::class,'searchUserAssign']);

Route::get("teacher/get_infor_user", [App\Http\Controllers\TeacherController::class,'getInforTeacherUser']);
Route::post("teacher/update_infor_user", [App\Http\Controllers\TeacherController::class,'updateInforTeacherUser']);

//api hocmai
//task 1
Route::post("openai/hocmai/task1/all", [App\Http\Controllers\ApiController::class,'task1All']);
//Vocabulary & Grammar Correction
Route::post("openai/hocmai/task1/vocabulary_grammar", [App\Http\Controllers\ApiController::class,'hocmaiTask1VocabularyGramma']);
//task_achiement
Route::post("openai/hocmai/task1/task_achiement", [App\Http\Controllers\ApiController::class,'hocmaiTask1TaskAchiement']);
//coherence_cohesion
Route::post("openai/hocmai/task1/coherence_cohesion", [App\Http\Controllers\ApiController::class,'hocmaiTask1CoherenceCohesion']);
//lexical_resource
Route::post("openai/hocmai/task1/lexical_resource", [App\Http\Controllers\ApiController::class,'hocmaiTask1LexicalResource']);
//grammatical_range_accuracy
Route::post("openai/hocmai/task1/grammatical_range_accuracy", [App\Http\Controllers\ApiController::class,'hocmaiTask1GrammaRange']);

//task 2
Route::post("openai/hocmai/task2/all", [App\Http\Controllers\ApiController::class,'task2All']);
//Vocabulary & Grammar Correction
Route::post("openai/hocmai/task2/vocabulary_grammar", [App\Http\Controllers\ApiController::class,'hocmaiTask2VocabularyGramma']);
//task_achiement
Route::post("openai/hocmai/task2/task_response", [App\Http\Controllers\ApiController::class,'hocmaiTask2TaskResponse']);
//coherence_cohesion
Route::post("openai/hocmai/task2/coherence_cohesion", [App\Http\Controllers\ApiController::class,'hocmaiTask2CoherenceCohesion']);
//lexical_resource
Route::post("openai/hocmai/task2/lexical_resource", [App\Http\Controllers\ApiController::class,'hocmaiTask2LexicalResource']);
//grammatical_range_accuracy
Route::post("openai/hocmai/task2/grammatical_range_accuracy", [App\Http\Controllers\ApiController::class,'hocmaiTask2GrammaRange']);
//Improved essay
Route::post("openai/hocmai/task2/improve_essay", [App\Http\Controllers\ApiController::class,'hocmaiTask2ImprovedEssay']);

//get log
Route::post("openai/hocmai/task2/getlog", [App\Http\Controllers\ApiController::class,'getLog']);

// Route::post("test/upload/pdf", [App\Http\Controllers\ApiController::class,'uploadPDFLms']);
//EMS
Route::post('fe/student/get-info-user-by-token', [ICANIDController::class, 'getInfoUserByToken'])->name('getInfoUserByToken');

//Sale
Route::post('student/create-with-course', [ICANIDController::class, 'createStudentWithCourse'])->name('createStudentWithCourse');
Route::get("course/list", [App\Http\Controllers\ProductController::class,'listCourseHocmai']);
