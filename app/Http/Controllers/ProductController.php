<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\ApiEs;
use App\Models\ApiMoodle;
use App\Models\ApiMoodleEms;
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

        // dd($this->getCurrentUser()->email);
        $currentEmail = $this->getCurrentUser()->email;

        return view('products.index', compact('products', 'currentEmail'));
    }

    public function detail(Request $request, $course_id)
    {
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

        $currentEmail = $this->getCurrentUser()->email;

        return view('product_detail.index', compact('products', 'courseData', 'currentEmail'));
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
                'creator' => $request->input('currentUser')
            ]);

            if ($newCategory) {
                return response()->json($newCategory);
            } else {
                return response()->json(['error' => 'Failed to save category to database'], 500);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS'], 500);
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
                'creator' => $request->input('currentUser')
            ]);

            if ($newCourse) {
                return response()->json($newCourse);
            } else {
                return response()->json(['error' => 'Failed to save course to database'], 500);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS'], 500);
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

        $categoryParentData = ApiMoodle::where('parent_id', 0)->where('moodle_type', 'category')->get();

        $arr = [
            'main' => $dataMain,
            'data' => $getContentCategory,
            'isSubCategory' => $isSubCategory,
            'selectedParentId' => $selectedParentId,
            'categoryParentData' => $categoryParentData
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
        
        if (isset($createSectionCourse[0]['sectionid'])) {
            $sectionId = $createSectionCourse[0]['sectionid'];

            $newSection = ApiMoodle::create([
                'moodle_id' => $sectionId,
                'moodle_name' => $sectionName,
                'moodle_type' => 'section',
                'parent_id' => $request->input('couseId'),
                'creator' => $request->input('currentUser')
            ]);

            if ($newSection) {
                return response()->json($newSection);
            } else {
                return response()->json(['error' => 'Failed to save category to database'], 500);
            }
        }

        return response()->json(['error' => 'Invalid data received from LMS'], 500);
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

    public function getSectionCourse(Request $request){
        // ApiMoodle::truncate();
        $sectionCourse = ApiMoodle::where('parent_id', $request->input('course_id'))->where('moodle_type', 'section')->get();
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

        // Nếu không phải 'quiz', trả về thông báo lỗi
        return response()->json(['error' => 'Invalid activity type'], 400);
    }

    public function updateActivityMoodles(Request $request)
    {
        if ($request->input('type') == 'quiz') {
            // Trả về kết quả từ hàm `createActivityQuiz`
            return $this->updateActivityQuiz($request);
        }

        if ($request->input('type') == 'url') {
            // Trả về kết quả từ hàm `createActivityQuiz`
            return $this->createActivityUrl($request);
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
                'creator' => $request->input('currentUser')
            ]);
        
            if (!$newQuiz) {
                return response()->json(['error' => 'Failed to create quiz in Moodle'], 500);
            }
        
            // Tạo mới Quiz trong ElasticSearch
            $newQuizEs = ApiEs::create([
                'es_id' => $questionId,
                'es_name' => $questionName,
                'es_type' => $questionType
            ]);
        
            if (!$newQuizEs) {
                return response()->json(['error' => 'Failed to create quiz in ElasticSearch'], 500);
            }
        
            // Tạo mối quan hệ giữa Moodle Quiz và ElasticSearch Quiz
            $newMoodleEms = ApiMoodleEms::create([
                'api_moodle_id' => $newQuiz->id,
                'api_system_id' => $newQuizEs->id,
                'api_system_name' => 'Es'
            ]);
        
            if (!$newMoodleEms) {
                return response()->json(['error' => 'Failed to link Moodle and ElasticSearch'], 500);
            }
        
            // Nếu tất cả các bước trên thành công, trả về dữ liệu Quiz vừa tạo
            return response()->json([
                'quiz' => $newQuiz,
                'quiz_es' => $newQuizEs,
                'moodle_ems' => $newMoodleEms
            ]);
        } else {
            return response()->json(['error' => 'Invalid cmid'], 400);
        }

        return response()->json(['error' => 'Invalid data received from LMS'], 500);
    }

    public function createActivityUrl($request)
    {
        $parent_id = $request->input('parent_id') ?? 0;
        $section_id = $request->input('section_id');
        $course_id = ApiMoodle::where('id', $request->input('course_id'))->value('moodle_id');

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

        $createactivityUrl = Common::local_custom_service_create_activity_url($data);

        if (isset($createactivityUrl['cmid'])) {
            $urlId = $createactivityUrl['cmid'];
            
            // Tạo mới Quiz trong Moodle
            $newUrl = ApiMoodle::create([
                'moodle_id' => $urlId,
                'moodle_name' => $urlName,
                'moodle_type' => 'url',
                'parent_id' => $parent_id,
                'creator' => $request->input('currentUser')
            ]);
        
            if (!$newUrl) {
                return response()->json(['error' => 'Failed to create quiz in Moodle'], 500);
            }
        
            return response()->json($newUrl);
        } else {
            return response()->json(['error' => 'Invalid cmid'], 400);
        }

        return response()->json(['error' => 'Invalid data received from LMS'], 500);
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
}
