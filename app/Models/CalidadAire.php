<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalidadAire extends Model
{
    // Nombre exacto de la tabla
    protected $table = 'calidadaire';

    // No usar created_at/updated_at automáticos
    public $timestamps = false;

    // Columnas asignables en masa
    protected $fillable = [
        'calidad_aire_valor',
        'unidad_calidad_aire_id',
        'estado',
        'bateria_porcentaje',
        'senal_red_dbm',
        'timestamp',
    ];

    // Castear timestamp a instancia de DateTime
    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
