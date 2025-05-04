<?php

namespace App\Http\Controllers;

use App\Models\Peluqueria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PeluqueriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
{
    $peluquerias = Peluqueria::all();
    return response()->json($peluquerias);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Peluqueria $peluqueria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peluqueria $peluqueria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peluqueria $peluqueria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peluqueria $peluqueria)
    {
        //
    }
}
