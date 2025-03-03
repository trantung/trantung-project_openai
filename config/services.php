<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'keycloak' => [
        'client_id' => env('KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
        'redirect' => env('KEYCLOAK_REDIRECT_URI'),
        'base_url' => env('KEYCLOAK_BASE_URL'),
        'realm' => env('KEYCLOAK_REALM', 'hocmai'),
        'realms' => env('KEYCLOAK_REALM', 'hocmai'),
    ],

    'icanid' => [
        'domain' => env('ICANID_DOMAIN'),
        'client_id' => env('ICANID_CLIENT_ID'),
        'client_secret' => env('ICANID_CLIENT_SECRET'),
        'redirect' => env('ICANID_REDIRECT_URI'),
    ],

    'lms' => [
        'URL_LMS' => env('URL_LMS'),
        'TOKEN_LMS' => env('TOKEN_LMS'),
        'URL_API_LMS' => env('URL_API_LMS'),
    ],
];
