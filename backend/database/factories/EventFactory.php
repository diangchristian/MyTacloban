<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(7),
            'description' => $this->faker->sentence(2),
            'category_id' => EventCategory::factory(),
            'content' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->city(),
            'image' => $this->faker->imageUrl(640, 480, 'events', true), // placeholder image
            'event_time' => $this->faker->time('H:i'),
            'event_date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
