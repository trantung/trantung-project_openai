<?php

namespace App\Http\Controllers;

use App\Services\ApiEmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\RubricTemplateService;
use App\Services\EmsTypeService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Commons\Constants\CategoryValue;
use App\Services\ApiMoodleService;

class RubricTemplateController extends BaseController
{
    //
    protected $rubricTemplateService;

    protected $apiEmsService;

    protected $breadcrumbs;

    protected $apiMoodleService;

    protected $emsTypeService;

    public function __construct(
        RubricTemplateService $rubricTemplateService,
        ApiEmsService $apiEmsService,
        ApiMoodleService $apiMoodleService,
        EmsTypeService $emsTypeService)
    {
        $this->rubricTemplateService = $rubricTemplateService;
        $this->apiEmsService = $apiEmsService;
        $this->apiMoodleService = $apiMoodleService;
        $this->emsTypeService = $emsTypeService;
        $this->breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('rubric_templates.index'),
                'text' => 'Danh sách bộ mẫu điểm',
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
    public function index(Request $request)
    {
        $routeName = 'rubric_templates.index';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
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
    public function create()
    {
        $routeName = 'rubric_templates.create';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
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
    public function store(Request $request)
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
    public function edit(int $id)
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
    public function update(Request $request, int $id)
    {
        try {
            $rubricTemplates = $this->rubricTemplateService->update($request->all(), $id);
            return redirect()->route('rubric_templates.index')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error!');
        }
    }

    /**
     * delete
     *
     * @param int $id
     *
     * @return RedirectResponse
     *
     */
    public function destroy(int $id)
    {
        $id = $this->rubricTemplateService->destroy($id);
        return redirect()->route('rubric_templates.index')->with('success', 'Xóa thành công!');
    }

    /**
     * ajax get Emstypes And ApiMoodles By Rubric Template Id
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getDataInPopupRubricTemplate(Request $request)
    {
        try {
            $rubricTemplateId = $request['rubric_template_id'] ?? 0;
            $emsTypes = $this->emsTypeService->getEmstypeByRubricTemplateId($rubricTemplateId);
            $apiMooles = $this->apiMoodleService->getApiMoodlesByRubricTemplateId($rubricTemplateId, CategoryValue::MOODLE_TYPE_COURSE);
            
            // dd($apiMooles);

            $html = view('rubric-templates.popup.table', [
                'emsTypes' => $emsTypes,
                'rubricTemplateId' => $rubricTemplateId,
                'apiMooles' => $apiMooles
            ])->render();
            return $this->response($html);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->response([],Response::HTTP_INTERNAL_SERVER_ERROR, false, $e->getMessage());
        }
    }

    /**
     * ajax update RubricTemplateId In ApiEms And Api Moodles
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateDataInPopupRubricTemplate(Request $request)
    {
        try {
            $this->apiEmsService->updateRubricTemplateIdInApiEms($request->all());
            $this->apiMoodleService->updateRubricTemplateIdInApiMoodles($request->all());
            return $this->response();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->response([],Response::HTTP_INTERNAL_SERVER_ERROR, false, $e->getMessage());
        }
    }
}
