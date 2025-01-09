<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Repositories\ApiMoodles\ApiMoodleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiMoodleService extends BaseService
{

    protected $apiMoodleRepository;

    public function __construct(ApiMoodleRepositoryInterface $apiMoodleRepository)
    {
        $this->apiMoodleRepository = $apiMoodleRepository;
    }

    /**
     * update RubricTemplateId In Api Moodle
     *
     * @param array $data
     *
     * @return void
     */
    public function updateRubricTemplateIdInApiMoodles($data)
    {
        if(empty($data['api_moodle_ids']))
        {
            $this->apiMoodleRepository->updateRubricTemplateToNullInApiMoodles([], $data['rubric_template_id']);
            return;
        }
        $apiMoodleIds = explode(',', $data['api_moodle_ids']);
        $dataUpdate = [
            'rubric_template_id' => $data['rubric_template_id']
        ];

        DB::transaction(function () use ($apiMoodleIds, $dataUpdate, $data) {
            $this->apiMoodleRepository->updateMultiple($apiMoodleIds, $dataUpdate);
            $this->apiMoodleRepository->updateRubricTemplateToNullInApiMoodles($apiMoodleIds, $data['rubric_template_id']);
        });
    }

    /**
     * get api_moodles By Rubric Template Id
     *
     * @param int $rubricTemplateId
     * @param int|null $moodleType
     *
     * @return Collection
     */
    public function getApiMoodlesByRubricTemplateId($rubricTemplateId, $moodleType = null)
    {
       return $this->apiMoodleRepository->getApiMoodlesByRubricTemplateId($rubricTemplateId, $moodleType);
    }
}
