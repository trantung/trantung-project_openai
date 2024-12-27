<?php

namespace App\Repositories\RubricScores;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RubricScores\RubricScoreRepositoryInterface;
use App\Models\RubricScore;

/**
 * Class RubricTemplateRepository.
 *
 * @package namespace App\Repositories\RubricTemplates;
 */
class RubricScoreRepository extends BaseRepository implements RubricScoreRepositoryInterface
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RubricScore::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function updateMultiple(array $data)
    {
        foreach ($data as $item) {
            $this->model->where('id', $item['id'])->update($item);
        }
    }

}
