<?php

namespace App\Repositories\Examples;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Examples\ExampleRepositoryInterface;
use App\Models\Example;

/**
 * Class ExampleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Examples;
 */
class ExampleRepository extends BaseRepository implements ExampleRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Example::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * presenter
     */
    public function presenter()
    {
        return "App\\Presenters\\ExamplePresenter";
    }

}
