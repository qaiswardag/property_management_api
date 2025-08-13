<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Corporation;
use App\Models\Property;
use App\Models\TenancyPeriod;
use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Corporation::factory(5)->create()->each(function ($corporation) {
            $buildings = Building::factory(rand(2, 3))->for($corporation)->create();
            $properties = $buildings->flatMap(fn($b) => Property::factory(rand(2, 4))->for($b)->create());
            $tenancies = $properties->flatMap(fn($p) => TenancyPeriod::factory(rand(1, 2))->for($p)->create());
            $tenancies->each(fn($t) => Tenant::factory(rand(1, 4))->for($t)->create());
        });
    }
}
