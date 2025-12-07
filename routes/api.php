<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MesasController;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\ListasController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\TelegramasController;
use App\Http\Controllers\ProvinciasController;


Route::get('/test', function() {
    return response()->json(['mensaje' => 'API funcionando correctamente']);
});


Route::prefix('candidatos')->group(function () {

    Route::get('/', [CandidatosController::class, 'index']);             
    Route::post('/', [CandidatosController::class, 'store']);           
    Route::get('/{id}', [CandidatosController::class, 'show']);          
    Route::put('/{id}', [CandidatosController::class, 'update']);       
    Route::delete('/{id}', [CandidatosController::class, 'destroy']);   
});


Route::prefix('listas')->group(function () {
   
    Route::get('/', [ListasController::class, 'index']);               
    Route::post('/', [ListasController::class, 'store']);              
    Route::get('/{id}', [ListasController::class, 'show']);             
    Route::put('/{id}', [ListasController::class, 'update']);          
    Route::delete('/{id}', [ListasController::class, 'destroy']);      
});


Route::prefix('mesas')->group(function () {
    
    Route::get('/', [MesasController::class, 'index']);                 
    Route::post('/', [MesasController::class, 'store']);                
    Route::get('/{id}', [MesasController::class, 'show']);             
    Route::put('/{id}', [MesasController::class, 'update']);           
    Route::delete('/{id}', [MesasController::class, 'destroy']);       
});


Route::prefix('provincias')->group(function () {
    
    Route::get('/', [ProvinciasController::class, 'index']);             
    Route::post('/', [ProvinciasController::class, 'store']);          
    Route::get('/{id}', [ProvinciasController::class, 'show']);          
    Route::put('/{id}', [ProvinciasController::class, 'update']);       
    Route::delete('/{id}', [ProvinciasController::class, 'destroy']);   
});


Route::prefix('resultados')->group(function () {
      
    Route::get('/', [ResultadosController::class, 'index']);             
    Route::post('/', [ResultadosController::class, 'store']);           
    Route::get('/{id}', [ResultadosController::class, 'show']);          
    Route::put('/{id}', [ResultadosController::class, 'update']);       
    Route::delete('/{id}', [ResultadosController::class, 'destroy']);   
});


Route::prefix('telegramas')->group(function () {
      
    Route::get('/', [TelegramasController::class, 'index']);             
    Route::post('/', [TelegramasController::class, 'store']);           
    Route::get('/{id}', [TelegramasController::class, 'show']);          
    Route::put('/{id}', [TelegramasController::class, 'update']);    
    Route::delete('/{id}', [TelegramasController::class, 'destroy']);  
});