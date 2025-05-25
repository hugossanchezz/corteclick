<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeluqueriaController;
use App\Http\Controllers\PeluqueriaSolicitudController;
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

// ------ Auth ------------------------------------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::patch('/user/update/{id}', [AuthController::class, 'updateUser']);
});

// ------ Locals ----------------------------------------------------------
Route::post('/new-request', [PeluqueriaSolicitudController::class, 'createLocalRequest']);

Route::post('/new-local', [PeluqueriaController::class, 'createNewLocal']);

Route::delete('/delete-local/{email}', [PeluqueriaController::class, 'deleteLocalByEmail']);

Route::get('/locals', [PeluqueriaController::class, 'getPeluquerias']);

Route::get('/locals/{id}', [PeluqueriaController::class, 'getPeluqueriaById']);

// ------ Localities ------------------------------------------------------
Route::get('/localities/{valor}', [LocalidadController::class, 'getIdsByCodigoPostalONombre']);

Route::get('/localities', [LocalidadController::class, 'getLocalidadesIdNombre']);

Route::get('/localities/{id}/name', [LocalidadController::class, 'getNombreById']);

// ------ Appointments ----------------------------------------------------

// ------ Admin ----------------------------------------------------------
Route::get('/admin/requests', [PeluqueriaSolicitudController::class, 'getLocalsRequest']);

Route::post('/admin/requests/{id}/cambiarEstado', [PeluqueriaSolicitudController::class, 'cambiarEstado']);

