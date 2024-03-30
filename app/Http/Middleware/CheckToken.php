<?php

namespace App\Http\Middleware;

use GuzzleHttp\Client;
use Closure;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        $client = new Client();

        $url = config('app.url_auth_api');

        $response = $client->get("{$url}/api/v3/auth/user/sesion/check", [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ],
            'http_errors' => false,
            'verify' => false,
        ]);
        

        $body = json_decode($response->getBody());

        if (!isset($body->available) || $body->available == false) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
