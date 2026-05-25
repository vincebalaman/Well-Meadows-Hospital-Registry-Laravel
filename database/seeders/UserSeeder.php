<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'admin@test.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        User::updateOrCreate([
            'email' => 'staff@test.com',
        ], [
            'name' => 'Staff User',
            'password' => Hash::make('12345678'),
            'role' => 'staff',
        ]);

        User::updateOrCreate([
            'email' => 'patient@test.com',
        ], [
            'name' => 'Patient User',
            'password' => Hash::make('12345678'),
            'role' => 'patient',
        ]);
    }
}
