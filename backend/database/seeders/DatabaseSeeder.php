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
        $this->call(SystemSettingsSeeder::class);
        // $this->call(AnnouncementCategorySeeder::class);
        $this->call(EventCategorySeeder::class);
        $this->call(EventSeeder::class);
        // $this->call(ReportCategorySeeder::class);
        $this->call(AnnouncementSeeder::class);
        // $this->call(ReportSeeder::class);
        User::factory()->create([
            'username' => 'Test User',
            'full_name' => 'admin tester',
            'bio' => 'i am the admin of this type shi',
            'role' => 'Admin',
            'email' => 'test@example.com',
        ]);
    }
}
