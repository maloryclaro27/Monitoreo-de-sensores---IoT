<?php

namespace App\Http\Controllers;

use App\Models\Termometro;
use Illuminate\Http\Request;

class TermometroController extends Controller
{
    // Carga la vista inyectando las credenciales
    public function index()
    {
        return view('termometro', [
            'supabaseUrl' => config('services.supabase.url'),
            'supabaseKey' => config('services.supabase.key'),
        ]);
    }

    // Devuelve JSON con las últimas lecturas
    public function ultimas()
    {
        return Termometro::select([
                'temperatura_valor',
                'bateria_porcentaje',
                'senal_red_dbm',
                'timestamp',
            ])
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();
    }
}
