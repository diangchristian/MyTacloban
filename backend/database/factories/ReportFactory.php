<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ReportCategory;
use App\Models\Barangay;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Creates a related user
            'barangay_id' => Barangay::inRandomOrder()->first()->id, // Creates a related user
            // Pick an existing category randomly
            'category_id' => ReportCategory::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(6, true),
            'description' => $this->faker->paragraph(3, true),
            'image' => $this->faker->imageUrl(640, 480, 'nature', true),
            'coordinates' => $this->faker->latitude() . ',' . $this->faker->longitude(), 
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'resolved']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
