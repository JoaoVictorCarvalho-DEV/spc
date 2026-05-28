<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceCommand;
use App\Models\Device;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function storeForm(Request $request, int $device_id)
    {
        try {
            // Buscar dispositivo com validação
            $device = Device::findOrFail($device_id);
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Dispositivo não encontrado. Por favor, tente novamente.');
        }

        // Validação dos dados
        $validated = $request->validate([
            'command' => 'required|in:turn_on,turn_off',
            'execute_at' => 'required|date|after_or_equal:now',
        ], [
            'command.required' => 'Por favor, selecione um comando.',
            'command.in' => 'O comando selecionado é inválido.',
            'execute_at.required' => 'Por favor, informe uma data e horário para execução.',
            'execute_at.date' => 'Por favor, informe uma data válida.',
            'execute_at.after_or_equal' => 'A data e horário devem ser iguais ou posteriores ao momento atual.',
        ]);

        try {
            // Verificar comando pendente
            $pendingCommand = DeviceCommand::where('device_id', $device_id)
                ->where('executed', false)
                ->where('execute_at', '>=', Carbon::now())
                ->first();

            if ($pendingCommand) {
                $commandLabel = $this->getCommandLabel($pendingCommand->command);
                $formattedDate = Carbon::parse($pendingCommand->execute_at)->format('d/m/Y H:i');

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "Já existe um comando pendente para este dispositivo. Comando: {$commandLabel} agendado para {$formattedDate}. Aguarde a execução.");
            }

            // Criar o comando agendado
            $deviceCommand = DeviceCommand::create([
                'device_id' => $device_id,
                'command' => $validated['command'],
                'executed' => false,
                'execute_at' => Carbon::parse($validated['execute_at'])
            ]);

            $commandLabel = $this->getCommandLabel($validated['command']);
            $formattedDate = Carbon::parse($validated['execute_at'])->format('d/m/Y H:i');

            return redirect()
                ->route('devices.show', $device_id)
                ->with('success', "Comando '{$commandLabel}' agendado com sucesso para {$formattedDate}.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar o agendamento. Por favor, tente novamente. ' . $e->getMessage());
        }
    }


    public function create(int $device_id)
    {
        try {
            $device = Device::findOrFail($device_id);

            return Inertia::render('Commands/Create', [
                'device' => $device,
                'available_commands' => [
                    ['value' => 'turn_on', 'label' => 'Ligar dispositivo'],
                    ['value' => 'turn_off', 'label' => 'Desligar dispositivo']
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Dispositivo não encontrado.');
        }
    }


    private function getCommandLabel(string $command): string
    {
        return match ($command) {
            'turn_on' => 'Ligar',
            'turn_off' => 'Desligar',
            default => $command
        };
    }
}
