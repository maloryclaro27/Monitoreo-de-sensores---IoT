<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TermometroController;
use App\Http\Controllers\HigrometroController;
use App\Http\Controllers\CalidadAireController;


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
Route::get('/higrometro', [HigrometroController::class, 'index'])
     ->name('higrometro');

Route::get('/higrometro/ultimas', [HigrometroController::class, 'ultimas'])
     ->name('higrometro.ultimas');

Route::get('/calidad-aire', [CalidadAireController::class, 'index'])
     ->name('calidad_aire');

// API: últimas lecturas para inicializar y refrescar
Route::get('/calidad-aire/ultimas', [CalidadAireController::class, 'ultimas'])
     ->name('calidad_aire.ultimas');
