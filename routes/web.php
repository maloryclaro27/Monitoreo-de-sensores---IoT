<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TermometroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Vista principal de monitoreo con los 5 cards
Route::view('/', 'monitoreo')
     ->name('monitoreo');

// Página de termómetro (gráfico en tiempo real)
Route::get('/termometro', [TermometroController::class, 'index'])
     ->name('termometro');

// API: últimas lecturas para inicializar o refrescar datos
Route::get('/termometro/ultimas', [TermometroController::class, 'ultimas'])
     ->name('termometro.ultimas');

// Vistas estáticas para otros sensores
Route::view('/higrometro', 'higrometro')
     ->name('higrometro');

Route::view('/calidad-aire', 'calidad_aire')
     ->name('calidad_aire');
