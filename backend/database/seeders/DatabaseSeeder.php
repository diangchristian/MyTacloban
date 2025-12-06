<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

      

        $this->call(BarangaySeeder::class);
        $this->call(AnnouncementCategorySeeder::class);
        $this->call(EventCategorySeeder::class);
        // $this->call(ReportCategorySeeder::class);
        $this->call(AnnouncementSeeder::class);
        $this->call(ReportSeeder::class);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
