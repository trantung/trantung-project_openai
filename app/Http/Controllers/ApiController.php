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
use Illuminate\Support\Facades\Log;


class ApiController extends Controller
{
    public function checkParamCms($jsonData, $fields)
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

    public function writeTask2Create(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $checkToken = $this->checkParamCms($request, ['username', 'user_id', 'topic','question']);
        if(!$checkToken) {
            return $this->responseSuccess(403, 'Token invalid');
        }
        DB::beginTransaction();
        try {
            // Insert vào b?ng ApiUserQuestion
            $data = [
                'question' => $jsonData['question'],
                'topic' => $jsonData['topic'],
                'user_id' => $jsonData['user_id'],
                'username' => $jsonData['username'],
                'status' => 0
            ];
            $questionTable = ApiUserQuestion::create($data);
            if ($questionTable->id) {
                // Insert vào b?ng ApiUserQuestionPart
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
            return $this->responseSuccess(200, ['question_id' => $questionTable->id]);
            // return response()->json(['message' => 'Data inserted and job dispatched successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return $this->responseSuccess(403, $e->getMessage());
            // return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function writeTask2Detail(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $checkToken = $this->checkParamCms($request, ['question_id']);
        if(!$checkToken) {
            return $this->responseSuccess(403, 'Token invalid');
        }
        $userQuestionId = $jsonData['question_id'];
        $questionData = ApiUserQuestion::find($userQuestionId);
        if(!$questionData) {
            return $this->responseSuccess(404, 'question_id: '. $userQuestionId . ' not found');
        }
        return $this->responseSuccess(200, json_decode($questionData['openai_response'],true));
    }
    
    public function ieltsWriteTask2(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];

        //cuc 1: introduction
        $introductionData = $this->introduction($request);
        $introductionRes = $introductionData['dataResponseChat'];
        //cuc 2: topic sentence+conclusion
        $conclusionData = $this->conclusion($request);
        $conclusionRes = $conclusionData['dataResponseChat'];
        $topicSentenceData = $this->topicSentence($request);
        $topicSentenceRes = $topicSentenceData['dataResponseChat'];

        //cuc 3: band task response
        $taskResponseData = $this->bandTaskResponse($request);
        $taskResponseRes = $taskResponseData['dataResponseChat'];
        //cuc 4: coherence cohesion
        $coherence_cohesionData = $this->coherenceCohesion($request);
        $coherence_cohesionRes = $coherence_cohesionData['dataResponseChat'];
        //cuc 5:lexical Resource
        $lexicalResourceData = $this->lexicalResource($request);
        $lexicalResourceRes = $lexicalResourceData['dataResponseChat'];
        //cuc 6:gramma
        $grammaData = $this->gramma($request);
        $grammaRes = $grammaData['dataResponseChat'];
        //calculator token
        $totalToken = $introductionData['totalToken'] + $taskResponseData['totalToken'] + $conclusionData['totalToken'] + $topicSentenceData['totalToken'] + $coherence_cohesionData['totalToken'] + $lexicalResourceData['totalToken'] + $grammaData['totalToken']; 
        $completionTokens = $introductionData['completionTokens'] + $taskResponseData['completionTokens'] + $conclusionData['completionTokens'] + $topicSentenceData['completionTokens'] + $coherence_cohesionData['completionTokens'] + $lexicalResourceData['completionTokens'] + $grammaData['completionTokens'];
        $promptTokens = $introductionData['promptTokens'] + $taskResponseData['promptTokens'] + $conclusionData['promptTokens'] + $topicSentenceData['promptTokens'] + $coherence_cohesionData['promptTokens'] + $lexicalResourceData['promptTokens'] + $grammaData['promptTokens'];
        //check token from cms duy
        $checkToken = $this->checkTokenFromCms($request);
        if($checkToken == 'test') {
            //create record in table cms_questions
        }
        //response
        $response = [
            'introduction' => $introductionRes,
            'topic_sentence' => [
                'topic_sentence' => $topicSentenceRes,
                'conclusion' => $conclusionRes,
            ],
            'band_task_response' => $taskResponseRes,
            'coherence_cohesion_response' => $coherence_cohesionRes,
            'lexical_resource' => $lexicalResourceRes,
            'gramma' => $grammaRes,
            'totalToken' => $totalToken,
            'completionTokens' => $completionTokens,
            'promptTokens' => $promptTokens,
        ];
        $title = 'test postman';
        if(!empty($jsonData['title'])) {
            $title = $jsonData['title'];
        }
        //save db
        $testOpenaiData = [
            'name' => $title,
            'topic' => $topic,
            'question' => $question,
            'category_id' => 4,
            'answer' => json_encode($response),
            'total_token' => $totalToken,
            'prompt_token' => $promptTokens,
            'complete_token' => $completionTokens,
        ];
        if(!empty($jsonData['type']) && $jsonData['type'] == 'detail') {
            return $this->responseSuccess(200, $response);
        }else {
            TestOpenai::create($testOpenaiData);
            return $this->responseSuccess(200, $response);
        }
        
    }

    public function checkTokenFromCms($request)
    {
        if(empty($request['token'])) {
            return true;
        }
        if($request['token'] == 'test') {
            return 'test';
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

    public function conclusion($request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
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
           'max_tokens' => 1000
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
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $response,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
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

    public function topicSentence($request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify all topic sentence in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses, then improvement with example for each topic sentence in the Body Paragraphs, structured as:topic_sentence, improvement_examples, comments include strengths and weaknesses, where strengths and weaknesses are observations of the strong and weak points of each topic sentence. Response is JSON format"
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
        $topicSentence = $comment = $improvements = [];
        foreach(reset($dataResponseChat) as $key => $value)
        {
            $topicSentence[] = $value->topic_sentence;
            $comment[] = $value->comments;
            $improvements[] = $value->improvement_examples;
        }

        $response = [
            'topicSentence' => $topicSentence,
            'comment' => $comment,
            'improvements' => $improvements,
        ];
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $response,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
    }

    public function introductionTest(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $yourApiKey = getenv('OPENAI_API_KEY');
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           // 'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy. Identify introduction and show introduction of IELTS Writing Task 2. Please explain to me and give comments on the strengths and weaknesses of my IELTS Writing Task 2. Then provide suggestions for improving the introduction, structured as: introduction, strengths, weaknesses, improvement"
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
        // $dataResponseChat = json_decode($dataResponseChat);
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
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $chat = $client->chat()->create([
            'model' => $model,
           // 'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy. Identify introduction and show introduction of IELTS Writing Task 2. Please explain to me and give comments on the strengths and weaknesses of my IELTS Writing Task 2. Then provide suggestions for improving the introduction, structured as: introduction, strengths, weaknesses, improvement"
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
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
    }

    public function coherenceCohesion($request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseCoherenceCohesion($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
    }

    public function bandTaskResponse($request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseBandTaskResponse($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
    }

    public function lexicalResource(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseLexicalResource($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
    }

    public function gramma(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $chat = Common::responseGramma($jsonData);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat,true);
        $totalToken = $chat->usage->totalTokens;
        $res = [
            'dataResponseChat' => $dataResponseChat,
            'totalToken' => $totalToken,
            'completionTokens' => $chat->usage->completionTokens,
            'promptTokens' => $chat->usage->promptTokens,
        ];
        return $res;
    }

    public function test(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        // $model = 'gpt-3.5-turbo';
        $model = getenv('OPENAI_API_MODEL');
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
}