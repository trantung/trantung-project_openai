<?php

namespace App\Repositories\RubricScores;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RubricScores\RubricScoreRepositoryInterface;
use App\Models\RubricScore;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class RubricScoreRepository.
 *
 * @package namespace App\Repositories\RubricScores;
 */
class RubricScoreRepository extends BaseRepository implements RubricScoreRepositoryInterface
{
    const PER_PAGE = 10;

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

    /**
     * search
     *
     * @param array $params
     *
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        $query = $this->model->query();

        $query->when(!empty($params['id']), function ($q) use ($params) {
            $q->where('id', $params['id']);
        });

        $query->when(!empty($params['rubric_template_id']), function ($q) use ($params) {
            $q->where('rubric_template_id',$params['rubric_template_id']);
        });

        return $query->paginate(self::PER_PAGE);
    }

    /**
     * update Multiple
     *
     * @param array $data
     * 
     * @return void
     */
    public function updateMultiple(array $data): void
    {
        foreach ($data as $item) {
            $this->model->where('id', $item['id'])->update($item);
        }
    }

}
