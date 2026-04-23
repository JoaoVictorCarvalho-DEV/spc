<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceCommand;
use Illuminate\Http\Request;
use App\Models\Telemetry;
use Carbon\Carbon;
use App\Models\Event;

class DeviceReadingController extends Controller
{
    public function store(Request $request)
    {
        $device = $request->input('device');

        $data = $request->validate([
            'current'      => 'required|numeric',
            'voltage'      => 'nullable|numeric',
            'power'        => 'required|numeric',
            'onState'  => 'boolean',
        ]);

        // Salva a telemetria
        Telemetry::create([
            'device_id'   => $device->id,
            'current'     => $data['current'],
            'voltage'     => $data['voltage'] ?? null,
            'power'       => $data['power'],
            'measured_at' => Carbon::now(),
        ]);

        // Marca device como online
        $device->update([
            'status' => 'online',
        ]);


        if ($data['current'] < 0.05) {
            Event::create([
                'device_id'   => $device->id,
                'event_type'        => 'unplugged',
                'description' => 'Corrente próxima de zero detectada.',
            ]);
        }

        $command = DeviceCommand::where('device_id', $device->id)
            ->where('executed', 0)
            ->where('execute_at', '<=', now())
            ->orderBy('execute_at')
            ->first();

        if ($command) {
            $command->executed = 1;
            $command->updated_at = now();
            $command->save();

            return response()->json([
                'status' => 'ok',
                'should_execute' => true,
                'command' => $command->command,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }


    //TESTE PARA SIMULAR O ENVIO DE INFORMACOES DO ESP PARA O SERVIDOR
    public function test(Request $request)
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
