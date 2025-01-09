<?php

namespace App\Http\Controllers;

use App\Services\ApiEmsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\CommonEms;

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

    public function responseSuccess($statusCode, $data)
    {
        return response()->json(array(
            'code' => $statusCode,
            'data' => $data,
            'message' => 'Success'
        ), 200);
    }
    
    public function error($statusCode, $message)
    {
        return response()->json(array(
            'code' => $statusCode,
            'message' => $message,
        ), 400);
    }
    /**
     * index
     *
     * @param Request $request
     *
     * @return View
     *
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $apiEmses = $this->apiEmsService->search($params);
        return view('api-ems.index', [
            'breadcrumbs' => $this->breadcrumbs,
            'apiEmses' => $apiEmses
        ]);
    }
    
    public function examList(Request $request)
    {
        $request = request()->all();
        $check = $this->connectEms($request);
    }

    public function connectEms($request)
    {
        $data = CommonEms::getListExam();
        if(!$data) {
            return $this->error(400, 'fail');
        }

        $data = json_decode($data, true);
        $data = $data['data'];
        foreach($data as $value)
        {
            $this->apiEmsService->createOrUpdateEmsExam($value);
        }

    }
}
