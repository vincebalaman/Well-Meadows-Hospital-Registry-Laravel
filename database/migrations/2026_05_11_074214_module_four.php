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
        CREATE OR REPLACE PROCEDURE schedule_appointment(
            p_app_no VARCHAR(15),
            p_patient_no VARCHAR(10),
            p_consultant_no VARCHAR(10),
            p_date_time TIMESTAMP,
            p_room VARCHAR(10)
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            INSERT INTO Appointments (app_no, patient_no, consultant_staff_no, app_date_time, exam_room)
            VALUES (p_app_no, p_patient_no, p_consultant_no, p_date_time, p_room);
        END;
        $$;

        CREATE OR REPLACE PROCEDURE record_examination_outcome(
            p_app_no VARCHAR(15),
            p_diagnosis TEXT,
            p_treatment TEXT,
            p_outcome VARCHAR(50)
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            INSERT INTO Clinical_Records (app_no, diagnosis, treatment_plan, outcome)
            VALUES (p_app_no, p_diagnosis, p_treatment, p_outcome);
        END;
        $$;

        CREATE OR REPLACE VIEW view_patient_medical_history AS
        SELECT 
            p.patient_no,
            p.first_name || ' ' || p.last_name AS patient_name,
            a.app_date_time AS visit_date,
            cr.diagnosis,
            cr.treatment_plan,
            pm.drug_no,
            s.name AS drug_name,
            pm.units_per_day
        FROM Patients p
        LEFT JOIN Appointments a ON p.patient_no = a.patient_no
        LEFT JOIN Clinical_Records cr ON a.app_no = cr.app_no
        LEFT JOIN In_Patient_Stays ips ON p.patient_no = ips.patient_no
        LEFT JOIN Patient_Medication pm ON ips.stay_id = pm.stay_id
        LEFT JOIN Supplies s ON pm.drug_no = s.item_no;

        CREATE OR REPLACE PROCEDURE assign_care_staff(
            p_staff_no VARCHAR(10),
            p_stay_id INT,
            p_role VARCHAR(100)
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            INSERT INTO Staff_Patient_Assignment (staff_no, stay_id, role_description)
            VALUES (p_staff_no, p_stay_id, p_role);
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
        DROP PROCEDURE IF EXISTS schedule_appointment;
        DROP PROCEDURE IF EXISTS record_examination_outcome;
        DROP VIEW IF EXISTS view_patient_medical_history CASCADE;
        DROP PROCEDURE IF EXISTS assign_care_staff;
        ");
    }
};
