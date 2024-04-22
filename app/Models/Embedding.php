<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \OpenAI;

class Embedding extends Model
{
    use HasFactory;
    public $fillable = ['question', 'answer', 'created_at', 'model_name', 'base_on_id', 'model_ai_id', 'embedding'];
    public $timestamps = false;
    
    public static function tokenize($text, $chunk)
    {
        $normalizedText = preg_replace("/\n+/", "\n", $text);
        $words = explode(' ', $normalizedText);
        $words = array_filter($words);
        // return $words;
        $result = array_chunk($words, $chunk);
        return $result;
    }

    public static function getQueryEmbedding($text)
    {
        $open_ai_key = getenv('OPENAI_API_KEY');
        $open_ai = new OpenAi($open_ai_key);
        $result = $open_ai->embeddings([
            "model" => "text-embedding-ada-002",
            "input" => $text
        ]);
        $res = json_decode($result);
        if (count($res->data) == 0) {
            throw new Exception("Failed to generated query embedding!");
        }
        if(empty($res->data[0])) {
            throw new Exception("Failed to generated data");
        }
        return $res->data[0]->embedding;
    }
}
