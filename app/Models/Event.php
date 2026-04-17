<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'device_id',
        'event_type',
        'description',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
