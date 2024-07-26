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
        ];
        if($partNumber) {
            return $data[$partNumber];
        }
        return $data;
    }

    public static function callCms($dataResponseChat, $questionId, $partNumber)
    {
        $partInfo = self::getPartInfo($partNumber);
        $dataResponseChat = json_decode($dataResponseChat,true);
        $data = [
            'question_id' => $questionId,
            'part_number' => $partNumber,
            'part_info' => $partInfo,
            'data' => $dataResponseChat
        ];

        $data_string = json_encode($data);
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
    }

    public static function callCmsTask1($dataResponseChat, $questionId, $partNumber)
    {
        $partInfo = self::getPartInfo($partNumber);
        $dataResponseChat = json_decode($dataResponseChat,true);
        $data = [
            'question_id' => $questionId,
            'part_number' => $partNumber,
            'part_info' => $partInfo,
            'data' => $dataResponseChat
        ];

        $data_string = json_encode($data);
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
        curl_close($curl);
    }

    public static function getSystemPromptCommonTask1()
    {
        return " Provide the score for each criterion and explain with accompanying examples why the score is as it is.Then offer suggestions for improving the scores for each criterion. After that give overall score is the rounded average of the scores of the graded criteria according to the IELTS scoring rounding rule and explain in 4 to 5 sentences for overall why this overall score is not in a lower band nearest or a higher band nearest. Reponse is json format structured as: \n each criteria: \n - criteria_name is name criteria \n score_criteria is score \n -explain is explain \n -accompanying_examples is accompanying examples\n -improvement_suggestions is improvement suggestions \n and\n overall: \n -overall_score is overall score \n -belower_score is score nearest band lower \n -reason_not_belower_score is reason why the score is not in a lower band \n -higher_score is nearest band higher \n -reason_not_higher_score is reason why the score is not in a higher band";
    }

    public static function getSystemPromptCommon()
    {
        return " Provide the score for each criterion and explain with accompanying examples why the score is as it is.Then offer suggestions for improving the scores for each criterion.After that give overall score and explain in 4 to 5 sentences for overall why this overall score is not in a lower band nearest or a higher band nearest. Reponse is json format structured as: \n each criteria: \n - criteria_name is name criteria \n score_criteria is score \n -explain is explain \n -accompanying_examples is accompanying examples\n -improvement_suggestions is improvement suggestions \n and\n overall: \n -overall_score is overall score \n -belower_score is score nearest band lower \n -reason_not_belower_score is reason why the score is not in a lower band \n -higher_score is nearest band higher \n -reason_not_higher_score is reason why the score is not in a higher band";
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
                   "content" => "This is my IELTS Writing Task 2 essay:\n" . $question
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
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
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\nIdentify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 2. Reponse is json format structured as: error, explanations, corrections. for each error"
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
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];

        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.This is the prompt for the IELTS Writing Task 1 essay: \n" . $topic . "\nIdentify vocabulary and grammar errors, then provide explanations and corrections to align them with the requirements of IELTS Writing Task 1. Reponse is json format structured as: error, explanations, corrections. for each error"
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
        return $chat;
    }

    public static function task1BandLexicalResource($jsonData, $messageTopic = null)
    {
        $yourApiKey = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
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
        $model = getenv('OPENAI_API_MODEL');
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
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];
        $topic = $jsonData['topic'];
        if(!$messageTopic) {
            $messageTopic = $topic;
        }
        $system_prompt = Question::task1CoherenceCohesion();
        $commonPrompt = self::getSystemPromptCommonTask1();
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
        $model = getenv('OPENAI_API_MODEL');
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
        $question = $jsonData['question'];
        $chat = $client->chat()->create([
            'model' => $model,
           'response_format'=>["type"=>"json_object"],
           'messages' => [
               [
                   "role" => "system",
                   "content" => "You are a friendly IELTS preparation teacher and today you are very happy.Identify 3 or 4 of the main features and write about them generally without referencing any data of an IELTS Essay Task 1, give comments on the strengths and weaknesses, then improvement with example for each, structured as:topic_sentence, improvement_examples, comments include strengths and weaknesses, where strengths and weaknesses are observations of the strong and weak points of each. Response is JSON format"
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
        $model = getenv('OPENAI_API_MODEL');
        $question = $jsonData['question'];

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

}
