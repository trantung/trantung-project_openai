<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Repositories\ApiEms\ApiEmsRepositoryInterface;

class ApiEmsService extends BaseService
{

    protected $apiEmsRepository;

    public function __construct(ApiEmsRepositoryInterface $apiEmsRepository)
    {
        $this->apiEmsRepository = $apiEmsRepository;
    }

    /**
     * search
     *
     * @param array $array
     *
     * @return LengthAwarePaginator
     *
     */
    public function search($params)
    {
        return $this->apiEmsRepository->search($params);
    }

    /**
     * update RubricTemplateId In ApiEms
     *
     * @param array $data
     * 
     * @return void
     */
    public function updateRubricTemplateIdInApiEms($data)
    {
        if(empty($data['api_ems_ids']))
        {
            $this->apiEmsRepository->updateRubricTemplateToNullInApiEms([], $data['rubric_template_id']);
            return;
        } 
        $apiEmsIds = explode(',', $data['api_ems_ids']);
        $dataUpdate = [
            'rubric_template_id' => $data['rubric_template_id']
        ];
        
        DB::transaction(function () use ($apiEmsIds, $dataUpdate, $data) {
            $this->apiEmsRepository->updateMultiple($apiEmsIds, $dataUpdate);
            $this->apiEmsRepository->updateRubricTemplateToNullInApiEms($apiEmsIds, $data['rubric_template_id']);
        });
    }

    public function createOrUpdateEmsExamPaper($data)
    {
        $this->apiEmsRepository->createOrUpdateExamPaper($data);
    }
}
