<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeluqueriaController;
use App\Http\Controllers\PeluqueriaFotoController;
use App\Http\Controllers\PeluqueriasController;
use App\Http\Controllers\PeluqueriaSolicitudController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\ServiciosPeluqueriaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitaController;
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


// ------ Local Requests ----------------------------------------------------------
Route::post('/new-request', [PeluqueriaSolicitudController::class, 'createLocalRequest']);

Route::post('/approve-request/{id_solicitud}', [PeluqueriaSolicitudController::class, 'approveRequest']);


// ------ Locals ----------------------------------------------------------
Route::get('/local/{id_peluqueria}/name', [PeluqueriaController::class, 'getPeluqueriaNombreById']);

Route::get('/local/{id_peluqueria}/photos', [PeluqueriaFotoController::class, 'getFotosByPeluqueria']);

Route::get('/locals', [PeluqueriaController::class, 'getPeluquerias']);

Route::get('/locals/{id_peluqueria}', [PeluqueriaController::class, 'getPeluqueriaById']);

Route::get('/locals/{id_peluqueria}/services', [ServiciosPeluqueriaController::class, 'getServiciosByPeluqueriaId']);

Route::get('/locals/search/{valor}', [PeluqueriasController::class, 'getIdsByCodigoPostalONombre']);


// ------ Services --------------------------------------------------------
Route::get('/services/{id_servicio}/name', [ServicioController::class, 'getNombrePorId']);


// ------ Localities ------------------------------------------------------
Route::get('/localities', [LocalidadController::class, 'getLocalidadesIdNombre']);

Route::get('/localities/{id_localidad}/name', [LocalidadController::class, 'getNombreById']);


// ------ Appointments ----------------------------------------------------
Route::get('/appointments', [CitaController::class, 'getCitas']);

Route::post('/appointments/new', [CitaController::class, 'createCita']);

Route::get('/appointments/{id_peluqueria}', [CitaController::class, 'getCitasByIdPeluqueria']);

Route::get('/appointments/user/{id_usuario}', [CitaController::class, 'getCitasByIdUsuario']);

Route::delete('/appointments/{id_cita}/delete', [CitaController::class, 'deleteCita']);

Route::patch('/appointments/{id_cita}/cancel', [CitaController::class, 'cancelCita']);

Route::patch('/appointments/check-expired', [CitaController::class, 'marcarCitasTerminadas']);


// ------ User ----------------------------------------------------------


// ------ Admin ----------------------------------------------------------
Route::get('/admin/requests', [PeluqueriaSolicitudController::class, 'getLocalsRequest']);

Route::post('/admin/requests/{id_solicitud}/cambiarEstado', [PeluqueriaSolicitudController::class, 'cambiarEstado']);

