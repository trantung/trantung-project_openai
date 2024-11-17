<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerId;
use \OpenAI;
use Illuminate\Support\Facades\Log;

class OpenAiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $userId;

    public function __construct($id, $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $checkData = CustomerId::where('id', $this->id)->first();
            if (!empty($checkData) && $checkData->status == 0) {
                $open_ai_key = env('OPENAI_API_KEY');
                $client = OpenAI::client($open_ai_key);
                $response = $client->chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => 'hello'],
                    ],
                ]);

                $answer = $response->choices[0]->message->content;
                CustomerId::where('id', $this->id)->update(['answer' => $answer, 'status' => 1]);
                
                DB::commit();
                Log::info('Update successful id: ' . $this->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update failed for id: ' . $this->id . ' with error: ' . $e->getMessage());
        }
    }
}