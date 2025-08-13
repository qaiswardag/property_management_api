<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'tenancy_period_id',
    ];

    public function tenancyPeriod()
    {
        return $this->belongsTo(TenancyPeriod::class, 'tenancy_period_id');
    }
}
