<?php

namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Illuminate\Support\Arr;

class KeycloakService extends AbstractProvider implements ProviderInterface
{
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            "{$this->config['base_url']}/auth/realms/{$this->config['realm']}/protocol/openid-connect/auth", $state
        );
    }

    protected function getTokenUrl()
    {
        return "{$this->config['base_url']}/auth/realms/{$this->config['realm']}/protocol/openid-connect/token";
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            "{$this->config['base_url']}/auth/realms/{$this->config['realm']}/protocol/openid-connect/userinfo", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return [
            'id' => $user['sub'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];
    }

    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'redirect_uri' => config('services.keycloak.redirect'),
            // 'realm' => config('services.keycloak.realm'),
            'code' => $code,
        ];
    }

    protected function getCodeFields($state = null)
    {
        return array_merge(
            parent::getCodeFields($state),
            ['response_mode' => 'query']
        );
    }
}
