<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        'App\Events\Part1JobCompleted' => [
            'App\Listeners\HandlePart1JobCompletion',
        ],
        // C�c s? ki?n v� listener kh�c c� th? du?c dang k� t?i d�y
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
