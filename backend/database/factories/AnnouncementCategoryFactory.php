<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnnouncementCategory>
 */
class AnnouncementCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Emergency',
            'Community Event',
            'Notice',
            'Public Service',
            'Holiday',
            'General Announcement',
        ];

        return [
            'category_name' => $this->faker->randomElement($categories),
        ];
    }
}
