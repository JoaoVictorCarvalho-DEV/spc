<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applience extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    public function device()
    {
        return $this->belongsToMany(Device::class);
    }
}
