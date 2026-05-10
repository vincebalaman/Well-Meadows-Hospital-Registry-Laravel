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
        if (!Schema::hasTable('job_positions')) {
            Schema::create('job_positions', function (Blueprint $table) {
                $table->id('position_id');
                $table->string('position_name', 100)->unique();
            });

            DB::table('job_positions')->insertOrIgnore([
                ['position_name' => 'Charge Nurse'],
                ['position_name' => 'Staff Nurse'],
                ['position_name' => 'Senior Nurse'],
                ['position_name' => 'Ward Clerk'],
            ]);
        }

        if (!Schema::hasTable('wards')) {
            Schema::create('wards', function (Blueprint $table) {
                $table->id('ward_id');
                $table->string('ward_name', 100)->unique();
                $table->string('charge_nurse_id', 10)->nullable();
                $table->foreign('charge_nurse_id')->references('staff_no')->on('staff')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('staff_contracts')) {
            Schema::create('staff_contracts', function (Blueprint $table) {
                $table->string('staff_no', 10)->primary();
                $table->unsignedBigInteger('position_id');
                $table->char('contract_type', 1);
                $table->decimal('hours_per_week', 5, 2);
                $table->foreign('staff_no')->references('staff_no')->on('staff')->onDelete('cascade');
                $table->foreign('position_id')->references('position_id')->on('job_positions');
            });
        }

        if (!Schema::hasTable('staff_allocations')) {
            Schema::create('staff_allocations', function (Blueprint $table) {
                $table->id();
                $table->string('staff_no', 10);
                $table->unsignedBigInteger('ward_id');
                $table->date('week_beginning');
                $table->string('shift_type', 10);
                $table->unique(['staff_no', 'week_beginning']);
                $table->foreign('staff_no')->references('staff_no')->on('staff')->onDelete('cascade');
                $table->foreign('ward_id')->references('ward_id')->on('wards')->onDelete('cascade');
            });
        }

        DB::unprepared(<<<'SQL'
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

                INSERT INTO staff_allocations (
                    staff_no, ward_id, week_beginning, shift_type
                )
                VALUES (
                    p_staff_no, p_ward_id, p_week_beginning, p_shift
                )
                ON CONFLICT (staff_no, week_beginning) DO NOTHING;
            END;
            $$;
        SQL
        );

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE PROCEDURE update_staff_contract(
                p_staff_no VARCHAR(10),
                p_position_id INT,
                p_type CHAR(1),
                p_hours DECIMAL
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                IF p_hours > 40.00 THEN
                    RAISE EXCEPTION 'Contracted hours cannot exceed 40 per week.';
                END IF;

                INSERT INTO staff_contracts (staff_no, position_id, contract_type, hours_per_week)
                VALUES (p_staff_no, p_position_id, p_type, p_hours)
                ON CONFLICT (staff_no) DO UPDATE SET 
                    position_id = EXCLUDED.position_id,
                    contract_type = EXCLUDED.contract_type,
                    hours_per_week = EXCLUDED.hours_per_week;
            END;
            $$;
        SQL
        );

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION validate_charge_nurse_role()
            RETURNS TRIGGER AS $$
            DECLARE
                v_position TEXT;
            BEGIN
                IF NEW.charge_nurse_id IS NULL THEN
                    RETURN NEW;
                END IF;

                SELECT jp.position_name
                INTO v_position
                FROM staff_contracts sc
                JOIN job_positions jp ON sc.position_id = jp.position_id
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
                    BEFORE INSERT OR UPDATE ON wards
                    FOR EACH ROW
                    EXECUTE FUNCTION validate_charge_nurse_role();
                END IF;
            END;
            $$;
        SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_validate_charge_nurse ON wards;');
        DB::unprepared('DROP FUNCTION IF EXISTS validate_charge_nurse_role();');
        DB::unprepared('DROP PROCEDURE IF EXISTS assign_staff_to_ward(VARCHAR, INT, DATE, VARCHAR);');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_staff_contract(VARCHAR, INT, CHAR, DECIMAL);');

        Schema::dropIfExists('staff_allocations');
        Schema::dropIfExists('staff_contracts');
        Schema::dropIfExists('wards');
        Schema::dropIfExists('job_positions');
    }
};
