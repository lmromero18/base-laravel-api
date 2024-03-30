<?php

namespace App\Http\Controllers;

use App\Models\Cargo as Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    use ApiResourceTrait,
        ApiCrudTrait
    ;

    protected $class = Model::class;
}
