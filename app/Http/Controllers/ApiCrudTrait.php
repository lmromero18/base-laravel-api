<?php

namespace App\Http\Controllers;


use Systemson\ApiMaker\ApiCrudTrait as ParentTrait;

trait ApiCrudTrait
{
    use ParentTrait;

    /**
     * Llena la tabla pivote para las relaciones de muchos a muchos
     */
    protected function syncRelations($model, $request, array $relations)
    {
        foreach ($relations as $relation) {
            $data = $request->get($relation);
            if (is_array($data)) {
                $model->{$relation}()->sync($data);
            }
        }

        return $model;
    }
}
