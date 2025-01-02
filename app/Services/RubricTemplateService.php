<?php

namespace App\Services;

use App\Models\RubricTemplate;
use App\Repositories\RubricTemplates\RubricTemplateRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Repositories\RubricScores\RubricScoreRepositoryInterface;
use App\Repositories\EmsTypes\EmsTypeRepositoryInterface;
use App\Repositories\ApiMoodles\ApiMoodleRepositoryInterface;

class RubricTemplateService extends BaseService
{

    protected $rubricTemplateRepository;

    protected $rubricScoreRepository;

    protected $emsTypeRepository;

    protected $apiMoodleRepository;

    public function __construct(
        RubricTemplateRepositoryInterface $rubricTemplateRepository,
        RubricScoreRepositoryInterface $rubricScoreRepository,
        EmsTypeRepositoryInterface $emsTypeRepository,
        ApiMoodleRepositoryInterface $apiMoodleRepository
        )
    {
        $this->rubricTemplateRepository = $rubricTemplateRepository;
        $this->rubricScoreRepository = $rubricScoreRepository;
        $this->emsTypeRepository = $emsTypeRepository;
        $this->apiMoodleRepository = $apiMoodleRepository;
    }

    /**
     * find by id
     *
     * @param int $id
     *
     * @return RubricTemplate
     *
     */
    public function findById(int $id): RubricTemplate
    {
        return $this->rubricTemplateRepository->find($id);
    }

    /**
     * all
     *
     * @return Collection
     *
     */
    public function all(): Collection
    {
        return $this->rubricTemplateRepository->all();
    }

    /**
     * search
     *
     * @param array $array
     *
     * @return LengthAwarePaginator
     *
     */
    public function search(array $params): LengthAwarePaginator
    {
        return $this->rubricTemplateRepository->search($params);
    }

    /**
     * save
     *
     * @param array $data
     *
     * @return RubricTemplate
     *
     */
    public function save(array $data): RubricTemplate
    {
        $rubricTemplateData = $this->handleRubricTemplateData($data);
        return DB::transaction(function () use ($rubricTemplateData, $data) {

            $rubricTemplate = $this->rubricTemplateRepository->create($rubricTemplateData);
            $rubricScoreData = $this->handleRubricScoreCreateData($data['rubric_scores']['create'] ?? [], $rubricTemplate->id);
            $rubricTemplate->rubric_scores()->insert($rubricScoreData);

            return $rubricTemplate;
        });
    }

    /**
     * update
     *
     * @param array $data
     *
     * @return RubricTemplate
     *
     */
    public function update(array $data, int $id): RubricTemplate
    {
        $rubricTemplateData = $this->handleRubricTemplateData($data);
        return DB::transaction(function () use ($rubricTemplateData, $data, $id) {

            $rubricTemplate = $this->rubricTemplateRepository->update($rubricTemplateData, $id);

            $rubricScoreInsert = $this->handleRubricScoreCreateData($data['rubric_scores']['create'] ?? [], $rubricTemplate->id);
            $this->rubricScoreRepository->insert($rubricScoreInsert);

            $rubricScoreUpdate = $this->handleRubricScoreUpdateData($data['rubric_scores']['edit'] ?? [], $rubricTemplate->id);
            $this->rubricScoreRepository->updateMultiple($rubricScoreUpdate);

            $ids = explode(',', $data['rubric_score_ids_delete'] ?? '');
            $this->rubricScoreRepository->destroy($ids);

            return $rubricTemplate;
        });
    }


    /**
     * update
     *
     * @param int $id
     *
     * @return int
     *
     */
    public function destroy(int $id): int
    {
        return $this->rubricTemplateRepository->destroy($id);
    }

    /**
     * handle Rubric Template Data before save
     *
     * @param array $data
     *
     * @return array
     *
     */
    private function handleRubricTemplateData(array $data): array
    {
        if (empty($data)) {
            return [];
        }
        $insert = [
            'name' => $data['name'] ?? null,
            'description' => $data['name'] ?? null,
        ];

        return $insert;
    }

    /**
     * handle Rubric score Data before save
     *
     * @param array $data
     * @param int $rubricTemplateId
     *
     * @return array
     *
     */
    private function handleRubricScoreCreateData(array $data, int $rubricTemplateId): array
    {
        $insert = [];
        if (empty($data)) {
            return $insert;
        }
        foreach($data as $item) {
            $insert[] = [
                'rubric_template_id' => $rubricTemplateId,
                'lms_score' => $item['lms_score'] ?? null,
                'rule_score' => $item['rule_score'] ?? null,
            ];
        }
        return $insert;
    }

    /**
     * handle Rubric score Data before update
     *
     * @param array $data
     * @param int $rubricTemplateId
     *
     * @return array
     *
     */
    private function handleRubricScoreUpdateData(array $data, int $rubricTemplateId): array
    {
        $insert = [];
        if (empty($data)) {
            return $insert;
        }
        foreach($data as $item) {
            $insert[] = [
                'id' => $item['id'],
                'rubric_template_id' => $rubricTemplateId,
                'lms_score' => $item['lms_score'] ?? null,
                'rule_score' => $item['rule_score'] ?? null,
            ];
        }
        return $insert;
    }
}
