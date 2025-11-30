<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\TelegramaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;

// Provincia
Route::post('/provincia/{id}/agregar-mesa', [ProvinciaController::class, 'agregarMesa']);
Route::post('/provincia/{id}/agregar-lista', [ProvinciaController::class, 'agregarLista']);
Route::get('/provincia/{id}/calcular-totales', [ProvinciaController::class, 'calcularTotales']);

// Telegrama
Route::get('/telegrama/{id}/validar', [TelegramaController::class, 'validarSumaVotos']);
Route::get('/telegrama/{id}/votos', [TelegramaController::class, 'obtenerVotosTotales']);

// Persona
Route::get('/persona/{dni}', [PersonaController::class, 'mostrarDatos']);

// Usuario
Route::post('/usuario/login', [UsuarioController::class, 'iniciarSesion']);
