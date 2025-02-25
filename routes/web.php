<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TrainingController;
use App\Jobs\DemoJob;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

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
Route::get('/test-queue', [HomeController::class, 'testQueue'])->middleware('auth')->name('testQueue');

Route::get('/test-streaming', [HomeController::class, 'testStreaming'])->middleware('auth')->name('testStreaming');
Route::get('upload', [VideoController::class, 'showForm'])->name('video.upload');
Route::post('convert12345', [VideoController::class, 'convert12345'])->name('video.convert12345');

//test task 1
Route::get('chat/task1', [TrainingController::class, 'chatTask1'])->middleware('auth')->name('chat.task1');

Route::get('/test-srt', [HomeController::class, 'srt'])->name('srt');
// Route::get('/video/secret/{key}', function ($key) {
//   dd(sys_get_temp_dir());
//     return Storage::disk('secrets')->download($key);
// })->name('video.key');

// Route::get('/video/{playlist}', function ($playlist) {

//     return FFMpeg::dynamicHLSPlaylist('secrets')
//         ->fromDisk('public')
//         ->open($playlist)
//         ->setKeyUrlResolver(function ($key) {
//             return route('video.key', ['key' => $key]);
//         })
//         ->setMediaUrlResolver(function ($mediaFilename) {
//             return Storage::disk('public')->url($mediaFilename);
//         })
//         ->setPlaylistUrlResolver(function ($playlistFilename) {
//             return route('video.playlist', ['playlist' => $playlistFilename]);
//         });
// })->name('video.playlist');

// Route::get('/dispatch-job', function () {
//     DemoJob::dispatch();
//     return 'Job dispatched!';
// });