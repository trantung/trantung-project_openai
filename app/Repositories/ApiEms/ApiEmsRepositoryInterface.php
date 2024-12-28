<?php

namespace App\Repositories\ApiEms;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface ApiEmsRepositoryInterface.
 *
 * @package namespace App\Repositories\ApiEms;
 */
interface ApiEmsRepositoryInterface extends RepositoryInterface
{

    /**
     * search
     *
     * @param array $params
     *
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;

    /**
     * update Multiple
     *
     * @param array $data
     * @param array $ids
     * 
     * @return void
     */
    public function updateMultiple(array $ids, array $data): void;

    /**
     * update Rubric Template To Null
     *
     * @param array $data
     * @param array $ids
     * 
     * @return void
     */
    public function updateRubricTemplateToNullInApiEms(array $ids, int $rubricTemplateId): void;
}
