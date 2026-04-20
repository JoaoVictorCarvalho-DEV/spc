<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Fruitcake\LaravelDebugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all();
        return Inertia::render('Dashboard', compact('devices'));
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
    public function show(Device $device)
    {

        $todayStart = Carbon::today();
        $todayEnd   = Carbon::now();

        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd   = Carbon::today();

        // 🔹 Telemetrias de hoje
        $todayTelemetries = $this->getTelemetries($device, $todayStart, $todayEnd);

        // 🔹 Telemetrias de ontem
        $yesterdayTelemetries = $this->getTelemetries($device, $yesterdayStart, $yesterdayEnd);

        $consumoHoje = $this->calculateKwh($todayTelemetries);
        $consumoOntem = $this->calculateKwh($yesterdayTelemetries);

        $tempoLigadoHoje = $this->calculateOnTime($todayTelemetries);

        $picoHoje = $todayTelemetries->max('power');

        $potenciaMediaLigado = $todayTelemetries
            ->where('power', '>', 5)
            ->avg('power');

        $custoHoje = $consumoHoje * config('energy.tarifa_kwh', 0.95);



        return Inertia::render('Devices/Show', [
            'device' => $device,
            'events' => $device->events()->latest()->take(10)->get(),
            'stats' => [
                'consumo_atual' => round($consumoHoje, 3),
                'eventos_hoje' => $device->events()->whereDate('created_at', today())->count(),
                'desligamentos_programados' => $device->deviceCommands()->count(),
                'consumo_ontem' => $consumoOntem,
                'tempo_ligado_hoje' => $tempoLigadoHoje,
                'pico_hoje' => $picoHoje,
                'potencia_media_ligado' => $potenciaMediaLigado,
                'custo_hoje' => $custoHoje
            ],
        ]);
    }


    private function getTelemetries($device, $start, $end)
    {
        return DB::table('telemetries')
            ->where('device_id', $device->id)
            ->whereBetween('measured_at', [$start, $end])
            ->orderBy('measured_at')
            ->get();
    }

    // 🔥 cálculo real de kWh
    private function calculateKwh($rows)
    {
        $kwh = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $prev = $rows[$i - 1];
            $curr = $rows[$i];

            $seconds = strtotime($curr->measured_at) - strtotime($prev->measured_at);
            $hours = $seconds / 3600;

            $kwh += ($prev->power / 1000) * $hours;
        }

        return round($kwh, 3);
    }

    // ⏱️ tempo ligado
    private function calculateOnTime($rows)
    {
        $secondsOn = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $prev = $rows[$i - 1];
            $curr = $rows[$i];

            if ($prev->power > 5) {
                $secondsOn += strtotime($curr->measured_at) - strtotime($prev->measured_at);
            }
        }

        return gmdate('H:i:s', $secondsOn);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }
}
