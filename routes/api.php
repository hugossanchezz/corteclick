<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeluqueriaController;
use App\Http\Controllers\LocalidadController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ------ Auth ------------------------------------------------------------
Route::post('/register', [AuthController::class , 'register']);

Route::post('/login', [AuthController::class , 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout']);
});

// ------ Appointments ----------------------------------------------------
Route::get('/locals', [PeluqueriaController::class , 'index']);

Route::get('/locals/{id}', [PeluqueriaController::class , 'show']);

// ------ Localities ------------------------------------------------------
Route::get('/localities/{valor}', [LocalidadController::class, 'getIdsByCodigoPostalONombre']);

Route::get('/localities', [LocalidadController::class, 'getLocalidadesIdNombre']);

Route::get('/localities/{id}/name', [LocalidadController::class, 'getNombreById']);

// ------ Locals ----------------------------------------------------------
Route::post('/new-local', [PeluqueriaController::class , 'createLocalRequest']);