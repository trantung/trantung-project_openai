<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

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
            'fields[0][grade]' => $dataRequest['gradeQuiz'],
            // 'fields[0][sumgrades]' => $dataRequest['quiz_name'],
            'fields[0][preferredbehaviour]' => $dataRequest['preferredbehaviour'],
            'fields[0][attemptimmediately]' => $dataRequest['attemptimmediatelyopen'],
            'fields[0][correctnessimmediately]' => $dataRequest['correctnessimmediatelyopen'],
            'fields[0][marksimmediately]' => $dataRequest['marksimmediatelyopen'],
            'fields[0][generalfeedbackimmediately]' => $dataRequest['generalfeedbackimmediatelyopen'],
            'fields[0][rightanswerimmediately]' => $dataRequest['rightanswerimmediatelyopen'], 
            'fields[0][completion]' => $dataRequest['completion'], 
            'fields[0][completionview]' => $dataRequest['completionview'], 
            'fields[0][completionexpected]' => $dataRequest['completionpass'], 
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


    public static function local_custom_service_create_activity_url($data)
    {
        $data = [
            'courseid' => $data['courseid'],
            'content' => $data['content'],
            'name' => $data['name'],
            'section' => $data['section'],
            'module' => 'url',
            'display' => 1,
            'visible' => 1
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_create_activity_label';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }

    public static function local_custom_service_update_url($data)
    {
        $data = [
            'courseid' => $data['courseid'],
            'content' => $data['content'],
            'name' => $data['name'],
            'section' => $data['section'],
            'module' => 'url',
            'display' => $data['display'],
            'visible' => $data['visible'],
            'coursemoduleid' => $data['coursemoduleid']
        ];
        // Gửi dữ liệu qua POST
        $method = 'POST';
        $url = getenv('URL_API_LMS') . 'local_custom_service_update_activity';
        $response = json_decode(self::execute_curl($url, $data, false, false, $method), true);
        return $response;
    }
}
