<?php

namespace App\Http\Controllers;


use Systemson\ApiMaker\ApiResourceTrait as ParentTrait;
use Illuminate\Support\Str;

trait ApiResourceTrait {
    use ParentTrait;


    protected function getLikeOperator($tablePrefix, $column, $value) {
        if (Str::contains($value, '*')) {
            $value = str_replace('*', '%', $value);
        } else {
            $value = '%' . $value . '%';
        }
        // Divide la columna en partes
        $parts = explode('.', $column);
        // Si la columna contiene el nombre de la tabla y el esquema
        if (count($parts) > 2) {
            // Agrega el prefijo solo a la segunda parte (nombre de la tabla)
            if (!Str::startsWith($parts[1], $tablePrefix)) {
                $parts[1] = $tablePrefix . $parts[1];
            }
            // Reconstruye la columna
            $column = implode('.', $parts);
        } else if (count($parts) == 2) {
            // Si la columna contiene solo el nombre de la tabla, agrega el prefijo
            if (!Str::startsWith($parts[0], $tablePrefix)) {
                $parts[0] = $tablePrefix . $parts[0];
            }
            // Reconstruye la columna
            $column = implode('.', $parts);
        } else {
            // Si la columna no contiene el nombre de la tabla, agrega el prefijo
            if (!Str::startsWith($column, $tablePrefix)) {
                $column = $tablePrefix . $column;
            }
        }
        return "UPPER({$column}) LIKE UPPER('{$value}')";
    }
    
    
    
    
    
    
    

}
