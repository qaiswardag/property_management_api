<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    protected $fillable = [
        'tenancy_period_id',
        'type',
        'height',
        'name',
        'move_in_date',
        'move_in_date',
    ];

    public function tenancyPeriod()
    {
        return $this->belongsTo(TenancyPeriod::class, 'tenancy_period_id');
    }
}
