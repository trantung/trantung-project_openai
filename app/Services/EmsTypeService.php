<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\EmsTypes\EmsTypeRepositoryInterface;

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
}
