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
            'Overall' => implode("\n", $dataResponseChat->Overall),
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
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify only the topic sentence and the main points in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses.Improvement with example for each topic sentence and main points in the Body Paragraphs. Response is JSON format"
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify the topic sentence and the main points in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses. Then help me to improve with examples of improvement for each topic sentence and main points in the Body Paragraphs.This is my IELTS Essay Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $dataResponseChat = json_decode($dataResponseChat);
        // dd($dataResponseChat);
        $response = [];
        foreach($dataResponseChat as $key => $value)
        {
            $response[$key] = [
                'TopicSentence' => $this->getValueFromArray('Topic',$value),
                'MainPoints' => implode("\n", $this->getValueFromArray('Point',$value)),
                'Comments' => [
                    'Strengths' => $value->Comments->Strengths,
                    'Weaknesses' => $value->Comments->Weaknesses,
                ],
                'Improvements' => [
                    'Topic_sentence_improve' => $this->getValueFromArray('Topic',$value->Improvements),
                    'Main_points' => $this->getValueFromArray('Point',$value->Improvements),
                ]
            ];
        }
        return $this->responseSuccess(200, $response);
    }

    public function introduction(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        dd(111);
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
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Please grade the IELTS Writing Task 2 essay based on the following criteria: \n- The writer addresses all the requirements of the question \n- The writer addresses all the requirements of the question in a balanced manner \n- The writer presents opinions clearly and consistently throughout the essay \n- The writer develops ideas with relevant arguments \n- The writer fully develops the ideas.\n All the above criteria are scored according to the following rules: \n -If not met, then Band ≤ 3\n -Needs improvement, then 4 ≤ Band ≤ 5 \n -Satisfactory, then Band = 6 \n-Good, then Band = 7 \n-Very good, then 8 ≤ Band ≤ 9 \n-The overall score will be the average of the total scores for the five criteria.\n This is the prompt for the IELTS Writing Task 2 essay:\n Even though Globalization affects the world’s economics in a very positive way, its negative sides should not be forgotten. Discuss. \n Response is JSON format"
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the my IELTS Writing Task 2 essay. Show me grade for each criteria and explain why the scoring is done this way for each criterion. This is my IELTS Writing Task 2: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        $data = json_decode($dataResponseChat)->criteria;
        // dd(json_decode($dataResponseChat));
        $response = [];
        foreach($data as $value) {
            $response[] = [
                'criterion' => $value->criterion,
                'score' => $value->score,
                'explanation' => $value->explanation,
            ];
        }
        $response['overall_score'] = json_decode($dataResponseChat)->overall_score;
        return $this->responseSuccess(200, $response);
    }
}