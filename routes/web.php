<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeluqueriaController;

Route::get('/', function () {
    return view('index');
});

// Ruta comodín para capturar todas las demás peticiones GET
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');
