<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE Job_Positions (
                position_id SERIAL PRIMARY KEY,
                position_name VARCHAR(50) UNIQUE NOT NULL, 
                salary_scale VARCHAR(10),                 
                pay_type CHAR(1) CHECK (pay_type IN ('W', 'M')), 
                current_salary DECIMAL(10,2)              
            );

            CREATE TABLE Staff (
                staff_no VARCHAR(10) PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                address TEXT,
                tel_no VARCHAR(20),
                dob DATE,
                sex CHAR(1) CHECK (sex IN ('M', 'F')),
                nin VARCHAR(15) UNIQUE NOT NULL         
            );

            CREATE TABLE Wards (
                ward_id INT PRIMARY KEY,
                ward_name VARCHAR(100) NOT NULL,
                location VARCHAR(50),
                total_beds INT,
                tel_extn VARCHAR(10),
                budget INT,
                charge_nurse_id VARCHAR(10) REFERENCES Staff(staff_no)
            );


            CREATE TABLE Beds (
                bed_id SERIAL PRIMARY KEY,
                ward_id INT REFERENCES Wards(ward_id),
                bed_number INT NOT NULL, 
                UNIQUE(ward_id, bed_number)
            );

            CREATE TABLE Staff_Qualifications (
                qual_id SERIAL PRIMARY KEY,
                staff_no VARCHAR(10) REFERENCES Staff(staff_no),
                type VARCHAR(100),
                qual_date DATE,
                institution VARCHAR(100)
            );

            CREATE TABLE Staff_Contracts (
                contract_id SERIAL PRIMARY KEY,
                staff_no VARCHAR(10) UNIQUE REFERENCES Staff(staff_no),
                position_id INT REFERENCES Job_Positions(position_id),
                contract_type CHAR(1) CHECK (contract_type IN ('P', 'T')),
                hours_per_week DECIMAL(4,2) CHECK (hours_per_week <= 40.00) 
            );

            CREATE TABLE Staff_Allocation (
                allocation_id SERIAL PRIMARY KEY,
                staff_no VARCHAR(10) NOT NULL REFERENCES Staff(staff_no),
                ward_id INT NOT NULL REFERENCES Wards(ward_id),           
                week_beginning DATE NOT NULL,                            
                shift_type VARCHAR(10) NOT NULL CHECK (shift_type IN ('Early', 'Late', 'Night')), 
                CONSTRAINT unique_staff_shift_week UNIQUE (staff_no, week_beginning) 
            );

            CREATE TABLE Staff_Experience (
                exp_id SERIAL PRIMARY KEY,
                staff_no VARCHAR(10) REFERENCES Staff(staff_no),
                org_name VARCHAR(100),
                position_held VARCHAR(50),
                start_date DATE,
                finish_date DATE
            );

            CREATE TABLE Local_Doctors (
                clinic_no VARCHAR(20) PRIMARY KEY,
                full_name VARCHAR(100),
                address TEXT,
                tel_no VARCHAR(20)
            );

            CREATE TABLE Patients (
                patient_no VARCHAR(10) PRIMARY KEY,
                first_name VARCHAR(50),
                last_name VARCHAR(50),
                address TEXT,
                tel_no VARCHAR(20),
                dob DATE,
                sex CHAR(1),
                marital_status VARCHAR(20),
                date_registered DATE,
                clinic_no VARCHAR(20) REFERENCES Local_Doctors(clinic_no)
            );

            CREATE TABLE Next_of_Kin (
                nok_id SERIAL PRIMARY KEY,
                patient_no VARCHAR(10) REFERENCES Patients(patient_no),
                full_name VARCHAR(100),
                relationship VARCHAR(50),
                address TEXT,
                tel_no VARCHAR(20)
            );

            CREATE TABLE Appointments (
                app_no VARCHAR(15) PRIMARY KEY,
                patient_no VARCHAR(10) REFERENCES Patients(patient_no),
                consultant_staff_no VARCHAR(10) REFERENCES Staff(staff_no),
                app_date_time TIMESTAMP,
                exam_room VARCHAR(10)
            );

            CREATE TABLE In_Patient_Stays (
                stay_id SERIAL PRIMARY KEY,
                patient_no VARCHAR(10) REFERENCES Patients(patient_no),
                ward_id INT REFERENCES Wards(ward_id),
                bed_no INT REFERENCES Beds(bed_id),
                date_placed_waiting DATE,
                expected_duration INT,
                date_placed_ward DATE,
                expected_leave DATE,
                actual_leave DATE,
                status VARCHAR(15)
            );

            CREATE TABLE Suppliers (
                supplier_no VARCHAR(10) PRIMARY KEY,
                name VARCHAR(100),
                address TEXT,
                tel_no VARCHAR(20),
                fax_no VARCHAR(20)
            );

            CREATE TABLE Supplies (
                item_no VARCHAR(10) PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                qty_in_stock INT DEFAULT 0,
                reorder_level INT,
                cost_per_unit DECIMAL(10,2),
                supplier_no VARCHAR(10) REFERENCES Suppliers(supplier_no)
            );

            CREATE TABLE Pharmaceuticals (
                drug_no VARCHAR(10) PRIMARY KEY REFERENCES Supplies(item_no), 
                dosage VARCHAR(50) NOT NULL,
                method_admin VARCHAR(50) NOT NULL
            );

            CREATE TABLE Patient_Medication (
                med_id SERIAL PRIMARY KEY,
                stay_id INT REFERENCES In_Patient_Stays(stay_id),
                drug_no VARCHAR(10) REFERENCES Pharmaceuticals(drug_no),
                units_per_day INT,
                start_date DATE,
                finish_date DATE
            );

            CREATE TABLE Ward_Requisitions (
                req_no VARCHAR(20) PRIMARY KEY,
                staff_no VARCHAR(10) NOT NULL REFERENCES Staff(staff_no),
                ward_id INT NOT NULL REFERENCES Wards(ward_id),
                date_ordered DATE NOT NULL,
                date_received DATE,
                receiving_staff_no VARCHAR(10) REFERENCES Staff(staff_no) 
            );

            CREATE TABLE Requisition_Items (
                req_item_id SERIAL PRIMARY KEY,
                req_no VARCHAR(20) REFERENCES Ward_Requisitions(req_no) ON DELETE CASCADE,
                item_no VARCHAR(10) REFERENCES Supplies(item_no),
                quantity_required INT CHECK (quantity_required > 0)
            );

            CREATE TABLE Clinical_Records (
                record_id SERIAL PRIMARY KEY,
                app_no VARCHAR(15) REFERENCES Appointments(app_no),
                diagnosis TEXT,
                treatment_plan TEXT,
                outcome VARCHAR(50) CHECK (outcome IN ('Out-patient', 'Wait-list', 'Discharged'))
            );

            CREATE TABLE Staff_Patient_Assignment (
                assignment_id SERIAL PRIMARY KEY,
                staff_no VARCHAR(10) REFERENCES Staff(staff_no),
                stay_id INT REFERENCES In_Patient_Stays(stay_id),
                role_description VARCHAR(100) -- e.g., 'Primary Nurse', 'Physical Therapist'
            );

            CREATE TABLE Patient_Billing (
                bill_id SERIAL PRIMARY KEY,
                stay_id INT REFERENCES In_Patient_Stays(stay_id),
                total_amount DECIMAL(10,2),
                amount_paid DECIMAL(10,2) DEFAULT 0.00,
                payment_status VARCHAR(20) DEFAULT 'Pending' -- 'Pending', 'Partial', 'Cleared'
            );
        ");

    }

    public function down(): void
    {
        DB::unprepared("
            -- Drop child/junction tables first
            DROP TABLE IF EXISTS Patient_Billing;
            DROP TABLE IF EXISTS Staff_Patient_Assignment;
            DROP TABLE IF EXISTS Clinical_Records;
            DROP TABLE IF EXISTS Requisition_Items;
            DROP TABLE IF EXISTS Ward_Requisitions;
            DROP TABLE IF EXISTS Patient_Medication;
            DROP TABLE IF EXISTS Pharmaceuticals;
            DROP TABLE IF EXISTS Supplies;
            DROP TABLE IF EXISTS Suppliers;
            DROP TABLE IF EXISTS In_Patient_Stays;
            DROP TABLE IF EXISTS Appointments;
            DROP TABLE IF EXISTS Next_of_Kin;
            DROP TABLE IF EXISTS Patients;
            DROP TABLE IF EXISTS Local_Doctors;
            DROP TABLE IF EXISTS Staff_Experience;
            DROP TABLE IF EXISTS Staff_Allocation;
            DROP TABLE IF EXISTS Staff_Contracts;
            DROP TABLE IF EXISTS Staff_Qualifications;
            DROP TABLE IF EXISTS Beds;
            DROP TABLE IF EXISTS Wards;
            
            -- Drop base/parent tables last
            DROP TABLE IF EXISTS Staff;
            DROP TABLE IF EXISTS Job_Positions;
        ");
    }
};