<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Device;
class DeviceCommand extends Model
{
    protected $fillable = [
        'device_id',
        'command',
        'executed',
        'execute_at'
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }
}
