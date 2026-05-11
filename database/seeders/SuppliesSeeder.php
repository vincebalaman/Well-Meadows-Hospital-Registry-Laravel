<?php

namespace Database\Seeders;

use App\Models\Supplies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplies::insert([
            [
                'item_no' => 'S001',
                'name' => 'Paracetamol Tablets',
                'description' => '500mg tablets for pain relief and fever reduction.',
                'qty_in_stock' => 500,
                'reorder_level' => 100,
                'cost_per_unit' => 0.25,
            ],
            [
                'item_no' => 'S002',
                'name' => 'Amoxicillin Capsules',
                'description' => '500mg antibiotic capsules.',
                'qty_in_stock' => 200,
                'reorder_level' => 50,
                'cost_per_unit' => 0.75,
            ],
            [
                'item_no' => 'S003',
                'name' => 'Saline IV Fluid',
                'description' => '0.9% sodium chloride intravenous solution.',
                'qty_in_stock' => 150,
                'reorder_level' => 30,
                'cost_per_unit' => 2.50,
            ],
        ]);
    }
}
