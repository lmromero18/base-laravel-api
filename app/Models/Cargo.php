<?php

namespace App\Models;

class Cargo extends Model
{
    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_cargo';

    const CREATED_AT = 'ts_fecha_timestamp_ins';
    const UPDATED_AT = 'ts_fecha_timestamp_upd';

    public $timestamps= true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tx_cargo',
        'st_cargo',
    ];

    /**
     * The attributes that should be shown in lists.
     *
     * @var array
     */
    protected $listable = [
    	'id_cargo',
        'tx_cargo',
        'st_cargo',
    ];
}
