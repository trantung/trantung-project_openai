<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleCreateRequest;
use App\Http\Requests\ExampleUpdateRequest;
use App\Services\ExampleService;
use Illuminate\Http\JsonResponse;

/**
 * Class ExampleController.
 *
 * @package namespace App\Http\Controllers;
 */
class ExampleController extends BaseApiController
{
    /**
     * @var ExampleService
     */
    protected $exampleService;

    /**
     * ExampleController constructor.
     *
     * @param ExampleService $exampleService
     */
    public function __construct(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
    }

    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $examples = $this->exampleService->all();
            return $this->responseSuccess('200', $examples);
        } catch (\Exception $e) {
            return $this->responseError('500', $e->getMessage());
        }
    }

    /**
     * show
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $example = $this->exampleService->findById($id);
            return $this->responseSuccess('200', $example);
        } catch (\Exception $e) {
            return $this->responseError('500', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ExampleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function store(ExampleCreateRequest $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ExampleUpdateRequest $request
     * @param  int            $id
     *
     * @return Response
     */
    public function update(ExampleUpdateRequest $request, $id)
    {

    }

}
