<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AnnouncementCategory;
use App\Models\User;
use App\Models\Barangay;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>User::factory() ,
            'barangay_id' =>Barangay::inRandomOrder()->first()->id ,
            'category_id' => AnnouncementCategory::factory(),
            'title' => $this->faker->sentence(10),
            'body' => $this->faker->paragraph(3),
            'image' => $this->faker->imageUrl(640, 480, 'business', true),
            'isHighlight' => $this->faker->boolean(50),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']), // random status
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
