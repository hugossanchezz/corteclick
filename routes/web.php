<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Ruta comodín para capturar todas las demás peticiones GET
Route::get('/{any}', function () {
    return view('index'); // Devuelve tu vista principal (donde se monta Vue)
})->where('any', '.*');
