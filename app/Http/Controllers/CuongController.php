<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\ApiEs;
use App\Models\ApiMoodle;
use App\Models\ApiMoodleEms;
use App\Models\MoodleActivityFile;
use Illuminate\Support\Facades\Auth;

class CuongController extends Controller
{
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
}
