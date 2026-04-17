<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telemetry extends Model
{
    /** @use HasFactory<\Database\Factories\TelemetryFactory> */
    use HasFactory;

    protected $fillable = [
        'device_id',
        'current',
        'voltage',
        'power',
        'created_at',
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }
}
