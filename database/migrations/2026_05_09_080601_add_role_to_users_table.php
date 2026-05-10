<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('patient')->after('email');
            }
            if (!Schema::hasColumn('users', 'staff_no')) {
                $table->string('staff_no')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'patient_no')) {
                $table->string('patient_no')->nullable()->after('staff_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_filter(['role', 'staff_no', 'patient_no'], fn($c) => Schema::hasColumn('users', $c));
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
