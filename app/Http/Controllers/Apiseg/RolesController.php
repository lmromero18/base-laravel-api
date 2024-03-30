<?php

namespace App\Http\Controllers\Apiseg;

use App\Http\Controllers\ApiResourceTrait;
use App\Http\Controllers\ApiCrudTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RolesController extends Controller
{
    // use ApiResourceTrait, 
    //     ApiCrudTrait
    // ;

    protected $class = Model::class;

    public function handle(Request $request, $endpoint, $id = null)
    {
        $method = $request->method();
        $params = $request->all();
        $params['client_id'] = config('app.client_id_policia');

        $apiseg_url = config('app.url_auth_api') . '/api/v3/' . $endpoint;

        // Si el método es PATCH, transforma el array de permisos a un array de ids
        if ($method == 'PATCH' && isset($params['permissions'])) {
            $params['permissions'] = array_map(function ($permission) {
                // Verifica si $permission es un objeto antes de intentar acceder a su atributo 'id'
                if (is_object($permission) && property_exists($permission, 'id')) {
                    return $permission->id;
                }
                // Si $permission es un número, lo retorna tal cual
                else if (is_numeric($permission)) {
                    return $permission;
                }
            }, $params['permissions']);
        }

        // Si se proporciona un ID, añádelo a la URL
        if ($id) {
            $apiseg_url .= '/' . $id;
        }

        // Añade las relaciones a la URL si están presentes en la solicitud
        if (!empty($params)) {
            $apiseg_url .= '?' . http_build_query($params);
        }

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $request->bearerToken()
        ])->$method($apiseg_url, $params);

        return response($response->json(), $response->status());
    }
}
