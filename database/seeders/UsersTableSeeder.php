<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 employees
        $employees = [
            ['name' => 'John Doe', 'email' => 'john.doe@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com'],
            ['name' => 'Michael Brown', 'email' => 'michael.brown@example.com'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@example.com'],
            ['name' => 'Chris Wilson', 'email' => 'chris.wilson@example.com'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@example.com'],
            ['name' => 'David Lee', 'email' => 'david.lee@example.com'],
            ['name' => 'Laura Martinez', 'email' => 'laura.martinez@example.com'],
            ['name' => 'James Anderson', 'email' => 'james.anderson@example.com'],
            ['name' => 'Sophia Taylor', 'email' => 'sophia.taylor@example.com'],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('password'), // Default password
                'role' => 'employee', // Assign role
                'hourly_rate' => rand(2, 15), // Random hourly rate between $15 and $50
                'profile_image_url' => 'https://via.placeholder.com/150', // Dummy profile image URL
                'profile_image_path' => 'images/profiles/default.png', // Dummy profile image path
            ]);
        }
    }
}
