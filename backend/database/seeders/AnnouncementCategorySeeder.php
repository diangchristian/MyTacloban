<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AnnouncementCategory;
use Illuminate\Support\Facades\DB;

class AnnouncementCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Emergency',
            'Community Event',
            'Notice',
            'Public Service',
            'Holiday',
            'General Announcement',
        ];

        foreach ($categories as $category) {
            AnnouncementCategory::firstOrCreate(['category_name' => $category]);
        }
    }
}
