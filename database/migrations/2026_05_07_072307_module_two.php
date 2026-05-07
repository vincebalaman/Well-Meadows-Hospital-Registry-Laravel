<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE add_staff_member(
                s_no VARCHAR(10), s_fname VARCHAR(50), s_lname VARCHAR(50), 
                s_addr TEXT, s_tel VARCHAR(20), s_dob DATE, s_sex CHAR(1), s_nin VARCHAR(15)
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                INSERT INTO Staff (staff_no, first_name, last_name, address, tel_no, dob, sex, nin)
                VALUES (s_no, s_fname, s_lname, s_addr, s_tel, s_dob, s_sex, s_nin);
            END;
            $$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS add_staff_member;");
    }
};
