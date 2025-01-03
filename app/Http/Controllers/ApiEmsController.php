<?php

namespace App\Http\Controllers;

use App\Services\ApiEmsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiEmsController extends BaseController
{
    //
    protected $apiEmsService;

    protected $breadcrumbs;

    public function __construct(ApiEmsService $apiEmsService)
    {
        $this->apiEmsService = $apiEmsService;
        $this->breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('api_ems.index'),
                'text' => 'Danh sách bộ đề',
            ],
        ];
    }

    /**
     * index
     *
     * @param Request $request
     *
     * @return View
     *
     */
    public function index(Request $request): View
    {
        $params = $request->all();
        $apiEmses = $this->apiEmsService->search($params);
        return view('api-ems.index', [
            'breadcrumbs' => $this->breadcrumbs,
            'apiEmses' => $apiEmses
        ]);
    }
}
