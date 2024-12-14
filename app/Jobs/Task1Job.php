<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Models\ApiUserQuestion;
use App\Models\ApiUserQuestionPart;
use App\Jobs\ProcessBatchResultsJob;

class Task1Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jsonData;
    public $apiUserQuestionId;
    public $writing_task_number;

    /**
     * Create a new job instance.
     */
    public function __construct($jsonData, $apiUserQuestionId, $writing_task_number)
    {
        $this->jsonData = $jsonData;
        $this->apiUserQuestionId = $apiUserQuestionId;
        $this->writing_task_number = $writing_task_number;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('DemoJob started for question ID: ' . $this->apiUserQuestionId);
        
        dispatch(new TaskPart1Job($this->jsonData, 1, $this->apiUserQuestionId, $this->writing_task_number));
        dispatch(new TaskPart2Job($this->jsonData, 2, $this->apiUserQuestionId, $this->writing_task_number));
        dispatch(new TaskPart3Job($this->jsonData, 3, $this->apiUserQuestionId, $this->writing_task_number));
        dispatch(new TaskPart4Job($this->jsonData, 4, $this->apiUserQuestionId, $this->writing_task_number));
        dispatch(new TaskPart5Job($this->jsonData, 5, $this->apiUserQuestionId, $this->writing_task_number));
    }

    /**
     * Process batch results and update the database.
     */
    private function processBatchResults()
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

        for ($i = 1; $i <= 5; $i++) {
            if (isset($openAiResponses[$i]) && !empty($openAiResponses[$i]['openai_response'])) {
                $openAiResponse = $openAiResponses[$i];
                switch ($i) {
                    case 1:
                        $response['vocabulary_grammar'] = $openAiResponse['openai_response'];
                        break;
                    case 2:
                        $response['task_achiement'] = $openAiResponse['openai_response'];
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
