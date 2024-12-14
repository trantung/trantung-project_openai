<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use \OpenAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApiUserQuestion;
use App\Models\ApiUserQuestionPart;
use App\Models\CommonHocmai;
use Illuminate\Support\Facades\Log;
use App\Jobs\DemoJob;
use App\Jobs\Task1Job;

class ApiController extends Controller
{
    public function checkParams($jsonData, $fields)
    {
        if(empty($jsonData['token']) || $jsonData['token'] != 'tunglaso1') {
            return false;
        }
        foreach($fields as $value) {
            if(empty($jsonData[$value])) {
                return false;
            }
        }
        return true;
    }

    public function getDataFromRequest($request)
    {
        return $request->json()->all();
    }

    public function responseSuccess($statusCode, $message)
    {
        return response()->json(array(
            'code' => $statusCode,
            'data' => $message,
            'message' => 'Success'
        ), 200);
    }

    //test hocmai
    //vocabulary_grammar
    public function hocmaiTask1VocabularyGramma(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiTask1VocabularyGramma($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        // $dataResponseChat = json_decode($dataResponseChat,true);
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $this->responseSuccess(200, $res);
    }
    //task_achiement
    public function hocmaiTask1TaskAchiement(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiTask1BandTaskAchiement($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $this->responseSuccess(200, $res);
    }
    //coherence_cohesion
    public function hocmaiTask1CoherenceCohesion(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiTask1BandCoherenceCohesion($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $this->responseSuccess(200, $res);
    }
    //lexical_resource
    public function hocmaiTask1LexicalResource(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiTask1BandLexicalResource($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];

        return $this->responseSuccess(200, $res);
    }

    //grammatical_range_accuracy
    public function hocmaiTask1GrammaRange(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiTask1BandGrammaRange($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];

        return $this->responseSuccess(200, $res);
    }
    
    public function task1All(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $checkToken = $this->checkParams($request, ['username', 'user_id', 'sample','report']);
        if(!$checkToken) {
            return $this->responseSuccess(403, 'Token invalid');
        }
        $question = $jsonData['report'];
        $topic = $jsonData['sample'];
        $jsonData['report'] = strtr( $jsonData['report'], array(  "\n" => "\\n",  "\r" => "\\r"  ));
        $jsonData['sample'] = strtr( $jsonData['sample'], array(  "\n" => "\\n",  "\r" => "\\r"  ));
        DB::beginTransaction();
        try {
            // Insert v�o b?ng ApiUserQuestion
            $data = [
                'question' => $jsonData['report'],
                'topic' => $jsonData['sample'],
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'writing_task_number' => ApiUserQuestion::TASK_1,
                'status' => 0
            ];
            $questionTable = ApiUserQuestion::create($data);
            if ($questionTable->id) {
                // Insert v�o b?ng ApiUserQuestionPart
                for ($i = 1; $i <= 5; $i++) {
                    $dataUserQuestionPart = [
                        'user_question_id' => $questionTable->id,
                        'question' => $jsonData['question'],
                        'topic' => $jsonData['sample'],
                        'part_number' => $i,
                        'writing_task_number' => ApiUserQuestionPart::WRITING_TASK_1,
                        'status' => 0
                    ];
                    ApiUserQuestionPart::create($dataUserQuestionPart);
                }
            }
    
            // Commit transaction tru?c khi dispatch job
            DB::commit();
            // Dispatch job
            dispatch(new Task1Job($jsonData, $questionTable->id, ApiUserQuestion::TASK_1));
            return $this->responseSuccess(200, $questionTable->id);
            // return response()->json(['message' => 'Data inserted and job dispatched successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return $this->responseSuccess(403, $e->getMessage());
            // return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
