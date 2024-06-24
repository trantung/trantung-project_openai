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

class DemoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jsonData;
    public $apiUserQuestionId;

    /**
     * Create a new job instance.
     */
    public function __construct($jsonData, $apiUserQuestionId)
    {
        $this->jsonData = $jsonData;
        $this->apiUserQuestionId = $apiUserQuestionId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('DemoJob started for question ID: ' . $this->apiUserQuestionId);
        
        dispatch(new Part1Job($this->jsonData, 1, $this->apiUserQuestionId));
        dispatch(new Part2Job($this->jsonData, 2, $this->apiUserQuestionId));
        dispatch(new Part3Job($this->jsonData, 3, $this->apiUserQuestionId));
        dispatch(new Part4Job($this->jsonData, 4, $this->apiUserQuestionId));
        dispatch(new Part5Job($this->jsonData, 5, $this->apiUserQuestionId));
        dispatch(new Part6Job($this->jsonData, 6, $this->apiUserQuestionId));
        dispatch(new Part7Job($this->jsonData, 7, $this->apiUserQuestionId));
        // dispatch(new ProcessBatchResultsJob($this->apiUserQuestionId));
    }

    /**
     * Process batch results and update the database.
     */
    private function processBatchResults()
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

        $status = $partsCompleted ? 1 : 0;

        $updateData = [
            'status' => $status,
            'prompt_token' => $promptTokens,
            'total_token' => $totalToken,
            'complete_token' => $completionTokens,
            'openai_response' => json_encode($response),
        ];

        // Perform the update operation
        ApiUserQuestion::find($this->apiUserQuestionId)->update($updateData);
    }
}
