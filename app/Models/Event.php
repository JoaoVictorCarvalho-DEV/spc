<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Device;
class Event extends Model
{
    protected $fillable = [
        'device_id',
        'event_type',
        'description'
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }

}
