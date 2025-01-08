<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use \OpenAI;
use Illuminate\Http\Request;
use App\Models\TestOpenai;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Jobs\DemoJob;
use App\Jobs\Task1Job;
use App\Models\ApiUserQuestion;
use App\Models\ApiUserQuestionPart;
use App\Models\Common;
use App\Models\Task1Image;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Element\Comment;

class AiVideoController extends Controller
{
    public function checkParamCms($jsonData, $fields)
    {
        foreach($fields as $value) {
            if(empty($jsonData[$value])) {
                return false;
            }
        }
        return true;
    }

    public function responseSuccess($statusCode, $message)
    {
        return response()->json(array(
            'code' => $statusCode,
            'data' => $message,
            'message' => 'Success'
        ), 200);
    }

    public function aiVideoImport(Request $request)
    {
        $res = Common::aiVideoImport($request);
        if(empty($request['token']) || $request['token'] != getenv('TOKEN_TEST_AI_SUBTITLE')) {
            dd('sai token');
        }
        if($res['type'] == 1) {
            $chat = $res['data'];
            $dataResponseChat = $chat->choices[0]->message->content;
            // dd($dataResponseChat);
            $dataResponseChat = json_decode($dataResponseChat, true);
            return $this->responseSuccess(200, ['answer' => $dataResponseChat]);
        }
        return $this->responseSuccess(200, ['json_content_file' => $res['data']]);
    }
}