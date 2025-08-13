<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $capitals = [
            "Kabul",
            "London",
            "Tokyo",
            "Paris",
            "Madrid",
            "Rome",
            "Seoul",
            "Nairobi",
            "Tripoli",
            "Kigali",
            "Lusaka",
            "Dakar",
            "Gaborone",
            "Victoria",
            "Kampala",
            "Maputo",
        ];

        $firstName = $this->faker->unique()->randomElement($capitals);
        $lastName = "Tower";


        return [
            'name' => $firstName . ' ' . $lastName,
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
