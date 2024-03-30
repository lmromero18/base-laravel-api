<?php

namespace App\Http\Middleware;

use Closure;

class GetToken
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
        $id_cuenta_msp = getenv('ID_CUENTA_MSP');

        if (isset($token) && !is_null($token)) {
            try {
                $decoded = $this->getToken($token);
                $request->merge([
                    "id_cuenta_msp" => $id_cuenta_msp,
                ]);
                if (isset($decoded->payload->user->tx_atributo)) {
                    if ($request->isMethod('put') || $request->isMethod('patch')) {
                        $request->merge([
                            "id_actualizado" => $decoded->payload->sub,
                        ]);
                    } else if ($request->isMethod('post')) {
                        $request->merge([
                            "id_creado" => $decoded->payload->sub,
                        ]);
                    }

                    // dd($request);
                    return $next($request);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => 'unauthenticated',
                    'message' => $e->getMessage()
                ], 401);
            }
        }

        return response()->json([
            'status' => 'unauthenticated',
            'message' => 'No valid token provided.'
        ], 401);
    }

    function getToken($tokenString)
    {
        $raw = explode(".", $tokenString);

        return (object) [
            "headers" => json_decode(base64_decode($raw[0])),
            "payload" => json_decode(base64_decode($raw[1])),
            // "signature" => base64_decode($raw[2]),
        ];
    }
}
