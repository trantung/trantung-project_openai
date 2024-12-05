<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ApiMoodle;

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
            $view->with('courses', $courses);
        });
    }
}
