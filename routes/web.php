<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WardsController;
use Illuminate\Support\Facades\Route;

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
    Route::resource('patients', PatientController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('staffs', StaffController::class);
    Route::resource('wards', WardsController::class);
    Route::get('wards/{ward}/assign-staff', [WardsController::class, 'assignStaffForm'])->name('wards.assign-staff');
    Route::post('wards/{ward}/assign-staff', [WardsController::class, 'storeStaffAssignment'])->name('wards.assign-staff.store');
    Route::get('staffs/{staff}/assign-ward', [StaffController::class, 'createWardAssignment'])->name('staffs.assign-ward');
    Route::post('staffs/{staff}/assign-ward', [StaffController::class, 'storeWardAssignment'])->name('staffs.assign-ward.store');
    Route::get('staffs/{staff}/contract', [StaffController::class, 'editContract'])->name('staffs.contract.edit');
    Route::post('staffs/{staff}/contract', [StaffController::class, 'updateContract'])->name('staffs.contract.update');
});

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
