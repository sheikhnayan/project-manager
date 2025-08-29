<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Role with all permissions
        $adminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Admin',
                'description' => 'Has access to all system features and settings',
                'is_active' => true
            ]
        );

        // Assign all permissions to admin
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Create Project Manager Role
        $pmRole = Role::firstOrCreate(
            ['name' => 'project_manager'],
            [
                'display_name' => 'Project Manager',
                'description' => 'Can manage projects, clients, and team members',
                'is_active' => true
            ]
        );

                $pmPermissions = Permission::whereIn('name', [
            'view_users', 'create_users', 'edit_users', 'archive_users',
            'view_projects', 'create_projects', 'edit_projects', 'archive_projects', 'manage_project_team',
            'create_tasks', 'edit_tasks', 'delete_tasks', 'view_tasks',
            'view_clients', 'create_clients', 'edit_clients', 'archive_clients',
            'view_own_timesheet', 'edit_own_timesheet', 'view_all_timesheets', 'edit_all_timesheets', 'approve_timesheets',
            'view_reports', 'export_reports',
            'view_settings'
        ])->get();
        $pmRole->permissions()->sync($pmPermissions->pluck('id'));

        // Create Team Leader Role
        $tlRole = Role::firstOrCreate(
            ['name' => 'team_leader'],
            [
                'display_name' => 'Team Leader',
                'description' => 'Can manage assigned projects and approve timesheets',
                'is_active' => true
            ]
        );

        $tlPermissions = Permission::whereIn('name', [
            'view_projects', 'edit_projects',
            'create_tasks', 'edit_tasks', 'view_tasks',
            'view_clients',
            'view_own_timesheet', 'edit_own_timesheet', 'view_all_timesheets', 'approve_timesheets',
            'view_reports',
            'view_users'
        ])->get();
        $tlRole->permissions()->sync($tlPermissions->pluck('id'));

        // Create Employee Role
        $employeeRole = Role::firstOrCreate(
            ['name' => 'employee'],
            [
                'display_name' => 'Employee',
                'description' => 'Can view assigned projects and manage own timesheet',
                'is_active' => true
            ]
        );

        $employeePermissions = Permission::whereIn('name', [
            'view_projects',
            'view_tasks',
            'view_clients',
            'view_own_timesheet', 'edit_own_timesheet',
            'view_reports'
        ])->get();
        $employeeRole->permissions()->sync($employeePermissions->pluck('id'));

        // Create Client Role (if clients need system access)
        $clientRole = Role::firstOrCreate(
            ['name' => 'client'],
            [
                'display_name' => 'Client',
                'description' => 'Can view project progress and reports',
                'is_active' => true
            ]
        );

        $clientPermissions = Permission::whereIn('name', [
            'view_projects',
            'view_tasks',
            'view_reports'
        ])->get();
        $clientRole->permissions()->sync($clientPermissions->pluck('id'));
    }
}
