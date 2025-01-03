<?php

namespace App\Repositories\RubricTemplates;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface RubricTemplateRepositoryInterface.
 *
 * @package namespace App\Repositories\RubricTemplates;
 */
interface RubricTemplateRepositoryInterface extends RepositoryInterface
{
    //
    /**
     * search
     *
     * @param array $params
     *
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator;
}
