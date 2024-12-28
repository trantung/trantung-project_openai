<?php

namespace App\Repositories\EmsTypes;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\EmsTypes\EmsTypeRepositoryInterface;
use App\Models\EmsType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EmsTypeRepository.
 *
 * @package namespace App\EmsTypes\EmsTypeRepository;
 */
class EmsTypeRepository extends BaseRepository implements EmsTypeRepositoryInterface
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return EmsType::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot(): void
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * get Emstype By Rubric Template Id
     *
     * @param int $rubricTemplateId
     * 
     * @return Collection
     */
    public function getEmstypeByRubricTemplateId(int $rubricTemplateId): Collection
    {
        return $this->model
        ->whereHas('api_ems',function($query) use ($rubricTemplateId) {
            $query->where('rubric_template_id', $rubricTemplateId)
                  ->orWhereNull('rubric_template_id');
        })
        ->with(['api_ems' => function($query) use ($rubricTemplateId) {
            $query->where(function($query) use ($rubricTemplateId) {
                $query->where('rubric_template_id', $rubricTemplateId)
                      ->orWhereNull('rubric_template_id');
            });
        }])
        ->get();
    }
}
