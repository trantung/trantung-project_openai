<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Jobs\DemoJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/token', function () {
    return csrf_token(); 
});


Route::get('/dashboard', [HomeController::class, 'index1'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::middleware(['auth', 'admin'])->group(function () {
 
    Route::get('admin/dashboard', [HomeController::class, 'index']);
 
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin/users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin/users/create');
    Route::post('/admin/users/save', [UserController::class, 'store'])->name('admin/users/save');
    Route::get('/admin/users/edit/{id}', [UserController::class, 'edit'])->name('admin/users/edit');
    Route::put('/admin/users/edit/{id}', [UserController::class, 'update'])->name('admin/users/update');
    Route::get('/admin/users/delete/{id}', [UserController::class, 'delete'])->name('admin/users/delete');
});
 
Route::get('/test-queue', [HomeController::class, 'testQueue'])->middleware('auth')->name('testQueue');

Route::get('/test-streaming', [HomeController::class, 'testStreaming'])->middleware('auth')->name('testStreaming');

Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('course.index');
Route::get('/product/detail/{course_id}', [ProductController::class, 'detail'])->middleware('auth')->name('course.detail');

Route::get('/load-image-form', function () {
    return view('template.edit_image_form');
})->name('load.image.form');