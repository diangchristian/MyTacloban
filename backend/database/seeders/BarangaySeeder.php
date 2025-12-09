<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $barangays = [
            [
                'name' => 'Barangay 101',
                'population' => 3681,
                'households' => rand(300, 900),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3320,124.9492',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 103',
                'population' => 4681,
                'households' => rand(400, 1000),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.2549,124.9470',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 104',
                'population' => 2348,
                'households' => rand(250, 800),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.2335,124.9606',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 105',
                'population' => 12296,
                'households' => rand(500, 1500),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3159,124.9541',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 106',
                'population' => 11661,
                'households' => rand(600, 1600),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3242,124.9511',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 107',
                'population' => 1749,
                'households' => rand(300, 800),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3412,124.9368',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 108',
                'population' => 4759,
                'households' => rand(500, 1200),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3445,124.9548',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 78',
                'population' => 2219,
                'households' => rand(400, 900),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.2016,125.0054',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 100',
                'population' => 3293,
                'households' => rand(300, 1000),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3101,124.9456',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 99',
                'population' => 6415,
                'households' => rand(500, 1300),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.2626,124.9664',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 102',
                'population' => 2890,
                'households' => rand(300, 900),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.2950,124.9600',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 109',
                'population' => 4100,
                'households' => rand(400, 1100),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3500,124.9600',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 110',
                'population' => 3890,
                'households' => rand(400, 1000),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3550,124.9650',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 111',
                'population' => 4500,
                'households' => rand(500, 1200),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3600,124.9700',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
            [
                'name' => 'Barangay 112',
                'population' => 3200,
                'households' => rand(300, 900),
                'contact_person' => fake()->name(),
                'contact_no' => fake()->phoneNumber(),
                'coordinates' => '11.3650,124.9750',
                'area' => rand(1, 20) + rand(0,99)/100,
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
            ],
        ];

        foreach ($barangays as $data) {
            Barangay::updateOrCreate(
                ['name' => $data['name']], 
                $data
            );
        }
    }
}
