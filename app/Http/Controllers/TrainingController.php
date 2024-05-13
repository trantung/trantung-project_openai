<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use \OpenAI;
use App\Models\Embedding;
use Storage;
use App\Models\UserModelData;
use App\Models\UserModelDataStatus;
use App\Models\UserFileTrainings;
use App\Models\Question;
use App\Models\TestOpenai;

class TrainingController extends BaseApiController
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
        $this->middleware('auth')->only('create');
        $this->middleware('auth')->only('formChat');
        $this->middleware('auth')->only('detailChat');
    }

    public function index()
    {
        // $data = DB::connection('pgsql')->table('embeddings')->paginate(10);
        $data = UserModelData::paginate(10);
        return view('training.index', compact('data'));
    }

    public function create()
    {
        return view('training.form');
    }

    public function createSlug($str, $delimiter = '-')
    {
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    }

    public function store(Request $request)
    {
        $data = $this->convertDataToJsonTo($request);
        if ($data['code'] != 200) {
            return $this->responseError(422, $this->convertDataToJsonTo($request)['data']['messages']);
        }

        $modelCode = $this->createSlug($request['model_name']);
        $checkExist = UserModelData::where('model_code', $modelCode)->first();
        if ($checkExist) {
            return $this->responseError(412, 'Model name is exist');
        }
        $user = Auth::user();

        $message = $data['data']['messages'];
        $resultQuestion = $topicBaseOn = '';
        foreach ($message as $value) {
            foreach ($value['messages'] as $v) {
                if ($v['role'] == 'user') {
                    $resultQuestion = $resultQuestion . ' ' . $v['content'];
                }
            }
        }

        $topicRelate = $this->getTopicRelate($resultQuestion);
        if (!empty($request['base_model'])) {
            $baseOnId = $request['base_model'];
            $checkBaseOnId = UserModelData::find($baseOnId);
            if (!$checkBaseOnId) {
                return $this->responseError(412, 'Model name is exist');
            }
            $topicBaseOn = $checkBaseOnId->topic_detail;
        }
        $topicDetail = $topicRelate . ',' . $topicBaseOn;
        $modelDataId = UserModelData::create([
            'username' => $user->name,
            'model_name' => $request['model_name'],
            'model_code' => $modelCode,
            'model_ai_id' => '',
            'status' => 0,
            'base_on_id' => $request['base_model'],
            'type' => 0,
            'approved' => 1,
            'topic_detail' => $topicDetail,
        ])->id;

        // $message = $jsonData['content'];
        $res = $this->createUserFileTrain($modelDataId, $data, $request);
        if (!empty($res['message']) && $res['code'] == 305) {
            return $this->responseError(412, $res['message']);
        }
        if ($res == true) {
            $messages = array(
                'messages' => 'create is successful',
                'model_data_id' => $modelDataId
            );
            return $this->responseSuccess(200, $messages);
        }
        return $this->responseError(305, 'Create is error');
    }


    public function formChat()
    {
        return view('chat.form');
    }

    public function readJsonFile(Request $request)
    {
        $tmpFilePath = $_FILES['import_file']['tmp_name'];
        $jsonContent = file_get_contents($tmpFilePath);
        $jsonData = json_decode($jsonContent, true);
        $messages = array();
        if ($jsonData !== null) {
            foreach ($jsonData as $item) {
                $userContent = '';
                $assistantContent = '';
                foreach ($item['messages'] as $message) {
                    if ($message['role'] === 'user') {
                        $userContent = trim(preg_replace('/\s\s+/', ' ', $message['content']));
                    } elseif ($message['role'] === 'assistant') {
                        $assistantContent = trim(preg_replace('/\s\s+/', ' ', $message['content']));
                    }
                }
                $messages[] = [
                    "messages" => [
                        [
                            "role" => "system",
                            "content" => "Use the following pieces of context to answer the user's question. If you don't know the answer, just say that you don't know, don't try to make up an answer."
                        ],
                        [
                            "role" => "user",
                            "content" => str_replace('\\', "'", $userContent)
                        ],
                        [
                            "role" => "assistant",
                            "content" => str_replace('\\', "'", $assistantContent)
                        ]
                    ]
                ];
            }
            $arr = [
                'code' => 200,
                'data' => [
                    'messages' => $messages
                ]
            ];
            echo json_encode($arr);
            // echo json_encode($messages);
        } else {
            $arr = [
                'code' => 305,
                'data' => [
                    'messages' => 'Failed to decode JSON or invalid JSON format'
                ]
            ];
            echo json_encode($arr);
            exit;
        }
    }

    public function chat(Request $request)
    {
        if ($request['type'] == 'detail') {
            $open_ai_key = getenv('OPENAI_API_KEY');
            $client = OpenAI::client($open_ai_key);
            $model = getenv('OPENAI_API_MODEL');
            $response = $client->chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $request['title']],
                    ['role' => 'user', 'content' => $request['question']],
                ],
            ]);
            return $response->choices[0]->message->content;
        }

        $data = [
            'name' => $request['title'],
            'question' => $request['question'],
            'category_id' => $request['type'],
            'topic' => $request['topic']
        ];

        if ($request['type'] == 0) {
            $open_ai_key = getenv('OPENAI_API_KEY');
            $client = OpenAI::client($open_ai_key);
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo-0125',
                'messages' => [
                    ['role' => 'system', 'content' => $request['title']],
                    ['role' => 'user', 'content' => $request['question']],
                ],
            ]);
            $data['answer'] = $response->choices[0]->message->content;
            $questionTable = TestOpenai::create($data)->id;
            // return $response->choices[0]->message->content;
            $response = [
                'id' => $questionTable,
                'answer' => $response->choices[0]->message->content
            ];
            return $this->responseError(200, $response);
        }

        if ($request['type'] == 1 || $request['type'] == 2 || $request['type'] == 3) {
            $data['answer'] = $request['answer'];
            $questionTable = TestOpenai::create($data)->id;

            // return $request['answer'];
            return $this->responseError(200, $questionTable);
        }
    }

    // public function chat(Request $request)
    // {
    //     // $filename = time() .'_history';
    //     $filename = 'test_history';

    //     $file_path = storage_path('app/chat/' . $filename . '.json');

    //     if( ! file_exists($file_path) ) {
    //         $historyChat[] = [
    //             'username' => Auth::user()->name,
    //             // 'model_id' => $modelId,
    //             'role' => 'user',
    //             'content' => $request['question'],
    //             'time' => time(),
    //         ];
    //         Storage::disk('local')->put('/chat/' . $filename . '.json', json_encode($historyChat));
    //     }else{
    //         $historyChat = json_decode(file_get_contents($file_path),true);
    //         $arrayUserQuestion[] = [
    //             'username' => Auth::user()->name,
    //             // 'model_id' => $modelId,
    //             'role' => 'user',
    //             'content' => $request['question'],
    //             'time' => time(),
    //         ];
    //         $historyChat = array_merge($historyChat, $arrayUserQuestion);
    //         Storage::disk('local')->put('/chat/' . $filename . '.json', json_encode($historyChat));
    //     }

    //     $resChat = [];
    //     foreach($historyChat as $value) {
    //         $resChat[] = [
    //             'role' => $value['role'],
    //             'content' => $value['content'],
    //         ];
    //     }

    //     $open_ai_key = getenv('OPENAI_API_KEY');
    //     $client = OpenAI::client($open_ai_key);
    //     $response = $client->chat()->create([
    //         'model' => 'gpt-3.5-turbo',
    //         'messages' => $resChat
    //     ]);

    //     $dataUpdateFileContent[] = [
    //         'username' => 'assistant_chat_bot',
    //         // 'model_id' => $modelId,
    //         'role' => 'assistant',
    //         'content' => $response->choices[0]->message->content,
    //         'to' => Auth::user()->name,
    //         'time' => time(),
    //     ];
    //     $contentFile = array_merge($historyChat, $dataUpdateFileContent);
    //     Storage::disk('local')->put('/chat/' . $filename . '.json', json_encode($contentFile));

    //     return $response->choices[0]->message->content;
    // }

    public function getMessage($file_path, $modelId, $context, $question)
    {
        $system_template = "
        Use the following pieces of context to answer the users question. 
        If you don't know the answer, just say that you don't know, don't try to make up an answer.
        ----------------
        {context}
        ";
        $systemMessage = '';
        $prompUser = "Use the following pieces of context to answer the users question. If you don't know the answer, just say that you don't know, don't try to make up an answer.";
        $userModelData = UserModelData::find($modelId);
        if (!empty($userModelData->prompt)) {
            $prompUser = $userModelData->prompt . '.';
        }
        $systemMessage = "
        $prompUser        
        {question}
            ----------------
            CONTEXT: 
            {context}
            ----------------
            FINAL ANSWER:";
        $systemMessage = str_replace("{question}", $question, $systemMessage);
        $systemMessage = str_replace("{context}", $context, $systemMessage);
        $system_prompt = str_replace("{context}", $context, $system_template);
        $delimiter = "```";
        $contentUser = $delimiter . $question . $delimiter;

        $historyChat = json_decode(file_get_contents($file_path), true);
        $historyChatSystemMessage[] =  [
            "role" => "system",
            "content" => $system_prompt
        ];
        $resChat = [];
        // dd($historyChat);
        foreach ($historyChat as $value) {
            $resChat[] = [
                'role' => $value['role'],
                'content' => $value['content'],
            ];
        }
        $resChat = array_merge($historyChatSystemMessage, $resChat);
        // dd($resChat);
        $arrayEnd = end($resChat);
        if (!empty($arrayEnd['role']) && $arrayEnd['role'] == 'user') {
            $arrayEnd['content'] = $systemMessage;
        }
        return $resChat;
    }

    public function detailChat($id)
    {
        $question = TestOpenai::where('id', $id)->first();
        return view('chat.detail', compact('question'));
    }

    public function deleteChat($id)
    {
        // $embedding = DB::connection('pgsql')->table('embeddings')->find($id);
        $question = TestOpenai::find($id);
        if (!$question) {
            session()->flash('error', 'Data Not Found');
            return redirect(route('dashboard'));
        }

        TestOpenai::where('id', $id)->delete();

        session()->flash('success', 'Data Deleted Successfully');
        return redirect(route('dashboard'));
    }

    public function convertDataToJsonTo($request)
    {
        $questions = $request['questions'];
        $answers = $request['answers'];

        $messages = array();

        if (count($questions) > 0) {
            if (count($questions) < 10 || count($answers) < 10) {
                $arr = [
                    'code' => 305,
                    'data' => [
                        'messages' => 'Question and answer does not have enough 10 questions and 10 answers'
                    ]
                ];
                return $arr;
            } else {
                foreach ($questions as $index => $question) {
                    $questionContent = str_replace(['\\', '"'], ["'", '\"'], trim(preg_replace('/\s\s+/', ' ', $question)));
                    $answerContent = str_replace(['\\', '"'], ["'", '\"'], trim(preg_replace('/\s\s+/', ' ', $answers[$index])));

                    $message = array(
                        "messages" => array(
                            array(
                                "role" => "system",
                                "content" => "Use the following pieces of context to answer the users question. If you don't know the answer, just say that you don't know, don't try to make up an answer."
                            ),
                            array(
                                "role" => "user",
                                "content" => $questionContent
                            ),
                            array(
                                "role" => "assistant",
                                "content" => $answerContent
                            )
                        )
                    );
                    $messages[] = $message;
                }
            }
        } else {
            $arr = [
                'code' => 305,
                'data' => [
                    'messages' => 'Invalid parameter'
                ]
            ];
            return $arr;
        }
        $arr = [
            'code' => 200,
            'data' => [
                'messages' => $messages
            ]
        ];
        return $arr;
    }

    public function deleteTraining($id)
    {
        // $embedding = DB::connection('pgsql')->table('embeddings')->find($id);
        $embedding = UserModelData::find($id);
        if (!$embedding) {
            session()->flash('error', 'Data Not Found');
            return redirect(route('training.index'));
        }

        UserModelData::where('id', $id)->delete();

        session()->flash('success', 'Data Deleted Successfully');
        return redirect(route('training.index'));
    }

    public function detailTraining($id)
    {
        // $embedding = DB::connection('pgsql')->table('embeddings')->find($id);
        $embedding = UserModelData::find($id);
        $data = UserFileTrainings::where('user_model_data_id', $id)->first();
        $content = [];
        if (!empty($data)) {
            $content = json_decode($data['content']);
        }
        
        // foreach($content as $value){
        //     $userContent = '';
        //     $assistantContent = '';
        //     if(!empty($value->messages)){
        //         foreach ($value->messages as $message) {
        //             if ($message->role === 'user' && !empty($message->content)) {
        //                 $userContent = $message->content;
        //             } elseif ($message->role === 'assistant' && !empty($message->content)) {
        //                 $assistantContent = $message->content;
        //             }
        //         }  
        //     } 
        //     // if ($value->role === 'user' && !empty($value->content)) {
        //     //     $userContent = $value->content;
        //     // } elseif ($value->role === 'assistant' && !empty($value->content)) {
        //     //     $assistantContent = $value->content;
        //     // }
        //     dd($userContent, $assistantContent);
        // }
        return view('training.detail', compact('embedding', 'content'));
    }

    public function createUserFileTrain($modelDataId, $data, $request)
    {
        $jsonContent = json_encode($data['data']['messages'], JSON_PRETTY_PRINT);
        //convert json to jsonl and create file jsonl
        $jsonlContent = $this->convertJsonToJsonl($data['data']['messages']);
        $user = Auth::user();
        $filename = $modelDataId . '_' . time();
        $userFileTrainingId = UserFileTrainings::create([
            'user_model_data_id' => $modelDataId,
            'username' => $user->name,
            'mode_ai_id_base_on' => $request['base_model'],
            'file_id' => $filename,
            'content' => $jsonContent,
        ])->id;
        Storage::disk('local')->put('/training/' . $user->name . '/' . $filename . '.jsonl', $jsonlContent);
        $file_path = storage_path('app/training/' . $user->name . '/' . $filename . '.jsonl');
        $open_ai_key = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($open_ai_key);
        $response = $client->files()->upload([
            'purpose' => 'fine-tune',
            'file' => fopen($file_path, 'r'),
        ]);
        $result = $response->toArray();
        if (!empty($result['id'])) {
            UserFileTrainings::where('id', $userFileTrainingId)->update(['open_ai_file_id' => $response->id]);
            $res = $this->createUserModelDataStatus($userFileTrainingId, $modelDataId, $request, $response->id);
            // dd($res);
            if (!empty($res['message']) && $res['code'] == 305) {
                $response = [
                    'code' => 305,
                    'message' => $res['message']
                ];
                return $response;
            }
            if ($res == true) {
                return true;
            }
        } else {
            $userModelData = UserModelData::find($modelDataId);
            if (!empty($userModelData)) {
                $userModelData->update([
                    'status' => UserModelData::OPENAI_ERROR,
                    'note' => $result['error']['message']
                ]);
            }
            $response = [
                'code' => 305,
                'message' => 'training failed'
            ];
            return $response;
        }
        return false;
    }

    public function createUserModelDataStatus($userFileTrainingId, $modelDataId, $jsonData, $fileId)
    {
        $user = Auth::user();
        $userModelDataStatusId = UserModelDataStatus::create([
            'user_file_training_id' => $userFileTrainingId,
            'user_model_data_id' => $modelDataId,
            'username' => $user->name,
            'mode_ai_id_base_on' => $jsonData['base_model'],
            'status' => UserModelDataStatus::STATUS_VALIDATE,
            'cron' => UserModelDataStatus::NOT_RUN,
        ])->id;
        $model = 'gpt-3.5-turbo-0125';
        if (!empty($jsonData['base_model'])) {
            $userModelData = UserModelData::find($jsonData['base_model']);
            if (!$userModelData) {
                return $this->responseError(404, 'Not found');
            }
            $model = $userModelData->model_ai_id;
        }

        $open_ai_key = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($open_ai_key);
        $response = $client->fineTuning()->createJob([
            'training_file' => $fileId,
            'validation_file' => null,
            'model' => $model,
            'hyperparameters' => [
                'n_epochs' => 4,
            ],
            'suffix' => null,
        ]);

        $result = $response->toArray();
        // dd($result);
        if (!empty($result['id'])) {
            UserModelDataStatus::where('id', $userModelDataStatusId)
                ->update(['openai_job_id' => $result['id'], 'status' => UserModelDataStatus::STATUS_TRAINING]);
            return true;
        } else {
            $userModelData = UserModelData::find($modelDataId);
            if (!empty($userModelData)) {
                $userModelData->update([
                    'status' => UserModelData::OPENAI_ERROR,
                    'note' => $result['error']['message']
                ]);
            }
            $arr = [
                'code' => 305,
                'message' => 'training file failed'
            ];
            return $arr;
        }
        return false;
    }

    public function getTopicRelate($resultQuestion)
    {
        $model = 'ft:gpt-3.5-turbo-0613:pythaverse-space:datatrain12-12:8UoNA0Dc';
        $model = 'gpt-3.5-turbo-0125';
        $open_ai_key = getenv('OPENAI_API_KEY');
        $client = OpenAI::client($open_ai_key);
        $chat = $client->chat()->create([
            'model' => $model,
            'messages' => [
                [
                    "role" => "user",
                    "content" => "Please help me identify the shortest possible topics separated by commas from the following list of questions: \n" . $resultQuestion
                ],
            ],
            'temperature' => 0,
            'max_tokens' => 1000
        ]);
        if (!empty($chat->error)) {
            return '';
        }
        return $chat->choices[0]->message->content;
    }
}
