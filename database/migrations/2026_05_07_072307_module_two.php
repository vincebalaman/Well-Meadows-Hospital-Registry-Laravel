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

            CREATE OR REPLACE PROCEDURE assign_staff_to_ward(
                p_staff_no VARCHAR(10),
                p_ward_id INT,
                p_week_beginning DATE,
                p_shift VARCHAR(10)
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                IF p_shift NOT IN ('Early', 'Late', 'Night') THEN
                    RAISE EXCEPTION 'Invalid shift. Must be Early, Late, or Night.';
                END IF;

                INSERT INTO Staff_Allocation (
                    staff_no, ward_id, week_beginning, shift_type
                )
                VALUES (
                    p_staff_no, p_ward_id, p_week_beginning, p_shift
                )
                ON CONFLICT (staff_no, week_beginning) DO NOTHING;
            END;
            $$;

            CREATE OR REPLACE PROCEDURE assign_staff_to_ward(
                p_staff_no VARCHAR(10),
                p_ward_id INT,
                p_week_beginning DATE,
                p_shift VARCHAR(10)
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                IF p_shift NOT IN ('Early', 'Late', 'Night') THEN
                    RAISE EXCEPTION 'Invalid shift. Must be Early, Late, or Night.';
                END IF;

                INSERT INTO Staff_Allocation (
                    staff_no, ward_id, week_beginning, shift_type
                )
                VALUES (
                    p_staff_no, p_ward_id, p_week_beginning, p_shift
                )
                ON CONFLICT (staff_no, week_beginning) DO NOTHING;
            END;
            $$;

            CREATE OR REPLACE PROCEDURE update_staff_contract(
                p_staff_no VARCHAR(10), p_position_id INT, p_type CHAR(1), p_hours DECIMAL
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                -- Ensure hours do not exceed the 40-hour limit
                IF p_hours > 40.00 THEN
                    RAISE EXCEPTION 'Contracted hours cannot exceed 40 per week.';
                END IF;

                INSERT INTO Staff_Contracts (staff_no, position_id, contract_type, hours_per_week)
                VALUES (p_staff_no, p_position_id, p_type, p_hours)
                ON CONFLICT (staff_no) DO UPDATE SET 
                    position_id = EXCLUDED.position_id,
                    contract_type = EXCLUDED.contract_type,
                    hours_per_week = EXCLUDED.hours_per_week;
            END;
            $$;

            CREATE OR REPLACE FUNCTION validate_charge_nurse_role()
            RETURNS TRIGGER AS $$
            DECLARE
                v_position TEXT;
            BEGIN
                SELECT jp.position_name
                INTO v_position
                FROM Staff_Contracts sc
                JOIN Job_Positions jp ON sc.position_id = jp.position_id
                WHERE sc.staff_no = NEW.charge_nurse_id
                LIMIT 1;

                IF v_position IS NULL THEN
                    RAISE EXCEPTION 'Staff % has no contract assigned.', NEW.charge_nurse_id;
                END IF;

                IF v_position <> 'Charge Nurse' THEN
                    RAISE EXCEPTION 'Only Charge Nurse can be assigned to a ward.';
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_trigger
                    WHERE tgname = 'trigger_validate_charge_nurse'
                ) THEN
                    CREATE TRIGGER trigger_validate_charge_nurse
                    BEFORE INSERT OR UPDATE ON Wards
                    FOR EACH ROW
                    EXECUTE FUNCTION validate_charge_nurse_role();
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
            DROP PROCEDURE IF EXISTS add_staff_member;
            DROP PROCEDURE IF EXISTS assign_staff_to_ward;
            DROP PROCEDURE IF EXISTS update_staff_contract;
            DROP FUNCTION IF EXISTS validate_charge_nurse_role CASCADE;
        ");
    }
};
