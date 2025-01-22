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
    
    //danh sach de thi 
    public function examList(Request $request)
    {
        $request = request()->all();
        $check = $this->connectEms($request);

        return json_decode($check, true);
    }

    public function examListDetail(Request $request)
    {
        $contestDetail = [
            "status" => true,
            "data" => [
                "name" => "Introduction Mock Tests 1&2 exam",
                "contest_type" => 19,
                "maxNumAttempt" => 0,
                "timeStart" => "2024-01-01T00:00:00.000Z",//thời gian bắt đầu thi
                "timeEnd" => "2035-01-01T23:59:59.999Z",//thời gian kết thúc thi
                "idMockContest" => 548,
                "rounds" => [
                    [
                        "type" => 6,
                        "name" => "Listening",
                        "listBaikiemtra" => [
                            [
                                "name" => "IELTS LISTENING 01",
                                "timeAllow" => 300,
                                "maxMark" => 10,
                                "testFormat" => 13,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-07-01T04:08:00.000Z",//thời gian bắt đầu phần thi
                                "timeEnd" => "2025-01-01T04:08:00.000Z",//thời gian kết thúc phần thi
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10666
                            ]
                        ],
                        "subMockContestHsa" => null,
                        "subjectHsa" => null
                    ],
                    [
                        "type" => 7,
                        "name" => "Reading",
                        "listBaikiemtra" => [
                            [
                                "name" => "IELTS READING 01",
                                "timeAllow" => 3600,
                                "maxMark" => 10,
                                "testFormat" => 14,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-07-01T04:10:00.000Z",
                                "timeEnd" => "2025-01-01T04:10:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10667
                            ]
                        ],
                        "subMockContestHsa" => null,
                        "subjectHsa" => null
                    ],
                    [
                        "type" => 8,
                        "name" => "Writing",
                        "listBaikiemtra" => [
                            [
                                "name" => "IELTS WRITING 01 ",
                                "timeAllow" => 3600,
                                "maxMark" => 10,
                                "testFormat" => 15,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-07-01T04:13:00.000Z",
                                "timeEnd" => "2025-01-01T04:13:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10668
                            ]
                        ],
                        "subMockContestHsa" => null,
                        "subjectHsa" => null
                    ],
                    [
                        "type" => 9,
                        "name" => "Speaking",
                        "listBaikiemtra" => [
                            [
                                "name" => "IELTS SPEAKING 01 ",
                                "timeAllow" => 1800,
                                "maxMark" => 16,
                                "testFormat" => 16,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-07-01T04:17:00.000Z",
                                "timeEnd" => "2025-01-01T04:18:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10669
                            ]
                        ],
                        "subMockContestHsa" => null,
                        "subjectHsa" => null
                    ]
                ]
            ]
        ];
        // dd($request->exam_contest_type, $request->exam_idMockContest);

        return response()->json($contestDetail);
    }

    public function connectEms($request)
    {
        $dataCommonEms = CommonEms::getListExam();
        if(!$dataCommonEms) {
            return $this->error(400, 'fail');
        }

        $decodeData = json_decode($dataCommonEms, true);
        foreach($decodeData['data'] as $value)
        {
            $this->apiEmsService->createOrUpdateEmsExamPaper($value);
        }

        return $dataCommonEms;
    }
}
