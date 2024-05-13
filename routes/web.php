<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TrainingController;
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
 

//Route::get('admin/dashboard', [HomeController::class, 'index']);
Route::get('training', [TrainingController::class, 'index'])->middleware('auth')->name('training.index');
Route::get('training/form', [TrainingController::class, 'create'])->middleware('auth')->name('training.form');
Route::post('training/store', [TrainingController::class, 'store'])->name('training.store');
Route::get('training/delete/{id}', [TrainingController::class, 'deleteTraining'])->name('training.delete');
Route::get('training/detail/{id}', [TrainingController::class, 'detailTraining'])->name('training.detail');

Route::get('chat/form', [TrainingController::class, 'formChat'])->middleware('auth')->name('chat.form');
Route::get('chat/detail/{id}', [TrainingController::class, 'detailChat'])->middleware('auth')->name('chat.detail');
Route::get('chat/delete/{id}', [TrainingController::class, 'deleteChat'])->name('chat.delete');
Route::post('chat', [TrainingController::class, 'chat'])->name('chat.chat');
Route::post('chat/importJson', [TrainingController::class, 'readJsonFile'])->name('chat.importJson');

