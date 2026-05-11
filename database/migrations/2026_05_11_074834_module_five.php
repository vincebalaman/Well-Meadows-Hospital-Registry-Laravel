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
            CREATE OR REPLACE FUNCTION calculate_total_bill(p_stay_id INT)
            RETURNS DECIMAL(10,2) AS $$
            DECLARE
                v_medication_cost DECIMAL(10,2);
                v_daily_rate DECIMAL(10,2) := 50.00; -- Example fixed daily bed rate
                v_stay_duration INT;
                v_total_bill DECIMAL(10,2);
            BEGIN
                SELECT SUM(pm.units_per_day * s.cost_per_unit) INTO v_medication_cost
                FROM Patient_Medication pm
                JOIN Supplies s ON pm.drug_no = s.item_no
                WHERE pm.stay_id = p_stay_id;

                SELECT (COALESCE(actual_leave, CURRENT_DATE) - date_placed_ward) INTO v_stay_duration
                FROM In_Patient_Stays
                WHERE stay_id = p_stay_id;

                v_total_bill := COALESCE(v_medication_cost, 0) + (v_stay_duration * v_daily_rate);
                
                RETURN v_total_bill;
            END;
            $$ LANGUAGE plpgsql;

            CREATE OR REPLACE PROCEDURE generate_bill(p_stay_id INT)
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_calculated_total DECIMAL(10,2);
            BEGIN
                -- 1. Use your existing function to get the amount
                v_calculated_total := calculate_total_bill(p_stay_id);

                -- 2. Check if a bill already exists to avoid duplicates
                -- If it exists, we update it; if not, we insert it.
                IF EXISTS (SELECT 1 FROM Patient_Billing WHERE stay_id = p_stay_id) THEN
                    UPDATE Patient_Billing 
                    SET total_amount = v_calculated_total 
                    WHERE stay_id = p_stay_id;
                    RAISE NOTICE 'Existing bill for stay % has been updated.', p_stay_id;
                ELSE
                    INSERT INTO Patient_Billing (stay_id, total_amount, amount_paid, payment_status)
                    VALUES (p_stay_id, v_calculated_total, 0.00, 'Pending');
                    RAISE NOTICE 'New bill for stay % has been generated and saved.', p_stay_id;
                END IF;
            END;
            $$;

            CREATE OR REPLACE PROCEDURE record_payment(p_bill_id INT, p_amount DECIMAL)
            LANGUAGE plpgsql
            AS $$
            BEGIN
                UPDATE Patient_Billing
                SET amount_paid = amount_paid + p_amount,
                    payment_status = CASE 
                        WHEN (amount_paid + p_amount) >= total_amount THEN 'Cleared'
                        ELSE 'Partial'
                    END
                WHERE bill_id = p_bill_id;
            END;
            $$;

            CREATE OR REPLACE VIEW view_patient_billing_status AS
            SELECT 
                bill_id,
                stay_id,
                total_amount AS original_invoice,
                amount_paid,
                -- Dynamic calculation of the remaining debt
                (total_amount - amount_paid) AS outstanding_balance,
                payment_status
            FROM Patient_Billing;

            CREATE OR REPLACE VIEW report_ward_occupancy_stats AS
            SELECT 
                w.ward_id,
                w.ward_name,
                -- Count total beds assigned to this ward in the Beds table
                COUNT(b.bed_id) AS total_beds,
                -- Count only beds where the status is 'Occupied'
                COUNT(b.bed_id) FILTER (WHERE b.status = 'Occupied') AS occupied_beds,
                -- Calculate occupancy rate with division-by-zero protection
                COALESCE(
                    ROUND(
                        (COUNT(b.bed_id) FILTER (WHERE b.status = 'Occupied')::DECIMAL 
                        / NULLIF(COUNT(b.bed_id), 0)) * 100, 2
                    ), 0
                ) AS occupancy_rate
            FROM Wards w
            LEFT JOIN Beds b ON w.ward_id = b.ward_id
            GROUP BY w.ward_id, w.ward_name;

            CREATE OR REPLACE VIEW report_management_summary_complete AS
            SELECT 
                -- Patient Metrics
                (SELECT COUNT(*) FROM Patients) AS total_registered_patients,
                (SELECT COUNT(*) FROM In_Patient_Stays WHERE actual_leave IS NULL) AS current_inpatients, 
                (SELECT COUNT(*) FROM In_Patient_Stays WHERE date_placed_ward IS NULL) AS waiting_list_count, 

                -- Financial Metrics
                (SELECT SUM(total_amount) FROM Patient_Billing) AS total_gross_revenue,
                (SELECT SUM(qty_in_stock * cost_per_unit) FROM Supplies) AS total_inventory_value,

                -- Staffing Metrics
                (SELECT COUNT(*) FROM Staff) AS total_staff_count,
                (SELECT COUNT(*) FROM Staff_Allocation 
                WHERE week_beginning = (SELECT MAX(week_beginning) FROM Staff_Allocation)) AS staff_on_current_rota,

                -- Operational Alerts
                (SELECT COUNT(*) FROM Supplies WHERE qty_in_stock <= reorder_level) AS items_below_reorder_level
            ;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        db::unprepared("
            DROP FUNCTION IF EXISTS calculate_total_bill CASCADE;
             DROP PROCEDURE IF EXISTS generate_bill;
            DROP PROCEDURE IF EXISTS record_payment;
            DROP VIEW IF EXISTS view_patient_billing_status CASCADE;
            DROP VIEW IF EXISTS report_ward_occupancy_stats CASCADE;
            DROP VIEW IF EXISTS report_management_summary_complete CASCADE;
        ");
    }
};
