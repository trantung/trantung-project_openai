<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ApiUserQuestionPart;
use App\Models\ApiUserQuestion;
use Illuminate\Support\Facades\Log;

class CheckJobsCompletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $apiUserQuestionId;

    public function __construct($apiUserQuestionId)
    {
        $this->apiUserQuestionId = $apiUserQuestionId;
    }

    public function handle()
    {
        $completedJobs = ApiUserQuestionPart::where('user_question_id', $this->apiUserQuestionId)
                                             ->where('status', 1)
                                             ->count();

        if ($completedJobs == 7) {
            Log::info("All parts completed for question ID: " . $this->apiUserQuestionId);
            ProcessBatchResultsJob::dispatch($this->apiUserQuestionId);
        }
    }
}
