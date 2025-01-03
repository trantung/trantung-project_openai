<?php

namespace App\Repositories\RubricScores;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface RubricScoreRepositoryInterface.
 *
 * @package namespace App\Repositories\RubricScores;
 */
interface RubricScoreRepositoryInterface extends RepositoryInterface
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
     * 
     * @return void
     */
    public function updateMultiple(array $data): void;
}
