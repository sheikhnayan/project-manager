<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;

// Create a test employee user
$employeeRole = Role::where('name', 'employee')->first();

if ($employeeRole) {
    $employee = User::updateOrCreate(
        ['email' => 'employee@test.com'],
        [
            'name' => 'Test Employee',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'role_id' => $employeeRole->id,
            'hourly_rate' => 25,
        ]
    );
    
    echo "Employee user created/updated:\n";
    echo "Email: employee@test.com\n";
    echo "Password: password\n";
    echo "Role: {$employeeRole->display_name}\n";
    echo "Permissions: " . $employeeRole->permissions->pluck('name')->implode(', ') . "\n";
    
    // Test specific permissions
    echo "\nPermission checks:\n";
    echo "view_own_timesheet: " . ($employee->hasPermission('view_own_timesheet') ? 'YES' : 'NO') . "\n";
    echo "edit_own_timesheet: " . ($employee->hasPermission('edit_own_timesheet') ? 'YES' : 'NO') . "\n";
    echo "view_all_timesheets: " . ($employee->hasPermission('view_all_timesheets') ? 'YES' : 'NO') . "\n";
    echo "view_projects: " . ($employee->hasPermission('view_projects') ? 'YES' : 'NO') . "\n";
} else {
    echo "Employee role not found!\n";
}

// Also create a project manager for testing
$pmRole = Role::where('name', 'project_manager')->first();

if ($pmRole) {
    $pm = User::updateOrCreate(
        ['email' => 'pm@test.com'],
        [
            'name' => 'Test Project Manager',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'role_id' => $pmRole->id,
            'hourly_rate' => 50,
        ]
    );
    
    echo "\nProject Manager user created/updated:\n";
    echo "Email: pm@test.com\n";
    echo "Password: password\n";
    echo "Role: {$pmRole->display_name}\n";
    
    echo "\nPM Permission checks:\n";
    echo "view_own_timesheet: " . ($pm->hasPermission('view_own_timesheet') ? 'YES' : 'NO') . "\n";
    echo "view_all_timesheets: " . ($pm->hasPermission('view_all_timesheets') ? 'YES' : 'NO') . "\n";
    echo "approve_timesheets: " . ($pm->hasPermission('approve_timesheets') ? 'YES' : 'NO') . "\n";
}
