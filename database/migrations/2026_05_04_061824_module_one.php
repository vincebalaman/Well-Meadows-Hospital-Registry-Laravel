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
            CREATE OR REPLACE PROCEDURE register_patient(
            p_no VARCHAR(10), p_fname VARCHAR(50), p_lname VARCHAR(50), 
            p_addr TEXT, p_tel VARCHAR(20), p_dob DATE, p_sex CHAR(1), 
            p_marital VARCHAR(20), p_clinic VARCHAR(20),
            nok_name VARCHAR(100), nok_rel VARCHAR(50), nok_tel VARCHAR(20)
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                INSERT INTO Patients (patient_no, first_name, last_name, address, tel_no, dob, sex, marital_status, date_registered, clinic_no)
                VALUES (p_no, p_fname, p_lname, p_addr, p_tel, p_dob, p_sex, p_marital, CURRENT_DATE, p_clinic);

                INSERT INTO Next_of_Kin (patient_no, full_name, relationship, tel_no)
                VALUES (p_no, nok_name, nok_rel, nok_tel);
            END;
            $$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS register_patient;");
    }
};
