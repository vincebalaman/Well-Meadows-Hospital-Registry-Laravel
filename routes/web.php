<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClinicalRecordController;
use App\Http\Controllers\StaffPatientAssignmentController;
use App\Http\Controllers\PatientHistoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// All authenticated users (admin, staff, patient)
Route::middleware(['auth', 'role:admin,staff,patient'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patients & appointments: everyone can view + create
    Route::resource('patients', PatientController::class)
        ->only(['index', 'create', 'store', 'show']);
    Route::resource('appointments', AppointmentController::class)
        ->only(['index', 'create', 'store', 'show']);
});

// Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    //
});

// Admin + Staff only
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    // Staff can edit/delete on patients & appointments
    Route::resource('patients', PatientController::class)
        ->only(['edit', 'update', 'destroy']);
    Route::resource('appointments', AppointmentController::class)
        ->only(['edit', 'update', 'destroy']);

    // Staff records (from teammate's branch)
    Route::resource('staffs', StaffController::class);

    // Fully staff-only modules
    Route::resource('clinical-records', ClinicalRecordController::class)
        ->parameters(['clinical-records' => 'clinicalRecord']);
    Route::resource('staff-assignments', StaffPatientAssignmentController::class)
        ->parameters(['staff-assignments' => 'staffAssignment']);
    Route::get('patient-history', [PatientHistoryController::class, 'index'])
        ->name('patient-history.index');
    Route::get('patient-history/{patient}', [PatientHistoryController::class, 'show'])
        ->name('patient-history.show');
});

require __DIR__.'/auth.php';