<?php

namespace App\Repositories\ApiMoodles;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ApiMoodles\ApiMoodleRepositoryInterface;
use App\Models\ApiMoodle;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EmsTypeRepository.
 *
 * @package namespace App\ApiMoodles\ApiMoodleRepository;
 */
class ApiMoodleRepository extends BaseRepository implements ApiMoodleRepositoryInterface
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ApiMoodle::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * get ApiMoodles By Rubric Template Id
     *
     * @param int $rubricTemplateId
     * @param int|null $moodleType
     *
     * @return Collection
     */
    public function getApiMoodlesByRubricTemplateId($rubricTemplateId, $moodleType = null)
    {
        // dd($rubricTemplateId, $moodleType);
        $res = $this->model->where(function ($query) use ($rubricTemplateId) {
                        $query->where('rubric_template_id', $rubricTemplateId)
                            ->orWhereNull('rubric_template_id');
                    })->when($moodleType, function ($query) use ($moodleType) {
                        $query->where('moodle_type', $moodleType);
                    })->get();
                    
        return $res;
    }

    /**
     * update Multiple
     *
     * @param array $data
     * @param array $ids
     *
     * @return void
     */
    public function updateMultiple($ids, $data)
    {
        $this->model->whereIn('id', $ids)->update($data);
    }

    /**
     * update Rubric Template To Null
     *
     * @param array $data
     * @param array $ids
     *
     * @return void
     */
    public function updateRubricTemplateToNullInApiMoodles($ids, $rubricTemplateId)
    {
        $query = $this->model->where('rubric_template_id', $rubricTemplateId);
        if(!empty($ids)) {
            $query = $query->whereNotIn('id', $ids);
        }
        $query->update(['rubric_template_id' => null]);
    }
}
