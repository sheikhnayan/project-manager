<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'name' => 'Task 1',
                'project_id' => 1, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 2',
                'project_id' => 1, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 3',
                'project_id' => 1, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 4',
                'project_id' => 2, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 5',
                'project_id' => 2, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 6',
                'project_id' => 2, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 7',
                'project_id' => 3, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 8',
                'project_id' => 3, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Task 9',
                'project_id' => 3, // Assuming you have a project with ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
