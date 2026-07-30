<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class cors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set(
            'Access-Control-Allow-Origin',
            'http://127.0.0.1:3011'
        );

        $response->headers->set(
            'Access-Control-Allow-Headers',
            'Content-Type, X-Auth-Token, Authorization, Origin'
        );

        $response->headers->set(
            'Access-Control-Allow-Methods',
            'GET, POST, PUT, PATCH, DELETE, OPTIONS'
        );

        $response->headers->set(
            'Access-Control-Allow-Credentials',
            'true'
        );

        return $response;
    }
}



