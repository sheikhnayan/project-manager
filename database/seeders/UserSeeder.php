<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Ulrich Raysse',
                'email' => 'ulrich@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'hourly_rate' => 50.00, // Example hourly rate
                'is_archived' => false,
                'profile_image_url' => 'https://example.com/images/ulrich.jpg',
                'profile_image_path' => 'images/ulrich.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'hourly_rate' => 40.00, // Example hourly rate
                'is_archived' => false,
                'profile_image_url' => 'https://example.com/images/john.jpg',
                'profile_image_path' => 'images/john.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'hourly_rate' => 30.00, // Example hourly rate
                'is_archived' => false,
                'profile_image_url' => 'https://example.com/images/jane.jpg',
                'profile_image_path' => 'images/jane.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
