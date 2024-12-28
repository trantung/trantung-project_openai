<?php

namespace App\Http\Controllers;

use App\Services\RubricScoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RubricScoreController extends BaseController
{
    //
    protected $rubricScoreService;

    protected $breadcrumbs;

    public function __construct(RubricScoreService $rubricScoreService)
    {
        $this->rubricScoreService = $rubricScoreService;
        $this->breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('rubric_templates.index'),
                'text' => 'Danh sách sản phẩm',
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
        $rubricScores = $this->rubricScoreService->search($params);
        return view('rubric-scores.index', [
            'breadcrumbs' => $this->breadcrumbs,
            'rubricScores' => $rubricScores
        ]);
    }
}
