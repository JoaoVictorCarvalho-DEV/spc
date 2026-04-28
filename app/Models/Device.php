<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Telemetry;
use App\Models\Event;
use App\Models\Schedule;
use App\Models\DeviceCommand;
use App\Models\User;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'device_uid',
        'location',
        'api_token',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function telemetries()
    {
        return $this->hasMany(Telemetry::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }


    public function deviceCommands()
    {
        return $this->hasMany(DeviceCommand::class);
    }

    public function appliences(){
        return $this->belongsToMany(Applience::class);
    }
    protected static function booted()
    {
        static::creating(function ($device) {
            $device->api_token = Str::random(60);
        });
    }

}
