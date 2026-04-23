<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceCommand;
use Illuminate\Http\Request;

class DeviceCommandController extends Controller
{

    public function next(Request $request)
    {
        $device = $request->input('device');

        $deviceCommand = $device->deviceCommands()
            ->where('executed', false)
            ->oldest()
            ->first(['id', 'command']);


        return response()->json(
            [
                'device_command' => $deviceCommand,
            ],
            200
        );
    }


    public function confirmCommand(Request $request, $espIdentifier, DeviceCommand $command)
    {

        if (!$command->update(['executed' => true])) {
            return response()->json(['message' => 'error'], 400);
        }

        return response()->json(['message' => 'success'], 201);
    }

    public function store(Request $request)
    {
        $device = $request->input('device');

        $deviceCommand = DeviceCommand::create([
            'command' => 'turn_on',
            'device_id' => $device->id
        ]);

        if (!$deviceCommand) {
            return response()->json(['message' => 'error'], 400);
        }

        return response()->json(['message' => 'success'], 201);
    }
}
