<?php

namespace App\Repositories\RubricTemplates;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RubricTemplates\RubricTemplateRepositoryInterface;
use App\Models\RubricTemplate;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class RubricTemplateRepository.
 *
 * @package namespace App\Repositories\RubricTemplates;
 */
class RubricTemplateRepository extends BaseRepository implements RubricTemplateRepositoryInterface
{

    const PER_PAGE = 10;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RubricTemplate::class;
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

        $query->when(!empty($params['name']), function ($q) use ($params) {
            $q->where('name', 'like','%'.$params['name'].'%');
        });

        return $query->paginate(self::PER_PAGE);
    }

}
