<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternalTask;
use App\Models\Company;

class DefaultInternalTaskSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Default internal tasks to create
        $defaultTasks = [
            [
                'name' => 'Team Meetings',
                'description' => 'Regular team meetings, standups, and planning sessions',
                'department' => 'Admin',
                'category' => 'Meeting',
                'is_active' => true,
                'requires_approval' => false,
                'max_hours_per_day' => 4,
                'company_id' => null,
                'created_by' => null,
            ],
            [
                'name' => 'Training & Development',
                'description' => 'Professional development, learning new skills, attending courses',
                'department' => 'Training',
                'category' => 'Training',
                'is_active' => true,
                'requires_approval' => false,
                'max_hours_per_day' => 8,
                'company_id' => null,
                'created_by' => null,
            ],
            [
                'name' => 'Administrative Tasks',
                'description' => 'General administrative work, paperwork, documentation',
                'department' => 'Admin',
                'category' => 'Administrative',
                'is_active' => true,
                'requires_approval' => false,
                'max_hours_per_day' => null,
                'company_id' => null,
                'created_by' => null,
            ],
            [
                'name' => 'HR Activities',
                'description' => 'HR related activities, interviews, onboarding',
                'department' => 'HR',
                'category' => 'Administrative',
                'is_active' => true,
                'requires_approval' => true,
                'max_hours_per_day' => null,
                'company_id' => null,
                'created_by' => null,
            ],
            [
                'name' => 'System Maintenance',
                'description' => 'Internal system maintenance, updates, technical support',
                'department' => 'IT',
                'category' => 'Maintenance',
                'is_active' => true,
                'requires_approval' => false,
                'max_hours_per_day' => null,
                'company_id' => null,
                'created_by' => null,
            ],
        ];

        foreach ($defaultTasks as $taskData) {
            InternalTask::create($taskData);
        }

        $this->command->info('Default internal tasks created successfully!');
    }
}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultInternalTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
