<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ApiUserQuestionPart;
use App\Commons\Constants\CategoryValue;

class CommonHocmai extends Model
{
    //const task1
    const VOCABULARY_GRAMMA_TASK_1 = 1;
    const TASK_ACHIEMENT_TASK_1 = 2;
    const COHERENCE_COHESION_TASK_1 = 3;
    const LEXICAL_RESOURCE_TASK_1 = 4;
    const GRAMMA_RANGE_TASK_1 = 5;

    public static function callCmsTask1($dataResponseChat, $questionId, $partNumber)
    {
        $contestTypeId = '';
        $apiUserQuestionPartData = ApiUserQuestionPart::where('user_question_id', $questionId)->first();
        Log::info('callCmsTask1 Part ' . $partNumber . ' of question_id: ' . $questionId . ' have data ' . count($apiUserQuestionPartData));
        if($apiUserQuestionPartData) {
            if($apiUserQuestionPartData->contest_type_id == CategoryValue::CONTEST_TYPE_1) {
                $urlConfig = getenv('EMS_API_TASK1_CONTEST_TYPE_19');
                $contestTypeId = 19;
            }
        } else {
            $urlConfig = getenv('EMS_API_TASK1');
        }
        $urlConfig = getenv('EMS_API_TASK1');
        Log::info('callCmsTask1 urlConfig ' . $urlConfig);
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
        Log::info('task1 Part ' . $partNumber . ' of question_id: ' . $questionId . ' have status is ' . $check);
    }

