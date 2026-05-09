<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            JobPositionSeeder::class,
            LocalDoctorSeeder::class,
            // Add other seeders here (e.g., StaffSeeder, WardSeeder)
        ]);
    }
}
