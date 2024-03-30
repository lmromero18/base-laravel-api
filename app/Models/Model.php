<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Support\Str;
use Systemson\ApiMaker\ListableTrait;
use Systemson\ModelValidations\ValidationsTrait;

class Model extends LaravelModel
{
    use HasFactory,
        ListableTrait,
        ValidationsTrait
    ;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? Str::snake(class_basename($this));
    }
}
