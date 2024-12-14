<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

class Common extends Model
{
    const PART_IDENTIFY_ERROR_RESPONSE = 8;
    const PART_NUMBER_GRAMMA_RESPONSE = 7;
    const PART_NUMBER_LEXICAL_RESPONSE = 6;
    const PART_NUMBER_COHERENCE_COHESION_RESPONSE = 5;
    const PART_NUMBER_BAND_TASK_RESPONSE = 4;
    const PART_NUMBER_CONCLUSION_RESPONSE = 3;
    const PART_NUMBER_TOPIC_SENTENCE_RESPONSE = 2;
    const PART_NUMBER_INTRODUCTION_RESPONSE = 1;
    const PART_NUMBER_TASK_ACHIEVEMENT = 4;

    public static function getPartInfo($partNumber = null)
    {
        $data = [
            self::PART_NUMBER_INTRODUCTION_RESPONSE => 'introduction',
            self::PART_NUMBER_TOPIC_SENTENCE_RESPONSE => 'topic_sentence',
            self::PART_NUMBER_CONCLUSION_RESPONSE => 'conclusion',
            self::PART_NUMBER_BAND_TASK_RESPONSE => 'band_task_response',
            self::PART_NUMBER_COHERENCE_COHESION_RESPONSE => 'coherence_cohesion',
            self::PART_NUMBER_LEXICAL_RESPONSE => 'lexical',
            self::PART_NUMBER_GRAMMA_RESPONSE => 'gramma',
            self::PART_IDENTIFY_ERROR_RESPONSE => 'vocalburary_error',
        ];
        if($partNumber) {
            return $data[$partNumber];
        }
        return $data;
    }

    public static function callCms($dataResponseChat, $questionId, $partNumber, $checkData = null)
    {
        $partInfo = self::getPartInfo($partNumber);
        $dataResponseChat = json_decode($dataResponseChat,true);
        // foreach($dataResponseChat as $key => $value)
        // {
        //     if(str_contains($key, 'criteria')) {
        //         $dataResponseChat['criteria'] = $value;
        //     }
        // }
        // if($checkData) {
        //     $data = [
        //         'question_id' => $questionId,
        //         'part_number' => $partNumber,
        //         'status_code' => 500,
        //         'part_info' => $partInfo,
        //         'data' => $dataResponseChat
        //     ];
        // } else {
            $data = [
                'question_id' => $questionId,
                'part_number' => $partNumber,
                'part_info' => $partInfo,
                'data' => $dataResponseChat
            ];
        // }
        
        $data_string = json_encode($data, true);
        $data_string = trim($data_string, '"');
        $curl = curl_init('https://apiems.microgem.io.vn/hook/resultWritingTask2');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-api-key: d84c814c155bed7147115b5ec91c28246460cd8998ea6e57e89896362c164491',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($curl);
        curl_close($curl);
        if(empty($result)) {
            $check = 'false';
        } else {
            $check = 'true';
        }
        Log::info('task2 Part ' . $partNumber . ' of question_id: ' . $questionId . ' have status is ' . $check);
    }

    public static function callCmsTask1($dataResponseChat, $questionId, $partNumber)
    {
        $partInfo = self::getPartInfo($partNumber);
        $errorJson = $dataResponseChat;
        $dataResponseChat = json_decode($dataResponseChat,true);
        // if (json_last_error() === JSON_ERROR_NONE) {
        $data = [
            'question_id' => $questionId,
            'part_number' => $partNumber,
            'part_info' => $partInfo,
            'data' => $dataResponseChat
        ];
        $data_string = json_encode($data, true);
        $data_string = trim($data_string, '"');
        $curl = curl_init('https://apiems.microgem.io.vn/hook/resultWritingTask1');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-api-key: d84c814c155bed7147115b5ec91c28246460cd8998ea6e57e89896362c164491',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($curl);
        if(empty($result)) {
            $check = 'false';
        } else {
            $check = 'true';
        }
        curl_close($curl);
        // } else {
        //     Log::info('task1 Part ' . $partNumber . ' of question_id: ' . $questionId . ' error json ' . $errorJson);
        // }
        
        Log::info('task1 Part ' . $partNumber . ' of question_id: ' . $questionId . ' have status is ' . $check);
    }

