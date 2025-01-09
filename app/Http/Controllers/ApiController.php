<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use \OpenAI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApiUserQuestion;
use App\Models\ApiMoodle;
use App\Models\ApiMoodleEms;
use App\Models\EmsType;
use App\Models\ApiEms;
use App\Models\ApiEs;

use App\Models\ApiUserQuestionPart;
use App\Models\Course;
use App\Models\CommonHocmai;
use App\Models\CommonHocmaiTask2;
use App\Models\CommonEms;
use Illuminate\Support\Facades\Log;
use App\Jobs\DemoJob;
use App\Jobs\Task1Job;

class ApiController extends Controller
{
    public function checkParams($jsonData, $fields)
    {
        // if(empty($jsonData['token']) || $jsonData['token'] != 'tunglaso1') {
        //     return false;
        // }
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
        $chat = CommonHocmai::hocmaiVocabularyGramma($jsonData);
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
        // dd(222);
        $test = ApiUserQuestionPart::whereIn('user_question_id', [57,58])
            ->whereIn('part_number', [1,2])
        // ->where('user_question_id', 57)
        ->orderBy('id', 'desc')
        // ->where('writing_task_number', 2)
        ->get();
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiBandTaskAchiement($jsonData);
        // $chat = CommonHocmaiTask2::hocmaiTask1BandTaskAchiement($jsonData);
        
        $dataResponseChat = $chat->choices[0]->message->content;
        dd($test->toArray(), $dataResponseChat, json_decode($dataResponseChat, true), json_encode($dataResponseChat,true));

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
        // dd(11);
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
                        'question' => $jsonData['report'],
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

    //test hocmai: task2
    public function task2All(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $checkToken = $this->checkParams($request, ['username', 'user_id', 'topic','report']);
        if(!$checkToken) {
            return $this->responseSuccess(403, 'Token invalid');
        }
        $question = $jsonData['report'];
        $topic = $jsonData['topic'];
        $jsonData['report'] = strtr( $jsonData['report'], array(  "\n" => "\\n",  "\r" => "\\r"  ));
        $jsonData['topic'] = strtr( $jsonData['topic'], array(  "\n" => "\\n",  "\r" => "\\r"  ));
        DB::beginTransaction();
        try {
            // Insert v�o b?ng ApiUserQuestion
            $data = [
                'question' => $jsonData['report'],
                'topic' => $jsonData['topic'],
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'writing_task_number' => ApiUserQuestion::TASK_2,
                'status' => 0
            ];
            $questionTable = ApiUserQuestion::create($data);
            if ($questionTable->id) {
                // Insert v�o b?ng ApiUserQuestionPart
                for ($i = 1; $i <= 6; $i++) {
                    $dataUserQuestionPart = [
                        'user_question_id' => $questionTable->id,
                        'question' => $jsonData['report'],
                        'topic' => $jsonData['topic'],
                        'part_number' => $i,
                        'writing_task_number' => ApiUserQuestionPart::WRITING_TASK_2,
                        'status' => 0
                    ];
                    ApiUserQuestionPart::create($dataUserQuestionPart);
                }
            }
            // Commit transaction tru?c khi dispatch job
            DB::commit();
            // Dispatch job
            dispatch(new DemoJob($jsonData, $questionTable->id, ApiUserQuestion::TASK_2));
            return $this->responseSuccess(200, $questionTable->id);
            // return response()->json(['message' => 'Data inserted and job dispatched successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return $this->responseSuccess(403, $e->getMessage());
            // return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    //vocabulary_grammar
    public function hocmaiTask2VocabularyGramma(Request $request)
    {
        // dd(222);
        $test = ApiUserQuestionPart::whereIn('user_question_id', [65])
            ->whereIn('part_number', [1])
            ->select('part_number', 'openai_response', 'user_question_id')
        // ->where('user_question_id', 57)
        ->orderBy('id', 'desc')
        // // ->where('writing_task_number', 2)
        ->first();
        //todo
        // ApiEms::truncate();
        // EmsType::truncate();
        // ['type_id' => rand(1, 10), 'type_name' => 'Ems Type 1'],
        // foreach([1,2,3,4] as $value)
        // {
        //     EmsType::create([
        //         'type_id' => $value, 'type_name' => 'Ems Type ' . $value
        //     ]);
        // }
        // dd($apiEms->toArray());
        // foreach(ApiEs::all() as $value)
        // {
        //     ApiEms::create([
        //         'ems_id' => $value->es_id,
        //         'ems_name' => $value->es_name,
        //         'ems_type_id' =>  rand(1,4),
        //     ]);
        // }
        // $testCourse = EmsType::all();

        dd(Course::all()->toArray());

        $testApiMoodle= ApiMoodle::where('moodle_type', 'course')->get();
        // dd($testApiMoodle->toArray());
        // foreach([40,41,42,43,44,45,46,47,48] as $value)
        // {
        //     ApiMoodle::destroy($value);
        // }
        // dd(ApiMoodle::where('moodle_type', 'course')->get()->toArray());
        // $testApiMoodle= ApiMoodle::all();
        // foreach($testApiMoodle as $value)
        // {
        //     $value->update(['moodle_type' => 1]);
        // }
        dd($testCourse->toArray(), $testApiMoodle->toArray());
        $data = [
            'question_id' => 65,
            'part_number' => 1,
            'part_info' => 1,
            'data' => $test->openai_response
        ];
        $data_string = json_encode($data, true);
        dd($test->toArray(), $data_string);
        // // dd(json_decode($test->toArray()[0]['openai_response']));
        $jsonData = $this->getDataFromRequest($request);
        // // $chat = CommonHocmai::hocmaiVocabularyGramma($jsonData);
        $chat = CommonHocmaiTask2::hocmaiVocabularyGramma($jsonData);
        
        $dataResponseChat = $chat->choices[0]->message->content;
        // dd($test->toArray(), $dataResponseChat, json_decode($dataResponseChat, true), json_encode($dataResponseChat,true));

        // if (!is_string($dataResponseChat)) {
        //     dd($dataResponseChat);
        // }
        // dd(1111, $dataResponseChat, json_decode($dataResponseChat, true));
        // $dataResponseChat = json_decode($dataResponseChat,true);
        // $dataResponseChat = $chat->choices[0]->message->content;
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $this->responseSuccess(200, $res);
    }

    public function hocmaiTask2TaskResponse(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmaiTask2::hocmaiBandTaskResponse($jsonData);
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
    public function hocmaiTask2CoherenceCohesion(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmaiTask2::hocmaiBandCoherenceCohesion($jsonData);
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
    public function hocmaiTask2LexicalResource(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmaiTask2::hocmaiBandLexicalResource($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        // dd($dataResponseChat);
        $test = CommonHocmaiTask2::callCms($dataResponseChat, 1, 4);
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
    public function hocmaiTask2GrammaRange(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmaiTask2::hocmaiBandGrammaRange($jsonData);
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

    //Improved essay
    public function hocmaiTask2ImprovedEssay(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmaiTask2::hocmaiImprovedEssay($jsonData);
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

    public function getLog(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $questionId = $jsonData['question_id'];
        $writing_task_number = $jsonData['writing_task_number'];
        $apiUser = ApiUserQuestion::find($questionId);
        $apiUserPart = ApiUserQuestionPart::where('writing_task_number',$writing_task_number)->where('user_question_id',$questionId)->get();
        dd($apiUser->toArray(), $apiUserPart->toArray());
    }

    public function uploadPDFLms(Request $request)
    {
        $activity_id = $request->input('activity_id');
        if (!$activity_id) {
            return response()->json([
                'success' => false,
                'message' => 'Activity ID required',
                'data' => []
            ], 200);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Kiểm tra xem file có hợp lệ hay không
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File upload error',
                    'data' => []
                ], 200);
            }

            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFileName = $fileName . '.' . $extension;

            $directory = 'public/checkmate/checkmate_pdf/activity_' . $activity_id;

            try {
                // Xóa các tệp cũ trong thư mục trước khi tải lên tệp mới
                if (Storage::exists($directory)) {
                    Storage::deleteDirectory($directory);
                }

                // Lưu tệp mới vào thư mục
                $filePath = $file->storeAs($directory, $newFileName);

                // Loại bỏ tiền tố "public/" trong filePath
                $cleanFilePath = str_replace('public/', '', $filePath);
                
                $fileNameOrigin = $fileName . '.' . $extension;

                $res = [
                    'urlStorage' => url('/storage'),
                    'filePath' => $cleanFilePath,
                    'fileNameOrigin' => $fileNameOrigin,
                    'fileName' => $fileName,
                    'newFileName' => $newFileName,
                    'urlStorageFile' => url('/storage') . '/' . $cleanFilePath,
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Uploaded file successfully',
                    'data' => $res
                ], 200);
            } catch (\Exception $e) {
                // Trường hợp xảy ra lỗi khi lưu file
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save the file: ' . $e->getMessage(),
                    'data' => []
                ], 200);
            }
        }

        // Trường hợp không có file trong request
        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
            'data' => []
        ], 200);
    }


}
