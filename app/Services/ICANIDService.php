<?php

namespace App\Services;

use ICANID\SDK\ICANID;

class ICANIDService
{
    protected $icanid;

    public function __construct()
    {
        $this->icanid = new ICANID([
            'domain'        => config('services.icanid.domain'),
            'redirect_uri'  => config('services.icanid.redirect'),
            'client_id'     => config('services.icanid.client_id'),
            'client_secret' => config('services.icanid.client_secret'),
            'client_secret_authentication_method' => 'client_secret_basic',
            'skip_userinfo' => false,
        ]);
    }

    public function login()
    {
        return $this->icanid->login();
    }

    public function handleCallback()
    {
        $user = $this->icanid->getUser();
        $accessToken = $this->icanid->getAccessToken();
        $refreshToken = $this->icanid->getRefreshToken();
        $idToken = $this->icanid->getIdToken();

        return compact('user', 'accessToken', 'refreshToken', 'idToken');
    }

    public function logout()
    {
        $this->icanid->logout();
    }
    
}
