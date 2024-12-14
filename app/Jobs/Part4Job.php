<?php

namespace App\Jobs;

use App\Events\Part1JobCompleted;
use App\Models\ApiUserQuestionPart;
use App\Models\Common;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batchable;

class Part4Job implements ShouldQueue
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
            $chat = Common::responseBandTaskResponse($this->jsonData);
            $dataResponseChat = $chat->choices[0]->message->content;
            $totalToken = $chat->usage->totalTokens;
            $completionTokens = $chat->usage->completionTokens;
            $promptTokens = $chat->usage->promptTokens;

            $checkDataQuestion = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
                ->where('part_number', $this->partNumber)
                ->where('writing_task_number', $this->writing_task_number)
                ->first();

            if (!empty($checkDataQuestion)) {
                $dataResponseChat = Common::bandFormatData($dataResponseChat);
                $checkData = true;
                if(empty($dataResponseChat)) {
                    //call lai openai
                    $chatCallAgain = Common::responseBandTaskResponse($this->jsonData);
                    $dataResponseChatAgain = $chatCallAgain->choices[0]->message->content;
                    $dataResponseChatAgain = Common::bandFormatData($dataResponseChatAgain);
                    if(empty($dataResponseChatAgain)) {
                        $checkData = false;
                    } else {
                        $dataResponseChat = $dataResponseChatAgain;
                    }
                }
                //check thanh phan trong dataResponseChat
                $dataResponseChatCall = $dataResponseChat = json_encode($dataResponseChat,true);
                $checkValueChild = Common::checkChildValue($this->apiUserQuestionId, $dataResponseChat);
                if(!$checkValueChild) {
                    $checkData = false;
                }
                if(!$checkData) {
                    //call lai openai
                    $chatCallAgain = Common::responseBandTaskResponse($this->jsonData);
                    $dataResponseChatAgain = $chatCallAgain->choices[0]->message->content;
                    $checkValueChildAgain = Common::checkChildValue($this->apiUserQuestionId, $dataResponseChatAgain);
                    if(empty($checkValueChildAgain)) {
                        $checkData = false;
                    }
                    $dataResponseChat = json_encode($dataResponseChatAgain,true);
                }
                $updateStatus = 1;
                if(empty($checkData)) {
                    $status = 0;
                }
                $updateData = [
                    'openai_response' => $dataResponseChat,
                    'total_token' => $totalToken,
                    'prompt_token' => $promptTokens,
                    'complete_token' => $completionTokens,
                    'status' => $updateStatus
                ];

                // Perform the update operation
                ApiUserQuestionPart::find($checkDataQuestion->id)->update($updateData);
                // $dataResponseChat = json_encode($dataResponseChat,true);

                Common::callCms($dataResponseChat, $this->apiUserQuestionId, Common::PART_NUMBER_BAND_TASK_RESPONSE, $checkData);
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
