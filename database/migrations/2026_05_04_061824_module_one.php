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

            CREATE OR REPLACE PROCEDURE add_patient_medication(
                p_stay_id INT, p_drug_no VARCHAR(10), p_units INT, p_start DATE, p_finish DATE
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                -- Validation: Check if it's a pharmaceutical item
                IF EXISTS (SELECT 1 FROM Pharmaceuticals WHERE drug_no = p_drug_no) THEN
                    INSERT INTO Patient_Medication (stay_id, drug_no, units_per_day, start_date, finish_date)
                    VALUES (p_stay_id, p_drug_no, p_units, p_start, p_finish);
                ELSE
                    RAISE EXCEPTION 'Item % is not a valid pharmaceutical drug.', p_drug_no;
                END IF;
            END;
            $$;

            CREATE OR REPLACE PROCEDURE request_ward_admission(
                p_patient_no VARCHAR(10),
                p_ward_id INT,
                p_expected_duration INT
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                -- Check if the patient is already in an active stay to prevent double admission
                IF EXISTS (SELECT 1 FROM In_Patient_Stays WHERE patient_no = p_patient_no AND actual_leave IS NULL) THEN
                    RAISE EXCEPTION 'Patient % is already admitted or on a waiting list.', p_patient_no;
                END IF;

                -- Insert into stays with a NULL bed_no and 'Waiting' status
                INSERT INTO In_Patient_Stays (
                    patient_no, 
                    ward_id, 
                    bed_no, 
                    date_placed_waiting, 
                    expected_duration, 
                    status
                )
                VALUES (
                    p_patient_no, 
                    p_ward_id, 
                    NULL,
                    CURRENT_DATE, 
                    p_expected_duration, 
                    'admitted'
                );
            END;
            $$;

            CREATE OR REPLACE PROCEDURE discharge_patient(p_stay_id INT)
                LANGUAGE plpgsql
                AS $$
                DECLARE
                    v_bed_id INT;
                BEGIN
                    -- 1. Find the bed associated with this stay
                    SELECT bed_no INTO v_bed_id FROM In_Patient_Stays WHERE stay_id = p_stay_id;

                    -- 2. Update the stay record
                    UPDATE In_Patient_Stays 
                    SET actual_leave = CURRENT_DATE, status = 'discharged'
                    WHERE stay_id = p_stay_id;
                    
                    -- 3. Free up the bed
                    IF v_bed_id IS NOT NULL THEN
                        UPDATE Beds SET status = 'Available' WHERE bed_id = v_bed_id;
                    END IF;
                END;
                $$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS register_patient;
            DROP PROCEDURE IF EXISTS add_patient_medication;
            DROP PROCEDURE IF EXISTS request_ward_admission;
            DROP PROCEDURE IF EXISTS discharge_patient;
        ");
    }
};
