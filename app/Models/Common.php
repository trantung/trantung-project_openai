<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Common extends Model
{
    //LMS
    public static function execute_curl($url, $data = false, $contentType = false, $token = false, $method)
    {
        $ch = curl_init();
        $headers = array();
        if ($contentType) {
            $headers[] = $contentType;
        }
        if ($token) {
            $headers[] = $token;
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    public static function core_course_get_courses()
    {
        $method = 'GET';
        $contentType = "Content-Type: application/json";
        $url = getenv('URL_API_LMS') . 'core_course_get_courses';
        return json_decode(self::execute_curl($url, false, $contentType, false, $method), true);
    }


    public static function core_course_create_courses($courseName, $courseCategory, $courseVisible)
    {
        $data = [
            'courses[0][fullname]' => $courseName,
            'courses[0][shortname]' => $courseName,
            'courses[0][categoryid]' => $courseCategory,
            // 'courses[0][summary]' => $courseSummary, // Nội dung CKEditor đã được mã hóa
            'courses[0][visible]' => $courseVisible,
            // 'courses[0][format]' => $courseFormat,
            'courses[0][enablecompletion]' => 1, 
        ];

        // Thêm courseformatoptions nếu có
        // if (!empty($courseFormatOptions)) {
        //     foreach ($courseFormatOptions as $index => $option) {
        //         $data["courses[0][courseformatoptions][$index][name]"] = $option['name'];
        //         $data["courses[0][courseformatoptions][$index][value]"] = $option['value'];
        //     }
        // }

        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'core_course_create_courses';
        // var_dump(self::execute_curl($url, $data, false, false, $method));die;
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_create_activity_quiz($courseId, $name, $section)
    {
        $name = str_replace(' ', '%20', $name);
        $url = getenv('URL_API_LMS') . 'local_custom_service_create_activity_quiz' . '&courseid=' . $courseId . '&name=' . $name . '&section=' . $section;
        // if(!empty($completioncmid)){
        //     $url .= '&completioncmid=' . $completioncmid;
        // }
        $method = 'POST';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_update_activity_quiz($dataRequest)
    {
        $quizName = str_replace(' ', '%20', $dataRequest['quiz_name']);

        $sectionIdMoodle = ApiMoodle::where('id', $dataRequest['quiz_section'])->where('moodle_type', 'section')->first();

        $activityIdMoodle = ApiMoodle::where('id', $dataRequest['activity_id'])->where('moodle_type', 'quiz')->first();

        $courseIdMoodle = ApiMoodle::where('id', $dataRequest['course_id'])->where('moodle_type', 'course')->first();

        $sectionData = self::local_custom_service_get_sections($courseIdMoodle->moodle_id);

        $arr = [
            'sections' => [],
        ];

        if (!isset($sectionData['errorcode'])) {
            foreach ($sectionData as $section) {
                if (isset($section['id']) && $section['id'] == $sectionIdMoodle->moodle_id) {
                    $arr['sections'] = $section;
                    break;
                }
            }
        }

        // $cmid = 108;
        // $fields[0][name] = 'tesst quiz 11';
        // $fields[0][intro] = 'desc';
        // $fields[0][timelimit] = 0;
        // $fields[0][timeopen] = 0;
        // $fields[0][timeclose] = 0;
        // $fields[0][attempts] = 2;
        // $fields[0][grademethod] = 1;
        // $fields[0][shufflequestions] = 0;
        // $fields[0][questionsperpage] = 1;
        // $fields[0][section] = 1;
        // $fields[0][visible] = 1;
        // $fields[0][grade] = 10;
        // $fields[0][sumgrades] = 10;
        // $fields[0][preferredbehaviour] = 'adaptive';
        // $fields[0][attemptimmediately] = 1;
        // $fields[0][correctnessimmediately] = 1;
        // $fields[0][marksimmediately] = 1;
        // $fields[0][generalfeedbackimmediately] = 1;
        // $fields[0][rightanswerimmediately] = 1;
        // $fields[0][availability][completioncmid][0] = 110;
        // $fields[0][availability][completioncmid][1] = 114;
        // $fields[0][completion] = 2;
        // $fields[0][completionview] = 1;
        // $fields[0][completionexpected] = 1731812580;
        $completionview = $dataRequest['completionview'];
        $completionpassgrade = $dataRequest['completionpass'];
        if(empty($dataRequest['completion'])){
            $completionView = 0;
            $completionpassgrade = 0;
        }

        $data = [
            'cmid' => $activityIdMoodle->moodle_id,
            'fields[0][name]' => $dataRequest['quiz_name'],
            // 'fields[0][intro]' => $dataRequest['quiz_name'],
            // 'fields[0][timelimit]' => $dataRequest['quiz_name'],
            // 'fields[0][timeopen]' => $dataRequest['quiz_name'],
            // 'fields[0][timeclose]' => $dataRequest['quiz_name'],
            'fields[0][attempts]' => $dataRequest['attempts'],
            'fields[0][grademethod]' => $dataRequest['grademethod'],
            'fields[0][shufflequestions]' => $dataRequest['shuffleanswers'],
            // 'fields[0][questionsperpage]' => $dataRequest['quiz_name'],
            'fields[0][section]' => $arr['sections']['sectionnum'],
            'fields[0][visible]' => $dataRequest['quiz_visible'],
            'fields[0][grade]' => intval($dataRequest['gradeQuiz']),
            'fields[0][gradepass]' => intval($dataRequest['gradePass']),
            // 'fields[0][sumgrades]' => $dataRequest['quiz_name'],
            'fields[0][preferredbehaviour]' => $dataRequest['preferredbehaviour'],
            'fields[0][attemptimmediately]' => $dataRequest['attemptimmediatelyopen'],
            'fields[0][correctnessimmediately]' => $dataRequest['correctnessimmediatelyopen'],
            'fields[0][marksimmediately]' => $dataRequest['marksimmediatelyopen'],
            'fields[0][generalfeedbackimmediately]' => $dataRequest['generalfeedbackimmediatelyopen'],
            'fields[0][rightanswerimmediately]' => $dataRequest['rightanswerimmediatelyopen'], 
            'fields[0][completion]' => $dataRequest['completion'], 
            'fields[0][completionview]' => $completionview, 
            // 'fields[0][completionexpected]' => $dataRequest['completionpass'], 
            'fields[0][completionpassgrade]' => $completionpassgrade,
        ];

        if (!empty($dataRequest['availability_item'])) {
            $availability_item = explode(',', $dataRequest['availability_item']);  // Tách chuỗi thành mảng
            $index = 0;  // Chỉ mục cho các phần tử availability_item
        
            foreach ($availability_item as $item) {
                $data["fields[0][availability][completioncmid][$index]"] = $item;
                $index++;
            }
        }

        // dd($data);
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_update_activity_quiz';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function convertDateToBigInt($dateTime){
        $date = new \DateTime($dateTime);
        $timestamp = $date->getTimestamp();
        return $timestamp;
    }

    public static function local_custom_service_update_activity_url($dataRequest)
    {
        $sectionIdMoodle = ApiMoodle::where('id', $dataRequest['url_section'])->where('moodle_type', 'section')->first();

        $activityIdMoodle = ApiMoodle::where('id', $dataRequest['activity_id'])->where('moodle_type', 'url')->first();

        $courseIdMoodle = ApiMoodle::where('id', $dataRequest['course_id'])->where('moodle_type', 'course')->first();

        $sectionData = self::local_custom_service_get_sections($courseIdMoodle->moodle_id);
        
        $arr = [
            'sections' => [],
        ];

        if (!isset($sectionData['errorcode'])) {
            foreach ($sectionData as $section) {
                if (isset($section['id']) && $section['id'] == $sectionIdMoodle->moodle_id) {
                    $arr['sections'] = $section;
                    break;
                }
            }
        }

        $completionexpected = 0;

        if($dataRequest['completionexpected'] == 1){
            $completionexpected = self::convertDateToBigInt($dataRequest['completionexpected_date'] . $dataRequest['completionexpected_starthour'] . $dataRequest['completionexpected_startminute']);
        }

        $completionview = $dataRequest['completionview'];
        if(empty($dataRequest['completion'])){
            $completionview = 0;
        }

        $data = [
            'cmid' => $activityIdMoodle->moodle_id,
            'fields[0][name]' => $dataRequest['url_name'],
            'fields[0][intro]' => $dataRequest['url_intro'],
            'fields[0][section]' => $arr['sections']['sectionnum'],
            'fields[0][visible]' => $dataRequest['url_visible'],
            'fields[0][completion]' => $dataRequest['completion'],
            'fields[0][completionview]' => $completionview,
            'fields[0][completionexpected]' => $completionexpected,
            'fields[0][introformat]' => 1,
            'fields[0][externalurl]' => $dataRequest['externalurl'],
            'fields[0][display]' => $dataRequest['url_display'],
            'fields[0][showdescription]' => $dataRequest['url_printintro'],
        ];

        if (!empty($dataRequest['availability_item'])) {
            $availability_item = explode(',', $dataRequest['availability_item']);  // Tách chuỗi thành mảng
            $index = 0;  // Chỉ mục cho các phần tử availability_item
        
            foreach ($availability_item as $item) {
                $data["fields[0][availability][completioncmid][$index]"] = $item;
                $index++;
            }
        }

        // dd($data);

        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_update_activity_url';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function core_course_get_contents($courseId)
    {
        $url = getenv('URL_API_LMS') . 'core_course_get_contents' . '&courseid=' . $courseId;
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }
        
    public static function local_custom_service_get_sections($courseId)
    {
        $url = getenv('URL_API_LMS') . 'local_custom_service_get_sections' . '&courseid=' . $courseId;
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function core_course_get_course_module($cmid)
    {
        $url = getenv('URL_API_LMS') . 'core_course_get_course_module' . '&cmid=' . $cmid;
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_update_sections($sectionNum, $name, $summary, $visible, $courseid)
    {
        // $summary = htmlspecialchars($summary, ENT_QUOTES, 'UTF-8');
        $summary = base64_encode($summary);
        $dataJson = [
            'courseid' => (int)$courseid,
            'sections' => [
                [
                    'type' => 'num',
                    'section' => (int)$sectionNum,
                    'name' => $name,
                    'summary' => $summary,
                    'visible' => (int)$visible
                ]
            ]
        ];
        $jsonEncoded = json_encode($dataJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $data = base64_encode($jsonEncoded);
        $url = getenv('URL_API_LMS') . 'local_custom_service_update_sections' . '&jsonRequest=' . $data;
        $method = 'POST';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function getCourseMoodleById($courseId)
    {
        $url = getenv('URL_API_LMS') . 'core_course_get_courses' . '&options[ids][0]=' . $courseId;
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function core_course_get_courses_by_field($courseId)
    {
        $url = getenv('URL_API_LMS') . 'core_course_get_courses_by_field' . '&field=id&value=' . $courseId;
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function core_course_update_courses($data)
    {
        // $data = [
        //     'courses[0][id]' => $data['course_id'],
        //     'courses[0][fullname]' => $data['fullname'],
        //     'courses[0][shortname]' => $data['fullname'],
        //     'courses[0][categoryid]' => $data['categoryid'],
        //     'courses[0][summary]' => $data['summary'],
        //     'courses[0][visible]' => $data['visible'],
        //     'courses[0][format]' => $data['courseFormat'],
        // ];
        $filteredData = []; // Mảng lưu các giá trị có dữ liệu để gửi đi
    
        if (!empty($data['id'])) {
            $filteredData['courses[0][id]'] = $data['id'];
        }
        if (!empty($data['fullname'])) {
            $filteredData['courses[0][fullname]'] = $data['fullname'];
        }
        if (!empty($data['shortname'])) {
            $filteredData['courses[0][shortname]'] = $data['shortname'];
        }
        if (!empty($data['categoryid'])) {
            $filteredData['courses[0][categoryid]'] = $data['categoryid'];
        }
        if (!empty($data['idnumber'])) {
            $filteredData['courses[0][idnumber]'] = $data['idnumber'];
        }
        if (!empty($data['summary'])) {
            $filteredData['courses[0][summary]'] = $data['summary'];
        }
        if (!empty($data['visible'])) {
            $filteredData['courses[0][visible]'] = $data['visible'];
        }
        if (!empty($data['startdate'])) {
            $filteredData['courses[0][startdate]'] = $data['startdate'];
        }
        if (!empty($data['enddate'])) {
            $filteredData['courses[0][enddate]'] = $data['enddate'];
        }
        if (!empty($data['lang'])) {
            $filteredData['courses[0][lang]'] = $data['lang'];
        }
        if (!empty($data['format'])) {
            $filteredData['courses[0][format]'] = $data['format'];
        }
        if (!empty($data['maxbytes'])) {
            $filteredData['courses[0][maxbytes]'] = $data['maxbytes'];
        }

        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'core_course_update_courses';
        $response = json_decode(self::execute_curl($url, $filteredData, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_create_sections($courseId, $number)
    {
        $url = getenv('URL_API_LMS') . 'local_custom_service_create_sections' . '&courseid=' . $courseId . '&position=0&number=' . $number;
        $method = 'POST';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function uploadImageCourse($courseid, $filecontent, $filename){
        $dataJson =  [
            'courseid' => $courseid,
            'filecontent' => $filecontent,
            'filename' => $filename,
        ];
    
        $method = 'POST';
        $contentType = "content-type: multipart/form-data";
        $url = getenv('URL_API_LMS') . 'local_custom_service_add_image_course';
        return json_decode(self::execute_curl($url, $dataJson, $contentType, false, $method),true);
    }

    public static function local_custom_service_update_activity_resource($dataRequest)
    {
        $sectionIdMoodle = ApiMoodle::where('id', $dataRequest['resource_section'])->where('moodle_type', 'section')->first();

        $activityIdMoodle = ApiMoodle::where('id', $dataRequest['activity_id'])->where('moodle_type', 'resource')->first();

        $courseIdMoodle = ApiMoodle::where('id', $dataRequest['course_id'])->where('moodle_type', 'course')->first();

        $sectionData = self::local_custom_service_get_sections($courseIdMoodle->moodle_id);
        
        $arr = [
            'sections' => [],
        ];

        if (!isset($sectionData['errorcode'])) {
            foreach ($sectionData as $section) {
                if (isset($section['id']) && $section['id'] == $sectionIdMoodle->moodle_id) {
                    $arr['sections'] = $section;
                    break;
                }
            }
        }

        $completionview = $dataRequest['completionview'];
        if(empty($dataRequest['completion'])){
            $completionview = 0;
        }

        $data = [
            'cmid' => $activityIdMoodle->moodle_id,
            'fields[0][name]' => $dataRequest['resource_name'],
            'fields[0][intro]' => $dataRequest['resource_intro'],
            'fields[0][section]' => $arr['sections']['sectionnum'],
            'fields[0][visible]' => $dataRequest['resource_visible'],
            'fields[0][completion]' => $dataRequest['completion'],
            'fields[0][completionview]' => $completionview,
            'fields[0][completionexpected]' => 0,
            'fields[0][introformat]' => 1,
            'fields[0][display]' => 0,
            'fields[0][showdescription]' => 0,
        ];

        if (!empty($dataRequest['availability_item'])) {
            $availability_item = explode(',', $dataRequest['availability_item']);  // Tách chuỗi thành mảng
            $index = 0;  // Chỉ mục cho các phần tử availability_item
        
            foreach ($availability_item as $item) {
                $data["fields[0][availability][completioncmid][$index]"] = $item;
                $index++;
            }
        }

        // $dataUploaded = [
        //     'cmid' => $activityIdMoodle->moodle_id,
        // ];

        // if ($dataRequest->hasFile('files')) {
        //     $files = $dataRequest->file('files');
        //     $index = 0;
        //     foreach ($files as $file) {
        //         $fileNameUpload = $file->getClientOriginalName();
        //         $fileContent = base64_encode(file_get_contents($file->getPathname()));

        //         $dataUploaded["files[$index][filename]"] = $fileNameUpload;
        //         $dataUploaded["files[$index][filecontent]"] = $fileContent;
        //         $index++;
        //     }
        // }

        // $upload = self::uploadFileActivityResource($dataUploaded);
        
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_update_activity_resource';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_update_activity_assign($dataRequest)
    {
        $sectionIdMoodle = ApiMoodle::where('id', $dataRequest['assign_section'])->where('moodle_type', 'section')->first();

        $activityIdMoodle = ApiMoodle::where('id', $dataRequest['activity_id'])->where('moodle_type', 'assign')->first();

        $courseIdMoodle = ApiMoodle::where('id', $dataRequest['course_id'])->where('moodle_type', 'course')->first();

        $sectionData = self::local_custom_service_get_sections($courseIdMoodle->moodle_id);
        
        $arr = [
            'sections' => [],
        ];

        if (!isset($sectionData['errorcode'])) {
            foreach ($sectionData as $section) {
                if (isset($section['id']) && $section['id'] == $sectionIdMoodle->moodle_id) {
                    $arr['sections'] = $section;
                    break;
                }
            }
        }

        $completionview = $dataRequest['completionview'];
        $completionsubmit = $dataRequest['completionsubmit'];
        $completionpassgrade = $dataRequest['completionusegrade'];

        if(empty($dataRequest['completion'])){
            $completionview = 0;
            $completionsubmit = 0;
            $completionpassgrade = 0;
        }

        $data = [
            'cmid' => $activityIdMoodle->moodle_id,
            'fields[0][name]' => $dataRequest['assign_name'],
            'fields[0][intro]' => $dataRequest['assign_intro'],
            'fields[0][section]' => $arr['sections']['sectionnum'],
            'fields[0][visible]' => intval($dataRequest['assign_visible']),
            'fields[0][completion]' => intval($dataRequest['completion']),
            'fields[0][completionview]' => intval($completionview),
            'fields[0][completionsubmit]' => intval($completionsubmit),
            'fields[0][introformat]' => 1,
            'fields[0][display]' => 0,
            'fields[0][showdescription]' => 0,
            'fields[0][grade]' => intval($dataRequest['assign_grade']),
            'fields[0][gradepass]' => intval($dataRequest['gradePass']),
            'fields[0][completionpassgrade]' => intval($completionpassgrade),
            'fields[0][assignsubmissiononlinetextenabled]' => intval($dataRequest['assignsubmission_onlinetext']),
            'fields[0][assignsubmissionfileenabled]' => intval($dataRequest['assignsubmission_file']),
            'fields[0][assignsubmissionfilefiletypes]' => $dataRequest['assignsubmission_file_filetypes'],
            'fields[0][courseid]' => $courseIdMoodle->moodle_id,
        ];

        if (!empty($dataRequest['availability_item'])) {
            $availability_item = explode(',', $dataRequest['availability_item']);  // Tách chuỗi thành mảng
            $index = 0;  // Chỉ mục cho các phần tử availability_item
        
            foreach ($availability_item as $item) {
                $data["fields[0][availability][completioncmid][$index]"] = $item;
                $index++;
            }
        }
        // dd($data);
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_update_activity_assign';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function uploadFileActivityResource($dataJson){
        $method = 'POST';
        $contentType = "content-type: multipart/form-data";
        $url = getenv('URL_API_LMS') . 'local_custom_service_add_file_resource';
        return json_decode(self::execute_curl($url, $dataJson, $contentType, false, $method),true);
    }

    public static function core_course_create_categories($categoriName, $categoriParent = 0)
    {
        $url = getenv('URL_API_LMS') . 'core_course_create_categories';
        $params = "categories[0][name]=".$categoriName."&categories[0][parent]=".$categoriParent;
        $method = 'POST';
        $contentType = 'Content-Type: application/x-www-form-urlencoded';
        // var_dump(self::execute_curl($url, $params, $contentType, false, $method));die;
        $response = json_decode(self::execute_curl($url, $params, $contentType, false, $method), true);
        return $response;
    }

    public static function core_course_get_categories($categoriID = null, $showSubCategory = 0) // showSub: 1
    {
        $url = getenv('URL_API_LMS') . 'core_course_get_categories';
        if(!empty($categoriID)){
            $url .= '&criteria[0][key]=id&criteria[0][value]=' . $categoriID . '&addsubcategories=' . $showSubCategory;
        }
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function core_course_update_categories($data)
    {
        $data = [
            'categories[0][id]' => $data['id'],
            'categories[0][name]' => $data['name'],
            'categories[0][idnumber]' => $data['code'],
            'categories[0][parent]' => $data['parent_id'],
            'categories[0][description]' => $data['description'],
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'core_course_update_categories';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function core_course_delete_categories($categoryId)
    {
        $data = [
            'categories[0][id]' => $categoryId,
            'categories[0][recursive]' => 1, // 1 xóa tất cả nội dung bên trong, 0 là di chuyển nội dung sang cate mới, nếu là 0 cần categories[0][newparent]
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'core_course_delete_categories';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function core_course_delete_courses($courseId)
    {
        $data = [
            'courseids[0]' => $courseId,
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'core_course_delete_courses';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }


    public static function local_custom_service_create_activity($data)
    {
        $data = [
            'courseid' => $data['courseid'],
            'content' => $data['content'],
            'name' => $data['name'],
            'section' => $data['section'],
            'module' => $data['module'],
            'display' => 1,
            'visible' => 1
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_create_activity_label';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_get_detail_module($cmid, $module)
    {
        $url = getenv('URL_API_LMS') . 'local_custom_service_get_detail_module&cmid='.$cmid.'&modulename='.$module;
        $method = 'GET';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    
    public static function local_custom_service_get_uploaded_files($cmid, $component, $filearea)
    {
        $url = getenv('URL_API_LMS') . 'local_custom_service_get_uploaded_files' . '&cmid=' . $cmid . '&filearea='.$filearea.'&component=' . $component;
        $method = 'POST';
        $response = json_decode(self::execute_curl($url, false, false, false, $method), true);
        return $response;
    }

    public static function core_course_delete_sections($courseId, $sectionId)
    {
        $data = [
            'courseid' => $courseId,
            'coursesectionids[0]' => $sectionId
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_delete_sections';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function core_course_delete_modules($cmid)
    {
        $data = [
            'cmids[0]' => $cmid
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'core_course_delete_modules';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function getCurrentUser()
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
}
