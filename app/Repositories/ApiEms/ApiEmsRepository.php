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
    public function search($params)
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
    public function updateRubricTemplateToNullInApiEms($ids, $rubricTemplateId)
    {
        $query = $this->model->where('rubric_template_id', $rubricTemplateId);
        if(!empty($ids)) {
            $query = $query->whereNotIn('id', $ids);
        }
        $query->update(['rubric_template_id' => null]);
    }

    public function createOrUpdateExamPaper($data)
    {
        dd($data);
        //create apiems
        // 'ems_id', 'ems_name', 'ems_type_id', 'rubric_template_id', 'skill'
        $ems_id = $data['idMockContest'];
        $dataEms = $this->model->find($ems_id);
        if(!$dataEms) {
            //tao moi de thi
            return $this->createExamPaper($data);
        }
        //update de thi
        return $this->updateExamPaper($data, $dataEms);
    }

    public function createExamPaper($data)
    {
        //tao moi api_ems
        $apiEmsId = $this->createEms($data);
        //tao moi api_ems_type
        $this->createEmsType($data);
        //tao moi api_ems_tags
        $this->createEmsTag($data);
    }

    public function createEmsType($data)
    {

    }
    
    public function createEmsTag($data)
    {

    }
    
    public function createEms($data)
    {
        $ems_id = $data['idMockContest'];
        $ems_name = $data['name'];
        return $this->model->create([
            'ems_id' => $ems_id,
            'ems_name' => $ems_name,
        ])->id;
    }

    public function updateExamPaper($data, $dataEms)
    {
        
    }
}
