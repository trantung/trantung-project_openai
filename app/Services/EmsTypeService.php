<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\EmsTypes\EmsTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EmsTypeService extends BaseService
{

    protected $emsTypeRepository;

    public function __construct(EmsTypeRepositoryInterface $emsTypeRepository)
    {
        $this->emsTypeRepository = $emsTypeRepository;
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
        return $this->emsTypeRepository->search($params);
    }

    /**
     * get Emstype By Rubric Template Id
     *
     * @param int $rubricTemplateId
     *
     * @return Collection
     */
    public function getEmstypeByRubricTemplateId(int $rubricTemplateId): Collection
    {
       return $this->emsTypeRepository->getEmstypeByRubricTemplateId($rubricTemplateId);
    }

}
