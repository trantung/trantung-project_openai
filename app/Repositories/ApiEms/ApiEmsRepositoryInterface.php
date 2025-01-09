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
    public function search($params);

    /**
     * update Multiple
     *
     * @param array $data
     * @param array $ids
     * 
     * @return void
     */
    public function updateMultiple($ids, $data);

    /**
     * update Rubric Template To Null
     *
     * @param array $data
     * @param array $ids
     * 
     * @return void
     */
    public function updateRubricTemplateToNullInApiEms($ids, $rubricTemplateId);
}
