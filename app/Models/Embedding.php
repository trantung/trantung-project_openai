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
        $client = OpenAI::client($open_ai_key);
        $result = $client->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $text,
        ]);
        // $res = json_decode($result->embeddings);
        if (count($result->embeddings) == 0) {
            throw new Exception("Failed to generated query embedding!");
        }
        if(empty($result->embeddings[0])) {
            throw new Exception("Failed to generated data");
        }
        return $result->embeddings[0]->embedding;
    }
}
