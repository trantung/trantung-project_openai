<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/openai/test',
        '/api/openai/sentence_1',
        'api/openai/introduction',
        '/api/openai/topic_sentence',
    ];
}
