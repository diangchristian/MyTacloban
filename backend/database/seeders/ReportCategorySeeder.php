<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReportCategory;


class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Street Light',
                'slug' => 'street_light',
                'icon_name' => 'Lightbulb',
                'color' => '#facc15',
            ],
            [
                'category_name' => 'Flooding',
                'slug' => 'flooding',
                'icon_name' => 'Waves',
                'color' => '#60a5fa',
            ],
            [
                'category_name' => 'Garbage Collection',
                'slug' => 'garbage_collection',
                'icon_name' => 'Trash2',
                'color' => '#4ade80',
            ],
            [
                'category_name' => 'Road Damage',
                'slug' => 'road_damage',
                'icon_name' => 'CarFront',
                'color' => '#000000',
            ],
            [
                'category_name' => 'Public Safety',
                'slug' => 'public_safety',
                'icon_name' => 'Building',
                'color' => '#f87171',
            ],
        ];

        foreach ($categories as $category) {
            ReportCategory::create($category);
        }
    }
}
