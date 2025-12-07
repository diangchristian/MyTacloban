<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barangay>
 */
class BarangayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->streetName(),
            'population' => $this->faker->numberBetween(1000, 20000),
            'households' => $this->faker->numberBetween(200, 5000),
            'contact_person' => $this->faker->name(),
            'contact_no' => $this->faker->phoneNumber(),
            'coordinates' => $this->faker->latitude() . ',' . $this->faker->longitude(),
            'area' => $this->faker->randomFloat(2, 1, 20), // e.g., 5.23 km²
            'email' => $this->faker->unique()->safeEmail(),
            'barangay_captain' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
