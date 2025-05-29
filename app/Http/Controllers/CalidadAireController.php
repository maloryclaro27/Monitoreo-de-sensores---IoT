<?php

namespace App\Http\Controllers;

use App\Models\CalidadAire;
use Illuminate\Http\Request;

class CalidadAireController extends Controller
{
    /**
     * Mostrar la vista Blade con las credenciales de Supabase
     */
    public function index()
    {
        return view('calidad_aire', [
            'supabaseUrl' => config('services.supabase.url'),
            'supabaseKey' => config('services.supabase.key'),
        ]);
    }

    /**
     * Endpoint para devolver las últimas 50 lecturas en JSON
     */
    public function ultimas()
    {
        return CalidadAire::select([
                'calidad_aire_valor',
                'bateria_porcentaje',
                'senal_red_dbm',
                'estado',
                'timestamp',
            ])
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();
    }
}
