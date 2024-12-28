<?php

use App\Http\Controllers\ApiEmsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RubricScoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AuthSSOController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RubricTemplateController;
use App\Jobs\DemoJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/token', function () {
    return csrf_token();
});

Route::resource('rubric-templates', RubricTemplateController::class)->names('rubric_templates');
Route::get('/rubric-templates/ajax/ems-types', [RubricTemplateController::class, 'getEmsTypeInPopup'])->name('rubric_templates.ajax.ems_types');
Route::post('/rubric-templates/ajax/update-rubric-template-id-in-api-ems', [RubricTemplateController::class, 'updateRubricTemplateIdInApiEms'])->name('rubric_templates.ajax.update_multiple_api_ems');

Route::resource('api-ems', ApiEmsController::class)->names('api_ems');

Route::resource('rubric-scores', RubricScoreController::class)->names('rubric_scores');

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

Route::get('/test-chat', [HomeController::class, 'testChat'])->middleware('auth')->name('testChat');

Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('course.index');
Route::get('/product/detail/{course_id}', [ProductController::class, 'detail'])->middleware('auth')->name('course.detail');

Route::get('/load-image-form', function () {
    return view('template.edit_image_form');
})->name('load.image.form');

Route::get('/teacher/index', [TeacherController::class, 'index'])->middleware('auth')->name('teacher.index');
Route::post('/teacher/delete', [TeacherController::class, 'delete'])->middleware('auth')->name('teacher.delete');

Route::get('/class/index', [ClassController::class, 'index'])->middleware('auth')->name('class.index');
Route::get('/class/add', [ClassController::class, 'add'])->middleware('auth')->name('class.add');
Route::post('/class/store', [ClassController::class, 'store'])->middleware('auth')->name('class.store');
Route::get('/class/search', [ClassController::class, 'search'])->middleware('auth')->name('class.search');
Route::get('/class/edit/{id}', [ClassController::class, 'edit'])->middleware('auth')->name('class.edit');
Route::post('/class/update/{id}', [ClassController::class, 'update'])->middleware('auth')->name('class.update');
Route::post('/class/delete', [ClassController::class, 'delete'])->middleware('auth')->name('class.delete');

Route::get('/ssologin', function () {
    return Socialite::driver('keycloak')->redirect();
})->name('ssologin');

Route::get('/callback', [AuthSSOController::class, 'keycloakCallback']);

Route::post('/ssologout', function () {
    return 'logout';
    Auth::logout(); // Đăng xuất khỏi Laravel
    session()->invalidate(); // Hủy phiên hiện tại
    session()->regenerateToken(); // Tạo token CSRF mới

    // Lấy thông tin từ .env
    $keycloakUrl = env('KEYCLOAK_BASE_URL');
    $realm = env('KEYCLOAK_REALM');
    $redirectUri = env('KEYCLOAK_REDIRECT_URI', url('/')); // Mặc định là URL của app nếu không có

    // Điều hướng người dùng về trang đăng xuất của Keycloak
    return redirect("{$keycloakUrl}/realms/{$realm}/protocol/openid-connect/logout?redirect_uri=" . urlencode($redirectUri));
})->name('ssologout');


// Route::get('/callback', function () {
//     $keycloakUser = Socialite::driver('keycloak')->user();

//     // Tìm user trong database hoặc tạo mới
//     $user = User::firstOrCreate([
//         'email' => $keycloakUser->getEmail(),
//     ], [
//         'name' => $keycloakUser->getName(),
//         'password' => bcrypt(Str::random(16)), // Mật khẩu ngẫu nhiên
//     ]);

//     // Đăng nhập user
//     Auth::login($user);

//     return redirect('/dashboard');
// });

Route::get('/ssouser/teacher/index', [AuthSSOController::class, 'sso_teacher'])->middleware('auth')->name('ssouser.teacher.index');
Route::get('/ssouser/teacher/search', [AuthSSOController::class, 'sso_teacher_search'])->middleware('auth')->name('ssouser.teacher.search');

Route::get('/role/index', [RoleController::class, 'index'])->middleware('auth')->name('role.index');
Route::get('/role/add', [RoleController::class, 'add'])->middleware('auth')->name('role.add');
Route::post('/role/store', [RoleController::class, 'store'])->middleware('auth')->name('role.store');
Route::get('/role/edit/{id}', [RoleController::class, 'edit'])->middleware('auth')->name('role.edit');
Route::post('/role/update/{id}', [RoleController::class, 'update'])->middleware('auth')->name('role.update');
Route::post('/role/delete', [RoleController::class, 'delete'])->middleware('auth')->name('role.delete');
Route::get('/role/assigns/{id}', [RoleController::class, 'assignsIndex'])->middleware('auth')->name('role.assigns');
Route::post('/role/assignUser', [RoleController::class, 'assignUsers'])->middleware('auth')->name('role.assignUser');

Route::get('/permission/index', [RoleController::class, 'permissionIndex'])->middleware('auth')->name('permission.index');
Route::get('/permission/add', [RoleController::class, 'permissionAdd'])->middleware('auth')->name('permission.add');
Route::post('/permission/store', [RoleController::class, 'permissionStore'])->middleware('auth')->name('permission.store');
Route::get('/permission/edit/{id}', [RoleController::class, 'permissionEdit'])->middleware('auth')->name('permission.edit');
Route::post('/permission/update/{id}', [RoleController::class, 'permissionUpdate'])->middleware('auth')->name('permission.update');
Route::post('/permission/delete', [RoleController::class, 'permissionDelete'])->middleware('auth')->name('permission.delete');
