<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('wards')) {
            DB::table('wards')->insertOrIgnore([
                ['ward_name' => 'General Ward', 'charge_nurse_id' => null],
                ['ward_name' => 'Emergency', 'charge_nurse_id' => null],
                ['ward_name' => 'Maternity', 'charge_nurse_id' => null],
                ['ward_name' => 'Pediatrics', 'charge_nurse_id' => null],
                ['ward_name' => 'Surgical', 'charge_nurse_id' => null],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wards')) {
            DB::table('wards')->whereIn('ward_name', [
                'General Ward',
                'Emergency',
                'Maternity',
                'Pediatrics',
                'Surgical',
            ])->delete();
        }
    }
};
