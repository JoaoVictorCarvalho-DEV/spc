<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceCommand;
use App\Models\Device;
use Carbon\Carbon;

class CommandController extends Controller
{

    public function store(Request $request, int $device_id)
    {

        $device = Device::find($device_id, 'status');


        switch ($device->status) {
            case 'online':
                $command = 'turn_off';
                break;
            case 'offline':
                $command = 'turn_on';
                break;
            default:
                return false;
        }

        $data = [
            'device_id' => $device_id,
            'command' => $command,
            'executed' => 0,
            'execute_at' => Carbon::now()
        ];

        $device = DeviceCommand::create($data);

        if ($device) {
            return true;
        }
        return false;
    }
}
