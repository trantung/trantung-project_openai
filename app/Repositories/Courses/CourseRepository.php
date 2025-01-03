<?php

namespace App\Repositories\Courses;

use App\Models\Course;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Courses\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CourseRepository.
 *
 * @package namespace App\Courses\CourseRepository;
 */
class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{

    const PER_PAGE = 10;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Course::class;
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

        $query->when(!empty($params['name']), function ($q) use ($params) {
            $q->where('name', 'like','%'.$params['name'].'%');
        });

        $query->when(!empty($params['rubric_template_id']), function ($q) use ($params) {
            $q->whereHas('api_moodle', function ($q) use ($params){
                $q->where('rubric_template_id', $params['rubric_template_id']);
            });
        });

        return $query->paginate(self::PER_PAGE);
    }
}