    public static function getSystemPromptCommonTask1()
    {
        return " Provide the score for each criterion and explain with accompanying examples why the score is as it is.Then offer suggestions for improving the scores for each criterion. After that give overall score is the rounded average of the scores of the graded criteria according to the IELTS scoring rounding rule and explain in 4 to 5 sentences for overall why this overall score is not in a lower band nearest or a higher band nearest. Reponse is json format structured as: \n criteria: \n - criteria_name is name criteria \n score_criteria is score \n -explain is explain \n -accompanying_examples is accompanying examples\n -improvement_suggestions is improvement suggestions \n and\n overall: \n -overall_score is overall score \n -belower_score is score nearest band lower \n -reason_not_belower_score is reason why the score is not in a lower band \n -higher_score is nearest band higher \n -reason_not_higher_score is reason why the score is not in a higher band";
    }

    public static function getSystemPromptCommon()
    {
        // return " Provide the score for each criterion and explain with accompanying examples why the score is as it is.Then offer suggestions for improving the scores for each criterion.After that give overall score and explain in 4 to 5 sentences for overall why this overall score is not in a lower band nearest or a higher band nearest. Reponse is json format structured as: \n each criteria: \n - criteria_name is name criteria \n score_criteria is score \n -explain is explain \n -accompanying_examples is accompanying examples\n -improvement_suggestions is improvement suggestions \n and\n overall: \n -overall_score is overall score \n -belower_score is score nearest band lower \n -reason_not_belower_score is reason why the score is not in a lower band \n -higher_score is nearest band higher \n -reason_not_higher_score is reason why the score is not in a higher band";

        return " Provide the score for each criterion and explain with accompanying examples why the score is as it is.Then offer suggestions for improving the scores for each criterion. After that give overall score is the rounded average of the scores of the graded criteria according to the IELTS scoring rounding rule and explain in 4 to 5 sentences for overall why this overall score is not in a lower band nearest or a higher band nearest. Reponse is json format structured as: \n criteria: \n - criteria_name is name criteria \n score_criteria is score \n -explain is explain \n -accompanying_examples is accompanying examples\n -improvement_suggestions is improvement suggestions \n and\n overall: \n -overall_score is overall score \n -belower_score is score nearest band lower \n -reason_not_belower_score is reason why the score is not in a lower band \n -higher_score is nearest band higher \n -reason_not_higher_score is reason why the score is not in a higher band.\n Make sure the output data is structured in JSON according to the specified format, and that each section contains content"; 
    }

