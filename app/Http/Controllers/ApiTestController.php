<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Storage;
use \OpenAI;
use Illuminate\Http\Request;

class ApiTestController extends Controller
{
    public function ieltsWriteTask2(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
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
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify all topic sentence in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses.Improvement with example for each topic sentence in the Body Paragraphs. Response is JSON format"
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
        $topicSentence = $this->getValueFromArray('Topic', $dataResponseChat);
        $strengths = implode("\n", $this->getValueFromArray('Strengths', $dataResponseChat->Comments));
        $weaknesses = implode("\n", $this->getValueFromArray('Weaknesses', $dataResponseChat->Comments));
        $improvements = $this->getValueFromArray('Improvement', $dataResponseChat);
        $response = [
            'topicSentence' => $topicSentence,
            'comment' => [
                'strengths' => $strengths,
                'weaknesses' => $weaknesses,
            ],
            'improvements' => $improvements,
        ];
        return $this->responseSuccess(200, $response);
    }

    public function introductionTest(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
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
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
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
        
        return $dataResponseChat;
    }

    public function bandTaskResponse(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = 'ft:gpt-3.5-turbo-0125:openai-startup::9I8gnIVb';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = "Criterion 'Address all parts of the question.': \n -If the prompt is appropriately addressed and explored in depth, the band=9 \n If the prompt is appropriately and sufficiently addressed, the band=8\n-If the main parts of the prompt are appropriately addressed, the band=7\n-If the main parts of the prompt are addressed (though some may be more fully covered than others) and an appropriate format is used, the band = 6\n-If the main parts of the prompt are incompletely addressed and the format may be inappropriate in places, the band=5\n-If the prompt is tackled in a minimal way, or the answer is tangential, possibly due to some misunderstanding of the prompt and the format may be inappropriate, the band=4\n-If No part of the prompt is adequately addressed, or the prompt has been misunderstood, the band=3\n-If the content is barely related to the prompt, the band=2\n-If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1\nCriterion 'Present a clear and developed position throughout.':\n -If a clear and fully developed position is presented which directly answers the question/s, the band=9\n -If a clear and well-developed position is presented in response to the question/s, the band=8\n -If aclear and developed position is presented,the band=7\n -If a position is presented that is directly relevant to the prompt,although the conclusions drawn may be unclear, unjustified or repetitive, the band=6\n -If the writer expresses a position, but the development is not always clear,the band=5\n -If a position is discernible, but the reader has to read carefully to find it,the band=4\n -If no relevant position can be identified, and/or there is little direct response to the question/s,the band=3\n -If no position can be identified,the band=2\n -If responses of 20 words or fewer are rated at Band 1 and The content is wholly unrelated to the prompt,the band=1\nCriterion 'Present, develop, support ideas.':\n -If Ideas are relevant, fully extended and well supported.Any lapses in content or support are extremely rare, the band=9\n -If Ideas are relevant, well extended and supported.There may be occasional omissions or lapses in content, the band=8\n -If Main ideas are extended and supported but there may be a tendency to over-generalise or there may be a lack of focus and precision in supporting ideas/material, the band=7\n -If Main ideas are relevant, but some may be insufficiently developed or may lack clarity, while some supporting arguments and evidence may be less relevant or inadequate, the band=6\n -If Some main ideas are put forward, but they are limited and are not sufficiently developed and/or there may be irrelevant detail. There may be some repetition, the band=5\n -If Main ideas are difficult to identify and such ideas that are identifiable may lack relevance, clarity and or support. Large parts of the response may be repetitive, the band=4\n -If There are few ideas, and these may be irrelevant or insufficiently developed, the band=3\n -If There may be glimpses of one or two ideas without development, the band=2\n -If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1";

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
        // dd($chat);
        $dataResponseChat = $chat->choices[0]->message->content;
        return $dataResponseChat;
    }

