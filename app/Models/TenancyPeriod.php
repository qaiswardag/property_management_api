<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenancyPeriod extends Model
{
    /** @use HasFactory<\Database\Factories\TenancyPeriodFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'type',
        'height',
        'name',
        'start_date',
        'end_date',
        'active',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
