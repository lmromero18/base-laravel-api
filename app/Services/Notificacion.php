<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Notificacion
{
    public $urlCorreo;

    public function __construct()
    {
        $baseCorreo = config('app.url_correo_api');

        $this->urlCorreo = "{$baseCorreo}/api/correo";
    }

    public function enviar($codigo, $asunto = null, $mensaje = null, $destinatario = null, $adjunto = null,  $cc = null, $cco = null)
    {
        try {
           
                try {
                    $correoResultado = $this->notificarCorreo($codigo, $asunto, $mensaje, $destinatario, $adjunto, $cc, $cco);
                } catch (\Exception $e) {
                    $correoResultado = [
                        "enviado" => false
                    ];
                }
            
        } catch (\Throwable $th) {
            //No se hace nada para evitar que se rompa el flujo
            Log::error($th);
        }
    }

    public function notificarCorreo( $codigo= null, $asunto = null, $mensaje = null, $destinatario = null, $adjunto = null, $cc = null, $cco = null)
    {
        // if ($config->in_correo == 1) {

            $result = [];
                $response = $this->client()->post($this->urlCorreo, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer " . request()->bearerToken(),
                    ],
                    'json' => [
                        'destinatario' => $destinatario ,
                        'contenido' => $mensaje ,
                        'asunto' => $asunto ,
                        'id_usuario' => 1,
                        'adjunto' =>$adjunto,
                        'cc'=>$cc,
                        'cco'=>$cco,
                    ],
                    "timeout" => 5,
                    //'http_errors' => false,
                ]);
              
                $result[] = (json_decode((string) $response->getBody()))->id ?? null;

            return [
                "enviado" => true,
                "correos" => $result,
            ];
        // }

        // return [
        //     "enviado" => false
        // ];
    }

    /**
     * Recibe un parametro config y returna una lista de correos
     *
     * @param  json $config
     * @return array $correos
     */
    public function getCorreos($config)
    {
        $correos = [];

        if ($config->contactos) {
            $correos = $config->contactos->pluck('tx_email')->toArray();
        }

        if ($config->emails) {
            $correos = array_merge($config->emails, $correos);
        }

        return $correos;
    }

  

    public function client(): Client {
        return new Client(['verify' => false,'timeout' => 20]);
    }
}