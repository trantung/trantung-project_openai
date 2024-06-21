<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ApiUserQuestion;
use App\Models\ApiUserQuestionPart;
use Illuminate\Support\Facades\Log;

class ProcessBatchResultsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $apiUserQuestionId;

    public function __construct($apiUserQuestionId)
    {
        $this->apiUserQuestionId = $apiUserQuestionId;
    }

    public function handle()
    {
        $response = [];
        $promptTokens = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)->sum('prompt_token');
        $totalToken = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)->sum('total_token');
        $completionTokens = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)->sum('complete_token');

        $openAiResponses = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)->get()->keyBy('part_number');

        $partsCompleted = true;

        for ($i = 1; $i <= 7; $i++) {
            if (isset($openAiResponses[$i]) && !empty($openAiResponses[$i]['openai_response'])) {
                $openAiResponse = $openAiResponses[$i];
                switch ($i) {
                    case 1:
                        $response['introduction'] = $openAiResponse['openai_response'];
                        break;
                    case 2:
                        $response['topic_sentence']['topic_sentence'] = $openAiResponse['openai_response'];
                        break;
                    case 3:
                        $response['topic_sentence']['conclusion'] = $openAiResponse['openai_response'];
                        break;
                    case 4:
                        $response['band_task_response'] = $openAiResponse['openai_response'];
                        break;
                    case 5:
                        $response['coherence_cohesion_response'] = $openAiResponse['openai_response'];
                        break;
                    case 6:
                        $response['lexical_response'] = $openAiResponse['openai_response'];
                        break;
                    case 7:
                        $response['gramma_response'] = $openAiResponse['openai_response'];
                        break;
                }
            } else {
                $partsCompleted = false;
            }
        }

        $status = $partsCompleted ? ApiUserQuestion::STATUS_SUCCESS : ApiUserQuestion::STATUS_NOT_SUCCESS;

        $updateData = [
            'status' => $status,
            'prompt_token' => $promptTokens,
            'total_token' => $totalToken,
            'complete_token' => $completionTokens,
            'openai_response' => json_encode($response),
        ];

        // Perform the update operation
        ApiUserQuestion::find($this->apiUserQuestionId)->update($updateData);
        //curl api cms
        $res = ApiUserQuestion::where('id', $this->apiUserQuestionId)
            ->where('status', ApiUserQuestion::STATUS_SUCCESS)
            ->first();
        if($res) {
            $openai_response = $res->openai_response;
            $data = [
                'question_id' => $this->apiUserQuestionId,
                'data' => $response
            ];

            $data_string = json_encode($data);
            $curl = curl_init('https://apiems.microgem.io.vn/hook/resultWritingTask2');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                // 'api-key: P950LqE9SfejPhIVdzRpyLRWeCmJULk5',
                'Content-Length: ' . strlen($data_string))
            );
            $result = curl_exec($curl);
            curl_close($curl);
        }
    }
}
