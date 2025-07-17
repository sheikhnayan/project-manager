<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'name' => 'Project A',
                'client_id' => 1, // Assuming you have a client with ID 1
                'description' => 'Description for Project A',
                'start_date' => now(),
                'end_date' => now()->addMonths(3), // Example end date
                'budget_total' => 10000.00, // Example budget
                'is_archived' => false,
                'project_number' => 'P001', // Add project number
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project B',
                'client_id' => 1, // Assuming you have a client with ID 1
                'description' => 'Description for Project A',
                'start_date' => now(),
                'end_date' => now()->addMonths(3), // Example end date
                'budget_total' => 10000.00, // Example budget
                'is_archived' => false,
                'project_number' => 'P002', // Add project number
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project C',
                'client_id' => 1, // Assuming you have a client with ID 1
                'description' => 'Description for Project A',
                'start_date' => now(),
                'end_date' => now()->addMonths(3), // Example end date
                'budget_total' => 10000.00, // Example budget
                'is_archived' => false,
                'project_number' => 'P003', // Add project number
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
