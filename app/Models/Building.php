<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    /** @use HasFactory<\Database\Factories\BuildingFactory> */
    use HasFactory;

    protected $fillable = [
        'corporation_id',
        'type',
        'height',
        'name',
        'zip_code',
    ];

    public function corporation()
    {
        return $this->belongsTo(Corporation::class);
    }
}
