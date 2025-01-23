<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommonHocmaiTask2 extends Model
{
    //const task2
    const VOCABULARY_GRAMMA = 1;
    const TASK_RESPONSE = 2;
    const COHERENCE_COHESION = 3;
    const LEXICAL_RESOURCE = 4;
    const GRAMMA_RANGE = 5;
    const IMPROVE_ESSAY = 6;

    public static function callCms($dataResponseChat, $questionId, $partNumber)
    {
        $contestTypeId = '';
        // $apiUeserQuestionPartData = ApiUserQuestionPart::where('user_question_id', $questionId)->first();
        // if($apiUeserQuestionPartData) {
        //     if($apiUeserQuestionPartData->contest_type_id == CategoryValue::CONTEST_TYPE_1) {
        //         $urlConfig = getenv('EMS_API_TASK2_CONTEST_TYPE_19');
        //         $contestTypeId = 19;
        //     }
        // } else {
        //     $urlConfig = getenv('EMS_API_TASK2');
        // }
        $urlConfig = getenv('EMS_API_TASK2');
        $data = [
            'question_id' => $questionId,
            'part_number' => $partNumber,
            'part_info' => $partNumber,
            'data' => $dataResponseChat,
            'contest_type_id' => $contestTypeId
        ];
        $data_string = json_encode($data, true);
        $data_string = trim($data_string, '"');
        $curl = curl_init($urlConfig);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        // curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        $result = curl_exec($curl);
        if(empty($result)) {
            $check = 'false';
        } else {
            $check = 'true';
        }
        curl_close($curl);
        Log::info('task2 Part: ' . $partNumber . ' of question_id: ' . $questionId . ' have status is ' . $check);
        return $result;
    }

    //task1 start
    //vocabulary_grammar start 
    public static function hocmaiSystemPromptVocabularyGramma()
    {
        return "You are an IELTS examiner. Please review the student's **Task 2 Essay**, identify specific lexical and grammatical errors, and suggest corrections.\n ### Instructions:\n 1. Focus on **vocabulary**: Look for errors such as incorrect word choice, inappropriate word forms, poor collocation, and repetition of words.\n2. Check **grammar**: Identify errors related to subject-verb agreement, incorrect use of tenses, word order, punctuation, articles, and sentence structure.\n3. Identify **repetitive language** and suggest more varied vocabulary or alternative expressions to avoid repetition.\n4. Provide **specific corrections** for grammatical mistakes and explain the rationale behind each correction (e.g., fixing errors in tense usage, adjusting word order, or improving sentence construction).\n5. Offer suggestions on how to enhance vocabulary usage, such as using more precise or advanced vocabulary, and improving grammatical variety.\n ### Strictly follow this format. Use simple language for students to understand:1. Vocabulary Errors:- **Vocabulary Error**: 'Many people think that crime rate is high.' **Correction**: 'Many people think that the crime rate is high.' **Explanation**: 'Crime rate' should have 'the' before it, as it refers to a specific concept. 2. Grammar Errors:- **Grammar Error**: 'He was having a good chance to improving his skills.' **Correction**: 'He had a good chance to improve his skills.' **Explanation**: The past continuous tense 'was having' is unnecessary here. The verb 'improve' should also be in the base form.";
    }

    public static function hocmaiVocabularyGramma($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptVocabularyGramma();
        $report = $jsonData['report'];

        $chat = $client->chat()->create([
            'model' => $model,
            // 'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                   "role" => "system",
                //    "content" => $systeMessage . '. Respond is json format and have structure as: lexical_errors is lexical errors, gramma_errors is grammatical errors, suggestion includes lexical_errors and grammar_errors, where lexical_errors are the suggested corrections for lexical and grammar_errors are the suggested corrections for gramma'
                   "content" => $systeMessage
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 2 essay:\n" . $report
               ],
            ],
            'temperature' => 0,
            'max_tokens' => 2048
        ]);

        return $chat;
    }
    //vocabulary_grammar end

    //task_response start
    public static function hocmaiSystemPromptTaskResponse()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 2, focusing on the **Task Response (TR)** criterion.";
    }

    public static function hocmaiRuleTaskResponse()
    {
        return "Band 9: Fully addresses all parts of the task with a clear position and fully-developed arguments (with reasons and/or examples). Ideas are presented logically and follow the format of an IELTS academic essay (Introduction, Body paragraphs, Conclusion). \n Band 8: Addresses all parts of the task effectively with a clear position and well-developed arguments (with reasons and/or examples). Ideas are presented logically and follow the format of an IELTS academic essay (Introduction, Body paragraphs, Conclusion). \n Band 7: Addresses all parts of the task with some clarity. Ideas are developed but can still be more fully extended. There may be some overgeneralization. Ideas are generally presented logically and follow the format of an IELTS academic essay (Introduction, Body paragraphs, Conclusion). \n Band 6: The writing has to be at least 250 words. Addresses the task but may lack some clarity and organization. Ideas are generally relevant but not always well-developed. They’re maybe attempts to develop the arguments with reasons and/or examples, but these can be lacking sometimes. If the question asks whether the student agrees or disagrees with a statement, they can choose to discuss both views or choose one side.\n Band 5: The response is less than 245 words. Addresses some parts of the task, but the arguments may be unclear. Ideas are not well-developed. There may be no reasons and/or examples to develop the arguments. The response may be less than 250 words. \n Band 4: The response is less than 180 words. Only partially addresses the task, with significant weaknesses in organization and development of ideas. \n Band 3: The response is less than 120 words. Addresses the task in a very limited way, with frequent confusion and disorganization. Arguments can be irrelevant. \n Band 2: The response is less than 80 words. Only minimally addresses the task, with major incoherence and lack of development. Arguments can be irrelevant.\n Band 1: The response is less than 20 words. Does not address the task at all.";
    }

    public static function hocmaiBandTaskResponse($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptTaskResponse();
        $rule = self::hocmaiRuleTaskResponse();
        $report = $jsonData['report'];
        $topic = $jsonData['topic'];

        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                    "role" => "system",
                    "content" => $systeMessage . '\n Marking Rubric for Task Response:' . $rule,
                ],
                
                [
                    "role" => "user",
                    "content" => "Please grade the Task Response of IELTS Writing Task 2 essay. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n This is my IELTS Writing Task 2 essay:\n" . $report
                ],
            ],
            'temperature' => 0,
            'max_tokens' => 1000
        ]);

        return $chat;
    }
    //task_response end

    //coherence_cohesion start
    public static function hocmaiSystemPromptCoherenceCohesion()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 2, focusing on the **Coherence & Cohesion (CC)** criterion.";
    }

    public static function hocmaiRuleCoherenceCohesion()
    {
        return "Band 9: Skillfully uses a range of cohesive devices and paragraphing to enhance coherence. Ideas flow smoothly, making the text easy to follow.\n Band 8: Effectively uses cohesive devices and paragraphing to maintain coherence. Ideas are linked coherently.\n Band 7: Generally maintains coherence through the use of cohesive devices. Some occasional lapses in coherence may occur.\n Band 6: Uses cohesive devices, but some may be used ineffectively or with overuse. Coherence may be somewhat impaired.\n Band 5: Demonstrates basic cohesion, but coherence may be weak due to ineffective use of cohesive devices.\n Band 4: Limited use of cohesive devices results in poor coherence and difficulty in following ideas. The response is less than 180 words.\n Band 3: Very limited use of cohesive devices, leading to severe issues with coherence. The response is less than 120 words.\n Band 2: Virtually no cohesion, resulting in a highly incoherent achievement. The response is less than 80 words.\n Band 1: No evidence of cohesion; the achievement is extremely disjointed. The response is less than 20 words.";
    }

    public static function hocmaiBandCoherenceCohesion($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptCoherenceCohesion();
        $rule = self::hocmaiRuleCoherenceCohesion();
        $report = $jsonData['report'];
        $topic = $jsonData['topic'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Coherence & Cohesion:' . $rule,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the Coherence & Cohesion of IELTS Writing Task 2 essay. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n This is my IELTS Writing Task 2 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);

        return $chat;
    }
    //coherence_cohesion end

    //lexical_resource start
    public static function hocmaiSystemPromptLexicalResource()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 2, focusing on the **Lexical Resource (LR)** criterion.";
    }

    public static function hocmaiRuleLexicalResource()
    {
        return "Band 9:Uses a wide range of vocabulary accurately and appropriately. Shows excellent control of lexical resources, including uncommon lexical items. Shows excellent awareness of style and collocation. Make virtually no mistakes.\n Band 8: Uses a wide range of vocabulary effectively with very few errors. Demonstrates good control of lexical resources, including uncommon lexical items. Shows high awareness of style and collocation.\n Band 7: Uses a sufficient range of vocabulary with some flexibility to talk about familiar and less familiar topics. Demonstrates relatively good control of lexical resources, including uncommon lexical items. Shows good awareness of style and collocation. May have occasional errors, but they don't hinder comprehension.\n Band 6: Demonstrates an adequate range of vocabulary to talk about familiar topics and less familiar topics. Uses vocabulary with some variation but may be repetitive or imprecise at times. Errors do not significantly impede understanding. May attempt to use less common lexical items but may not be successful.\n Band 5: Demonstrates a minimally adequate range of vocabulary to talk about familiar topics. Uses limited vocabulary, with noticeable repetition and some inaccuracies that may affect clarity.\n Band 4: Demonstrates limited vocabulary range with frequent repetition even with familiar topics. Errors in word choice can dramatically hinder communication. The response is less than 180 words.\n Band 3: Very limited vocabulary range, leading to significant difficulties in expressing ideas. The response is less than 120 words.\n Band 2: Extremely limited vocabulary, making communication extremely difficult. The response is less than 80 words.\n Band 1: Virtually no vocabulary; communication is nearly impossible. The response is less than 20 words.";
    }

    public static function hocmaiBandLexicalResource($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptLexicalResource();
        $rule = self::hocmaiRuleLexicalResource();
        $report = $jsonData['report'];
        $topic = $jsonData['topic'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Lexical Resource:' . $rule,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the Lexical Resource of IELTS Writing Task 2 essay. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay. This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n This is my IELTS Writing Task 2 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);

        return $chat;
    }
    //lexical_resource end

    //grammatical_range_accuracy start
    public static function hocmaiSystemPromptGrammaRange()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 2, focusing on the **Grammatical Range & Accuracy (GRA)** criterion.";
    }

    public static function hocmaiRuleGrammaRange()
    {
        return "Band 9: Demonstrates a high degree of grammatical accuracy and a wide range of sentence structures. Makes virtually no mistakes, even with complex sentences.\n Band 8: Exhibits a good command of grammar with occasional minor errors. Employs a variety of complex sentence structures.\n Band 7: Generally maintains grammatical control, though some errors may occur. Uses a mix of sentence structures, although there are more compound and complex structures. Errors mostly occur in complex structures, but these should not affect communication.\n Band 6: Contains noticeable grammatical errors, but communication remains clear. Uses a range of simple and complex sentences, although there might be slightly more simple than compound and complex structures.\n Band 5: Frequently makes grammatical errors, leading to occasional confusion. Sentence structures may be limited and are mostly simple ones. Rarely attempts complex structures, and these attempts are usually unsuccessful. Generally manages accuracy of simple structures.\n Band 4: Shows significant issues with grammar, hindering overall clarity. Virtually no use of complex structures. Makes noticeable mistakes even with simple structures. Structures are repetitive. The response is less than 180 words.\n Band 3: Contains severe and frequent grammatical errors that impede understanding. No use of compound and complex structures. The response is less than 120 words.\n Band 2: Virtually no control over grammar, making communication very challenging. The response is less than 80 words.\n Band 1: No evidence of grammatical control; the achievement is incomprehensible. The response is less than 20 words.";
    }

    public static function hocmaiBandGrammaRange($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptGrammaRange();
        $rule = self::hocmaiRuleGrammaRange();
        $topic = $jsonData['topic'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Grammatical Range & Accuracy:' . $rule,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the Grammatical Range & Accuracy of IELTS Writing Task 2 essay. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay.This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n This is my IELTS Writing Task 2 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }
    //grammatical_range_accuracy end
    
    //improve_essay start
    public static function hocmaiSystemPromptImprovedEssay()
    {
        return "You are an IELTS examiner. Please review the student's **Task 2 Essay**, and provide a revised version that improves the vocabulary, grammar, and ideas. Your task is to enhance the student's original essay while maintaining the original meaning and argument.";
    }

    public static function hocmaiImprovedEssay($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptImprovedEssay();
        $report = $jsonData['report'];
        $topic = $jsonData['topic'];

        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . 'Respond is JSON format following structure: revised_essay is reviews'
               ],
               [
                   "role" => "user",
                   "content" => "This is the prompt for the IELTS Writing Task 2 essay: \n" . $topic . "\n This is my IELTS Writing Task 2 essay:\n" . $report
               ],
            ],
            'temperature' => 0,
            'max_tokens' => 1000
        ]);

        return $chat;
    }
    //improve_essay end
}