    public function coherenceCohesion(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = "Criterion 'Organize Information logically with clear progression throughout the response.': \n -If the prompt can be followed effortlessly. Cohesion is used in such a way that it very rarely attracts attention, the band=9 \n If the prompt can be followed with ease.Information and ideas are logically sequenced, and cohesion is well managed, the band=8\n-If information and ideas are logically organised, and there is a clear progression throughout the response. (A few lapses may occur, but these are minor.), the band=7\n-If Information and ideas are generally arranged coherently and there is a clear overall progression, the band = 6\n-If the maOrganisation is evident but is not wholly logical and there may be a lack of overall progression. Nevertheless, there is a sense of underlying coherence to the response. The relationship of ideas can be followed but the sentences are not fluently linked to each other, the band=5\n-If Information and ideas are evident but not arranged coherently and there is no clear progression within the response. Relationships between ideas can be unclear and/or inadequately marked, the band=4\n-If There is no apparent logical organisation. Ideas are discernible but difficult to relate to each other, the band=3\n-If There is little relevant message, or the entire response may be off-topic, the band=2\n-If Responses of 20 words or fewer are rated at Band 1, the band=1\nCriterion 'Use cohesive devices including reference and substitution .':\n -If Cohesion is used in such a way that it very rarely attracts attention. Any lapses in coherence or cohesion are minimal, the band=9\n -If Occasional lapses in coherence and cohesion may occur, the band=8\n -If A range of cohesive devices including reference and substitution is used flexibly but with some inaccuracies or some over/under use,the band=7\n -If Cohesive devices are used to some good effect but cohesion within and/or between sentences may be faulty or mechanical due to misuse, overuse or omission. The use of reference and substitution may lack flexibility or clarity and result in some repetition or error, the band=6\n -IfThere may be limited/overuse of cohesive devices with some inaccuracy. The writing may be repetitive due to inadequate and/or inaccurate use of reference and substitution,the band=5\n -If There is some use of basic cohesive devices, which may be inaccurate or repetitive. There is inaccurate use or a lack of substitution or referencing,the band=4\n -If There is minimal use of sequencers or cohesive devices. Those used do not necessarily indicate a logical relationship between ideas. There is difficulty in identifying referencing,the band=3\n -If There is little relevant message, or the entire response may be off-topic. There is little evidence of control of organisational features,the band=2\n -If responses of 20 words or fewer are rated at Band 1 and The content is wholly unrelated to the prompt,the band=1\nCriterion 'Paraphrasing.':\n -If Paragraphing is skilfully managed, the band=9\n -If Paragraphing is used sufficiently and appropriately, the band=8\n -If Paragraphing is generally used effectively to support overall coherence, and the sequencing of ideas within a paragraph is generally logical, the band=7\n -If Paragraphing may not always be logical and/or the central topic may not always be clear, the band=6\n -If Paragraphing may be inadequate or missing, the band=5\n -If There may be no paragraphing and/or no clear main topic within paragraphs, the band=4\n -If Any attempts at paragraphing are unhelpful, the band=3\n -If There is little evidence of control of organisational features, the band=2\n -If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1";

        $chat = $client->chat()->create([
            'model' => $model,
           // 'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the Coherence & Cohesion of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . " Provide the score for each criterion and explain with accompanying examples why the score is as it is. Then offer suggestions for improving the scores for each criterion, structured as: score, explanation, accompanying examples, improvement suggestions"
               ],
               [
                   "role" => "user",
                   "content" => "Provide the score for each criterion and explain why the score is as it is. Then offer suggestions for improving the scores for each criterion, structured as: score, explanation, accompanying examples, improvement suggestions.. This is my IELTS Writing Task 2 essay:\n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        // $dataResponseChat = json_decode($dataResponseChat);
        dd($dataResponseChat);
        return $this->responseSuccess(200, $dataResponseChat);
        // return $dataResponseChat;
    }

    public function test(Request $request)
    {
        $jsonData = $this->getDataFromRequest($request);
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
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
}