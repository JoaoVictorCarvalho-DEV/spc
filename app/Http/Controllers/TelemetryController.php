<?php

namespace App\Http\Controllers;

use App\Models\Telemetry;
use App\Http\Requests\StoreTelemetryRequest;
use App\Http\Requests\UpdateTelemetryRequest;

class TelemetryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTelemetryRequest $request)
    {
        $dados = $request->va
    }

    /**
     * Display the specified resource.
     */
    public function show(Telemetry $telemetry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Telemetry $telemetry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTelemetryRequest $request, Telemetry $telemetry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Telemetry $telemetry)
    {
        //
    }
}
