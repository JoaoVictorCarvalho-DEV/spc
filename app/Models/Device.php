<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'device_uid',
        'location',
        'status'
    ];

    public function telemetry()
    {
        return $this->hasMany(Telemetry::class);
    }
}
