<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use \OpenAI;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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

    public function topicSentence(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = 'gpt-4-turbo';
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify the topic sentence and the main points in the Body Paragraphs of an IELTS Essay Task 2, along with explanations and suggestions for improvement for each topic sentence and main points. Response is JSON format"
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify the topic sentence and the main points in the Body Paragraphs of an IELTS Essay Task 2, along with explanations and suggestions for improvement for each topic sentence and main points.This is my IELTS Essay Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat);
        $res = [];
        foreach($dataResponseChat as $key => $value)
        {
            $res[$key] = [
                'TopicSentence' => $value->TopicSentence,
                'MainPoints' => implode("\n", $value->MainPoints),
                'Explanation' => $this->TopicMainPoint($value->Explanation),
                'Suggestions' => $this->TopicMainPoint($value->Suggestions),
            ];
        }
        return $this->responseSuccess(200, $response);
    }

    public function introduction(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = 'gpt-4-turbo';
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify introduction of IELTS Writing Task 2. Response is JSON format"
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify introduction of IELTS Writing Task 2. Please explain to me and give comments on the strengths and weaknesses of my IELTS Writing Task 2. Then provide suggestions for improving the introduction. This is my IELTS Writing Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat);
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

    public function test(Request $request)
    {
// 
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = 'gpt-4-turbo';
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 2. Response is JSON format"
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
        foreach(json_decode($dataResponseChat) as $value) {
            foreach($value as $result) {
                if(!empty($result->error) && !empty($result->correction) && !empty($result->explanation)) {
                    $response[] = [
                        'error' => $result->error,
                        'correction' => $result->correction,
                        'explanation' => $result->explanation,
                    ];
                }
            }
        }
        return $this->responseSuccess(200, $response);
    }
}
