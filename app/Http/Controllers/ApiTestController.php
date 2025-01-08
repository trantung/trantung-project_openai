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
use App\Models\ApiUserQuestion;

use App\Models\ApiUserQuestionPart;
use App\Models\Common;
use App\Models\CommonHocmai;
use App\Models\CommonWord;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Element\Comment;

class ApiTestController extends Controller
{
    public function ieltsWriteTask2(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = 'gpt-4-turbo';
        // $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
        $question = $jsonData['question'];
        $introduction = $this->introduction($request);
        $task_response = $this->bandTaskResponse($request);
        $response = [
            'introduction' => $introduction,
            'band_task_response' => $task_response,
        ];
        return $this->responseSuccess(200, $response);
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

    public function mainPoints($data)
    {
        $res = $result = [];
        foreach($data as $key => $value)
        {
            $res[] = $value;
        }
        foreach($res as $k => $v) {
            $result[] = [
                'point' => $v
            ];
        }
        return $result;
    }

    public function TopicMainPoint($data)
    {
        $res = [];
        $res = [
            'TopicSentence' => $data->TopicSentence,
            'MainPoints' => $this->mainPoints($data->MainPoints),
        ];
        return $res;
    }

    public function getValueFromArray($str, $data)
    {
        foreach($data as $key => $value)
        {
            if (str_contains($key, $str)) {
                return $value;
            }
        }
        return [];
    }

    public function getValueFromText($str, $key, $value)
    {
        if (str_contains($key, $str) && is_string($value)) {
            return $value;
        }
        return null;
    }

    public function conclusion(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseConclusion($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        // dd($dataResponseChat);
        $dataResponseChat = json_decode($dataResponseChat,true);
        return $this->responseSuccess(200, $dataResponseChat);



        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify conclusion of an IELTS Essay Task 2, give comments on the strengths and weaknesses. Improvement with example for conclusion. After that provide an overall consisting of 4 to 6 concise sentences indicating what needs to be improved. Response is JSON with format following rule: Conclusion is string, Comments has Strengths is string and Weaknesses is string, Improvements is string and Overall is array" 
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify conclusion of an IELTS Essay Task 2, give comments on the strengths and weaknesses. Then help me to improve with examples of conclusion. After that provide an overall consisting of 4 to 6 concise sentences indicating what needs to be improved. This is my IELTS Essay Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat);
        $response = [
            'Conclusion' => $dataResponseChat->Conclusion,
            'Comments' => [
                'Strengths' => $dataResponseChat->Comments->Strengths,
                'Weaknesses' => $dataResponseChat->Comments->Weaknesses,
            ],
            'Improvements' => $dataResponseChat->Comments->Improvements,
            'Overall' => implode("\n", $dataResponseChat->Comments->Overall),
        ];
        return $this->responseSuccess(200, $response);
    }

    public function getComment($data)
    {
        $strengths = $weaknesses = [];
        foreach($data as $key => $value) {
            if ($key == 'Strengths') {
                $strengths = $value;
            }
            if ($key== 'Weaknesses') {
                $weaknesses = $value;
            }
        }
        dd($strengths, $weaknesses);
        $res = [
            'Strengths' => implode("\n", $strengths),
            'Weaknesses' => implode("\n", $weaknesses),
        ];
        return $res;
    }

    public function topicSentence(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        // $yourApiKey = getenv('OPENAI_API_KEY');
        // $client = OpenAI::client($yourApiKey);
        // // $model = 'gpt-4-turbo';
        // $model = getenv('OPENAI_API_MODEL');
        // $question = $jsonData['question'];
        // $topic = $jsonData['topic'];
        // $test = Common::responseTopicSentence($jsonData);
        $chat = Common::responseTopicSentence($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        return $this->responseSuccess(200, $dataResponseChat);

        dd($test);
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify all topic sentence in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses, then improvement with example for each topic sentence in the Body Paragraphs, structured as:topic_sentence, improvement_examples, comments include strengths and weaknesses, where strengths and weaknesses are observations of the strong and weak points of each topic sentence. Response is JSON format"
                   // "content" => "Identify the topic sentences and main points in the Body Paragraphs of an IELTS Essay Task 2 with the prompt of the essay being:\n
                   // $topic \n
                   //  Then, provide comments on the strengths and weaknesses, followed by providing improvement examples for each topic sentence and main point in the Body Paragraphs. Response is JSON format"
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Essay Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat);
        // dd(reset($dataResponseChat));
        // $topicSentence = $this->getValueFromArray('Topic', $dataResponseChat);
        // $strengths = implode("\n", $this->getValueFromArray('Strengths', $dataResponseChat->Comments));
        // $weaknesses = implode("\n", $this->getValueFromArray('Weaknesses', $dataResponseChat->Comments));
        // $improvements = $this->getValueFromArray('Improvement', $dataResponseChat);
        $topicSentence = $comment = $improvements = [];
        foreach(reset($dataResponseChat) as $key => $value)
        {
            $topicSentence[] = $value->topic_sentence;
            $comment[] = $value->comments;
            $improvements[] = $value->improvement_examples;
        }
        $totalToken = $chat->usage->totalTokens;
        $response = [
            'topicSentence' => $topicSentence,
            'comment' => $comment,
            'improvements' => $improvements,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];

        return $this->responseSuccess(200, $response);
    }

    public function introductionTest(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseIntroduction($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        return $this->responseSuccess(200, $dataResponseChat);

        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // dd(111);
        // $model = 'gpt-4-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy. Identify introduction and show introduction of IELTS Writing Task 2. Please explain to me and give comments on the strengths and weaknesses of my IELTS Writing Task 2. Then provide suggestions for improving the introduction. Response is JSON with format following rule: introduction, strengths, weaknesses, improvement"
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
       
        return $this->responseSuccess(200, $dataResponseChat);
        // dd($dataResponseChat);
        $response = [
            'Introduction' => $dataResponseChat->Introduction,
            'Comments' => [
                'Strengths' => implode("\n", $dataResponseChat->Comments->Strengths),
                'Weaknesses' => implode("\n", $dataResponseChat->Comments->Weaknesses),
            ],
            'Suggestions' => implode("\n", $dataResponseChat->Suggestions),
        ];
        return $this->responseSuccess(200, $response);
    }

    public function introduction($request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
        $question = $jsonData['question'];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy. Identify introduction and show introduction of IELTS Writing Task 2. Please explain to me and give comments on the strengths and weaknesses of my IELTS Writing Task 2. Then provide suggestions for improving the introduction. Response is JSON with format following rule: introduction, strengths, weaknesses, improvement"
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        
        return $dataResponseChat;
    }

    public function bandTaskResponse(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $data = ApiUserQuestion::orderBy('id', 'desc')->first()->toArray();
        $check = ApiUserQuestionPart::whereIn('user_question_id',[248,249])
            ->where('part_number',8)
            // ->where('writing_task_number',2)
            // ->where('status', 0)
            // ->where('user_question_id',177)
            ->orderBy('user_question_id', 'desc')
            // ->pluck('openai_response', 'part_number');
            ->get();
            // $openai_response = $check->openai_response;
        dd($check->toArray());

        //     $test = json_decode($openai_response);
        // dd(json_decode($openai_response));
        $chat = Common::task2IdentifyErrors($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        // dd($dataResponseChat);
        $dataResponseChat1 = json_decode($dataResponseChat,true);
        dd($dataResponseChat1);
        $data = [
            'question_id' => 123,
            'part_number' => 4,
            'part_info' => 4,
            'data' => $dataResponseChat1
        ];

        $formattedJson = json_encode($data);
        // $data = [
        //     // 'question_id' => $questionId,
        //     'part_number' => 7,
        //     // 'part_info' => $partInfo,
        //     'data' => $dataResponseChat
        // ];

        // $data_string = json_encode($data);
        dd($dataResponseChat, $dataResponseChat1, $formattedJson);

        var_dump($dataResponseChat);
        var_dump('bandFormatData');
        $dataResponseChat = Common::bandFormatData($dataResponseChat);
        $checkData = true;
        if(empty($dataResponseChat)) {
            //call lai openai
            $chatCallAgain = Common::responseBandTaskResponse($jsonData);
            $dataResponseChatAgain = $chatCallAgain->choices[0]->message->content;
            $dataResponseChatAgain = Common::bandFormatData($dataResponseChatAgain);
            if(empty($dataResponseChatAgain)) {
                $checkData = false;
            } else {
                $dataResponseChat = $dataResponseChatAgain;
            }
        }
        //check thanh phan trong dataResponseChat
        var_dump($dataResponseChat);
        $dataResponseChat = json_encode($dataResponseChat,true);
        var_dump('json_encode');
        var_dump($dataResponseChat);
        $checkValueChild = Common::checkChildValue('test', $dataResponseChat);
        if(empty($checkValueChild)) {
            $checkData = false;
        }
        // $dataResponseChat = json_decode($dataResponseChat,true);
        dd($dataResponseChat, $checkValueChild);

        return $this->responseSuccess(200, $dataResponseChat);
    }

    public function coherenceCohesion(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseCoherenceCohesion($jsonData);
        dd($chat);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        return $this->responseSuccess(200, $dataResponseChat);
    }

    public function lexicalResource(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseLexicalResource($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        return $this->responseSuccess(200, $dataResponseChat);
    }

    public function gramma(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseGramma($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        return $this->responseSuccess(200, $dataResponseChat);
    }

    public function task1Test(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        if(isset($jsonData['test'])) {
            // $question_id = $jsonData['question_id'];
            // $checkQuestion = ApiUserQuestion::find($question_id);
            // if($checkQuestion) {
            //     $checkQuestionId = $checkQuestion->id;
            //     $checkPart = ApiUserQuestionPart::where('user_question_id', $checkQuestionId)
            //         ->get()->toArray();
            //     dd($checkPart);
            // }
        }
        // $analyze = $this->image($request);
        $analyze = $jsonData['image_content'];
        $messageTopic = $topic . "\n" . "This is the content of the chart:\n" . $analyze;
        $jsonData['topic'] = $messageTopic;
        // if(isset($jsonData['test'])) {
        //     dd($jsonData['topic']);
        // }
        $dataResponseChat = Common::task1IdentifyErrors($jsonData);
        $dataResponseChat = $dataResponseChat->choices[0]->message->content;
        $response = json_decode($dataResponseChat, true);

        return $this->responseSuccess(200, $response);

        dd($chat);
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];

        $jsonData['question'] = strtr( $jsonData['question'], array(  "\n" => "\\n",  "\r" => "\\r"  ));
        $jsonData['topic'] = strtr( $jsonData['topic'], array(  "\n" => "\\n",  "\r" => "\\r"  ));
        DB::beginTransaction();
        try {
            // Insert v�o b?ng ApiUserQuestion
            $data = [
                'question' => $jsonData['question'],
                'topic' => $jsonData['topic'],
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'writing_task_number' => ApiUserQuestion::TASK_1,
                'status' => 0
            ];
            $questionTable = ApiUserQuestion::create($data);
            if ($questionTable->id) {
                // Insert v�o b?ng ApiUserQuestionPart
                for ($i = 1; $i <= 8; $i++) {
                    $data1 = [
                        'user_question_id' => $questionTable->id,
                        'question' => $jsonData['question'],
                        'topic' => $jsonData['topic'],
                        'part_number' => $i,
                        'writing_task_number' => ApiUserQuestionPart::WRITING_TASK_1,
                        'status' => 0
                    ];
                    ApiUserQuestionPart::create($data1);
                }
            }
    
            // Commit transaction tru?c khi dispatch job
            DB::commit();
            // Dispatch job
            $chat = Common::task1IdentifyErrors($jsonData);
            $dataResponseChat = $chat->choices[0]->message->content;
            $totalToken = $chat->usage->totalTokens;
            $completionTokens = $chat->usage->completionTokens;
            $promptTokens = $chat->usage->promptTokens;
            $checkData = ApiUserQuestionPart::where('user_question_id', $questionTable->id)
                ->where('part_number', 8)
                ->where('writing_task_number', 1)
                ->first();
            if (!empty($checkData)) {
                $updateData = [
                    'openai_response' => $dataResponseChat,
                    'total_token' => $totalToken,
                    'prompt_token' => $promptTokens,
                    'complete_token' => $completionTokens,
                    'status' => 1
                ];

                // Perform the update operation
                ApiUserQuestionPart::where('user_question_id', $checkData->id)->update($updateData);
                // Common::callCmsTask1($dataResponseChat, $questionTable->id, Common::PART_IDENTIFY_ERROR_RESPONSE);
                dd($dataResponseChat);
                // CheckJobsCompletion::dispatch($this->apiUserQuestionId, $this->writing_task_number);
                // Log::info('Part' . $this->partNumber . 'end');
                
            }

            dd($dataResponseChat);
            // dispatch(new Task1Job($jsonData, $questionTable->id, ApiUserQuestion::TASK_1));
            return $this->responseSuccess(200, $questionTable->id);
            // return response()->json(['message' => 'Data inserted and job dispatched successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return $this->responseSuccess(403, $e->getMessage());
            // return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function task1Test8(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $question_id = $jsonData['question_id'];
        $check = ApiUserQuestionPart::where('writing_task_number',1)
            // where('writing_task_number',2)
            // ->where('status', 0)
            ->where('part_number',8)
            ->where('user_question_id',$question_id)
            ->orderBy('id', 'desc')
            ->get()->toArray();
        dd($check);
    }

    public function test(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // dd($jsonData);
        // $chat = Common::task1IdentifyErrors($jsonData);
        // $dataResponseChat = $chat->choices[0]->message->content;
        // dd($dataResponseChat);

        // $dataResponseChat = Common::formatData($dataResponseChat);
        // dd($dataResponseChat);
        $data = ApiUserQuestion::orderBy('id', 'desc')->first()->toArray();
        $check = ApiUserQuestionPart::where('writing_task_number',2)
            // where('writing_task_number',2)
            // ->where('status', 0)
            ->where('part_number',8)
            // ->where('user_question_id',15)s
            ->orderBy('id', 'desc')
            ->get()->toArray();
        dd($check, $data);
        // $model = 'gpt-4-turbo';
        // $model = 'gpt-3.5-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];

        $prompUser = "Please grade the task response of my IELTS Writing Task 2. Show me grade for each criteria and explain why the scoring is done this way for each criterion and give me suggestions for improvements it.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic;

        $userContent = "
        $prompUser \n This is my essay
        {question}
            ----------------
            CONTEXT: 
            {context}
            ----------------
            FINAL ANSWER:";

        $userContent = str_replace("{question}", $question, $userContent);

        $system_prompt = "Criterion 'Address all parts of the question.': \n -If the prompt is appropriately addressed and explored in depth, the band=9 \n If the prompt is appropriately and sufficiently addressed, the band=8\n-If the main parts of the prompt are appropriately addressed, the band=7\n-If the main parts of the prompt are addressed (though some may be more fully covered than others) and an appropriate format is used, the band = 6\n-If the main parts of the prompt are incompletely addressed and the format may be inappropriate in places, the band=5\n-If the prompt is tackled in a minimal way, or the answer is tangential, possibly due to some misunderstanding of the prompt and the format may be inappropriate, the band=4\n-If No part of the prompt is adequately addressed, or the prompt has been misunderstood, the band=3\n-If the content is barely related to the prompt, the band=2\n-If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1\nCriterion 'Present a clear and developed position throughout.':\n -If a clear and fully developed position is presented which directly answers the question/s, the band=9\n -If a clear and well-developed position is presented in response to the question/s, the band=8\n -If aclear and developed position is presented,the band=7\n -If a position is presented that is directly relevant to the prompt,although the conclusions drawn may be unclear, unjustified or repetitive, the band=6\n -If the writer expresses a position, but the development is not always clear,the band=5\n -If a position is discernible, but the reader has to read carefully to find it,the band=4\n -If no relevant position can be identified, and/or there is little direct response to the question/s,the band=3\n -If no position can be identified,the band=2\n -If responses of 20 words or fewer are rated at Band 1 and The content is wholly unrelated to the prompt,the band=1\nCriterion 'Present, develop, support ideas.':\n -If Ideas are relevant, fully extended and well supported.Any lapses in content or support are extremely rare, the band=9\n -If Ideas are relevant, well extended and supported.There may be occasional omissions or lapses in content, the band=8\n -If Main ideas are extended and supported but there may be a tendency to over-generalise or there may be a lack of focus and precision in supporting ideas/material, the band=7\n -If Main ideas are relevant, but some may be insufficiently developed or may lack clarity, while some supporting arguments and evidence may be less relevant or inadequate, the band=6\n -If Some main ideas are put forward, but they are limited and are not sufficiently developed and/or there may be irrelevant detail. There may be some repetition, the band=5\n -If Main ideas are difficult to identify and such ideas that are identifiable may lack relevance, clarity and or support. Large parts of the response may be repetitive, the band=4\n -If There are few ideas, and these may be irrelevant or insufficiently developed, the band=3\n -If There may be glimpses of one or two ideas without development, the band=2\n -If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1";

        $userContent = str_replace("{context}", $system_prompt, $userContent);

        $chat = $client->chat()->create([
            'model' => $model,
           // 'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the task response of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . " Provide the score for each criterion and explain why the score is as it is. Then offer suggestions for improving the scores for each criterion, structured as: score, explanation, improvement suggestions."
               ],
               [
                   "role" => "user",
                   // "content" => $userContent
                   "content" => "Provide the score for each criterion and explain why the score is as it is. Then offer suggestions for improving the scores for each criterion, structured as: score, explanation, improvement suggestions.. This is my IELTS Writing Task 2 essay:\n" . $question
                   // "content" => "Please grade the task response of my IELTS Writing Task 2. Show me grade for each criteria and explain why the scoring is done this way for each criterion and give me suggestions for improvements it. This is my IELTS Writing Task 2 essay:\n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        // $data = json_decode($dataResponseChat);
        dd($chat, $dataResponseChat);
        $response = [];
        foreach($data as $key => $value) {
            $response[] = [
                'criterion' => $value->criterion,
                'score' => $value->score,
                'explanation' => $value->explanation,
            ];
        }
        return $this->responseSuccess(200, $response);
    }

    public function newApiTestJob(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        //ApiUserQuestion::truncate();
        //ApiUserQuestionPart::truncate();
        if($jsonData['test'] == 'tunglaso1') {
            // dd(ApiUserQuestion::whereIn('id', [2,4,5])->get()->toArray());
            $test = ApiUserQuestionPart::where('user_question_id',$jsonData['question_id'])
                ->pluck('status','part_number');
            $test1 = ApiUserQuestion::find($jsonData['question_id']);
            $test3 = ApiUserQuestionPart::where('user_question_id',$jsonData['question_id'])
                ->where('part_number',2)->first()->toArray();
            dd($test, $test1,$test3);
        }
        // dd(ApiUserQuestion::find(5));
        DB::beginTransaction();
        try {
            // Insert v�o b?ng ApiUserQuestion
            $data = [
                'question' => $jsonData['question'],
                'topic' => $jsonData['topic'],
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'status' => 0
            ];
            $questionTable = ApiUserQuestion::create($data);
    
            if ($questionTable->id) {
                // Insert v�o b?ng ApiUserQuestionPart
                for ($i = 1; $i <= 7; $i++) {
                    $data1 = [
                        'user_question_id' => $questionTable->id,
                        'question' => $jsonData['question'],
                        'topic' => $jsonData['topic'],
                        'part_number' => $i,
                        'status' => 0
                    ];
                    ApiUserQuestionPart::create($data1);
                }
            }
    
            // Commit transaction tru?c khi dispatch job
            DB::commit();
    
            // Dispatch job
            dispatch(new DemoJob($jsonData, $questionTable->id));
            return $this->responseSuccess(200, $questionTable->id);
            // return response()->json(['message' => 'Data inserted and job dispatched successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return $this->responseSuccess(403, $e->getMessage());
            // return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function audio(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        
        if(empty($jsonData['context'])) {
            $context = [
                "question" => "What did you do yesterday",
            ];
        } else {
            $context = $jsonData['context'];
        }
        $audio_format = "wav";
        $user_metadata = [
            'speaker_gender' => "male",
            'speaker_age' => "child",
            'speaker_english_level' => "advanced",
        ];
        
        $data = [
            'audio_base64' => $audio_base64,
            'audio_format' => $audio_format,
            'user_metadata' => $user_metadata,
            'context' => $context,
        ];

        $data_string = json_encode($data);
        $curl = curl_init('https://apis.languageconfidence.ai/speech-assessment/unscripted/us');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'api-key: P950LqE9SfejPhIVdzRpyLRWeCmJULk5',
            'x-user-id: 1',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($curl);
        curl_close($curl);
        return $this->responseSuccess(200, json_decode($result, true));
        // dd(json_decode($result, true));
    }

    public function task2LexicalGramma(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $chat = Common::task1LexicalResource($jsonData,$messageTopic);
        // $analyze = $this->image($request);
        dd($chat);
        // $messageTopic = $topic . "This is the content of the chart \n" . $analyze;
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\nIdentify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 2. Response is JSON format"
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 2. Show me the errors and suggest improvements and explain for suggest improvements. This is my IELTS Writing Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $response = json_decode($dataResponseChat, true);
        return $this->responseSuccess(200, $response);
    }

    public function task2IdentifyErrors(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        //todo
        // $test = ApiUserQuestion::all()->toArray();
        // dd($test);
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        // $analyze = $this->image($request);
        // // dd($analyze->data);
        // $messageTopic = $topic . "\n" . "This is the content of the chart:\n" . $analyze;
        $chat = Common::task2IdentifyErrors($jsonData);
        // $analyze = $this->image($request);
        // $chat = $client->chat()->create([
        //     'model' => $model,
        //    'response_format'=>["type"=>"json_object"],
        //    'messages' => [
        //        [
        //            "role" => "system",
        //         //    "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 2 essay. Please help me identify all incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content. Please sure that fixed content is different error" . "This is my IELTS Writing Task 2 essay: \n" . $question
        //         //test
        //         // "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 2 essay. Please help me identify any incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation in about 3-4 sentences for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanation is the explanation of vocabulary or grammar error, correction is the fixed content. Please sure that fixed content is different error" . "This is my IELTS Writing Task 2 essay: \n" . $question
        //         //end test
                
                
        //         //gan dung nhat
        //         "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 2 essay. Please help me identify any incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content. Please sure that fixed content is different error" . "This is my IELTS Writing Task 2 essay: \n" . $question


        //         //    "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 2 essay. Please help me identify any incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content" . "This is my IELTS Writing Task 2 essay: \n" . $question
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy. I want you identify only vocabulary and gramma error in my IELTS Writing Task 2 essay then fix it. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors in my essay then explanation for each error after that fix each error. Error content is limited 10 words and not same fixed content. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content"
        //            //correct
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy. I want you identify only vocabulary and gramma error in my IELTS Writing Task 2 essay then fix it. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors in my essay then explanation for each error after that fix each error. Error content not same fixed content. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content"
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy. I want to improve my vocabulary and gramma for IELTS writing task 2. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Only identify vocabulary and grammar errors in my essay then explanation for each error and fix each one and content fix not same error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content"
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy. I want to improve my vocabulary and gramma for IELTS writing task 2. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors in my essay after that give me an explanation for each error and suggestion improvement for each one and suggestion improvement not same error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar need to improvement, explanations is the explanation of vocabulary or grammar need to improvement, correction is the corrected content"
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy. I want to improve my vocabulary and gramma for IELTS writing task 2. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors in my essay after that give me an explanation for each error and suggestion improvement for each one and suggestion improvement not same error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar need to improvement, explanations is the explanation of vocabulary or grammar need to improvement, correction is the corrected content"
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors, then provide an explanation for each error and suggestion for fix each one. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the mistake, explanations is the explanation of the mistake, correction is the corrected content"
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors, then provide an explanation for each error and correct each one. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the mistake, explanations is the explanation of the mistake, correction is the corrected content"
        //         //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \nIdentify vocabulary and grammar errors, then provide explanations and suggestion correction. Reponse is json format structured as: error, explanations, suggestion for each error"
        //             // "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "This is my IELTS Writing Task 2: \n" . $question  ." \n Identify vocabulary and grammar errors, then provide an explanation for each error and correct each one. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the error, explanations is the explanation of the error, correction is the corrected content"
        //         ],
        //        [
        //            "role" => "user",
        //            "content" => "This is my IELTS Writing Task 2: \n" . $question
        //        ],

        //     ],
        //    'temperature' => 0,
        //    'max_tokens' => 1000
        // ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        dd($dataResponseChat);
        $response = json_decode($dataResponseChat, true);
        return $this->responseSuccess(200, $response);
    }

// Lexical & Grammatical errors task1
    public function task1LexicalGramma(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $analyze = $this->image($request);
        // dd($analyze);
        $messageTopic = $topic . "\n" . "This is the content of the chart:\n" . $analyze;
        $chat = Common::task1BandLexicalResource($jsonData,$messageTopic);
        // $analyze = $this->image($request);
        dd($chat);
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay: \n" . $messageTopic . "\nIdentify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 1. Reponse is json format structured as: error, explanations, corrections. for each error"
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 2. Show me the errors and suggest improvements and explain for suggest improvements. This is my IELTS Writing Task 1: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $response = json_decode($dataResponseChat, true);
        return $this->responseSuccess(200, $response);
    }
    public function task1TaskAchievement(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        // $yourApiKey = getenv('OPENAI_API_KEY');
        // $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $analyze = $this->image($request);
        // // // dd($analyze);
        $messageTopic = $topic . "\n" . "This is the content of the charts:\n" . $analyze;
        $chat = Common::task1BandCoherenceCohesion($jsonData,$analyze);
        $dataResponseChat = $chat->choices[0]->message->content;
        $response = json_decode($dataResponseChat, true);
        return $this->responseSuccess(200, $response);
    }
    
    public function image(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = 'gpt-4o';
        $content = [
            [
                'type' => 'text',
                'text' => $jsonData['analyze'],
            ],
            [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $jsonData['url'],
                ]
            ],
        ];
        if(!empty($jsonData['detail'])) {
            if(!isset($jsonData['analyze'])) {
                $analyze = 'Please analyze the following chart for me so that I can have the information about it';
            }
            $content = [
                [
                    'type' => 'text',
                    'text' => $jsonData['analyze'],
                ],
                [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => $jsonData['url'],
                        'detail' => $jsonData['detail']
                    ]
                ],
            ];
        }

        $analyze = 'Please analyze the following chart for me so that I can have the information about it';
        $content = [
            [
                'type' => 'text',
                'text' => $jsonData['analyze'],
            ],
            [
                'type' => 'image_url',
                'image_url' => [
                    'url' => $jsonData['url'],
                    'detail' => $jsonData['detail']
                ]
            ],
        ];
        $chat = $client->chat()->create([
            'model' => $model,
            // 'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                    "role" => "user",
                    "content" => $content
                ],
            ],
            'max_tokens' => 1000,
            'temperature' => 0
        ]);
        // dd($chat);
        $dataResponseChat = $chat->choices[0]->message->content;
        return $dataResponseChat;

    }

    public function writeTask1CreateWrite(Request $request)
    {

    }

    //test hocmai
    //vocabulary_grammar
    public function hocmaiTask1VocabularyGramma(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = CommonHocmai::hocmaiTask1VocabularyGramma($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
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
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $chat->usage->totalTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];

        return $this->responseSuccess(200, $res);
    }

    public function exportResult(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        // $userName = $jsonData['user_name'];
        // $userId = $jsonData['user_id'];

        // $phpWord = new PhpWord();
        // $section = $phpWord->addSection();
        // // $essay = "Some peoples think government should control how much violent is show in film and TV. They think violence can affect viewers badly, especially young peoples. Other peoples say violent films should not be made at all because it make society more dangerous. This essay will talk about both ideas and my opinion.\n\nFirst, some peoples think government need to control violent content in movies and TV. They believe violent is bad for kids because they might copy what they see. For example, when children watch fighting or killing, they might do same thing in real life. Also, too much violent make peoples feel not care about real-life violence anymore. If government stop too much violent, it can help protect the peoples and make society safe.\n\nSecond, some peoples think violent films should not exist. They believe even if violent is control, it still make peoples act bad. They think there is no need for violent films because movies can be interesting without it. This way, peoples will have better role models and not like aggressive behavior.\n\nIn my opinion, it is important to control violent in media, but stop violent films completely is not a good idea. Many peoples like action or thriller films, which usually have violent. Instead of ban these films, we can use age limits and warning signs. Parents also should check what their kids are watching.\n\nIn conclusion, government should control violent films but not stop them. With good rules and parents' help, peoples can watch movies without bad effects.";

        // // // Danh sách lỗi với các cụm từ
        // // $errors = [
        // //     "Some peoples think government should control how much violent is show",
        // //     "especially young peoples.",
        // //     "Other peoples say violent films should not be made",
        // //     "it make society more dangerous.",
        // //     "First, some peoples think government need to control",
        // //     "They believe violent is bad for kids",
        // //     "they might do same thing in real life.",
        // //     "too much violent make peoples feel not care",
        // //     "If government stop too much violent",
        // //     "it can help protect the peoples",
        // //     "some peoples think violent films should not exist.",
        // //     "even if violent is control, it still make peoples act bad.",
        // //     "peoples will have better role models",
        // //     "control violent in media, but stop violent films",
        // //     "Many peoples like action or thriller films, which usually have violent.",
        // //     "Instead of ban these films",
        // //     "government should control violent films but not stop them.",
        // //     "peoples can watch movies without bad effects"
        // // ];

        // // // Khởi tạo mảng để lưu kết quả
        // // $result = [];
        // // $lastPosition = 0;

        // // foreach ($errors as $error) {
        // //     // Tìm vị trí của lỗi trong đoạn văn
        // //     $errorPosition = strpos($essay, $error);
        // //     if (!empty($errorPosition)) {
        // //         // Lấy phần đoạn văn trước lỗi
        // //         $beforeError = substr($essay, $lastPosition, $errorPosition - $lastPosition);
        // //         if (trim($beforeError) !== '') {
        // //             $result[] = trim($beforeError); // Lưu phần trước lỗi
        // //         }

        // //         $result[] = $error; // Lưu lỗi
        // //         $lastPosition = $errorPosition + strlen($error); // Cập nhật vị trí cuối cùng
        // //     }
        // // }

        // // // Xử lý đoạn còn lại sau lỗi cuối cùng
        // // $remainingText = substr($essay, $lastPosition);
        // // if (trim($remainingText) !== '') {
        // //     $result[] = trim($remainingText);
        // // }

        // // // In kết quả
        // // foreach ($result as $index => $part) {
        // //     echo "Part " . ($index + 1) . ": " . $part . "\n\n";
        // // }

        // // foreach ($errors as $index => $error) {
        // //     $replacement = sprintf("...error%d...", $index + 1); // Định dạng: ...error1...
        // //     $essay = str_replace($error, $replacement, $essay);
        // // }
        // //start todo

        //     // use PhpOffice\PhpWord\PhpWord;
        //     // use PhpOffice\PhpWord\Element\Comment;

        //     // // Khởi tạo đối tượng PhpWord
        //     // $phpWord = new PhpWord();
        //     // $section = $phpWord->addSection();

        //     // // Đoạn văn bản đã chỉnh sửa với các placeholder "errorX"
        //     $essay = "...error1... in film and TV. They think violence can affect viewers badly, ...error2... ...error3... at all because ...error4... This essay will talk about both ideas and my opinion.\n\n...error5... violent content in movies and TV. ...error6... because they might copy what they see. For example, when children watch fighting or killing, ...error7... Also, ...error8... about real-life violence anymore. ...error9..., ...error10... and make society safe.\n\nSecond, ...error11... They believe ...error12... They think there is no need for violent films because movies can be interesting without it. This way, ...error13... and not like aggressive behavior.\n\nIn my opinion, it is important to ...error14... completely is not a good idea. ...error15... ...error16..., we can use age limits and warning signs. Parents also should check what their kids are watching.\n\nIn conclusion, ...error17... With good rules and parents' help, ...error18....";

        //     // // Danh sách lỗi và các bình luận tương ứng
        //     $errors = [
        //         ["error" => "error1", "comment" => "Rewrite: Some people think the government should control how much violence is shown."],
        //         ["error" => "error2", "comment" => "Replace: especially young people."],
        //         ["error" => "error3", "comment" => "Correct: Other people say violent films should not be made."],
        //         ["error" => "error4", "comment" => "Fix: it makes society more dangerous."],
        //         ["error" => "error5", "comment" => "Suggestion: First, some people think the government needs to control violent content."],
        //         ["error" => "error6", "comment" => "Rewrite: They believe violence is bad for kids."],
        //         ["error" => "error7", "comment" => "Add missing article: they might do the same thing in real life."],
        //         ["error" => "error8", "comment" => "Change: too much violence makes people feel indifferent to real-life violence."],
        //         ["error" => "error9", "comment" => "Fix: If the government stops too much violence."],
        //         ["error" => "error10", "comment" => "Correct: it can help protect the people."],
        //         ["error" => "error11", "comment" => "Rewrite: Second, some people think violent films should not exist."],
        //         ["error" => "error12", "comment" => "Correct: even if violence is controlled, it still makes people act badly."],
        //         ["error" => "error13", "comment" => "Rewrite: people will have better role models."],
        //         ["error" => "error14", "comment" => "Suggestion: control violence in media, but stopping violent films completely is not a good idea."],
        //         ["error" => "error15", "comment" => "Fix: Many people like action or thriller films, which usually have violence."],
        //         ["error" => "error16", "comment" => "Rewrite: Instead of banning these films."],
        //         ["error" => "error17", "comment" => "Fix: the government should control violent films but not stop them."],
        //         ["error" => "error18", "comment" => "Correct: people can watch movies without bad effects."]
        //     ];

        //     // Tách đoạn văn theo "...errorX..."
        //     $splitEssay = preg_split('/(\.\.\.error\d+\.\.\.)/', $essay, -1, PREG_SPLIT_DELIM_CAPTURE);

        //     // Thêm từng phần của đoạn văn và các comment tương ứng
        //     foreach ($splitEssay as $part) {
        //         if (preg_match('/error(\d+)/', $part, $matches)) {
        //             $errorIndex = (int)$matches[1] - 1; // Lấy chỉ mục lỗi tương ứng trong mảng $errors
        //             if (isset($errors[$errorIndex])) {
        //                 $errorText = $errors[$errorIndex]['error'];
        //                 $commentText = $errors[$errorIndex]['comment'];

        //                 // Tạo TextRun để thêm lỗi và comment
        //                 $textRun = $section->addTextRun();

        //                 // Tạo comment cho lỗi
        //                 $comment = new Comment('Editor', new \DateTime());
        //                 $comment->addText($commentText);
        //                 $phpWord->addComment($comment);

        //                 // Thêm lỗi vào đoạn văn và gắn comment
        //                 $textError = $textRun->addText($errorText, ['bold' => true]);
        //                 $textError->setCommentRangeStart($comment);
        //                 $textError->setCommentRangeEnd($comment);
        //             }
        //         } else {
        //             // Thêm các phần văn bản không phải lỗi
        //             $section->addText($part);
        //         }
        //     }
        //     $fileName = 'document_with_comments_test2.docx';
        //     $filePath = storage_path('app/public/' . $fileName);
        //     $phpWord->save($filePath, 'Word2007');
            $filePath = CommonWord::commentWord();

            return response()->json([
                'message' => 'File đã được lưu thành công!',
                'path' => Storage::url($filePath), // Đường dẫn để tải file
            ]);
            // // Lưu file Word
            // $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            // $writer->save('EssayWithComments.docx');

            // echo "File 'EssayWithComments.docx' đã được tạo thành công!";

        //end todo



        // $phpWord = new PhpWord();
        // $section = $phpWord->addSection();

        // // Thêm đoạn văn bản có comment
        // $textWithComment = $section->addText("Đây là đoạn văn bản có comment.");

        // // Thêm comment vào tài liệu
        // $commentManager = new \PhpOffice\PhpWord\Element\Comment('Foo Bar', new \DateTime());
        // $comment = $commentManager->addText('A comment with a start and an end');
        // $phpWord->addComment($commentManager);
        // $commentManager = $phpWord->getCommentManager();
        // $comment = $commentManager->addComment("Người dùng", date('Y-m-d H:i:s'));
        // $comment->addText("Đây là comment cho đoạn văn bản này.");

        // // Gắn comment vào đoạn văn bản
        // $commentManager->attachComment($textWithComment, $comment);

        // // Lưu tài liệu
        $data = ApiUserQuestion::orderBy('id', 'desc')->first()->toArray();
        $check = ApiUserQuestionPart::whereIn('user_question_id',[230])
            ->where('part_number',8)
            // ->where('writing_task_number',2)
            // ->where('status', 0)
            // ->where('user_question_id',177)
            ->orderBy('user_question_id', 'desc')
            // ->pluck('openai_response', 'part_number');
            ->first();
            // $openai_response = $check->openai_response;
        $report = $check->question;

        // dd($report, )


        // $reportFormat = nl2br($report);
        // $textRun = $section->addTextRun();
        // $textWithComment = $textRun->addText($report);

        // $commentWithStartAndEnd = new \PhpOffice\PhpWord\Element\Comment($userName, new \DateTime());

        $dataOpenAi = json_decode($check->openai_response, true);
        // dd($check, json_decode($check->openai_response, true));
        // $phpWord = new PhpWord();
        // $section = $phpWord->addSection();
        dd($report, $dataOpenAi);
        // $textrunWithEnd = $section->addTextRun();

        //explode student essay follow error(quote)
        foreach($dataOpenAi['errors'] as $value)
        {
            $commentWithStartAndEnd = new \PhpOffice\PhpWord\Element\Comment($userName, new \DateTime());
            $quote = $value['error'];
            $correction = $value['corrections'];
            $explain = $value['explanations'];
            $commentWithStartAndEnd->addText($quote . '->' . $correction);
            $phpWord->addComment($commentWithStartAndEnd);
            // $textWithComment->setCommentRangeStart($commentWithStartAndEnd);
            // $textWithComment->setCommentRangeEnd($commentWithStartAndEnd);

            // $textrun = $section->addTextRun();
            $textParts = explode($quote, $report);
            if (isset($textParts[0]) && !empty($textParts[0])) {
                $textrunWithEnd = $section->addTextRun();

                $textToStartOn = $textrunWithEnd->addText($textParts[0]);
                $textToStartOn->setCommentRangeStart($commentWithStartAndEnd);

                $textToEndOn = $textrunWithEnd->addText($quote, array('bold' => true, 'color' => 'red'));
                $textToEndOn->setCommentRangeEnd($commentWithStartAndEnd);
            } else {
                $textrunWithEnd = $section->addTextRun();

                $textToStartOn = $textrunWithEnd->addText('');
                $textToStartOn->setCommentRangeStart($commentWithStartAndEnd);

                $textToEndOn = $textrunWithEnd->addText($quote, array('bold' => true, 'color' => 'red'));
                $textToEndOn->setCommentRangeEnd($commentWithStartAndEnd);
                
            }
            
            
        }




        // $commentWithStartAndEnd = new \PhpOffice\PhpWord\Element\Comment('Foo Bar', new \DateTime());
        // $commentWithStartAndEnd->addText('A comment with a start and an end');
        // $phpWord->addComment($commentWithStartAndEnd);

        // $textrunWithEnd = $section->addTextRun();
        // $textrunWithEnd->addText('This ');
        // $textToStartOn = $textrunWithEnd->addText('is', array('bold' => true));
        // $textToStartOn->setCommentRangeStart($commentWithStartAndEnd);
        // $textrunWithEnd->addText(' another', array('italic' => true));
        // $textToEndOn = $textrunWithEnd->addText(' test');
        // $textToEndOn->setCommentRangeEnd($commentWithStartAndEnd);

        $fileName = 'document_with_comments_test2.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');

        // $phpWord = new PhpWord();
        // $section = $phpWord->addSection();

        // // Thêm đoạn văn bản có nội dung
        // $textRun = $section->addTextRun();
        // $textRun->addText("Đây là một đoạn văn bản chính. ");

        // // Thêm Footnote
        // $footnote = $textRun->addFootnote();
        // $footnote->addText("Đây là comment (Footnote) cho đoạn văn bản chính.");

        // // Lưu file Word
        // $fileName = 'document_with_footnote.docx';
        // $filePath = storage_path('app/public/' . $fileName);
        // $phpWord->save($filePath, 'Word2007');
        // Trả về thông báo thành công
        return response()->json([
            'message' => 'File đã được lưu thành công!',
            'path' => Storage::url($filePath), // Đường dẫn để tải file
        ]);
    }
    

    // public function exportResultKienTest(Request $request)
    // {
    //     $phpWord = new PhpWord();
    //     $section = $phpWord->addSection();

    //     // Nội dung đoạn văn bản
    //     $essay = "Some peoples think government should control how much violent is show in film and TV. They think violence can affect viewers badly, especially young peoples.";
        
    //     $data = ApiUserQuestion::orderBy('id', 'desc')->first()->toArray();
    //     $check = ApiUserQuestionPart::whereIn('user_question_id',[230])
    //         ->where('part_number',8)
    //         ->orderBy('user_question_id', 'desc')
    //         ->first();

    //     $report = $check->question;

    //     dd($report);

    //     // Danh sách lỗi cần comment
    //     $errors = [
    //         [
    //             'error' => "Some peoples think government should control how much violent is show",
    //             'correction' => "Some people think the government should control how much violence is shown",
    //             'explanation' => "Grammar correction and better phrasing.",
    //         ],
    //         [
    //             'error' => "especially young peoples.",
    //             'correction' => "especially young people.",
    //             'explanation' => "Remove grammatical errors.",
    //         ]
    //     ];

    //     // Tách đoạn văn bản thành các phần
    //     $lastPosition = 0;
    //     foreach ($errors as $errorItem) {
    //         $error = $errorItem['error'];
    //         $correction = $errorItem['correction'];
    //         $explanation = $errorItem['explanation'];

    //         $errorPosition = strpos($essay, $error);
    //         if ($errorPosition !== false) {
    //             // Thêm đoạn văn bản trước lỗi
    //             $beforeError = substr($essay, $lastPosition, $errorPosition - $lastPosition);
    //             if (!empty(trim($beforeError))) {
    //                 $section->addText($beforeError);
    //             }

    //             // Thêm lỗi với comment
    //             $textrun = $section->addTextRun();
    //             $comment = new Comment('Editor', new \DateTime());
    //             $comment->addText("Correction: $correction\nExplanation: $explanation");

    //             $textWithComment = $textrun->addText($error, ['bold' => true, 'color' => 'red']);
    //             $textWithComment->setCommentRangeStart($comment);
    //             $textWithComment->setCommentRangeEnd($comment);

    //             $phpWord->addComment($comment);

    //             // Cập nhật vị trí sau lỗi
    //             $lastPosition = $errorPosition + strlen($error);
    //         }
    //     }

    //     // Xử lý phần còn lại
    //     $remainingText = substr($essay, $lastPosition);
    //     if (!empty(trim($remainingText))) {
    //         $section->addText($remainingText);
    //     }

    //     // Lưu file Word
    //     $fileName = 'document_with_comments_kien.docx';
    //     $filePath = storage_path('app/public/' . $fileName);
    //     $phpWord->save($filePath, 'Word2007');

    //     // Trả về thông báo thành công
    //     return response()->json([
    //         'message' => 'File đã được lưu thành công!',
    //         'path' => Storage::url($filePath),
    //     ]);
    // }

    public function exportResultWordFile(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $userName = $jsonData['user_name'];
        $userId = $jsonData['user_id'];
        //todo
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();


        $data = ApiUserQuestion::orderBy('id', 'desc')->first()->toArray();
        $check = ApiUserQuestionPart::whereIn('user_question_id',[230])
            ->where('part_number',8)
            ->orderBy('user_question_id', 'desc')
            ->first();
        $report = $check->question;

        $dataOpenAi = json_decode($check->openai_response, true);

        foreach($dataOpenAi['errors'] as $value)
        {
            $commentWithStartAndEnd = new \PhpOffice\PhpWord\Element\Comment($userName, new \DateTime());
            $quote = $value['error'];
            $correction = $value['corrections'];
            $explain = $value['explanations'];
            $commentWithStartAndEnd->addText($quote . '->' . $correction);
            $commentWithStartAndEnd->addText("explain:". $explain);
            $phpWord->addComment($commentWithStartAndEnd);
            $textParts = explode($quote, $report);
            if (isset($textParts[0]) && !empty($textParts[0])) {
                $textrunWithEnd = $section->addTextRun();

                $textToStartOn = $textrunWithEnd->addText($textParts[0]);
                $textToStartOn->setCommentRangeStart($commentWithStartAndEnd);

                $textToEndOn = $textrunWithEnd->addText($quote, array('bold' => true, 'color' => 'red'));
                $textToEndOn->setCommentRangeEnd($commentWithStartAndEnd);
            } else {
                $textrunWithEnd = $section->addTextRun();

                $textToStartOn = $textrunWithEnd->addText('');
                $textToStartOn->setCommentRangeStart($commentWithStartAndEnd);

                $textToEndOn = $textrunWithEnd->addText($quote, array('bold' => true, 'color' => 'red'));
                $textToEndOn->setCommentRangeEnd($commentWithStartAndEnd);
            }
        }

        $fileName = 'document_with_comments_kientest2.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');

        return response()->json([
            'message' => 'File đã được lưu thành công!',
            'path' => Storage::url($filePath), // Đường dẫn để tải file
        ]);

        dd($report, $dataOpenAi);

        $essay = "Some peoples think government should control how much violent is show in film and TV. They think violence can affect viewers badly, especially young peoples. Other peoples say violent films should not be made at all because it make society more dangerous. This essay will talk about both ideas and my opinion.\n\nFirst, some peoples think government need to control violent content in movies and TV. They believe violent is bad for kids because they might copy what they see. For example, when children watch fighting or killing, they might do same thing in real life. Also, too much violent make peoples feel not care about real-life violence anymore. If government stop too much violent, it can help protect the peoples and make society safe.\n\nSecond, some peoples think violent films should not exist. They believe even if violent is control, it still make peoples act bad. They think there is no need for violent films because movies can be interesting without it. This way, peoples will have better role models and not like aggressive behavior.\n\nIn my opinion, it is important to control violent in media, but stop violent films completely is not a good idea. Many peoples like action or thriller films, which usually have violent. Instead of ban these films, we can use age limits and warning signs. Parents also should check what their kids are watching.\n\nIn conclusion, government should control violent films but not stop them. With good rules and parents' help, peoples can watch movies without bad effects.";

        $errors = [
            "Some peoples think government should control how much violent is show",
            "especially young peoples.",
            "Other peoples say violent films should not be made",
            "it make society more dangerous.",
            "First, some peoples think government need to control",
            "They believe violent is bad for kids",
            "they might do same thing in real life.",
            "too much violent make peoples feel not care",
            "If government stop too much violent",
            "it can help protect the peoples",
            "some peoples think violent films should not exist.",
            "even if violent is control, it still make peoples act bad.",
            "peoples will have better role models",
            "control violent in media, but stop violent films",
            "Many peoples like action or thriller films, which usually have violent.",
            "Instead of ban these films",
            "government should control violent films but not stop them.",
            "peoples can watch movies without bad effects"
        ];

        $result = [];
        $lastPosition = 0;

        foreach ($errors as $error) {
            $errorPosition = strpos($essay, $error);
            if ($errorPosition !== false) {
                $beforeError = substr($essay, $lastPosition, $errorPosition - $lastPosition);
                if (trim($beforeError) !== '') {
                    $result[] = ['text' => trim($beforeError), 'isError' => false];
                }

                $result[] = ['text' => $error, 'isError' => true];
                $lastPosition = $errorPosition + strlen($error);
            }
        }

        $remainingText = substr($essay, $lastPosition);
        if (trim($remainingText) !== '') {
            $result[] = ['text' => trim($remainingText), 'isError' => false];
        }

        // Tạo nội dung trong file Word
        foreach ($result as $part) {
            if ($part['isError']) {
                $textrun = $section->addTextRun();

                $comment = new Comment('Editor', new \DateTime());
                $comment->addText("This sentence contains an error. Please review and correct.");

                $textWithComment = $textrun->addText($part['text'], ['bold' => true, 'color' => 'red']);
                $textWithComment->setCommentRangeStart($comment);
                $textWithComment->setCommentRangeEnd($comment);

                $phpWord->addComment($comment);
            } else {
                $section->addText($part['text']);
            }
        }

        $fileName = 'document_with_errors.docx';
        $filePath = storage_path('app/public/' . $fileName);
        // $phpWord->save($filePath, 'Word2016');
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2016');
        $writer->save('EssayWithComments.docx');

        return response()->json([
            'message' => 'File created successfully!',
            'path' => Storage::url($fileName)
        ]);
    }

}
