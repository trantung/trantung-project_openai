<?php

namespace App\Jobs;

use App\Events\Part1JobCompleted;
use App\Models\ApiUserQuestionPart;
use App\Models\CommonHocmai;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batchable;

class TaskPart5Job implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jsonData;
    public $partNumber;
    public $apiUserQuestionId;
    public $writing_task_number;

    /**
     * Create a new job instance.
     */
    public function __construct($jsonData, $partNumber, $apiUserQuestionId, $writing_task_number)
    {
        $this->jsonData = $jsonData;
        $this->partNumber = $partNumber;
        $this->apiUserQuestionId = $apiUserQuestionId;
        $this->writing_task_number = $writing_task_number;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $chat = CommonHocmai::hocmaiBandGrammaRange($this->jsonData);
            $dataResponseChat = $chat->choices[0]->message->content;
            $dataResponseChat = json_decode($dataResponseChat, true);
            $totalToken = $chat->usage->totalTokens;
            $completionTokens = $chat->usage->completionTokens;
            $promptTokens = $chat->usage->promptTokens;

            $checkData = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
                ->where('part_number', $this->partNumber)
                ->where('writing_task_number', $this->writing_task_number)
                ->first();

            if (!empty($checkData)) {
                $updateData = [
                    'openai_response' => json_encode($dataResponseChat,true),
                    'total_token' => $totalToken,
                    'prompt_token' => $promptTokens,
                    'complete_token' => $completionTokens,
                    'status' => 1
                ];

                // Perform the update operation
                ApiUserQuestionPart::find($checkData->id)->update($updateData);
                CommonHocmai::callCmsTask1($dataResponseChat, $this->apiUserQuestionId, CommonHocmai::GRAMMA_RANGE_TASK_1);
                CheckJobsCompletion::dispatch($this->apiUserQuestionId, $this->writing_task_number);
                
            }

            // Gửi response về Event để xử lý bởi Listener
            //event(new Part1JobCompleted($this->jsonData, $this->partNumber, $dataResponseChat, $this->apiUserQuestionId));

            // Log thành công
            Log::info('Part' . $this->partNumber . 'Job executed for question_id: ' . $this->apiUserQuestionId);
        } catch (\Exception $e) {
            // Xử lý lỗi và log
            $checkData = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
                ->where('part_number', $this->partNumber)
                ->where('writing_task_number', $this->writing_task_number)
                ->first();
            if (!empty($checkData)) {
                $updateData = [
                    'status' => 2
                ];

                // Perform the update operation
                ApiUserQuestionPart::find($checkData->id)->update($updateData);
            }
            Log::error('Part' . $this->partNumber . 'Job failed for part: ' . $this->partNumber . ' with error: ' . $e->getMessage());
        }
    }
}
