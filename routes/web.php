<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\StaffContractController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClinicalRecordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'role:admin,staff,patient')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('patients', PatientController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
});

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::resource('staffs', StaffController::class);
    Route::resource('wards', WardController::class);
    Route::resource('contracts', StaffContractController::class);
    Route::get('appointments/medical-history', [AppointmentController::class, 'medicalHistory'])
        ->name('appointments.medical_history');
    Route::resource('appointments', AppointmentController::class);
    Route::resource('clinicalrecords', ClinicalRecordController::class);
});

require __DIR__.'/auth.php';
