<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Telemetry;
use Carbon\Carbon;
use App\Models\Event;

class DeviceReadingController extends Controller
{
    public function store(Request $request, $esp_identifier)
    {
        $device = $request->input('device');

        $data = $request->validate([
            'current'      => 'required|numeric',
            'voltage'      => 'nullable|numeric',
            'power'        => 'required|numeric',
            'consumption'  => 'nullable|numeric',
        ]);

        // Salva a telemetria
        Telemetry::create([
            'device_id'   => $device->id,
            'current'     => $data['current'],
            'voltage'     => $data['voltage'] ?? null,
            'power'       => $data['power'],
            'consumption' => $data['consumption'] ?? null,
            'measured_at' => Carbon::now(),
        ]);

        // Marca device como online
        $device->update([
            'is_online' => true,
        ]);

        // 🔎 Regra inteligente: detectar aparelho desconectado
        if ($data['current'] < 0.05) {
            Event::create([
                'device_id'   => $device->id,
                'type'        => 'unplugged',
                'description' => 'Corrente próxima de zero detectada.',
            ]);
        }

        // 🔎 Regra inteligente: consumo alto
        if ($data['power'] > 1000) {
            Event::create([
                'device_id'   => $device->id,
                'type'        => 'high_consumption',
                'description' => 'Consumo acima de 1000W detectado.',
            ]);
        }

        return response()->json(['status' => 'ok']);
    }


    //TESTE PARA SIMULAR O ENVIO DE INFORMACOES DO ESP PARA O SERVIDOR
    public function test(Request $request, $esp_identifier)
    {
        $device = $request->input('device');

        // Dados mockados
        $mock = [
            'current' => 0.38,
            'voltage' => 127,
            'power' => 48.2,
            'consumption' => 0.0008,
        ];


        Telemetry::create([
            'device_id'   => $device->id,
            'current'     => $mock['current'],
            'voltage'     => $mock['voltage'],
            'power'       => $mock['power'],
            'consumption' => $mock['consumption'],
            'measured_at' => now(),
        ]);

        return response()->json([
            'message' => 'Telemetria de teste salva com sucesso',
            'device'  => $device->name,
            'data'    => $mock,
        ]);
    }
}
