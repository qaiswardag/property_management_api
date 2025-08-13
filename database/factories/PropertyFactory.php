<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->word()) . ' ' . ucfirst(fake()->word()) . ' ' . fake()->randomElement([
                'Villa',
                'Estate',
                'Manor',
                'Cottage',
                'Residence',
                'Lodge',
                'House',
                'Mansion',
                'Court',
                'Gardens',
                'Heights',
                'Terrace',
                'Place',
                'Park',
                'Retreat',
                'Villa Nova',
                'Haven',
                'Point',
                'Palace',
                'Hills'
            ]),

            'monthly_rent' => 10000,
        ];
    }
}
