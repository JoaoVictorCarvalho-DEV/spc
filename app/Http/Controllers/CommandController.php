<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceCommand;
use App\Models\Device;
use Carbon\Carbon;
use Inertia\Inertia;

class CommandController extends Controller
{

    public function store(Request $request, int $device_id)
    {
        $device = Device::find($device_id, ['*']);

        if (!$device) {
            return response()->json(['error' => 'Dispositivo não encontrado'], 404);
        }

        //Para verificar se já existe um comando antigo não executado
        $pendingCommand = DeviceCommand::where('device_id', $device_id)
            ->where('executed', 0)
            ->where('execute_at', '<=', Carbon::now())
            ->first();

        if ($pendingCommand) {
            $timeElapsed = Carbon::now()->diffForHumans($pendingCommand->execute_at);

            return response()->json([
                'error' => 'Já existe um comando pendente para este dispositivo',
                'pending_command' => [
                    'id' => $pendingCommand->id,
                    'command' => $pendingCommand->command,
                    'execute_at' => $pendingCommand->execute_at,
                    'time_elapsed' => $timeElapsed
                ]
            ], 409); // 409 Conflict
        }


        switch ($device->status) {
            case 'online':
                $command = 'turn_off';
                $device->update(['status' => 'offline']);
                break;
            case 'offline':
                $command = 'turn_on';
                $device->update(['status' => 'online']);
                break;
            default:
                return response()->json(['error' => 'Status inválido'], 400);
        }

        $data = [
            'device_id' => $device_id,
            'command' => $command,
            'executed' => 0,
            'execute_at' => Carbon::now()
        ];

        $deviceCommand = DeviceCommand::create($data);

        if ($deviceCommand) {
            return response()->json([
                'success' => true,
                'message' => 'Comando adicionado com sucesso',
                'command' => $deviceCommand
            ], 201);
        }

        return response()->json(['error' => 'Erro ao criar comando'], 500);
    }


    public function create()
    {
        return Inertia::render('Commands/Create');
    }


    public function storeAgendado(Request $request, int $device_id)
    {
        $device = Device::find($device_id, ['*']);

        $data = $request->all();

        dd($data);

        if (!$device) {
            return response()->json(['error' => 'Dispositivo não encontrado'], 404);
        }

        $dataCommand = [
            'device_id' => $device_id,
            'command' => $data['command'],
            'executed' => 0,
            'execute_at' => $data['execute_at']
        ];

        $deviceCommand = DeviceCommand::create($dataCommand);

        if ($deviceCommand) {
            return redirect()->route('dashboard')->with('success', 'Sensor criado com sucesso!');
        }

        return response()->json(['error' => 'Erro ao criar comando'], 500);
    }
}