    //start task 2
    public static function responseCoherenceCohesion($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = Question::criterionCoherenceCohesion();
        $commonPrompt = self::getSystemPromptCommon();
        $userContent = "This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the Coherence & Cohesion of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . $commonPrompt . "\r\n";
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                    "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the Coherence & Cohesion of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => $userContent . "This is my IELTS Writing Task 2 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1500
        ]);
        return $chat;
    }

    public static function responseLexicalResource($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = Question::criterionLexicalResource();
        $commonPrompt = self::getSystemPromptCommon();
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the Lexical resource of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 2 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function responseBandTaskResponse($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = Question::criterionTaskResponse();
        $commonPrompt = self::getSystemPromptCommon();
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the task response of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 2 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function responseGramma($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = Question::criterionGramma();
        $commonPrompt = self::getSystemPromptCommon();
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n" . "Please grade the Grammatical range & accuracy of my IELTS Writing Task 2 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 2 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function task2IdentifyErrors($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        // $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                //    "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 2 essay. Please help me identify any incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content. Please sure that fixed content is different error" . "This is my IELTS Writing Task 2 essay: \n" . $question

                    "content"=>"You are a friendly IELTS preparation teacher and today you are very happy"

                //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\nIdentify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 2. Reponse is json format structured as: error, explanations, corrections. for each error"
               ],
               [
                   "role" => "user",
                //    "content" => "This is my IELTS Writing Task 2: \n" . $question
                   "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 2 essay. Please help me identify any incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content. Please sure that fixed content is different error" . "This is my IELTS Writing Task 2 essay: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2048
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        return $chat;
    }

    public static function responseIntroduction($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
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
        // $dataResponseChat = json_decode($dataResponseChat);
        return $chat;
    }

    public static function responseTopicSentence($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify all topic sentence in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses, then improvement with example for each topic sentence in the Body Paragraphs, structured as:topic_sentences is topic sentences include topic sentences and each topic sentence has structure as topic_sentence:\n , improvement_examples is improvement examples, comments is comments include strengths and weaknesses, where strengths and weaknesses are observations of the strong and weak points of each topic sentence. Response is JSON format"
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
        return $chat;
    }
    
    public static function responseConclusion($jsonData)
    {
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
           'max_tokens' => 2000
        ]);
        return $chat;
    }
    //end task 2

    //start task 1
    public static function task1IdentifyErrors($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "I want to improve the vocabulary and grammar in my IELTS Writing Task 1 essay. Please help me identify any incorrect or improvable vocabulary and grammar with quotes, limited to 20 words per quote. Then, then explanation for each error after that fix each error. Reponse is json format with the following structure: errors is a list of errors, where each error object has the following structure: error is the vocabulary or grammar error, explanations is the explanation of vocabulary or grammar error, correction is the fixed content. Please sure that fixed content is different error" . "This is my IELTS Writing Task 1 essay: \n" . $question
                //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay: \n" . $topic . "\nIdentify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 1. Reponse is json format structured as: error, explanations, corrections. for each error"
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        return $chat;
    }

    public static function checkChildValue($questionId, $data)
    {
        $dataResponseChat = json_decode($data,true);
        if(!isset($dataResponseChat['criteria']) || empty($dataResponseChat['criteria'])) {
            return false;
        }
        foreach($dataResponseChat['criteria'] as $key => $value)
        {
            if(empty($value)) {
                Log::error('questionId: ' . $questionId . 'error at key primary: ' . $key);
                return false;
            }
            foreach($value as $k => $v)
            {
                if(empty($v)) {
                    Log::error('questionId: ' . $questionId . 'empty value at key child: ' . $k);
                    return false;
                }
                if(!in_array($k, ['criteria_name', 'score_criteria', 'explain', 'accompanying_examples', 'improvement_suggestions'])) {
                    Log::error('questionId: ' . $questionId . 'key child is error: ' . $k);
                    return false;
                }
            }
            foreach(['criteria_name', 'score_criteria', 'explain', 'accompanying_examples', 'improvement_suggestions'] as $valueDefault)
            {
                if(!isset($value[$valueDefault])) {
                    Log::error('questionId: ' . $questionId . ' have not key chid: ' . $valueDefault);
                    return false;
                }
            }
        }
        return true;
    }

    public static function bandFormatData($data)
    {   
        $dataResponseChat = json_decode($data,true);
        $res = [];
        if(!isset($dataResponseChat['criteria']) || empty($dataResponseChat['criteria'])) {
            return $res;
        }

        foreach($dataResponseChat['criteria'] as $value)
        {
            $resValue = [];
            foreach($value as $k => $v)
            {
                if(str_contains($k, 'criteria_name')) {
                    $resValue['criteria_name'] = $v;
                }
                if(str_contains($k, 'score_criteria')) {
                    $resValue['score_criteria'] = $v;
                }
                if(str_contains($k, 'explain')) {
                    $resValue['explain'] = $v;
                }
                if(str_contains($k, 'example')) {
                    $resValue['accompanying_examples'] = $v;
                }
                if(str_contains($k, 'improvement')) {
                    $resValue['improvement_suggestions'] = $v;
                }
            }
            $res['criteria'][] = $resValue;
        }
        $dataResponseChat['criteria'] = $res['criteria'];
        return $dataResponseChat;
    }

    public static function formatData($data)
    {
        $dataResponseChat = json_decode($data,true);
        $res = [];
        // dd($dataResponseChat);
        if(isset($dataResponseChat['errors'])) {
            foreach($dataResponseChat['errors'] as $value)
            {
                $resValue = [];
                foreach($value as $k => $v)
                {
                    // dd($k);
                    if(str_contains($k, 'explanation')) {
                        $resValue['explanations'] = $v;
                    }
                    if(str_contains($k, 'correction')) {
                        $resValue['corrections'] = $v;
                    }
                    if(str_contains($k, 'error')) {
                        $resValue['error'] = $v;
                    }
                }
                $res['errors'][] = $resValue;
            }
        }
        // $res['errors'] = $resValue;
        return $res;
        
    }

    public static function task1BandLexicalResource($jsonData, $messageTopic = null)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        if(!$messageTopic) {
            $messageTopic = $topic;
        }
        $system_prompt = Question::task1LexicalResource();
        $commonPrompt = self::getSystemPromptCommonTask1();
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay:\n" . $messageTopic . "\n" . "Based on the prompt and the content of the chart, please grade the Lexical resource of my IELTS Writing Task 1 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function task1BandTaskAchievement($jsonData, $messageTopic = null)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        if(!$messageTopic) {
            $messageTopic = $topic;
        }
        $system_prompt = Question::task1TaskAchievement();
        $commonPrompt = self::getSystemPromptCommonTask1();
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay:\n" . $messageTopic . "\n" . "Based on the prompt and the content of the chart, please grade the Task Achievement of my IELTS Writing Task 1 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function task1BandCoherenceCohesion($jsonData, $messageTopic = null)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        if(!$messageTopic) {
            $messageTopic = $topic;
        }
        $system_prompt = Question::task1CoherenceCohesion();
        $commonPrompt = self::getSystemPromptCommonTask1();
        $message = [
            [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay: " . $topic . "\n" . "Based on the prompt and the content of the charts, please grade the Coherence & Cohesion of my IELTS Writing Task 1 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
           [
               "role" => "user",
               "content" => "This is my IELTS Writing Task 1 essay:\n" . $question
           ],
        ];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay:\n" . $messageTopic . "\n" . "Based on the prompt and the content of the chart, please grade the Coherence & Cohesion of my IELTS Writing Task 1 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function task1BandGramma($jsonData, $messageTopic = null)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        if(!$messageTopic) {
            $messageTopic = $topic;
        }
        $system_prompt = Question::task1Gramma();
        $commonPrompt = self::getSystemPromptCommonTask1();
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay:\n" . $messageTopic . "\n" . "Based on the prompt and the content of the chart, please grade the Grammatical range & accuracy of my IELTS Writing Task 1 essay based on the following criteria:\n" . $system_prompt . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function task1Introduction($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy. Identify introduction and show introduction of IELTS Writing Task 1. Please explain to me and give comments on the strengths and weaknesses of my IELTS Writing Task 1. Then provide suggestions for improving the introduction. Response is JSON with format following rule: introduction, strengths, weaknesses, improvement"
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        $dataResponseChat = $chat->choices[0]->message->content;
        // $dataResponseChat = json_decode($dataResponseChat);
        return $chat;
    }

    public static function task1TopicSentence($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = 'gpt-4-turbo';
        $model = getenv('OPENAI_API_MODEL');
        $model = 'gpt-4o';
        $question = $jsonData['question'];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                //    "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify 3 or 4 of the main features and write about them generally without referencing any data of an IELTS Essay Task 1, give comments on the strengths and weaknesses, then improvement with example for each, structured as:topic_sentence, improvement_examples, comments include strengths and weaknesses, where strengths and weaknesses are observations of the strong and weak points of each. Response is JSON format",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify all topic sentence in the Body Paragraphs of an IELTS Essay Task 2, give comments on the strengths and weaknesses, then improvement with example for each topic sentence in the Body Paragraphs, structured as:topic_sentences is topic sentences include topic sentences and each topic sentence has structure as topic_sentence:\n , improvement_examples is improvement examples, comments is comments include strengths and weaknesses, where strengths and weaknesses are observations of the strong and weak points of each topic sentence. Response is JSON format",
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Essay Task 1: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }
    
    public static function task1Conclusion($jsonData)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        // $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $model = 'gpt-4o';
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify conclusion of an IELTS Essay Task 1, give comments on the strengths and weaknesses. Improvement with example for conclusion. After that provide an overall consisting of 4 to 6 concise sentences indicating what needs to be improved. Response is JSON with format following rule: Conclusion is string, Comments has Strengths is string and Weaknesses is string, Improvements is string and Overall is array" 
               ],
               [
                   "role" => "user",
                   "content" => "could you help me to identify conclusion of an IELTS Essay Task 1, give comments on the strengths and weaknesses. Then help me to improve with examples of conclusion. After that provide an overall consisting of 4 to 6 concise sentences indicating what needs to be improved. This is my IELTS Essay Task 1: \n" . $question
               ],

            ],
           'temperature' => 0,
           'max_tokens' => 2000
        ]);
        return $chat;
    }
    //end task 1

    public static function testWrite()
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        $system_prompt = Question::task1LexicalResource();
        $commonPrompt = self::getSystemPromptCommonTask1();
        // dd("You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay: \n" . $messageTopic . "\n" . "Please grade the Lexical resource of my IELTS Writing Task 1 essay based on the following criteria:\n" . $system_prompt . $commonPrompt);
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Please write IELTS Writing Task 1 essay for " . $commonPrompt
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }

    public static function hocmaiSystemPromptTaskAchiement()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 1 of the IELTS Writing test, focusing only on the **Task Achievement (TA)** criterion. Use the provided Band 8 sample response for comparison.";

    }

    //hocmai start
    //task_achiement start
    public static function hocmaiTask1TaskAchiement()
    {
        return "Band 9: Reports all information with no lapses, including a clear overview and detailed description and comparisons. Information is presented logically and follows the proper format of an IELTS Writing Task 1 (Introduction, overview of main trends/changes/stages, body paragraphs). There is no irrelevant or inaccurate information.\n Band 8: Reports all information with very few lapses, including a clear overview and detailed description and comparisons. Information is presented logically and follows the proper format of an IELTS Writing Task 1 (Introduction, overview of main trends/changes/stages, body paragraphs). There is no irrelevant or inaccurate information.\n Band 7: Reports all main points. However, details might be missing, but this is only occasional. Information is generally presented logically and follows the proper format of an IELTS Writing Task 1 (Introduction, overview of main trends/changes/stages, body paragraphs). Presents a clear overview. There is no irrelevant or inaccurate information.\n Band 6: Reports most of the main points. Follows the format of an IELTS Writing task 1 report. May sometimes lack data or comparison to support description. There might occasionally be inaccurate or irrelevant information.\n Band 5: The response is 135-145 words. Only cover half of the main points. Report is mechanical and may focus too much details without referring to general trends/changes/stages. May not follow the format of an IELTS Writing task 1 report. There might be frequent inaccurate or irrelevant information.\n Band 4: The response is 100-135 words. Only cover roughly a third of the main points. Does not follow the format of an IELTS Writing task 1 report, or structure is highly disorganised. Does not have an overview. Virtually no data or detail to support description. Maybe off-topic.\n Band 3: The response is 60-100 words. The report minimally covers the information. Does not follow the format of an IELTS Writing task 1 report. Does not have an overview.  No data or detail to support description. Maybe off-topic.\n Band 2: The response is 20-60 words. Only covers minimal information or can be totally off-topic. No attempt to develop ideas. Does not have an overview. \n Band 1: The response is less than 20 words.";
    }

    public static function hocmaiTask1BandTaskAchiement($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systemessage = self::hocmaiSystemPromptTaskAchiement();
        $rule = self::hocmaiTask1TaskAchiement();
        $sample = $jsonData['sample'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
           'messages' => [
                [
                   "role" => "system",
                   "content" => $systemessage . '\n Marking Rubric for Task Achievement:' . $rule . '\n' . "This is Band 8 sample:\n" . $sample,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the IELTS Writing Task 1 essay without mentioning the Band 8 sample. This is my IELTS Writing Task 1 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);

        return $chat;
    }
    //task_achiement end
    //coherence_cohesion start
    public static function hocmaiTask1BandCoherenceCohesion($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systemessage = self::hocmaiSystemPrompt();
        $rule = self::hocmaiTask1TaskAchiement();
        $sample = $jsonData['sample'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
           'messages' => [
                [
                   "role" => "system",
                   "content" => $systemessage . '\n Marking Rubric for Task Achievement:' . $rule . '\n' . "This is Band 8 sample:\n" . $sample,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the IELTS Writing Task 1 essay without mentioning the Band 8 sample. This is my IELTS Writing Task 1 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        
        return $chat;
    }

}
