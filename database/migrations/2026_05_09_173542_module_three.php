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
            CREATE OR REPLACE PROCEDURE manage_ward_details(
                p_ward_id INT,
                p_name VARCHAR(100),
                p_location VARCHAR(50),
                p_tel_extn VARCHAR(10),
                p_charge_nurse VARCHAR(10)
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                INSERT INTO Wards (ward_id, ward_name, location, tel_extn, charge_nurse_id)
                VALUES (p_ward_id, p_name, p_location, p_tel_extn, p_charge_nurse)
                ON CONFLICT (ward_id) DO UPDATE SET
                    ward_name = EXCLUDED.ward_name,
                    location = EXCLUDED.location,
                    tel_extn = EXCLUDED.tel_extn,
                    charge_nurse_id = EXCLUDED.charge_nurse_id;
            END;
            $$;

            CREATE OR REPLACE PROCEDURE populate_ward_beds(
                p_ward_id INT, 
                p_num_to_add INT
            )
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_start_number INT;
                v_i INT;
            BEGIN
                -- 1. Determine the starting bed number for this ward
                -- If beds already exist, start after the highest current number. Otherwise, start at 1.
                SELECT COALESCE(MAX(bed_number), 0) + 1 INTO v_start_number
                FROM Beds
                WHERE ward_id = p_ward_id;

                -- 2. Loop to insert the specified number of beds
                FOR v_i IN 0..(p_num_to_add - 1) LOOP
                    INSERT INTO Beds (ward_id, bed_number, status)
                    VALUES (p_ward_id, v_start_number + v_i, 'Available')
                    ON CONFLICT (ward_id, bed_number) DO NOTHING;
                END LOOP;

                -- Note: Your 'trg_sync_bed_count' trigger will automatically update 
                -- Wards.total_beds for every successful insert.
                
                RAISE NOTICE 'Added % beds to Ward %.', p_num_to_add, p_ward_id;
            END;
            $$;

            CREATE OR REPLACE FUNCTION get_available_bed_count(p_ward_id INT)
            RETURNS INT AS $$
            DECLARE
                v_available_beds INT;
            BEGIN
                SELECT COUNT(*) INTO v_available_beds 
                FROM Beds 
                WHERE ward_id = p_ward_id AND status = 'Available';
                
                RETURN v_available_beds;
            END;
            $$ LANGUAGE plpgsql;

            CREATE OR REPLACE FUNCTION update_ward_total_beds()
            RETURNS TRIGGER AS $$
            BEGIN
                IF (TG_OP = 'INSERT') THEN
                    UPDATE Wards 
                    SET total_beds = COALESCE(total_beds, 0) + 1 
                    WHERE ward_id = NEW.ward_id;
                ELSIF (TG_OP = 'DELETE') THEN
                    UPDATE Wards 
                    SET total_beds = GREATEST(0, COALESCE(total_beds, 0) - 1) 
                    WHERE ward_id = OLD.ward_id;
                END IF;
                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trg_sync_bed_count
            AFTER INSERT OR DELETE ON Beds
            FOR EACH ROW EXECUTE FUNCTION update_ward_total_beds();

            CREATE OR REPLACE VIEW view_ward_occupancy AS
            SELECT 
                w.ward_name,
                b.bed_number,
                b.status, -- Now pulls directly from the Beds table
                p.first_name || ' ' || p.last_name AS patient_name,
                ips.date_placed_ward AS admission_date
            FROM Wards w
            JOIN Beds b ON w.ward_id = b.ward_id
            LEFT JOIN In_Patient_Stays ips ON b.bed_id = ips.bed_no 
                AND ips.actual_leave IS NULL
            LEFT JOIN Patients p ON ips.patient_no = p.patient_no;

            CREATE OR REPLACE PROCEDURE admit_patient_to_bed(
                p_stay_id INT,
                p_bed_id INT
            )
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_stay_ward_id INT;
                v_bed_ward_id INT;
                v_bed_status VARCHAR(20);
            BEGIN
                -- 1. Get the ward_id for the patient stay
                SELECT ward_id INTO v_stay_ward_id 
                FROM In_Patient_Stays 
                WHERE stay_id = p_stay_id;

                -- 2. Get the ward_id and current status for the bed
                SELECT ward_id, status INTO v_bed_ward_id, v_bed_status 
                FROM Beds 
                WHERE bed_id = p_bed_id;

                -- 3. Validation: Check if the bed belongs to the patient's assigned ward
                IF v_stay_ward_id <> v_bed_ward_id THEN
                    RAISE EXCEPTION 'Validation Error: Bed % does not belong to Ward %.', p_bed_id, v_stay_ward_id;
                END IF;

                -- 4. Validation: Check if the bed is actually available
                IF v_bed_status <> 'Available' THEN
                    RAISE EXCEPTION 'Validation Error: Bed % is already occupied.', p_bed_id;
                END IF;

                -- 5. Mark the bed as occupied
                UPDATE Beds SET status = 'Occupied' WHERE bed_id = p_bed_id;

                -- 6. Update the stay record
                UPDATE In_Patient_Stays
                SET date_placed_ward = CURRENT_DATE,
                    bed_no = p_bed_id,
                    status = 'Admitted', -- Update status from 'Waiting'
                    expected_leave = CURRENT_DATE + expected_duration
                WHERE stay_id = p_stay_id;

                RAISE NOTICE 'Patient stay % successfully assigned to bed %.', p_stay_id, p_bed_id;
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
            DROP PROCEDURE IF EXISTS manage_ward_details;
            DROP PROCEDURE IF EXISTS populate_ward_beds;
            DROP FUNCTION IF EXISTS get_available_bed_count;
            DROP FUNCTION IF EXISTS update_ward_total_beds CASCADE;
            DROP VIEW IF EXISTS view_ward_occupancy CASCADE;
            DROP PROCEDURE IF EXISTS admit_patient_to_bed;
        ");
    }
};
