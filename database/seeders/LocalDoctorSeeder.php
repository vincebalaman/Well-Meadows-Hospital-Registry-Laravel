<?php

namespace Database\Seeders;

use App\Models\LocalDoctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalDoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LocalDoctor::create([
            'clinic_no' => 'C_001',
            'full_name' => 'John Smith',
            'address' => '123 Medical Way, London',
            'tel_no' => '020-1234-5678'
        ]);

        LocalDoctor::create([
            'clinic_no' => 'C_002',
            'full_name' => 'Therese Willson',
            'address' => '456 River Rd, Manchester',
            'tel_no' => '0161-987-6543'
        ]);
    }
}
