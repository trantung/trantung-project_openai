<?php

namespace App\Repositories\EmsTypes;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface EmsTypeRepositoryInterface.
 *
 * @package namespace App\Repositories\EmsTypes;
 */
interface EmsTypeRepositoryInterface extends RepositoryInterface
{
    /**
     * get Emstype By Rubric Template Id
     *
     * @param int $rubricTemplateId
     * 
     * @return Collection
     */
    public function getEmstypeByRubricTemplateId(int $rubricTemplateId): Collection;
}
