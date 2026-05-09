<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPosition;

class JobPositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'position_name' => 'Charge Nurse',
                'salary_scale'  => 'Scale 1',
                'pay_type'      => 'M',
                'current_salary' => 4500.00
            ],
            [
                'position_name' => 'Staff Nurse',
                'salary_scale'  => 'Scale 2',
                'pay_type'      => 'M',
                'current_salary' => 3500.00
            ],
            [
                'position_name' => 'Medical Director',
                'salary_scale'  => 'Scale 5',
                'pay_type'      => 'M',
                'current_salary' => 8000.00
            ],
            [
                'position_name' => 'Consultant',
                'salary_scale'  => 'Scale 4',
                'pay_type'      => 'M',
                'current_salary' => 7000.00
            ],
        ];

        foreach ($positions as $position) {
            JobPosition::updateOrCreate(
                ['position_name' => $position['position_name']], // Unique check
                $position
            );
        }
    }
}