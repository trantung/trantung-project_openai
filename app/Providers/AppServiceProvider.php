<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ApiMoodle;
use App\Observers\ApiMoodleObserver;
use Illuminate\Pagination\Paginator;

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
            $courses = ApiMoodle::where('moodle_type', 'course')->get();
            $menus = $this->getMenus($courses);
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
                        'url' => env('URL_LMS') . '/course/view.php?id=' . $course->moodle_id,
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
