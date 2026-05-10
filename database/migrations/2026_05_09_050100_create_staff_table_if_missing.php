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
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->string('staff_no', 10)->primary();
                $table->string('first_name', 50);
                $table->string('last_name', 50);
                $table->text('address')->nullable();
                $table->string('tel_no', 20)->nullable();
                $table->date('dob');
                $table->char('sex', 1);
                $table->string('nin', 15)->unique();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
