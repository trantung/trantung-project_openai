<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RubricTemplateService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class RubricTemplateController extends Controller
{
    //

    protected $rubricTemplateService;

    protected $breadcrumbs;

    public function __construct(RubricTemplateService $rubricTemplateService)
    {
        $this->rubricTemplateService = $rubricTemplateService;
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
        $rubricTemplates = $this->rubricTemplateService->search($params);
        return view('rubric-templates.index', [
            'breadcrumbs' => $this->breadcrumbs,
            'rubricTemplates' => $rubricTemplates
        ]);
    }

     /**
     * create
     *
     *
     * @return View
     *
     */
    public function create(): View
    {
        return view('rubric-templates.create', [
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * store
     * @param Request $request
     *
     * @return RedirectResponse
     *
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $rubricTemplates = $this->rubricTemplateService->save($request->all());
            return redirect()->route('rubric_templates.index')->with('success', 'Thêm mới thành công!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error!');
        }
    }


    /**
     * edit
     *
     * @param int $id
     *
     * @return View
     *
     */
    public function edit(int $id): View
    {
        $rubricTemplate = $this->rubricTemplateService->findById($id);
        return view('rubric-templates.edit', [
            'breadcrumbs' => $this->breadcrumbs,
            'rubricTemplate' => $rubricTemplate
        ]);
    }


    /**
     * update
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     *
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            // dd($request->all());
            $rubricTemplates = $this->rubricTemplateService->update($request->all(), $id);
            return redirect()->route('rubric_templates.index')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error!');
        }
    }
}
