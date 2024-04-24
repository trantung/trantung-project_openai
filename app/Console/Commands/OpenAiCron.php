<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\UserModelDataStatus;
use App\Models\UserModelData;
use App\Models\UserFileTrainings;
use App\Models\UserModelEmbedding;
use App\Models\Embedding;
use Orhanerday\OpenAi\OpenAi;

class OpenAiCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to openai api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function embeddingUserModel($userModelDataId, $userFileTrainId, $content)
    {
        foreach($content as $key => $value)
        {
            $title = $value['messages'][1]['content'];
            $contentEmbedding = $value['messages'][2]['content'];
            $tokens = Embedding::tokenize($contentEmbedding, 200);
            foreach ($tokens as $token) {
                $text = implode("\n", $token);
                $vectors = Embedding::getQueryEmbedding($text);
                $embeddingId = DB::connection('pgsql')->table('embeddings')->insertGetId([
                    'question' => $title,
                    'answer' => $contentEmbedding,
                    'created_at' => date('Y-m-d H:i:s'),
                    'embedding' => json_encode($vectors)
                ]);
            }
        }
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $open_ai_key = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($open_ai_key);
        $datas = UserModelDataStatus::where('cron', UserModelDataStatus::NOT_RUN)->get();
        $listJobId = [];
        foreach ($datas as $value) {
            $userModelData = UserModelData::find($value->user_model_data_id);
            $fineTrainingData = UserFileTrainings::find($value->user_file_training_id);
            $listJobId[] = $value->openai_job_id;
            $res = $client->fineTuning()->retrieveJob($value->openai_job_id)->toArray();
            $result = json_decode($res, true);
            if(!empty($result['fine_tuned_model'])) {
                $openAiModelId = $result['fine_tuned_model'];
                //update user_model_data_status tables
                $value->update([
                    'cron' => UserModelDataStatus::RUN,
                    'status' => UserModelDataStatus::STATUS_COMPLETE,
                    'token_training' => $result['trained_tokens']
                ]);
                //update user_model_datas table
                if($userModelData) {
                    $userModelData->update([
                        'model_ai_id' => $openAiModelId,
                        'status' => UserModelData::OPENAI_SUCCESS
                    ]);
                    //create vector in user_model_embeddings table
                    $content = json_decode($fineTrainingData->content, true);
                    $this->embeddingUserModel($value->user_model_data_id, $value->user_file_training_id, $content);
                }
            } else {
                $value->update([
                    'cron' => UserModelDataStatus::NOT_RUN,
                    'status' => UserModelDataStatus::STATUS_TRAINING,
                ]);
                if(!empty($result['error']['message'])) {
                    $userModelData->update([
                        'status' => UserModelData::OPENAI_ERROR,
                        'note' => $result['error']['message']
                    ]);
                    $value->update([
                        'cron' => UserModelDataStatus::RUN,
                        'status' => UserModelDataStatus::STATUS_TRAINING,
                    ]);
                }
            }
        }
        return true;
    }
}
