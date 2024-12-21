<?php

namespace App\Services;

use App\Repositories\Examples\ExampleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Example;

/**
 * Class ExampleService.
 *
 * @package namespace App\Services;
 */
class ExampleService extends BaseService
{

    /**
     * @var ExampleRepositoryInterface
     */
    protected $exampleRepository;

    public function __construct(ExampleRepositoryInterface $exampleRepository)
    {
        $this->exampleRepository = $exampleRepository;
    }

    /**
     * all
     *
     * @return array
     */
    public function all(): array
    {
        return $this->exampleRepository->all();
    }

    /**
     * find by id
     *
     * @param $id
     *
     * @return array
     */
    public function findById(int $id): array
    {
        return $this->exampleRepository->find($id);
    }

    /**
     * create
     *
     * @return array
     */
    public function create(array $data): array
    {
        return $this->create($data);
    }

    /**
     * update
     *
     * @param array $data
     * @param int $id
     *
     * @return array
     */
    public function update(array $data, int $id): array
    {
        return $this->update($data, $id);
    }
}
