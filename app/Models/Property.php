<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'building_id',
        'type',
        'height',
        'name',
        'monthly_rent',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
