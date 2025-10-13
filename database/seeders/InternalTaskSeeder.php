<?php<?php



namespace Database\Seeders;namespace Database\Seeders;



use Illuminate\Database\Seeder;use Illuminate\Database\Seeder;

use App\Models\InternalTask;use App\Models\InternalTask;

use App\Models\Company;use App\Models\Company;



class InternalTaskSeeder extends Seederclass InternalTaskSeeder extends Seeder

{{

    /**    /**

     * Run the database seeder.     * Run the database seeder.

     */     */

    public function run(): void    public function run(): void

    {    {

        // Get all companies for multi-tenant setup        // Get all companies for multi-tenant setup

        $companies = Company::all();        $companies = Company::all();

                

        // Default internal tasks to create for each company        // Default internal tasks to create for each company

        $defaultTasks = [        $defaultTasks = [

            [            [

                'name' => 'Team Meetings',                'name' => 'Team Meetings',

                'description' => 'Regular team meetings, standups, and planning sessions',                'description' => 'Regular team meetings, standups, and planning sessions',

                'department' => 'Admin',                'department' => 'Admin',

                'category' => 'Meeting',                'category' => 'Meeting',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => 4,                'max_hours_per_day' => 4,

            ],            ],

            [            [

                'name' => 'Training & Development',                'name' => 'Training & Development',

                'description' => 'Professional development, learning new skills, attending courses',                'description' => 'Professional development, learning new skills, attending courses',

                'department' => 'Training',                'department' => 'Training',

                'category' => 'Training',                'category' => 'Training',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => 8,                'max_hours_per_day' => 8,

            ],            ],

            [            [

                'name' => 'Administrative Tasks',                'name' => 'Administrative Tasks',

                'description' => 'General administrative work, paperwork, documentation',                'description' => 'General administrative work, paperwork, documentation',

                'department' => 'Admin',                'department' => 'Admin',

                'category' => 'Administrative',                'category' => 'Administrative',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => null,                'max_hours_per_day' => null,

            ],            ],

            [            [

                'name' => 'HR Activities',                'name' => 'HR Activities',

                'description' => 'HR related activities, interviews, onboarding, employee relations',                'description' => 'HR related activities, interviews, onboarding, employee relations',

                'department' => 'HR',                'department' => 'HR',

                'category' => 'Administrative',                'category' => 'Administrative',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => true,                'requires_approval' => true,

                'max_hours_per_day' => null,                'max_hours_per_day' => null,

            ],            ],

            [            [

                'name' => 'System Maintenance',                'name' => 'System Maintenance',

                'description' => 'Internal system maintenance, updates, and technical support',                'description' => 'Internal system maintenance, updates, and technical support',

                'department' => 'IT',                'department' => 'IT',

                'category' => 'Maintenance',                'category' => 'Maintenance',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => null,                'max_hours_per_day' => null,

            ],            ],

            [            [

                'name' => 'Code Review',                'name' => 'Code Review',

                'description' => 'Reviewing code, pull requests, and quality assurance',                'description' => 'Reviewing code, pull requests, and quality assurance',

                'department' => 'IT',                'department' => 'IT',

                'category' => 'Review',                'category' => 'Review',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => 6,                'max_hours_per_day' => 6,

            ],            ],

            [            [

                'name' => 'Research & Planning',                'name' => 'Research & Planning',

                'description' => 'Market research, project planning, and strategic activities',                'description' => 'Market research, project planning, and strategic activities',

                'department' => 'Operations',                'department' => 'Operations',

                'category' => 'Planning',                'category' => 'Planning',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => null,                'max_hours_per_day' => null,

            ],            ],

            [            [

                'name' => 'Internal Documentation',                'name' => 'Internal Documentation',

                'description' => 'Creating and maintaining internal documentation, processes',                'description' => 'Creating and maintaining internal documentation, processes',

                'department' => 'Admin',                'department' => 'Admin',

                'category' => 'Documentation',                'category' => 'Documentation',

                'is_active' => true,                'is_active' => true,

                'requires_approval' => false,                'requires_approval' => false,

                'max_hours_per_day' => null,                'max_hours_per_day' => null,

            ],            ],

        ];        ];



        // Create tasks for each company or create global tasks if no companies exist        // Create tasks for each company or create global tasks if no companies exist

        if ($companies->isEmpty()) {        if ($companies->isEmpty()) {

            // Create global tasks (for single-tenant or initial setup)            // Create global tasks (for single-tenant or initial setup)

            foreach ($defaultTasks as $taskData) {            foreach ($defaultTasks as $taskData) {

                InternalTask::create(array_merge($taskData, [                InternalTask::create(array_merge($taskData, [

                    'company_id' => null,                    'company_id' => null,

                    'created_by' => null,                    'created_by' => null,

                ]));                ]));

            }            }

        } else {        } else {

            // Create tasks for each company (multi-tenant)            // Create tasks for each company (multi-tenant)

            foreach ($companies as $company) {            foreach ($companies as $company) {

                foreach ($defaultTasks as $taskData) {                foreach ($defaultTasks as $taskData) {

                    InternalTask::create(array_merge($taskData, [                    InternalTask::create(array_merge($taskData, [

                        'company_id' => $company->id,                        'company_id' => $company->id,

                        'created_by' => null, // Will be set to first admin user in real usage                        'created_by' => null, // Will be set to first admin user in real usage

                    ]));                    ]));

                }                }

            }            }

        }        }



        $this->command->info('Internal tasks seeded successfully!');        $this->command->info('Internal tasks seeded successfully!');

    }    }

}}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternalTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
