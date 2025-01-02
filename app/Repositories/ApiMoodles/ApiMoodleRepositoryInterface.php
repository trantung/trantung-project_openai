<?php

namespace App\Repositories\ApiMoodles;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ApiMoodleRepositoryInterface.
 *
 * @package namespace App\Repositories\ApiMoodles;
 */
interface ApiMoodleRepositoryInterface extends RepositoryInterface
{
    /**
     * get ApiMoodles By Rubric Template Id
     *
     * @param int $rubricTemplateId
     * @param int|null $moodleType
     *
     * @return Collection
     */
    public function getApiMoodlesByRubricTemplateId(int $rubricTemplateId, int|null $moodleType = null): Collection;

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
    public function updateRubricTemplateToNullInApiMoodles(array $ids, int $rubricTemplateId): void;
}
