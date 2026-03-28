<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Device;

class Telemetry extends Model
{
    protected $fillable = [
        'voltage',
        'current',
        'power',
        'consumption',
        'measured_at'
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }
}
