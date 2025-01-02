<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends BaseController
{
    //
    protected $courseService;

    protected $breadcrumbs;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
        $this->breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('courses.index'),
                'text' => 'Danh sách khóa học',
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
        $courses = $this->courseService->search($params);
        return view('courses.index', [
            'breadcrumbs' => $this->breadcrumbs,
            'courses' => $courses
        ]);
    }
}
