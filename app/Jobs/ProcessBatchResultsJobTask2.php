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

class ProcessBatchResultsJobTask2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $apiUserQuestionId;
    public $writing_task_number;

    public function __construct($apiUserQuestionId,$writing_task_number)
    {
        $this->apiUserQuestionId = $apiUserQuestionId;
        $this->writing_task_number = $writing_task_number;
    }

    public function handle()
    {
        $response = [];
        $promptTokens = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
            ->where('writing_task_number', $this->writing_task_number)
            ->sum('prompt_token');

        $totalToken = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
            ->where('writing_task_number', $this->writing_task_number)
            ->sum('total_token');

        $completionTokens = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
            ->where('writing_task_number', $this->writing_task_number)
            ->sum('complete_token');

        $openAiResponses = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
            ->where('writing_task_number', $this->writing_task_number)
            ->get()
            ->keyBy('part_number');

        $partsCompleted = true;

        for ($i = 1; $i <= 6; $i++) {
            if (isset($openAiResponses[$i]) && !empty($openAiResponses[$i]['openai_response'])) {
                $openAiResponse = $openAiResponses[$i];
                switch ($i) {
                    case 1:
                        $response['vocabulary_grammar'] = $openAiResponse['openai_response'];
                        break;
                    case 2:
                        $response['task_response'] = $openAiResponse['openai_response'];
                        break;
                    case 3:
                        $response['coherence_cohesion'] = $openAiResponse['openai_response'];
                        break;
                    case 4:
                        $response['lexical_resource'] = $openAiResponse['openai_response'];
                        break;
                    case 5:
                        $response['grammatical_range'] = $openAiResponse['openai_response'];
                        break;
                    case 6:
                        $response['improved_essay'] = $openAiResponse['openai_response'];
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
            'writing_task_number' => $this->writing_task_number,
            'openai_response' => json_encode($response),
        ];
        // // Perform the update operation
        ApiUserQuestion::find($this->apiUserQuestionId)->update($updateData);
    }
}
