<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DemoBatchJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jsonData;
    protected $apiUserQuestionId;

    public function __construct($jsonData, $apiUserQuestionId)
    {
        $this->jsonData = $jsonData;
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

        for ($i = 1; $i <= 5; $i++) {
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
                }
            } else {
                $partsCompleted = false;
            }
        }

        $status = $partsCompleted ? 1 : 0;

        $updateData = [
            'status' => $status,
            'prompt_token' => $promptTokens,
            'total_token' => $totalToken,
            'complete_token' => $completionTokens,
            'openai_response' => $response,
        ];

        // Perform the update operation
        ApiUserQuestion::find($this->apiUserQuestionId)->update($updateData);
    }
}

