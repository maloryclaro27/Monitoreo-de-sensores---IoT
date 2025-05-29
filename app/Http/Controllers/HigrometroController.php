<?php

namespace App\Http\Controllers;

use App\Models\Higrometro;
use Illuminate\Http\Request;

class HigrometroController extends Controller
{
    // Mostrar la vista con credenciales de Supabase
    public function index()
    {
        return view('higrometro', [
            'supabaseUrl' => config('services.supabase.url'),
            'supabaseKey' => config('services.supabase.key'),
        ]);
    }

    // Devolver JSON con las últimas 50 lecturas
    public function ultimas()
    {
        return Higrometro::select([
                'humedad_valor',
                'bateria_porcentaje',
                'senal_red_dbm',
                'timestamp',
            ])
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();
    }
}
