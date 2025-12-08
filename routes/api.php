<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\TelegramaController;
use App\Http\Controllers\ProvinciaController;


Route::get('/test', function() {
    return response()->json(['mensaje' => 'API funcionando correctamente']);
});


Route::prefix('candidatos')->group(function () {
    Route::get('/', [CandidatoController::class, 'index']);             
    Route::post('/', [CandidatoController::class, 'store']);           
    Route::get('/{id}', [CandidatoController::class, 'show']);          
    Route::put('/{id}', [CandidatoController::class, 'update']);       
    Route::delete('/{id}', [CandidatoController::class, 'destroy']);   
});


Route::prefix('listas')->group(function () {
    Route::get('/', [ListaController::class, 'index']);               
    Route::post('/', [ListaController::class, 'store']);              
    Route::get('/{id}', [ListaController::class, 'show']);             
    Route::put('/{id}', [ListaController::class, 'update']);          
    Route::delete('/{id}', [ListaController::class, 'destroy']);      
});


Route::prefix('mesas')->group(function () {
    Route::get('/', [MesaController::class, 'index']);                 
    Route::post('/', [MesaController::class, 'store']);                
    Route::get('/{id}', [MesaController::class, 'show']);             
    Route::put('/{id}', [MesaController::class, 'update']);           
    Route::delete('/{id}', [MesaController::class, 'destroy']);       
});


Route::prefix('provincias')->group(function () {
    Route::get('/', [ProvinciaController::class, 'index']);             
    Route::post('/', [ProvinciaController::class, 'store']);          
    Route::get('/{id}', [ProvinciaController::class, 'show']);          
    Route::put('/{id}', [ProvinciaController::class, 'update']);       
    Route::delete('/{id}', [ProvinciaController::class, 'destroy']);   
});


Route::prefix('resultados')->group(function () {
    Route::get('/', [ResultadoController::class, 'index']);             
    Route::post('/', [ResultadoController::class, 'store']);           
    Route::get('/{id}', [ResultadoController::class, 'show']);          
    Route::put('/{id}', [ResultadoController::class, 'update']);       
    Route::delete('/{id}', [ResultadoController::class, 'destroy']);   
});


Route::prefix('telegramas')->group(function () {
    Route::get('/', [TelegramaController::class, 'index']);             
    Route::post('/', [TelegramaController::class, 'store']);           
    Route::get('/{id}', [TelegramaController::class, 'show']);          
    Route::put('/{id}', [TelegramaController::class, 'update']);    
    Route::delete('/{id}', [TelegramaController::class, 'destroy']);  
});
