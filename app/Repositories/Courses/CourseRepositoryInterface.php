<?php

namespace App\Repositories\Courses;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface CourseRepositoryInterface.
 *
 * @package namespace App\Repositories\Courses;
 */
interface CourseRepositoryInterface extends RepositoryInterface
{

    /**
     * search
     *
     * @param array $params
     *
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
