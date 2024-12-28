<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\RubricScores\RubricScoreRepositoryInterface;

class RubricScoreService extends BaseService
{

    protected $rubricScoreRepository;

    public function __construct(RubricScoreRepositoryInterface $rubricScoreRepository)
    {
        $this->rubricScoreRepository = $rubricScoreRepository;
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
        return $this->rubricScoreRepository->search($params);
    }
}
