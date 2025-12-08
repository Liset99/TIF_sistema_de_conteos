<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvinciaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de Provincia
Route::get('/provincias', [ProvinciaController::class, 'index']);
Route::post('/provincias', [ProvinciaController::class, 'store']);
Route::get('/provincias/{id}', [ProvinciaController::class, 'show']);
Route::put('/provincias/{id}', [ProvinciaController::class, 'update']);
Route::delete('/provincias/{id}', [ProvinciaController::class, 'destroy']);