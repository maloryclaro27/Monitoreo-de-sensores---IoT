<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Higrometro extends Model
{
    // Nombre real de la tabla
    protected $table = 'higrometro';

    // No usar created_at/updated_at
    public $timestamps = false;

    // Asignación masiva
    protected $fillable = [
        'humedad_valor',
        'unidad_humedad_id',
        'estado',
        'bateria_porcentaje',
        'senal_red_dbm',
        'timestamp',
    ];

    // Cast para timestamp
    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
