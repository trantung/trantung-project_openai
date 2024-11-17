<?php

namespace App\Listeners;

use App\Events\Part1JobCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\DemoJob;

class HandlePart1JobCompletion
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Part1JobCompleted $event)
    {
        // Khởi tạo DemoJob và truyền các giá trị từ sự kiện Part1JobCompleted
        $demoJob = new DemoJob($event->jsonData, $event->apiUserQuestionId);
        $demoJob->addResponse($event->partNumber, $event->response);
    }
}
