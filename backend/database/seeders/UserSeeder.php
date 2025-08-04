<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create teacher user
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'enseignant'
        ]);

        // Create student user
        User::create([
            'name' => 'Student User',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'eleve'
        ]);

        // Create parent user
        User::create([
            'name' => 'Parent User',
            'email' => 'parent@school.com',
            'password' => Hash::make('password'),
            'role' => 'parent'
        ]);
    }
}