<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ApiMoodle;
use App\Observers\ApiMoodleObserver;
use Illuminate\Pagination\Paginator;
use App\Models\UserRole;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\CourseTeacher;
use App\Models\CourseStudent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            $user = Auth::user();
            $roleIds = UserRole::where('user_id', $user->id)->pluck('role_id');

            $roleNames = Roles::whereIn('id', $roleIds)->pluck('name')->toArray();

            if (in_array('admin', $roleNames)) {
                $courses = ApiMoodle::where('moodle_type', 'course')->get();
                $menus = $this->getMenus($courses);
            }else{
                $isTeacher = Teachers::where('user_id', $user->id)->first();

                if($isTeacher){
                    $courseIds = CourseTeacher::byTeacherId($isTeacher->id)->pluck('course_id');
                    $courses = ApiMoodle::whereIn('id', $courseIds)->where('moodle_type', 'course')->get();
                    $menus = $this->getMenus($courses);
                }
            }
            // CourseTeacher::byTeacherId($teacherId)
            //         ->byClassId($classId)
            //         ->byCourseId($courseId)
            //         ->first();
            
            // $view->with('courses', $courses);
            $view->with(compact('menus', 'courses'));
        });

        Paginator::useBootstrap();
        //observer
        ApiMoodle::observe(ApiMoodleObserver::class);
    }

    /**
     * Tạo cấu trúc menu.
     */
    private function getMenus($courses)
    {
        $menus = [
            [
                'title' => 'Khoá của tôi',
                'type' => 'dynamic',
                'items' => $courses->map(function ($course) {
                    return [
                        'name' => $course->moodle_name,
                        // 'url' => env('URL_LMS') . '/course/view.php?id=' . $course->moodle_id,
                        'url' => '/product/detail/' . $course->id,
                    ];
                })->toArray(),
            ],
            [
                'title' => 'Quản lí vận hành',
                'items' => [
                    ['name' => 'Danh sách lớp', 'route' => 'class.index'],
                    ['name' => 'Danh sách giáo viên', 'route' => 'teacher.index'],
                    ['name' => 'Danh sách giáo viên SSO', 'route' => 'ssouser.teacher.index'],
                ],
            ],
            [
                'title' => 'Quản lí nội dung',
                'items' => [
                    ['name' => 'Quản lí sản phẩm', 'route' => 'course.index'],
                ],
            ],
            [
                'title' => 'Hệ thống',
                'items' => [
                    ['name' => 'Danh sách vai trò', 'route' => 'role.index'],
                    // ['name' => 'Rubric template', 'route' => 'rubric_templates.index'],
                ],
            ],
            [
                'title' => 'Báo cáo',
                'items' => [], // Không có item nào nên sẽ bị ẩn
            ],
        ];

        // Lọc các menu không hợp lệ
        foreach ($menus as &$menu) {
            if (isset($menu['type']) && $menu['type'] === 'dynamic') {
                $menu['items'] = array_filter($menu['items']);
            } else {
                $menu['items'] = array_filter($menu['items'], function ($item) {
                    return isset($item['route']) ? checkPermissionUser($item['route']) : true;
                });
            }

            if (empty($menu['items'])) {
                unset($menu);
            }
        }

        return $menus;
    }
}
