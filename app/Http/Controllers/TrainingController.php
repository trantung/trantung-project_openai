<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use \OpenAI;
use App\Models\Embedding;

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
        $data = DB::connection('pgsql')->table('embeddings')->paginate(10);
        return view('training.index', compact('data'));
    }
 
    public function create()
    {
        return view('training.form');
    }
 
    public function store(Request $request)
    {
        // $data = $this->convertDataToJsonTo($request);
        // if($data['code'] != 200){
        //     return $this->responseError(422, $this->convertDataToJsonTo($request)['data']['messages']);
        // }
        $questions = $request['questions'];
        $answers = $request['answers'];
        foreach ($questions as $index => $question) {
            $questionContent = str_replace(['\\', '"'], ["'", '\"'], trim(preg_replace('/\s\s+/', ' ', $question)));
            $answerContent = str_replace(['\\', '"'], ["'", '\"'], trim(preg_replace('/\s\s+/', ' ', $answers[$index])));
            $tokens = Embedding::tokenize($answerContent, 200);
            foreach ($tokens as $token) {
                $text = implode("\n", $token);
                $vectors = Embedding::getQueryEmbedding($text);
                $embeddingId = DB::connection('pgsql')->table('embeddings')->insertGetId([
                    'question' => $questionContent,
                    'answer' => $answerContent,
                    'created_at' => date('Y-m-d H:i:s'),
                    'model_name' => $request['model_name'],
                    'embedding' => json_encode($vectors)
                ]);
            }            
        }
        // $message = $jsonData['content'];
        session()->flash('success', 'Data created successfully');
        return redirect(route('training.index'));
    }
    
    
    public function formChat()
    {
        return view('chat.form');
    }
    
    public function detailChat()
    {
        return view('chat.detail');
    }

    public function convertDataToJsonTo($request){
        $questions = $request['questions'];
        $answers = $request['answers'];

        $messages = array();

        if (count($questions) > 0) {
            if(count($questions) < 10 || count($answers) < 10){
                $arr = [
                    'code' => 305,
                    'data' => [
                        'messages' => 'Question and answer does not have enough 10 questions and 10 answers'
                    ]
                ];
                return $arr;
            }else{
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
        $embedding = DB::connection('pgsql')->table('embeddings')->find($id);

        if (!$embedding) {
            session()->flash('error', 'Data Not Found');
            return redirect(route('training.index'));
        }

        DB::connection('pgsql')->table('embeddings')->where('id', $id)->delete();

        session()->flash('success', 'Data Deleted Successfully');
        return redirect(route('training.index'));
    }

    public function detailTraining($id)
    {
        $embedding = DB::connection('pgsql')->table('embeddings')->find($id);
        return view('training.detail', compact('embedding'));
    }
}
