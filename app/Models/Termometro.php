<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termometro extends Model
{
    // Indica el nombre exacto de la tabla
    protected $table = 'termometro';

    // Desactiva created_at y updated_at
    public $timestamps = false;

    // Columnas que permiten asignación masiva
    protected $fillable = [
        'temperatura_valor',
        'unidad_temperatura_id',
        'estado',
        'bateria_porcentaje',
        'senal_red_dbm',
        'timestamp',
    ];

    // Si deseas que Eloquent trate 'timestamp' como una instancia de DateTime:
    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
