<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Part1JobCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $partNumber;
    public $response;
    public $apiUserQuestionId;
    public $jsonData;

    /**
     * Create a new event instance.
     */
    public function __construct($jsonData, $partNumber, $response, $apiUserQuestionId)
    {
        $this->partNumber = $partNumber;
        $this->response = $response;
        $this->apiUserQuestionId = $apiUserQuestionId;
        $this->jsonData = $jsonData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
