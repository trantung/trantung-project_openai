<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommonEms extends Model
{
    //const task1
    const VOCABULARY_GRAMMA_TASK_1 = 1;
    const TASK_ACHIEMENT_TASK_1 = 2;
    const COHERENCE_COHESION_TASK_1 = 3;
    const LEXICAL_RESOURCE_TASK_1 = 4;
    const GRAMMA_RANGE_TASK_1 = 5;

    public static function connectEms($apiUrl, $method, $contentType = null, $token = null)
    {
        $ch = curl_init();
        $headers = array();
        if ($contentType) {
            $headers[] = $contentType;
        }
        if ($token) {
            $headers[] = $token;
        }
        $domain = getenv('EMS_API_DOMAIN');
        $apiKey = getenv('EMS_API_KEY');
        $url = $domain . '/' . $apiUrl . '?apikey=' . $apiKey;
        // /api_server/thirdparty/lmsnew/mockContest/all?apikey=qxR8Hi6TlwVKR6LLdXryKe3FpXXdDH6r

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    //get list exam
    public static function getListExam()
    {
        $apiUrl = getenv('EMS_API_URL_ALL');
        $data = self::connectEms($apiUrl, 'GET');
        if($data) {
            return $data;
        }
        return false;
    }

}