    //task1 start
    //vocabulary_grammar start 
    public static function hocmaiSystemPromptVocabularyGramma()
    {
        return "You are an IELTS examiner. Please review the student's **Task 1 Report**, identify specific lexical and grammatical errors, and suggest corrections.###Instructions: 1. Focus on the **vocabulary** used in the Task 1 report. Look for errors in word choice, inappropriate collocations, and repetition of words or phrases. 2. Check the **grammar** of the report, focusing on issues such as subject-verb agreement, tense consistency, sentence structure, word order, and article use. 3. Identify any **repetitive language** and suggest synonyms or alternative expressions to improve lexical variety. 4. Provide **specific corrections** for any grammatical mistakes, explaining why the correction is needed (e.g., correcting verb tense, improving sentence structure, etc.). 5. Offer suggestions on how to improve the report by varying vocabulary and using a wider range of grammatical structures. ### Strictly follow this format. Use simple language for students to understand: 1. Vocabulary Errors **Error**: 'The graph shows the increasing of the population.' **Correction**: 'The graph shows the increase in the population.' **Explanation**: 'Increasing' should be replaced with the noun 'increase' to maintain correct word form and structure. 2. Grammar Errors **Error**: 'There was a noticeable rise in the amount of traffic.'**Correction**: 'There was a noticeable increase in the amount of traffic.' **Explanation**: 'Rise' is a verb; 'increase' is the correct noun form here.";
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
                //    "content" => $systeMessage . '. Respond is json format and have structure as: lexical_errors is lexical errors, gramma_errors is grammatical errors, suggestion includes lexical_errors and grammar_errors, where lexical_errors are the suggested corrections for lexical and grammar_errors are the suggested corrections for gramma',
                   "content" => $systeMessage
               ],
               [
                   "role" => "user",
                   "content" => "This is my IELTS Writing Task 1 essay:\n" . $report
               ],
            ],
            'temperature' => 0,
            'max_tokens' => 2048
        ]);
        return $chat;
    }
    //vocabulary_grammar end

    //task_achiement start
    public static function hocmaiSystemPromptTaskAchiement()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 1 of the IELTS Writing test, focusing only on the **Task Achievement (TA)** criterion. Use the provided Band 8 sample response for comparison.";
    }

    public static function hocmaiRuleTaskAchiement()
    {
        return "Band 9: Reports all information with no lapses, including a clear overview and detailed description and comparisons. Information is presented logically and follows the proper format of an IELTS Writing Task 1 (Introduction, overview of main trends/changes/stages, body paragraphs). There is no irrelevant or inaccurate information.\n Band 8: Reports all information with very few lapses, including a clear overview and detailed description and comparisons. Information is presented logically and follows the proper format of an IELTS Writing Task 1 (Introduction, overview of main trends/changes/stages, body paragraphs). There is no irrelevant or inaccurate information.\n Band 7: Reports all main points. However, details might be missing, but this is only occasional. Information is generally presented logically and follows the proper format of an IELTS Writing Task 1 (Introduction, overview of main trends/changes/stages, body paragraphs). Presents a clear overview. There is no irrelevant or inaccurate information.\n Band 6: Reports most of the main points. Follows the format of an IELTS Writing task 1 report. May sometimes lack data or comparison to support description. There might occasionally be inaccurate or irrelevant information.\n Band 5: The response is 135-145 words. Only cover half of the main points. Report is mechanical and may focus too much details without referring to general trends/changes/stages. May not follow the format of an IELTS Writing task 1 report. There might be frequent inaccurate or irrelevant information.\n Band 4: The response is 100-135 words. Only cover roughly a third of the main points. Does not follow the format of an IELTS Writing task 1 report, or structure is highly disorganised. Does not have an overview. Virtually no data or detail to support description. Maybe off-topic.\n Band 3: The response is 60-100 words. The report minimally covers the information. Does not follow the format of an IELTS Writing task 1 report. Does not have an overview.  No data or detail to support description. Maybe off-topic.\n Band 2: The response is 20-60 words. Only covers minimal information or can be totally off-topic. No attempt to develop ideas. Does not have an overview. \n Band 1: The response is less than 20 words.";
    }

    public static function hocmaiBandTaskAchiement($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptTaskAchiement();
        $rule = self::hocmaiRuleTaskAchiement();
        $sample = $jsonData['sample'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Task Achievement:' . $rule . '\n Please grade the IELTS Writing Task 1 essay without mentioning the Band 8 sample. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay. ' . "This is Band 8 sample:\n" . $sample,
               ],
               [
                   "role" => "user",
                   "content" => ". This is my IELTS Writing Task 1 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);

        return $chat;
    }
    //task_achiement end

    //coherence_cohesion start
    public static function hocmaiSystemPromptCoherenceCohesion()
    {
        return "You are an IELTS examiner. Evaluate the student's response to Task 1 of the IELTS Writing test, focusing only on the **Coherence & Cohesion (CC)** criterion. Use the provided Band 8 sample response for comparison.";
    }

    public static function hocmaiRuleCoherenceCohesion()
    {
        return "Band 9: Skillfully uses a range of cohesive devices and paragraphing to enhance coherence. Ideas flow smoothly, making the text easy to follow. No lapses.\n Band 8: Clear paragraphing. Each paragraph has a clear central idea. Uses topic sentences to inform the reader of the main idea of each body paragraph. Effectively uses a wide variety of linking words with virtually no mistakes. Ideas are linked coherently.\n Band 7: Clear paragraphing and progression. Each paragraph has a clear central idea. Uses topic sentences to inform the reader of the main idea of each body paragraph. Uses a wide variety of linking words effectively. Uses referencing and substitutions with only occasional mistakes. Some occasional lapses in coherence may occur.\n Band 6: Clear paragraphing and general progression. Uses a variety of linking words (firstly, secondly, however, additionally, etc), but some may be used ineffectively or with overuse. Coherence may be somewhat impaired as some details are irrelevant to the task. \n Band 5: There is paragraphing but this may not be effective. Progression may lack clarity. No topic sentences for body paragraphs. Only uses basic linking words (and, but, so, because) and tends to be repetitive. Does not use referencing or substitutions. Information within a paragraph may not be well-linked.\n Band 4: The response is 100-135 words. No paragraphing. No overall progression. Limited use of basic linking words. Does not use referencing or substitutions. Information within a paragraph is not well-linked.\n Band 3: The response is 60-100 words. Very limited use of cohesive devices, leading to severe issues with coherence. Consecutive sequences are minimally logically connected. Does not use referencing or substitutions.\n Band 2: The response is 20-60 words. No use of cohesive devices. Consecutive sequences are not logically connected. No coherence.\n Band 1: The response is less than 20 words.";
    }

    public static function hocmaiBandCoherenceCohesion($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptCoherenceCohesion();
        $rule = self::hocmaiRuleCoherenceCohesion();
        $sample = $jsonData['sample'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
           'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Coherence & Cohesion:' . $rule . '\n' . "This is Band 8 sample:\n" . $sample,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the Coherence & Cohesion of IELTS Writing Task 1 essay without mentioning the Band 8 sample. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay. This is my IELTS Writing Task 1 essay:\n" . $report
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
        return "You are an IELTS examiner. Evaluate the student's response to Task 1 of the IELTS Writing test, focusing only on the **Lexical Resource (LR)** criterion. Use the provided Band 8 sample response for comparison.";
    }

    public static function hocmaiRuleLexicalResource()
    {
        return "Band 9: Uses a wide range of vocabulary accurately and appropriately. Shows excellent control of lexical resources, including uncommon lexical items. Shows excellent awareness of style and collocation. Make virtually no mistakes.\n Band 8: Uses a wide range of vocabulary effectively with very few errors. Demonstrates good control of lexical resources, including uncommon lexical items. Shows high awareness of style and collocation.\n Band 7: Uses a sufficient range of vocabulary with some flexibility to talk about familiar and less familiar topics. Demonstrates relatively good control of lexical resources, including uncommon lexical items. Shows good awareness of style and collocation. May have occasional errors, but they don't hinder comprehension.\n Band 6: Demonstrates an adequate range of vocabulary to talk about familiar topics and less familiar topics. Uses vocabulary with some variation but may be repetitive or imprecise at times. Errors do not significantly impede understanding. May attempt to use less common lexical items but may not be successful.\n Band 5: Demonstrates a minimally adequate range of vocabulary to talk about familiar topics. Uses limited vocabulary, with noticeable repetition and some inaccuracies that may affect clarity.\n Band 4: Demonstrates limited vocabulary range with frequent repetition even with familiar topics. Errors in word choice can dramatically hinder communication. The response is less than 180 words.\n Band 3: Very limited vocabulary range, leading to significant difficulties in expressing ideas. The response is less than 120 words.\n Band 2: Extremely limited vocabulary, making communication extremely difficult. The response is less than 80 words.\n Band 1: Virtually no vocabulary; communication is nearly impossible. The response is less than 20 words.";
    }

    public static function hocmaiBandLexicalResource($jsonData)
    {
        $yourApiKey = getenv('OPENAI_HOCMAI_KEY');
        $client = OpenAI::client($yourApiKey);
        $model = getenv('OPENAI_API_MODEL');
        $systeMessage = self::hocmaiSystemPromptLexicalResource();
        $rule = self::hocmaiRuleLexicalResource();
        $sample = $jsonData['sample'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Lexical Resource:' . $rule . '\n' . "This is Band 8 sample:\n" . $sample,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the Lexical Resource of IELTS Writing Task 1 essay without mentioning the Band 8 sample. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay. This is my IELTS Writing Task 1 essay:\n" . $report
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
        return "You are an IELTS examiner. Evaluate the student's response to Task 1 of the IELTS Writing test, focusing only on the **Grammatical Range & Accuracy (GRA)** criterion. Use the provided Band 8 sample response for comparison.";
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
        $sample = $jsonData['sample'];
        $report = $jsonData['report'];
        
        $chat = $client->chat()->create([
            'model' => $model,
            'response_format'=>["type"=>"json_object"],
            'messages' => [
                [
                   "role" => "system",
                   "content" => $systeMessage . '\n Marking Rubric for Grammatical Range & Accuracy:' . $rule . '\n' . "This is Band 8 sample:\n" . $sample,
               ],
               [
                   "role" => "user",
                   "content" => "Please grade the Grammatical Range & Accuracy of IELTS Writing Task 1 essay without mentioning the Band 8 sample. After that, give me a response in JSON format with the following structure: score as the given score, and comment as the feedback about my essay.This is my IELTS Writing Task 1 essay:\n" . $report
               ],
            ],
           'temperature' => 0,
           'max_tokens' => 1000
        ]);
        return $chat;
    }
    //grammatical_range_accuracy end

}
