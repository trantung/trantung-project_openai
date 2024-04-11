<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Storage;
use OpenAI\Laravel\Facades\OpenAI;

class ApiController extends Controllers
{
    public function test(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);

        dd($jsonData);

        $model = 'gpt-4-turbo';
        $question = $jsonData['question'];
        // $open_ai_key = getenv('OPENAI_API_KEY');
        // $open_ai_key = getenv('OPENAI_API_KEY');
        // $open_ai = new OpenAi($open_ai_key);

        $chat = OpenAI::chat()->create([
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

        $dataResponseChat = json_decode($chat);
        $data = json_decode($chat)->choices[0]->message->content;
        $response = [];
        foreach(json_decode($data) as $value) {
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
