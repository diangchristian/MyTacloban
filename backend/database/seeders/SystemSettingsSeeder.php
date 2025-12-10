<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('system_settings')->insert([
            'system_name' => 'My Tacloban',
            'description' => 'Default description for the system settings.',
            'logo_path' => '/storage/system/default-logo.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
