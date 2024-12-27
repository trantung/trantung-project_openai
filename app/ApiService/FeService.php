<?php

namespace App\ApiService;

use App\Models\Students;

class FeService
{
    public function getDataUserInfo($json)
    {
        $url = env('ICANID_DOMAIN_GET_USER_INFO');
        $bearerToken = $jsonData['access_token'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $bearerToken
        ]);
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            return false;
        }
        $data = json_decode($response, true);

        return  $this->createStudent($data);
    }

    public function createStudent($data)
    {
        $email = $data['email'];
        $sso_id = $data['sub'];
        $sso_name = 'icanid';
        $phone = $data['phone'];
        $displayName = $data['displayName'];
        $username = explode('@', $email)[0];
        //check student
        $student = Students::where('email', $email)->first();
        if($student) {
            return $student->toArray();
        }

        $student = Students::create(
            [
                'email' => $email,
                'name' => $displayName,
                'username' => $username,
                'password' => bcrypt('Hocmai@1234'),
                'sso_id' => $sso_id,
                'sso_name' => 'icanid'
            ]
        );
        return $student->toArray();
    }
}
