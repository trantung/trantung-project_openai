<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\ApiEs;
use App\Models\ApiMoodle;
use App\Models\ApiMoodleEms;
use App\Models\MoodleActivityFile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function responseSuccess($statusCode, $message)
    {
        return response()->json(array(
            'code' => $statusCode,
            'data' => $message,
            'message' => 'Success'
        ), 200);
    }

    public function getCurrentUser()
    {
        $user = Auth::user();

        $arr = [
            'user' => [],
        ];
        if ($user) {
            $arr['user'] = $user;
        }
        return $arr['user'];
    }

    public function index()
    {
        $routeName = 'course.index';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $products = [
            [
                "id" => 1,
                "name" => "IELTS Tutoring - Introduction",
                "type" => "folder",
                "children" => [
                    [
                        "id" => 2,
                        "name" => "IELTS Tutoring - Introduction",
                        "type" => "product"
                    ]
                ]
            ],
            [
                "id" => 3,
                "name" => "Sản phẩm 114",
                "type" => "folder",
                "children" => [
                    [
                        "id" => 4,
                        "name" => "Sản phẩm 115",
                        "type" => "folder",
                        "children" => [
                            [
                                "id" => 5,
                                "name" => "Sản phẩm 116",
                                "type" => "product"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        // dd($user = Auth::user()->email);
        // ApiMoodle::truncate();
        // ApiMoodleEms::truncate();
        // ApiEs::truncate();

        // dd(ApiMoodle::all(),ApiMoodleEms::all(),ApiEs::all());
        // dd(User::all());
        // $updateCategory = User::where('id', 1)->update([
        //     'type' => 1,
        // ]);
        // $updateCategory = User::where('id', 5)->update([
        //     'type' => 1,
        // ]);
        // dd($this->getCurrentUser()->email);
        $currentEmail = Common::getCurrentUser()->email;

        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('course.index'),
                'text' => 'Danh sách sản phẩm',
            ]
        ];

        return view('products.index', compact('products', 'currentEmail', 'breadcrumbs'));
    }

    public function detail(Request $request, $course_id)
    {
        $routeName = 'course.detail';
        $checkPermissionUser = checkPermissionUser($routeName);
        if(!$checkPermissionUser){
            return redirect()->route('dashboard');
        }
        $products = [
            [
                "id" => 2,
                "name" => "Session 1 - Unit 1 - Lesson 1: Daily life",
                "type" => "folder",
                "children" => [
                    ["id" => 3, "name" => "Pre class - Ex1", "type" => "quiz", "status" => ""],
                    ["id" => 4, "name" => "Pre class - Ex2", "type" => "quiz", "status" => ""],
                    ["id" => 5, "name" => "Lesson 1 - Key", "type" => "resource", "status" => "dimmed"],
                    ["id" => 10, "name" => "Video 6811", "type" => "video", "status" => ""],
                ]
            ],
            [
                "id" => 6,
                "name" => "Thư mục 2",
                "type" => "folder",
                "children" => [
                    [
                        "id" => 7,
                        "name" => "Thư mục 3",
                        "type" => "folder",
                        "children" => [
                            ["id" => 8, "name" => "Đề thi 15026", "type" => "quiz", "status" => ""],
                        ]
                    ],
                    ["id" => 9, "name" => "Đề thi 15027", "type" => "quiz", "status" => ""],
                ]
            ]
        ];

        $courseData = ApiMoodle::where('id', $course_id)
        ->where('moodle_type', 'course')
        ->first();

        $currentEmail = Common::getCurrentUser()->email;

        $breadcrumbs = [
            [
                'url' => route('dashboard'),
                'text' => 'Tổng quan',
            ],
            [
                'url' => route('course.detail', ['course_id' => $course_id]),
                'text' => $courseData->moodle_name,
            ],
            [
                'text' => $courseData->moodle_name,
                'url' => null
            ]
        ];

        return view('product_detail.index', compact('products', 'courseData', 'currentEmail', 'breadcrumbs'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $id = $request->input('id');

        // Nếu có searchTerm thì tìm kiếm theo searchTerm
        if (!empty($searchTerm)) {
            $data = ApiMoodle::where(function ($query) use ($searchTerm) {
                    $query->where('moodle_name', 'LIKE', '%' . $searchTerm . '%')
                        ->whereIn('moodle_type', ['category', 'course']);
                })
                ->get();
        }
        // Nếu không có searchTerm nhưng có id thì tìm kiếm theo id
        elseif (!empty($id)) {
            $data = ApiMoodle::where('id', $id)->get();
        } else {
            // Nếu không có cả searchTerm và id thì trả về mảng rỗng
            $data = [];
        }

        return response()->json($data);
    }

    public function searchProductDetail(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $id = $request->input('id');

        $parent_id = $request->input('parentId');

        // Nếu có searchTerm thì tìm kiếm theo searchTerm
        if (!empty($searchTerm) && !empty($parent_id)) {
            // Tìm tất cả các item theo parent_id và áp dụng điều kiện searchTerm
            $allItems = $this->getAllItemsByParentId($parent_id, $searchTerm);
        } elseif (!empty($searchTerm)) {
            // Chỉ tìm kiếm theo searchTerm
            $allItems = ApiMoodle::where(function ($query) use ($searchTerm) {
                    $query->where('moodle_name', 'LIKE', '%' . $searchTerm . '%')
                        ->whereNotIn('moodle_type', ['category', 'course']);
                })
                ->get();
        }
        // Nếu không có searchTerm nhưng có id thì tìm kiếm theo id
        elseif (!empty($id)) {
            $allItems = ApiMoodle::where('id', $id)->get();
        }elseif (!empty($parent_id)) {
            // Chỉ tìm tất cả các item liên kết theo parent_id
            $allItems = $this->getAllItemsByParentId($parent_id);
        } else {
            // Nếu không có cả searchTerm và id thì trả về mảng rỗng
            $allItems = [];
        }

        return response()->json($allItems);
    }

    private function getAllItemsByParentId($parent_id, $searchTerm = null)
    {
        $allItems = $this->getAllRelatedItems($parent_id);

        // Bước 2: Lấy danh sách id của tất cả các item liên quan
        $allItemIds = $allItems->pluck('id')->toArray();

        // Lấy danh sách các item con của parent_id hiện tại
        $filteredItems = ApiMoodle::whereIn('id', $allItemIds)
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('moodle_name', 'LIKE', '%' . $searchTerm . '%')
                    ->whereNotIn('moodle_type', ['category', 'course']);
            })
            ->get();

        return $filteredItems;
    }

    private function getAllRelatedItems($parent_id)
    {
        $allItems = collect();

        // Lấy danh sách các item con của parent_id hiện tại
        $items = ApiMoodle::where('parent_id', $parent_id)->get();

        foreach ($items as $item) {
            $allItems->push($item); // Thêm item hiện tại vào danh sách
            // Gọi đệ quy để lấy các item con của item hiện tại
            $childItems = $this->getAllRelatedItems($item->id);
            $allItems = $allItems->merge($childItems);
        }

        return $allItems;
    }

    public function core_course_get_courses(){
        $listCourse = Common::core_course_get_courses();

        return $this->responseSuccess(200, $listCourse);
    }

    public function core_course_get_categories(){
        $listCourse = Common::core_course_get_categories();

        return $this->responseSuccess(200, $listCourse);
    }

    public function getCategoryMoodles()
    {
        $categoryMoodles = ApiMoodle::where('moodle_type', 'category')->where('parent_id', 0)->get();

        return response()->json($categoryMoodles);
    }

    public function createCategoryMoodles(Request $request)
    {
        // ApiMoodle::truncate();
        $parent_id = 0;
        if(!empty($request->input('parent_id'))){
            $parent_id = $request->input('parent_id');
        }

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }

        $parentCategoryLMS = 0;
        if(!empty($request->input('parent_category_lms'))){
            $parentCategoryLMS = $request->input('parent_category_lms');
        }
        
        $categoryCount = ApiMoodle::where('moodle_type', 'category')->count();
        // var_dump($categoryCount);die;
        $categoryName = 'Sản phẩm ' . ($categoryCount + 1);
        $createCategoryLMS = Common::core_course_create_categories($categoryName, $parentCategoryLMS);
        
        if (isset($createCategoryLMS[0]['id']) && isset($createCategoryLMS[0]['name'])) {
            $categoryId = $createCategoryLMS[0]['id'];
            $categoryName = $createCategoryLMS[0]['name'];

            $newCategory = ApiMoodle::create([
                'moodle_id' => $categoryId,
                'moodle_name' => $categoryName,
                'moodle_type' => 'category',
                'parent_id' => $parent_id,
                'level' => $level,
                'creator' => $request->input('currentUser')
            ]);

            if ($newCategory) {
                return response()->json($newCategory);
            } else {
                return response()->json(['error' => 'Failed to save category to database']);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function updateCategoryMoodles(Request $request)
    {
        $parent_id = $parent_id_moodle = 0;
        if(!empty($request->input('parent_id'))){
            $parent_id = $request->input('parent_id');

            $parent_id_moodle = ApiMoodle::where('id', $parent_id)
            ->where('moodle_type', 'category')->value('moodle_id');
        }

        $currentCategoryIdMoodle = ApiMoodle::where('id', $request->input('id'))
        ->where('moodle_type', 'category')->value('moodle_id');

        $dataUpdateCategoryMoodles = [
            'id' => $currentCategoryIdMoodle,
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'description' => $request->input('description'),
            'parent_id' => $parent_id_moodle,
        ];
        
        $updateCategoryLMS = Common::core_course_update_categories($dataUpdateCategoryMoodles);
        
        if (empty($updateCategoryLMS)) {
            try {
                $updateCategory = ApiMoodle::where('id', $request->input('id'))->update([
                    'moodle_name' => $request->input('name'),
                    'parent_id' => $parent_id,
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateCategory) {
                    return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật sản phẩm thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function createCourseMoodles(Request $request)
    {
        // ApiMoodle::truncate();
        // var_dump($request->input('parent_id'), $request->input('category_lms'));
        $parent_id = 0;
        if(!empty($request->input('parent_id'))){
            $parent_id = $request->input('parent_id');
        }

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }

        $categoryLMS = 1;
        if(!empty($request->input('category_lms'))){
            $categoryLMS = $request->input('category_lms');
        }
        
        $courseCount = ApiMoodle::where('moodle_type', 'course')->count();
        // var_dump($courseCount);die;
        $courseName = 'Khóa học ' . ($courseCount + 1);
        $createCourseLMS = Common::core_course_create_courses($courseName, $categoryLMS, 1);
        
        if (isset($createCourseLMS[0]['id'])) {
            $courseId = $createCourseLMS[0]['id'];

            $newCourse = ApiMoodle::create([
                'moodle_id' => $courseId,
                'moodle_name' => $courseName,
                'moodle_type' => 'course',
                'parent_id' => $parent_id,
                'level' => $level,
                'creator' => $request->input('currentUser')
            ]);

            if ($newCourse) {
                return response()->json($newCourse);
            } else {
                return response()->json(['error' => 'Failed to save course to database']);
            }
        }else{
            if($createCourseLMS['message']){
                return response()->json(['error' => $createCourseLMS['message']]);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    function detailCategoryMoodles(Request $request)
    {
        $getContentCategory = ApiMoodle::where('parent_id', $request->input('parent_id'))->get();

        $dataMain = ApiMoodle::where('id', $request->input('parent_id'))
        ->where('moodle_type', 'category')->first();

        $dataCategoryMoodle = Common::core_course_get_categories($dataMain->moodle_id);

        if (!empty($dataCategoryMoodle) && isset($dataCategoryMoodle[0])) {
            $categoryMoodle = $dataCategoryMoodle[0];
            
            $dataMain->idnumber = $categoryMoodle['idnumber'] ?? null;
            $dataMain->description = $categoryMoodle['description'] ?? '';
            $dataMain->descriptionformat = $categoryMoodle['descriptionformat'] ?? 1;
            $dataMain->coursecount = $categoryMoodle['coursecount'] ?? 0;
        }

        $isSubCategory = "true";
        $selectedParentId = $dataMain->parent_id;
        if($dataMain->parent_id == 0){
            $isSubCategory = "false";
        }

        // $categoryParentData = ApiMoodle::where('parent_id', 0)->where('moodle_type', 'category')->get();

        $currentLevel = $dataMain->level;

        $categoryParentData = ApiMoodle::where('moodle_type', 'category')
            ->where('level', '<', $currentLevel) // Điều kiện level nhỏ hơn currentLevel
            ->get();

        $arr = [
            'main' => $dataMain,
            'data' => $getContentCategory,
            'isSubCategory' => $isSubCategory,
            'selectedParentId' => $selectedParentId,
            'categoryParentData' => $categoryParentData,
            'level' => $currentLevel
        ];

        return response()->json($arr);
    }

    function detailCourseMoodles(Request $request)
    {
        // Lấy nội dung khóa học từ ApiMoodle dựa trên `course_id` gửi từ request
        $getContentCourse = ApiMoodle::where('parent_id', $request->input('course_id'))->get();

        // Lấy dữ liệu chính từ ApiMoodle theo `course_id`
        $dataMain = ApiMoodle::where('id', $request->input('course_id'))
            ->where('moodle_type', 'course')->first();

        // Lấy `parent_id` từ `$dataMain`
        $selectedParentId = $dataMain->parent_id;

        // Lấy dữ liệu khóa học từ Moodle API
        $dataCourseMoodle = Common::core_course_get_courses_by_field($dataMain->moodle_id);
        // dd( $dataCourseMoodle);
        // Kiểm tra và hợp nhất các trường từ `$dataCourseMoodle` vào `$dataMain`
        if (!empty($dataCourseMoodle['courses']) && isset($dataCourseMoodle['courses'][0])) {
            $courseMoodle = $dataCourseMoodle['courses'][0];

            // Gộp các field từ `$dataCourseMoodle` vào `$dataMain`
            $dataMain->shortname = $courseMoodle['shortname'] ?? null;
            $dataMain->categoryid = $courseMoodle['categoryid'] ?? null;
            $dataMain->fullname = $courseMoodle['fullname'] ?? '';
            $dataMain->idnumber = $courseMoodle['idnumber'] ?? '';
            $dataMain->summary = $courseMoodle['summary'] ?? '';
            
            // Chuyển đổi `startdate` và `enddate` từ timestamp sang ngày tháng năm
            $dataMain->startdate = $courseMoodle['startdate'] ? date('Y-m-d H:i', $courseMoodle['startdate']) : null;
            $dataMain->enddate = $courseMoodle['enddate'] ? date('Y-m-d H:i', $courseMoodle['enddate']) : null;

            $dataMain->visible = $courseMoodle['visible'] ?? 1;
            $dataMain->enablecompletion = $courseMoodle['enablecompletion'] ?? 0;
            $dataMain->format = $courseMoodle['format'] ?? 'topics';
            $dataMain->numsections = $courseMoodle['numsections'] ?? 0;
            $dataMain->lang = $courseMoodle['lang'] ?? '';

            // Xử lý `overviewfiles` và loại bỏ "webservice/" khỏi `fileurl`
            $overviewFiles = $courseMoodle['overviewfiles'] ?? [];
            $processedFiles = [];

            foreach ($overviewFiles as $file) {
                // Xóa "webservice/" khỏi `fileurl`
                $file['fileurl'] = str_replace('webservice/', '', $file['fileurl']);
                $processedFiles[] = $file;
            }

            // Gán `overviewfiles` vào `$dataMain`
            $dataMain->overviewfiles = $processedFiles;

            // Nếu cần lấy thêm dữ liệu từ `courseformatoptions`, bạn có thể làm như sau:
            $courseFormatOptions = $courseMoodle['courseformatoptions'] ?? [];
            foreach ($courseFormatOptions as $option) {
                if ($option['name'] === 'hiddensections') {
                    $dataMain->hiddensections = $option['value'];
                }
                if ($option['name'] === 'coursedisplay') {
                    $dataMain->coursedisplay = $option['value'];
                }
            }
        }

        // Lấy thông tin category từ ApiMoodle
        $categoryData = ApiMoodle::where('moodle_type', 'category')->get();

        // Chuẩn bị dữ liệu trả về
        $arr = [
            'main' => $dataMain,
            'data' => $getContentCourse,
            'selectedParentId' => $selectedParentId,
            'categoryData' => $categoryData
        ];

        // Trả về kết quả dạng JSON
        return response()->json($arr);
    }

    public function createSectionCourse(Request $request)
    {
        // ApiMoodle::truncate();
        $couseId = ApiMoodle::where('id', $request->input('couseId'))->value('moodle_id');
        
        $sectionCount = ApiMoodle::where('moodle_type', 'section')->where('parent_id', $request->input('couseId'))->count();
        $sectionName = 'Topic ' . ($sectionCount + 1);
        $createSectionCourse = Common::local_custom_service_create_sections($couseId, 1);

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }
        
        if (isset($createSectionCourse[0]['sectionid'])) {
            $sectionId = $createSectionCourse[0]['sectionid'];

            $newSection = ApiMoodle::create([
                'moodle_id' => $sectionId,
                'moodle_name' => $sectionName,
                'moodle_type' => 'section',
                'parent_id' => $request->input('couseId'),
                'creator' => $request->input('currentUser'),
                'level' => $level
            ]);

            if ($newSection) {
                return response()->json($newSection);
            } else {
                return response()->json(['error' => 'Failed to save category to database']);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function updateSectionCourse(Request $request)
    {
        $currentSectionDataMoodle = ApiMoodle::where('id', $request->input('id'))
        ->where('moodle_type', 'section')->first();

        $course_id = ApiMoodle::where('id', $currentSectionDataMoodle->parent_id)
        ->where('moodle_type', 'course')->value('moodle_id');

        $section_id = $currentSectionDataMoodle->moodle_id;
        $section_name = $request->input('section_name');
        $section_status = $request->input('section_status');
        $section_summary = $request->input('section_summary');

        $sectionDetail = $this->getSectionById($section_id, $course_id);

        $sectionnum = $sectionDetail['sectionnum'];
        
        $updateSectionLMS = Common::local_custom_service_update_sections($sectionnum, $section_name, $section_summary, $section_status, $course_id);
        
        if (empty($updateSectionLMS['warnings'])) {
            try {
                $updateSection = ApiMoodle::where('id', $request->input('id'))->update([
                    'moodle_name' => $request->input('section_name'),
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateSection) {
                    return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật sản phẩm thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }else{
            return response()->json(['error' => $updateSectionLMS['warnings'][0]['message']]);
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function getSectionCourse(Request $request) {
        $sectionCourse = ApiMoodle::where('parent_id', $request->input('course_id'))
                                  ->where('moodle_type', 'section')
                                  ->get();
    
        foreach ($sectionCourse as $section) {
            // Lấy thông tin chi tiết của section
            $course_id = ApiMoodle::where('id', $request->input('course_id'))->value('moodle_id');
            $sectionDetail = $this->getSectionById($section->moodle_id, $course_id);
            if ($sectionDetail) {
                // Các trường cần thêm
                $fieldsToMerge = ['sectionnum', 'summary', 'summaryformat', 'visible', 'uservisible', 'availability', 'sequence', 'courseformat'];
    
                // Kiểm tra và thêm từng trường
                foreach ($fieldsToMerge as $field) {
                    // Nếu trường tồn tại trong sectionDetail, thêm vào section
                    $section->{$field} = $sectionDetail[$field] ?? null;
                }
            }
        }
        return response()->json($sectionCourse);
    }

    function detailSectionCourse(Request $request)
    {
        // Lấy danh sách module của section từ DB
        $getContentSections = ApiMoodle::where('parent_id', $request->input('section_id'))->get();

        $dataMain = ApiMoodle::where('id', $request->input('section_id'))
            ->where('moodle_type', 'section')->first();
    
        // Lấy parent_id và course_id
        $parent_id = $dataMain->parent_id;
        $course_id = ApiMoodle::where('id', $parent_id)->value('moodle_id');

        $sectionDetail = $this->getSectionById($dataMain->moodle_id, $course_id);

        if ($sectionDetail) {
            // Danh sách các trường muốn thêm từ `$sectionDetail` vào `$dataMain`
            $fieldsToMerge = [
                'sectionnum', 
                'summary', 
                'summaryformat', 
                'visible', 
                'uservisible', 
                'availability', 
                'sequence', 
                'courseformat'
            ];
    
            // Thêm các trường từ `$sectionDetail` vào `$dataMain`
            foreach ($fieldsToMerge as $field) {
                if (isset($sectionDetail[$field])) {
                    $dataMain->{$field} = $sectionDetail[$field];
                }
            }
        }
    
        // Lấy dữ liệu section từ Moodle API
        $sectionData = Common::core_course_get_contents($course_id);
    
        // Duyệt qua từng phần tử của $getContentSections
        foreach ($getContentSections as &$moodleItem) {
            // Đặt giá trị mặc định cho availability
            $moodleItem->availabilityinfo = null;
    
            // Duyệt qua các section trong $sectionData
            foreach ($sectionData as $section) {
                // Kiểm tra nếu section có các module
                if (!empty($section['modules'])) {
                    foreach ($section['modules'] as $module) {
                        // So khớp module ID với moodle_id từ $getContentSections
                        if ($module['id'] == $moodleItem->moodle_id) {
                            // Gán giá trị availability nếu tồn tại
                            $moodleItem->availabilityinfo = $module['availabilityinfo'] ?? null;
                            $moodleItem->visible = $module['visible'];
                        }
                    }
                }
            }
        }

        $arr = [
            'main' => $dataMain,
            'data' => $getContentSections
        ];

        // Trả về kết quả JSON
        return response()->json($arr);
    }

    public function createActivityMoodles(Request $request)
    {
        if ($request->input('type') == 'quiz') {
            // Trả về kết quả từ hàm `createActivityQuiz`
            return $this->createActivityQuiz($request);
        }

        if ($request->input('type') == 'url') {
            // Trả về kết quả từ hàm `createActivityQuiz`
            return $this->createActivityUrl($request);
        }

        if ($request->input('type') == 'resource') {
            // Trả về kết quả từ hàm `createActivityQuiz`
            return $this->createActivityResource($request);
        }

        if ($request->input('type') == 'assign') {
            // Trả về kết quả từ hàm `createActivityQuiz`
            return $this->createActivityAssign($request);
        }

        // Nếu không phải 'quiz', trả về thông báo lỗi
        return response()->json(['error' => 'Invalid activity type'], 400);
    }

    public function updateActivityMoodles(Request $request)
    {
        if ($request->input('type') == 'quiz') {
            return $this->updateActivityQuiz($request);
        }

        if ($request->input('type') == 'url') {
            return $this->updateActivityUrl($request);
        }

        if ($request->input('type') == 'resource') {
            return $this->updateActivityResource($request);
        }

        if ($request->input('type') == 'assign') {
            return $this->updateActivityAssign($request);
        }

        // Nếu không phải 'quiz', trả về thông báo lỗi
        return response()->json(['error' => 'Invalid activity type'], 400);
    }

    public function updateActivityQuiz(Request $request)
    {
        $updateQuizMoodle = Common::local_custom_service_update_activity_quiz($request);
        if (isset($updateQuizMoodle['quizid'])) {
            try {
                $updateCourse = ApiMoodle::where('id', $request->input('activity_id'))->where('moodle_type', 'quiz')->update([
                    'moodle_name' => $request->input('quiz_name'),
                    'parent_id' => $request->input('quiz_section'),
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateCourse) {
                    return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật sản phẩm thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function updateActivityUrl(Request $request)
    {
        $updateUrlMoodle = Common::local_custom_service_update_activity_url($request);
        if (isset($updateUrlMoodle['urlid'])) {
            try {
                $updateCourse = ApiMoodle::where('id', $request->input('activity_id'))->where('moodle_type', 'url')->update([
                    'moodle_name' => $request->input('url_name'),
                    'parent_id' => $request->input('url_section'),
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateCourse) {
                    return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật sản phẩm thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function updateActivityResource(Request $request)
    {
        $updateResourceMoodle = Common::local_custom_service_update_activity_resource($request);
        if (isset($updateResourceMoodle['cmid'])) {
            try {
                $updateCourse = ApiMoodle::where('id', $request->input('activity_id'))->where('moodle_type', 'resource')->update([
                    'moodle_name' => $request->input('resource_name'),
                    'parent_id' => $request->input('resource_section'),
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateCourse) {
                    $dataUploaded = [
                        'cmid' => $updateResourceMoodle['cmid'],
                        'component' => 'mod_resource',
                        'filearea' => 'content',
                    ];
                    $uploadedFiles = [];

                    $oldResources = MoodleActivityFile::where('activity_id', $request->input('activity_id'))->get();

                    // Xóa các file cũ trong Storage
                    foreach ($oldResources as $resource) {
                        $filePath = storage_path('app/' . $resource->file_path);
                        if (file_exists($filePath)) {
                            unlink($filePath); // Xóa file khỏi storage
                        }
                    }

                    // Xóa bản ghi cũ trong cơ sở dữ liệu
                    MoodleActivityFile::where('activity_id', $request->input('activity_id'))->delete();

                    if ($request->hasFile('files')) {
                        $files = $request->file('files');
                        $index = 0;
                        foreach ($files as $file) {
                            $fileNameUpload = $file->getClientOriginalName();
                            $fileContent = base64_encode(file_get_contents($file->getPathname()));
            
                            $dataUploaded["files[$index][filename]"] = $fileNameUpload;
                            $dataUploaded["files[$index][filecontent]"] = $fileContent;
                            $index++;

                            $filePath = $file->storeAs('public/activity/resource/activity_'.$request->input('activity_id'), $fileNameUpload);

                            $resource = new MoodleActivityFile();
                            $resource->activity_id = $request->input('activity_id');
                            $resource->moodle_id = $updateResourceMoodle['cmid'];
                            $resource->moodle_type = 'resource';
                            $resource->file_name = $file->getClientOriginalName();
                            $resource->file_path = $filePath;
                            $resource->file_type = $file->getMimeType();
                            $resource->file_size = $file->getSize();
                            $resource->uploaded_by = $request->input('currentUser');
                            $resource->save();

                            $uploadedFiles[] = $resource;
                        }

                        $uploadResourceFileMoodle = Common::uploadFileActivityResource($dataUploaded);
                        if($uploadResourceFileMoodle['status']){
                            return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                        }else{
                            return response()->json(['error' => 'Upload file thất bại']);
                        }
                    }
        
                    return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật sản phẩm thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }
        return response()->json(['error' => 'Không tìm thấy tệp nào được tải lên.']);
    }

    public function updateActivityAssign(Request $request)
    {
        $updateAssignMoodle = Common::local_custom_service_update_activity_assign($request);
        if (isset($updateAssignMoodle['assignid'])) {
            try {
                $updateCourse = ApiMoodle::where('id', $request->input('activity_id'))->where('moodle_type', 'assign')->update([
                    'moodle_name' => $request->input('assign_name'),
                    'parent_id' => $request->input('assign_section'),
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateCourse) {
                    $dataUploaded = [
                        'cmid' => $updateAssignMoodle['cmid'],
                        'component' => 'mod_assign',
                        'filearea' => 'introattachment',
                    ];
                    $uploadedFiles = [];

                    $oldResources = MoodleActivityFile::where('activity_id', $request->input('activity_id'))->get();

                    // Xóa các file cũ trong Storage
                    foreach ($oldResources as $resource) {
                        $filePath = storage_path('app/' . $resource->file_path);
                        if (file_exists($filePath)) {
                            unlink($filePath); // Xóa file khỏi storage
                        }
                    }

                    // Xóa bản ghi cũ trong cơ sở dữ liệu
                    MoodleActivityFile::where('activity_id', $request->input('activity_id'))->delete();

                    if ($request->hasFile('files')) {
                        $files = $request->file('files');
                        $index = 0;
                        foreach ($files as $file) {
                            $fileNameUpload = $file->getClientOriginalName();
                            $fileContent = base64_encode(file_get_contents($file->getPathname()));
            
                            $dataUploaded["files[$index][filename]"] = $fileNameUpload;
                            $dataUploaded["files[$index][filecontent]"] = $fileContent;
                            $index++;

                            $filePath = $file->storeAs('public/activity/assign/activity_'.$request->input('activity_id'), $fileNameUpload);

                            $resource = new MoodleActivityFile();
                            $resource->activity_id = $request->input('activity_id');
                            $resource->moodle_id = $updateAssignMoodle['cmid'];
                            $resource->moodle_type = 'assign';
                            $resource->file_name = $file->getClientOriginalName();
                            $resource->file_path = $filePath;
                            $resource->file_type = $file->getMimeType();
                            $resource->file_size = $file->getSize();
                            $resource->uploaded_by = $request->input('currentUser');
                            $resource->save();

                            $uploadedFiles[] = $resource;
                        }

                        $uploadResourceFileMoodle = Common::uploadFileActivityResource($dataUploaded);
                        if($uploadResourceFileMoodle['status']){
                            return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                        }else{
                            return response()->json(['error' => 'Upload file thất bại']);
                        }
                    }
        
                    return response()->json(['success' => 'Cập nhật sản phẩm thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật sản phẩm thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }
    }

    function detailActivityMoodles(Request $request)
    {
        // Lấy dữ liệu `dataMain` từ DB
        $dataMain = ApiMoodle::where('id', $request->input('activity_id'))
            ->where('moodle_type', $request->input('activity_type'))->first();

        // Lấy `sectionId` và `courseId`
        $sectionId = ApiMoodle::where('id', $dataMain->parent_id)
            ->where('moodle_type', 'section')->value('parent_id');

        $courseData = ApiMoodle::where('id', $sectionId)
            ->where('moodle_type', 'course')->first();

        // Lấy nội dung module từ API
        $moduleContent = Common::core_course_get_course_module($dataMain->moodle_id);

        $selectedParentId = $dataMain->parent_id;

        $sectionData = ApiMoodle::where('parent_id', $courseData->id)
            ->where('moodle_type', 'section')->get();

        $str_availability_cmid = '';

        $currentDataActivityMoodle = Common::core_course_get_course_module($dataMain->moodle_id);

        if(!empty($currentDataActivityMoodle['cm']['availability'])){

            $availability = json_decode($currentDataActivityMoodle['cm']['availability'], true);

            if (isset($availability['c']) && is_array($availability['c'])) {
                $cmids = [];
        
                foreach ($availability['c'] as $completion) {
                    if ($completion['type'] == 'completion') {
                        $cmids[] = $completion['cm'];
                    }
                }
        
                $str_availability_cmid = implode(',', $cmids);
            }
        }

        // Kiểm tra và gán các field từ `$dataMain` vào `$moduleContent['cm']`
        if (isset($moduleContent['cm'])) {
            $moduleContent['cm']['main_id'] = $dataMain->id;
            $moduleContent['cm']['moodle_id'] = $dataMain->moodle_id;
            $moduleContent['cm']['moodle_name'] = $dataMain->moodle_name;
            $moduleContent['cm']['moodle_type'] = $dataMain->moodle_type;
            $moduleContent['cm']['created_at'] = $dataMain->created_at;
            $moduleContent['cm']['updated_at'] = $dataMain->updated_at;
            $moduleContent['cm']['parent_id'] = $dataMain->parent_id;
            $moduleContent['cm']['creator'] = $dataMain->creator;
            $moduleContent['cm']['modifier'] = $dataMain->modifier;

            $detail = Common::local_custom_service_get_detail_module($dataMain->moodle_id, $request->input('activity_type'));
            
            $detailData = json_decode($detail['data'], true);
            if($request->input('activity_type') == 'url'){
                $moduleContent['cm']['intro'] = $detailData['intro'];
                $moduleContent['cm']['introformat'] = $detailData['introformat'];
                $moduleContent['cm']['externalurl'] = $detailData['externalurl'];
                $moduleContent['cm']['display'] = $detailData['display'];
            }

            if($request->input('activity_type') == 'assign'){
                $moduleContent['cm']['intro'] = $detailData['intro'];
                $moduleContent['cm']['introformat'] = $detailData['introformat'];
                $moduleContent['cm']['completionsubmit'] = $detailData['completionsubmit'];

                $pluginConfigs = $detail['plugin_config'];
                foreach ($pluginConfigs as $config) {
                    if($config['plugin'] == 'onlinetext' && $config['name'] == 'enabled' && $config['subtype'] == 'assignsubmission'){
                        $fieldKey = $config['plugin'];
                        $moduleContent['cm'][$fieldKey] = $config['value'];
                    }

                    if($config['plugin'] == 'file' && $config['name'] == 'enabled' && $config['subtype'] == 'assignsubmission'){
                        $fieldKey = $config['plugin'];
                        $moduleContent['cm'][$fieldKey] = $config['value'];
                    }

                    if($config['plugin'] == 'file' && $config['name'] == 'filetypeslist' && $config['subtype'] == 'assignsubmission'){
                        $fieldKey = $config['name'];
                        $moduleContent['cm'][$fieldKey] = $config['value'];
                    }
                }

                $assigns = MoodleActivityFile::where('activity_id', $request->input('activity_id'))->get();

                $filesData = [];

                foreach ($assigns as $assign) {
                    // Đường dẫn file
                    $path = storage_path('app/' . $assign->file_path);

                    if (!file_exists($path)) {
                        continue; // Bỏ qua file nếu không tồn tại
                    }

                    // Đọc nội dung file và mã hóa base64
                    $fileContent = base64_encode(file_get_contents($path));
                    $mimeType = mime_content_type($path);

                    // Tạo cấu trúc file
                    $filesData[] = [
                        'name' => $assign->file_name,
                        'size' => round(filesize($path) / 1024, 2) . ' KB',
                        'type' => $mimeType,
                        'url' => 'data:' . $mimeType . ';base64,' . $fileContent,
                        'date' => $assign->created_at->format('Y-m-d H:i:s')
                    ];
                }

                $moduleContent['cm']['filesuploaded'][] = $filesData;
            }

            if($request->input('activity_type') == 'quiz'){
                $moduleContent['cm']['attempts'] = $detailData['attempts'];
                $moduleContent['cm']['preferredbehaviour'] = $detailData['preferredbehaviour'];
                $moduleContent['cm']['shuffleanswers'] = $detailData['shuffleanswers'];
                $moduleContent['cm']['grademethod'] = $detailData['grademethod'];
                $moduleContent['cm']['reviewattempt'] = $detailData['reviewattempt'];
                $moduleContent['cm']['reviewcorrectness'] = $detailData['reviewcorrectness'];
                $moduleContent['cm']['reviewmarks'] = $detailData['reviewmarks'];
                $moduleContent['cm']['reviewmaxmarks'] = $detailData['reviewmaxmarks'];
                $moduleContent['cm']['reviewrightanswer'] = $detailData['reviewrightanswer'];
                $moduleContent['cm']['reviewspecificfeedback'] = $detailData['reviewspecificfeedback'];
                $moduleContent['cm']['reviewgeneralfeedback'] = $detailData['reviewgeneralfeedback'];
                $moduleContent['cm']['reviewoverallfeedback'] = $detailData['reviewoverallfeedback'];
            }

            if($request->input('activity_type') == 'resource'){
                $resources = MoodleActivityFile::where('activity_id', $request->input('activity_id'))->get();

                // if ($resources->isEmpty()) {
                //     return response()->json(['error' => 'Không có file nào liên quan đến activity này.']);
                // }

                $filesData = [];

                foreach ($resources as $resource) {
                    // Đường dẫn file
                    $path = storage_path('app/' . $resource->file_path);

                    if (!file_exists($path)) {
                        continue; // Bỏ qua file nếu không tồn tại
                    }

                    // Đọc nội dung file và mã hóa base64
                    $fileContent = base64_encode(file_get_contents($path));
                    $mimeType = mime_content_type($path);

                    // Tạo cấu trúc file
                    $filesData[] = [
                        'name' => $resource->file_name,
                        'size' => round(filesize($path) / 1024, 2) . ' KB',
                        'type' => $mimeType,
                        'url' => 'data:' . $mimeType . ';base64,' . $fileContent,
                        'date' => $resource->created_at->format('Y-m-d H:i:s')
                    ];
                }

                $moduleContent['cm']['filesuploaded'][] = $filesData;
            }
        }

        $getCourseContent = Common::core_course_get_contents($courseData->moodle_id);

        $moduleContent['cm']['customdata'] = '';
    
        // Duyệt qua các section trong $sectionData
        foreach ($getCourseContent as $content) {
            // Kiểm tra nếu section có các module
            if (!empty($content['modules'])) {
                foreach ($content['modules'] as $module) {
                    // So khớp module ID với moodle_id từ $getContentSections
                    if ($module['id'] == $dataMain->moodle_id) {
                        // Gán giá trị availability nếu tồn tại
                        $moduleContent['cm']['customdata'] = $module['customdata'] ?? '';
                    }
                }
            }
        }

        // Kết quả cuối cùng
        $arr = [
            'main' => $dataMain,
            'data' => $moduleContent,
            'sectionData' => $sectionData,
            'selectedParentId' => $selectedParentId,
            'str_availability_cmid' => $str_availability_cmid
        ];

        // Trả về kết quả JSON
        return response()->json($arr);
    }

    public function getSectionById($section_id, $course_id)
    {
        $sectionData = Common::local_custom_service_get_sections($course_id);

        // Mảng lưu trữ kết quả
        $arr = [
            'sections' => [],
        ];

        if (!isset($sectionData['errorcode'])) {
            foreach ($sectionData as $section) {
                if (isset($section['id']) && $section['id'] == $section_id) {
                    $arr['sections'] = $section;
                    break;
                }
            }
        }

        // Trả về mảng kết quả thay vì JsonResponse
        return $arr['sections'];
    }
    
    public function createActivityQuiz($request)
    {
        $parent_id = $request->input('parent_id') ?? 0;
        $section_id = $request->input('section_id');
        $course_id = ApiMoodle::where('id', $request->input('course_id'))->value('moodle_id');

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }

        $sections = ApiMoodle::where('moodle_type', 'section')
            ->where('parent_id', $request->input('course_id'))
            ->pluck('id');

        // Đếm số lượng `quiz` có `parent_id` nằm trong danh sách `sections`
        $quizCount = ApiMoodle::where('moodle_type', 'quiz')
            ->whereIn('parent_id', $sections)
            ->count();

        // $quizCount = ApiMoodle::where('moodle_type', 'quiz')->count();
        $quizName = 'Quiz ' . ($quizCount + 1);

        $questionName = $request->input('questionName') ?? $quizName;
        $questionType = $request->input('questionType') ?? '';
        $questionId = $request->input('questionId') ?? 0;

        // Gọi hàm `getSectionById` để lấy dữ liệu section
        $sectionData = $this->getSectionById($section_id, $course_id);
        
        // $completioncmid = null;
        // if($quizCount != 0){
        //     $completioncmid = -1;
        // }

        $createactivityQuiz = Common::local_custom_service_create_activity_quiz($course_id, $questionName, $sectionData['sectionnum']);

        if (isset($createactivityQuiz['cmid'])) {
            $quizId = $createactivityQuiz['cmid'];
            
            // Tạo mới Quiz trong Moodle
            $newQuiz = ApiMoodle::create([
                'moodle_id' => $quizId,
                'moodle_name' => $questionName,
                'moodle_type' => 'quiz',
                'parent_id' => $parent_id,
                'creator' => $request->input('currentUser'),
                'level' => $level
            ]);
        
            if (!$newQuiz) {
                return response()->json(['error' => 'Failed to create quiz in Moodle']);
            }
        
            // Tạo mới Quiz trong ElasticSearch
            $newQuizEs = ApiEs::create([
                'es_id' => $questionId,
                'es_name' => $questionName,
                'es_type' => $questionType
            ]);
        
            if (!$newQuizEs) {
                return response()->json(['error' => 'Failed to create quiz in Es']);
            }
        
            // Tạo mối quan hệ giữa Moodle Quiz và ElasticSearch Quiz
            $newMoodleEms = ApiMoodleEms::create([
                'api_moodle_id' => $newQuiz->id,
                'api_system_id' => $newQuizEs->id,
                'api_system_name' => 'Es'
            ]);
        
            if (!$newMoodleEms) {
                return response()->json(['error' => 'Failed to link Moodle and Es']);
            }
        
            // Nếu tất cả các bước trên thành công, trả về dữ liệu Quiz vừa tạo
            return response()->json([
                'quiz' => $newQuiz,
                'quiz_es' => $newQuizEs,
                'moodle_ems' => $newMoodleEms
            ]);
        } else {
            return response()->json(['error' => 'Invalid cmid']);
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function createActivityUrl($request)
    {
        $parent_id = $request->input('parent_id') ?? 0;
        $section_id = $request->input('section_id');
        $course_id = ApiMoodle::where('id', $request->input('course_id'))->value('moodle_id');

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }

        $sections = ApiMoodle::where('moodle_type', 'section')
            ->where('parent_id', $request->input('course_id'))
            ->pluck('id');

        // Đếm số lượng `url` có `parent_id` nằm trong danh sách `sections`
        $qurlCount = ApiMoodle::where('moodle_type', 'url')
            ->whereIn('parent_id', $sections)
            ->count();

        $urlName = 'Url ' . ($qurlCount + 1);

        $sectionData = $this->getSectionById($section_id, $course_id);

        $data = [
            'courseid' => $course_id,
            'content' => 'https://',
            'name' => $urlName,
            'section' => $sectionData['sectionnum'],
            'module' => 'url',
            'display' => 1,
            'visible' => 1
        ];

        $createactivityUrl = Common::local_custom_service_create_activity($data);

        if (isset($createactivityUrl['cmid'])) {
            $urlId = $createactivityUrl['cmid'];
            
            // Tạo mới Quiz trong Moodle
            $newUrl = ApiMoodle::create([
                'moodle_id' => $urlId,
                'moodle_name' => $urlName,
                'moodle_type' => 'url',
                'parent_id' => $parent_id,
                'creator' => $request->input('currentUser'),
                'level' => $level
            ]);
        
            if (!$newUrl) {
                return response()->json(['error' => 'Failed to create quiz in Moodle']);
            }
        
            return response()->json($newUrl);
        } else {
            return response()->json(['error' => 'Invalid cmid']);
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function createActivityResource($request)
    {
        $parent_id = $request->input('parent_id') ?? 0;
        $section_id = $request->input('section_id');
        $course_id = ApiMoodle::where('id', $request->input('course_id'))->value('moodle_id');

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }

        $sections = ApiMoodle::where('moodle_type', 'section')
            ->where('parent_id', $request->input('course_id'))
            ->pluck('id');

        // Đếm số lượng `url` có `parent_id` nằm trong danh sách `sections`
        $qurlCount = ApiMoodle::where('moodle_type', 'resource')
            ->whereIn('parent_id', $sections)
            ->count();

        $resourceName = 'File ' . ($qurlCount + 1);

        $sectionData = $this->getSectionById($section_id, $course_id);

        $data = [
            'courseid' => $course_id,
            'content' => '',
            'name' => $resourceName,
            'section' => $sectionData['sectionnum'],
            'module' => 'resource',
            'display' => 1,
            'visible' => 1
        ];

        $createactivityResource = Common::local_custom_service_create_activity($data);

        if (isset($createactivityResource['cmid'])) {
            $resourceId = $createactivityResource['cmid'];
            
            // Tạo mới Quiz trong Moodle
            $newUrl = ApiMoodle::create([
                'moodle_id' => $resourceId,
                'moodle_name' => $resourceName,
                'moodle_type' => 'resource',
                'parent_id' => $parent_id,
                'creator' => $request->input('currentUser'),
                'level' => $level
            ]);
        
            if (!$newUrl) {
                return response()->json(['error' => 'Failed to create quiz in Moodle']);
            }
        
            return response()->json($newUrl);
        } else {
            return response()->json(['error' => 'Invalid cmid']);
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function createActivityAssign($request)
    {
        $parent_id = $request->input('parent_id') ?? 0;
        $section_id = $request->input('section_id');
        $course_id = ApiMoodle::where('id', $request->input('course_id'))->value('moodle_id');

        $level = 0;
        if(!empty($request->input('level'))){
            $level = $request->input('level');
        }

        $sections = ApiMoodle::where('moodle_type', 'section')
            ->where('parent_id', $request->input('course_id'))
            ->pluck('id');

        // Đếm số lượng `url` có `parent_id` nằm trong danh sách `sections`
        $qurlCount = ApiMoodle::where('moodle_type', 'assign')
            ->whereIn('parent_id', $sections)
            ->count();

        $assignName = 'Bài tập ' . ($qurlCount + 1);

        $sectionData = $this->getSectionById($section_id, $course_id);

        $data = [
            'courseid' => $course_id,
            'content' => '',
            'name' => $assignName,
            'section' => $sectionData['sectionnum'],
            'module' => 'assign',
            'display' => 1,
            'visible' => 1
        ];

        $createactivityAssign = Common::local_custom_service_create_activity($data);

        if (isset($createactivityAssign['cmid'])) {
            $assignId = $createactivityAssign['cmid'];
            
            // Tạo mới Quiz trong Moodle
            $newUrl = ApiMoodle::create([
                'moodle_id' => $assignId,
                'moodle_name' => $assignName,
                'moodle_type' => 'assign',
                'parent_id' => $parent_id,
                'creator' => $request->input('currentUser'),
                'level' => $level
            ]);
        
            if (!$newUrl) {
                return response()->json(['error' => 'Failed to create quiz in Moodle']);
            }
        
            return response()->json($newUrl);
        } else {
            return response()->json(['error' => 'Invalid cmid']);
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function convertDateToBigInt($dateTime){
        $date = new \DateTime($dateTime);
        $timestamp = $date->getTimestamp();
        return $timestamp;
    }

    public function updateCourseMoodles(Request $request)
    {
        // dd($request->input('parent_id'));
        $parent_id = $parent_id_moodle = 0;
        if(!empty($request->input('parent_id'))){
            $parent_id = $request->input('parent_id');

            $category_id_moodle = ApiMoodle::where('id', $parent_id)
            ->where('moodle_type', 'category')->value('moodle_id');
        }else{
            $parent_id = ApiMoodle::where('id', $request->input('id'))
            ->where('moodle_type', 'course')->value('parent_id');

            $category_id_moodle = ApiMoodle::where('id', $parent_id)
            ->where('moodle_type', 'category')->value('moodle_id');
        }

        $currentCourseIdMoodle = ApiMoodle::where('id', $request->input('id'))
        ->where('moodle_type', 'course')->value('moodle_id');

        $dataUpdateCourseMoodles = [
            'id' => $currentCourseIdMoodle,
            'fullname' => $request->input('fullname'),
            'shortname' => $request->input('shortname'),
            'categoryid' => $category_id_moodle,
            'idnumber' => $request->input('idNumber'),
            'summary' => $request->input('summary'),
            'visible' => $request->input('visible'),
            'startdate' => self::convertDateToBigInt($request->input('startdate')),
            'enddate' => self::convertDateToBigInt($request->input('enddate')),
            'lang' => $request->input('lang'),
            'format' => $request->input('format'),
            'maxbytes' => $request->input('maxbytes')
        ];
        
        $updateCourseLMS = Common::core_course_update_courses($dataUpdateCourseMoodles);
        // var_dump(empty($updateCourseLMS['warnings']));
        if (empty($updateCourseLMS['warnings'])) {
            try {
                $updateCourse = ApiMoodle::where('id', $request->input('id'))->update([
                    'moodle_name' => $request->input('fullname'),
                    'parent_id' => $parent_id,
                    'modifier' => $request->input('currentUser')
                ]);
            
                if ($updateCourse) {
                    $files = $request->file('courseImage');
                    if ($files && $files->isValid()) {
                        $filenameUpload = $files->getClientOriginalName();

                        $filecontent = base64_encode(file_get_contents($files->getPathname()));

                        $uploadImageCourse = Common::uploadImageCourse($currentCourseIdMoodle, $filecontent, $filenameUpload);
                        // if (isset($uploadImageCourse['url'])) {
                        //     $new_url = str_replace('/overviewfiles/0/', '/overviewfiles/', $uploadImageCourse['url']);
                        //     $dataInsert['course_image'] = $new_url;
                        // }
                    }
                    return response()->json(['success' => 'Cập nhật khóa học thành công']);
                } else {
                    return response()->json(['error' => 'Cập nhật khóa học thất bại']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS']);
    }

    public function deleteCategoryMoodles(Request $request)
    {
        // Lấy sản phẩm chính (category) theo ID
        $id = (int) $request->input('id');
        $data = ApiMoodle::where('id', $id)->first();
        // dd($request->input('id'), $data);
        if (!$data) {
            return response()->json(['error' => 'Sản phẩm không tồn tại']);
        }

        // Đệ quy xóa tất cả sản phẩm con của sản phẩm chính
        $this->deleteRelatedItems($data->id);

        // Cuối cùng xóa sản phẩm chính (category)
        $data->delete();
        
        Common::core_course_delete_categories($data->moodle_id);

        // Trả về phản hồi thành công
        return response()->json(['success' => 'Xóa sản phẩm và dữ liệu liên quan thành công!']);
    }

    public function deleteCourseMoodles(Request $request)
    {
        // Lấy sản phẩm chính (category) theo ID
        $id = (int) $request->input('id');
        $data = ApiMoodle::where('id', $id)->first();
        // dd($request->input('id'), $data);
        if (!$data) {
            return response()->json(['error' => 'Sản phẩm không tồn tại']);
        }

        // Đệ quy xóa tất cả sản phẩm con của sản phẩm chính
        $this->deleteRelatedItems($data->id);

        // Cuối cùng xóa sản phẩm chính (category)
        $data->delete();
        
        Common::core_course_delete_courses($data->moodle_id);

        // Trả về phản hồi thành công
        return response()->json(['success' => 'Xóa sản phẩm và dữ liệu liên quan thành công!']);
    }

    // Hàm đệ quy để xóa tất cả sản phẩm con
    private function deleteRelatedItems($parentId)
    {
        // Lấy tất cả các sản phẩm con của sản phẩm này
        $relatedItems = ApiMoodle::where('parent_id', $parentId)->get();

        // Nếu không có sản phẩm con, thoát khỏi hàm
        if ($relatedItems->isEmpty()) {
            return;
        }

        // Duyệt qua tất cả sản phẩm con
        foreach ($relatedItems as $item) {
            // Xóa sản phẩm con mà không cần phân biệt 'moodle_type'
            $item->delete();  // Hoặc nếu sử dụng SoftDelete: $item->delete();
            
            // Đệ quy gọi lại hàm để xóa các sản phẩm con của sản phẩm con
            $this->deleteRelatedItems($item->id);  // Xóa các sản phẩm con của sản phẩm con
        }
    }

    public function listContestIelts()
    {
        $products = [
            "status" => true,
            "data" => [
                "name" => "VTP IELTS EXAM TEST 7",
                "contest_type" => 3,
                "maxNumAttempt" => 0,
                "timeStart" => null,
                "timeEnd" => null,
                "idMockContest" => 107,
                "rounds" => [
                    [
                        "name" => "Listening",
                        "listBaikiemtra" => [
                            [
                                "name" => "VTP IELTS LISTENING TEST 7",
                                "timeAllow" => 2400,
                                "maxMark" => 10,
                                "testFormat" => 13,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-06-01T10:44:00.000Z",
                                "timeEnd" => "2030-01-01T10:45:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10252
                            ]
                        ],
                        "type" => 6
                    ],
                    [
                        "name" => "Reading",
                        "listBaikiemtra" => [
                            [
                                "name" => "VTP IELTS READING TEST 7",
                                "timeAllow" => 3600,
                                "maxMark" => 10,
                                "testFormat" => 14,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-06-01T10:02:00.000Z",
                                "timeEnd" => "2030-01-01T10:04:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10256
                            ]
                        ],
                        "type" => 7
                    ],
                    [
                        "name" => "Writing",
                        "listBaikiemtra" => [
                            [
                                "name" => "VTP IELTS WRITING TEST 7",
                                "timeAllow" => 3600,
                                "maxMark" => 10,
                                "testFormat" => 15,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-06-01T10:12:00.000Z",
                                "timeEnd" => "2030-01-01T10:13:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10257
                            ]
                        ],
                        "type" => 8
                    ],
                    [
                        "name" => "Speaking",
                        "listBaikiemtra" => [
                            [
                                "name" => "VTP IELTS SPEAKING TEST 7",
                                "timeAllow" => 1080,
                                "maxMark" => 16,
                                "testFormat" => 16,
                                "maxNumAttempt" => 0,
                                "timeStart" => "2023-06-01T10:18:00.000Z",
                                "timeEnd" => "2030-01-01T10:18:00.000Z",
                                "timeSubmit" => null,
                                "resultReturnType" => 3,
                                "idBaikiemtra" => 10258
                            ]
                        ],
                        "type" => 9
                    ]
                ]
            ]
        ];

        return response()->json($products);
    }

    public function detailAvailabilityMoodles(Request $request)
    {
        $section_id = $request->input('section_id');
        $activity_id = $request->input('activity_id');

        $course_id = ApiMoodle::where('id', $section_id)
            ->where('moodle_type', 'section')
            ->value('parent_id');

        $sectionData = ApiMoodle::where('parent_id', $course_id)
            ->where('moodle_type', 'section')
            ->get();

        $course_id_moodle = ApiMoodle::where('id', $course_id)
            ->where('moodle_type', 'course')
            ->first();

        $currentDataActivity = ApiMoodle::where('id', $activity_id)
            ->whereNotIn('moodle_type', ['course', 'category', 'section'])
            ->first();

        $array_csr_info = [];

        $str_availability_cmid = '';

        $currentDataActivityMoodle = Common::core_course_get_course_module($currentDataActivity->moodle_id);

        if(!empty($currentDataActivityMoodle['cm']['availability'])){
            $availability = json_decode($currentDataActivityMoodle['cm']['availability'], true);

            if (isset($availability['c']) && is_array($availability['c'])) {
                $cmids = [];
        
                foreach ($availability['c'] as $completion) {
                    if ($completion['type'] == 'completion') {
                        $cmids[] = $completion['cm'];
                    }
                }
        
                $str_availability_cmid = implode(',', $cmids);
            }
        }

        foreach ($sectionData as $section) {
            $sectionDataMoodle = $this->getSectionById($section->moodle_id, $course_id_moodle->moodle_id);

            if ($sectionDataMoodle) {
                $fieldsToMerge = [
                    'sectionnum',
                    'summary',
                    'summaryformat',
                    'visible',
                    'uservisible',
                    'availability',
                    'sequence',
                    'courseformat'
                ];

                foreach ($fieldsToMerge as $field) {
                    if (isset($sectionDataMoodle[$field])) {
                        $section->{$field} = $sectionDataMoodle[$field];
                    }
                }

                $section->level = 1;

                $array_csr_info[] = $section;

                $activities = $this->getActivitiesBySectionId($section->id, $activity_id);
                if (!empty($activities)) {
                    foreach ($activities as $activity) {
                        $activityDetail = $activity;

                        $activityDetail['level'] = 2;
                        $activityDetail['section_id'] = $section->id;

                        $array_csr_info[] = $activityDetail;
                    }
                }
            }
        }

        $arr = [
            'array_csr_info' => $array_csr_info,
            'str_availability_cmid' => $str_availability_cmid,
        ];

        return response()->json($arr);
    }


    private function getActivitiesBySectionId($section_id, $activity_id)
    {
        $activityData = ApiMoodle::where('parent_id', $section_id)
        ->whereNotIn('moodle_type', ['course', 'category', 'section'])
        ->get();

        $arr = [];

        foreach($activityData as $activity){
            if($activity->id != $activity_id){
                $moduleContent = Common::core_course_get_course_module($activity->moodle_id);
                if (isset($moduleContent['cm'])) {
                    $moduleContent['cm']['main_id'] = $activity->id;
                    $moduleContent['cm']['moodle_id'] = $activity->moodle_id;
                    $moduleContent['cm']['moodle_name'] = $activity->moodle_name;
                    $moduleContent['cm']['moodle_type'] = $activity->moodle_type;
                    $moduleContent['cm']['created_at'] = $activity->created_at;
                    $moduleContent['cm']['updated_at'] = $activity->updated_at;
                    $moduleContent['cm']['parent_id'] = $activity->parent_id;
                    $moduleContent['cm']['creator'] = $activity->creator;
                    $moduleContent['cm']['modifier'] = $activity->modifier;
    
                    $arr[] = $moduleContent['cm'];
                }
            }
        }
        return $arr;
    }

    public function deleteSection(Request $request)
    {
        $id = $request->input('id');
        $section_id = $request->input('section_id');
        $course_id = $request->input('course_id');
        if (!$id) {
            return response()->json(['message' => 'ID is required'], 400);
        }

        $deleteSection = Common::core_course_delete_sections($course_id, $section_id);

        if(!empty($deleteSection)){
            ApiMoodle::where('id', $id)->delete();

            // Xóa các bản ghi con (có parent_id = $id)
            ApiMoodle::where('parent_id', $id)->delete();
    
            return response()->json(['message' => 'Section deleted successfully'], 200);
        }
        return response()->json(['error' => 'Failed to delete section on Moodle'], 500);
    }

    public function deleteModule(Request $request)
    {
        $cmid = $request->input('cmid');
        $parent_id = $request->input('parent_id');
        $module_id = ApiMoodle::where('moodle_id', $cmid)->value('id');
        // var_dump($module_id);die;
        if (!$module_id) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $deleteModule = Common::core_course_delete_modules($cmid);
        if($deleteModule == null){
            ApiMoodle::where('id', $module_id)->delete();
            return response()->json(['success' => 'Module deleted successfully']);
        }

        return response()->json(['error' => 'Failed to delete module on Moodle'], 500);
    }

    public function listCourseHocmai(){
        $listCourse = ApiMoodle::where('moodle_type', 'course')->pluck('moodle_name', 'id')->toArray();

        return $this->responseSuccess(200, $listCourse);
    }
}
