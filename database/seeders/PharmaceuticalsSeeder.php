<?php

namespace Database\Seeders;

use App\Models\Pharmaceuticals;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PharmaceuticalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pharmaceuticals::insert([
            [
                'drug_no' => 'S001',
                'dosage' => '500mg',
                'method_admin' => 'Oral',
            ],
            [
                'drug_no' => 'S002',
                'dosage' => '500mg',
                'method_admin' => 'Oral',
            ],
            [
                'drug_no' => 'S003',
                'dosage' => '0.9% saline',
                'method_admin' => 'IV',
            ],
        ]);
    }
}
