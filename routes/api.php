<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TermometroController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group that
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Ruta para obtener las últimas lecturas del termómetro
Route::get('/termometro/ultimas', [TermometroController::class, 'ultimas']);

// Si usas autenticación via Sanctum o Passport, podrías tener también algo como:
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
