<?php

namespace Database\Factories;

use App\Models\Barangay;
use App\Models\BarangayOfficial;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BarangayOfficialFactory extends Factory
{
    protected $model = BarangayOfficial::class;

    public function definition(): array
    {
        return [
            'barangay_id' => Barangay::factory(),
            'official_name' => $this->faker->name(),
            'position' => $this->faker->randomElement([
                'captain',
                'councilor',
                'skchairman',
                'secretary',
                'treasurer'
            ]),
            'email' => $this->faker->unique()->safeEmail(),
            'contact_number' => $this->faker->numerify('09#########'), // Philippine mobile format
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Optional: Add state methods for specific positions
    public function captain()
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'captain',
        ]);
    }

    public function councilor()
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'councilor',
        ]);
    }

    public function skchairman()
    {
        return $this->state(fn (array $attributes) => [
            'position' => 'skchairman',
        ]);
    }
}
