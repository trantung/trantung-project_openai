<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RubricTemplates\RubricTemplateRepositoryInterface;
use App\Repositories\RubricTemplates\RubricTemplateRepository;
use App\Repositories\RubricScores\RubricScoreRepository;
use App\Repositories\RubricScores\RubricScoreRepositoryInterface;
use App\Repositories\EmsTypes\EmsTypeRepository;
use App\Repositories\EmsTypes\EmsTypeRepositoryInterface;
use App\Repositories\ApiEms\ApiEmsRepository;
use App\Repositories\ApiEms\ApiEmsRepositoryInterface;
use App\Repositories\ApiMoodles\ApiMoodleRepository;
use App\Repositories\ApiMoodles\ApiMoodleRepositoryInterface;
use App\Repositories\Courses\CourseRepository;
use App\Repositories\Courses\CourseRepositoryInterface;

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
        $this->app->bind(RubricTemplateRepositoryInterface::class, RubricTemplateRepository::class);
        $this->app->bind(RubricScoreRepositoryInterface::class, RubricScoreRepository::class);
        $this->app->bind(EmsTypeRepositoryInterface::class, EmsTypeRepository::class);
        $this->app->bind(ApiEmsRepositoryInterface::class, ApiEmsRepository::class);
        $this->app->bind(ApiMoodleRepositoryInterface::class, ApiMoodleRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        //:end-bindings:
    }
}
