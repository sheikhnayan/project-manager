<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            [
                'name' => 'view_users',
                'display_name' => 'View Users',
                'description' => 'Can view user list and details',
                'group' => 'user_management'
            ],
            [
                'name' => 'create_users',
                'display_name' => 'Create Users',
                'description' => 'Can create new users',
                'group' => 'user_management'
            ],
            [
                'name' => 'edit_users',
                'display_name' => 'Edit Users',
                'description' => 'Can edit user details',
                'group' => 'user_management'
            ],
            [
                'name' => 'delete_users',
                'display_name' => 'Delete Users',
                'description' => 'Can delete users',
                'group' => 'user_management'
            ],
            [
                'name' => 'archive_users',
                'display_name' => 'Archive Users',
                'description' => 'Can archive/unarchive users',
                'group' => 'user_management'
            ],

            // Project Management
            [
                'name' => 'view_projects',
                'display_name' => 'View Projects',
                'description' => 'Can view project list and details',
                'group' => 'project_management'
            ],
            [
                'name' => 'create_projects',
                'display_name' => 'Create Projects',
                'description' => 'Can create new projects',
                'group' => 'project_management'
            ],
            [
                'name' => 'edit_projects',
                'display_name' => 'Edit Projects',
                'description' => 'Can edit project details',
                'group' => 'project_management'
            ],
            [
                'name' => 'delete_projects',
                'display_name' => 'Delete Projects',
                'description' => 'Can delete projects',
                'group' => 'project_management'
            ],
            [
                'name' => 'archive_projects',
                'display_name' => 'Archive Projects',
                'description' => 'Can archive/unarchive projects',
                'group' => 'project_management'
            ],
            [
                'name' => 'manage_project_team',
                'display_name' => 'Manage Project Team',
                'description' => 'Can add/remove team members from projects',
                'group' => 'project_management'
            ],

            // Task Management
            [
                'name' => 'create_tasks',
                'display_name' => 'Create Tasks',
                'description' => 'Can create new tasks',
                'group' => 'task_management'
            ],
            [
                'name' => 'edit_tasks',
                'display_name' => 'Edit Tasks',
                'description' => 'Can edit task details',
                'group' => 'task_management'
            ],
            [
                'name' => 'delete_tasks',
                'display_name' => 'Delete Tasks',
                'description' => 'Can delete tasks',
                'group' => 'task_management'
            ],
            [
                'name' => 'view_tasks',
                'display_name' => 'View Tasks',
                'description' => 'Can view task details',
                'group' => 'task_management'
            ],

            // Client Management
            [
                'name' => 'view_clients',
                'display_name' => 'View Clients',
                'description' => 'Can view client list and details',
                'group' => 'client_management'
            ],
            [
                'name' => 'create_clients',
                'display_name' => 'Create Clients',
                'description' => 'Can create new clients',
                'group' => 'client_management'
            ],
            [
                'name' => 'edit_clients',
                'display_name' => 'Edit Clients',
                'description' => 'Can edit client details',
                'group' => 'client_management'
            ],
            [
                'name' => 'delete_clients',
                'display_name' => 'Delete Clients',
                'description' => 'Can delete clients',
                'group' => 'client_management'
            ],
            [
                'name' => 'archive_clients',
                'display_name' => 'Archive Clients',
                'description' => 'Can archive/unarchive clients',
                'group' => 'client_management'
            ],

            // Time Tracking
            [
                'name' => 'view_own_timesheet',
                'display_name' => 'View Own Timesheet',
                'description' => 'Can view own time entries',
                'group' => 'time_tracking'
            ],
            [
                'name' => 'edit_own_timesheet',
                'display_name' => 'Edit Own Timesheet',
                'description' => 'Can edit own time entries',
                'group' => 'time_tracking'
            ],
            [
                'name' => 'view_all_timesheets',
                'display_name' => 'View All Timesheets',
                'description' => 'Can view all user time entries',
                'group' => 'time_tracking'
            ],
            [
                'name' => 'edit_all_timesheets',
                'display_name' => 'Edit All Timesheets',
                'description' => 'Can edit any user time entries',
                'group' => 'time_tracking'
            ],
            [
                'name' => 'approve_timesheets',
                'display_name' => 'Approve Timesheets',
                'description' => 'Can approve time entries',
                'group' => 'time_tracking'
            ],

            // Reports
            [
                'name' => 'view_reports',
                'display_name' => 'View Reports',
                'description' => 'Can view time and project reports',
                'group' => 'reports'
            ],
            [
                'name' => 'export_reports',
                'display_name' => 'Export Reports',
                'description' => 'Can export reports to various formats',
                'group' => 'reports'
            ],

            // Settings & Administration
            [
                'name' => 'view_settings',
                'display_name' => 'View Settings',
                'description' => 'Can view system settings',
                'group' => 'administration'
            ],
            [
                'name' => 'edit_settings',
                'display_name' => 'Edit Settings',
                'description' => 'Can modify system settings',
                'group' => 'administration'
            ],
            [
                'name' => 'manage_roles',
                'display_name' => 'Manage Roles',
                'description' => 'Can create, edit, and delete roles',
                'group' => 'administration'
            ],
            [
                'name' => 'assign_roles',
                'display_name' => 'Assign Roles',
                'description' => 'Can assign roles to users',
                'group' => 'administration'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
