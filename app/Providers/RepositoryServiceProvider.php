<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Examples\ExampleRepository;
use App\Repositories\Examples\ExampleRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(ExampleRepositoryInterface::class, ExampleRepository::class);
        //:end-bindings:
    }
}
