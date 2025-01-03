<?php

namespace App\Repositories\ApiEms;

use App\Models\ApiEms;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ApiEms\ApiEmsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ApiEmsRepository.
 *
 * @package namespace App\ApiEms\ApiEmsRepository;
 */
class ApiEmsRepository extends BaseRepository implements ApiEmsRepositoryInterface
{

    const PER_PAGE = 10;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ApiEms::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot(): void
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

        $query->when(!empty($params['ems_name']), function ($q) use ($params) {
            $q->where('ems_name', 'like','%'.$params['ems_name'].'%');
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
     * @param array $ids
     * 
     * @return void
     */
    public function updateMultiple(array $ids, array $data): void
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
    public function updateRubricTemplateToNullInApiEms(array $ids, int $rubricTemplateId): void
    {
        $query = $this->model->where('rubric_template_id', $rubricTemplateId);
        if(!empty($ids)) {
            $query = $query->whereNotIn('id', $ids);
        }
        $query->update(['rubric_template_id' => null]);
    }
}
