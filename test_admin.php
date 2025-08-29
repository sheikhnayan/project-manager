<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;

// Update admin user to use the correct super_admin role
$user = User::where('email', 'admin@admin.com')->first();
$superAdminRole = Role::where('name', 'super_admin')->first();

if ($user && $superAdminRole) {
    $user->update([
        'role_id' => $superAdminRole->id,
        'role' => 'admin' // Keep the enum field as admin
    ]);
    echo "Updated admin user to use super_admin role (ID: {$superAdminRole->id})\n";
} else {
    echo "User or super_admin role not found!\n";
}

// Check if admin user exists
$user = User::where('email', 'admin@admin.com')->with('userRole.permissions')->first();

if ($user) {
    echo "\nAdmin user found:\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role ID: " . $user->role_id . "\n";
    echo "Role: " . ($user->userRole ? $user->userRole->name : 'No role') . "\n";
    echo "Role Display Name: " . ($user->userRole ? $user->userRole->display_name : 'No role') . "\n";
    echo "Permissions Count: " . ($user->userRole && $user->userRole->permissions ? $user->userRole->permissions->count() : 0) . "\n";
    
    // Test specific permissions
    echo "Has view_users permission: " . ($user->hasPermission('view_users') ? 'YES' : 'NO') . "\n";
    echo "Has create_users permission: " . ($user->hasPermission('create_users') ? 'YES' : 'NO') . "\n";
    echo "Has edit_users permission: " . ($user->hasPermission('edit_users') ? 'YES' : 'NO') . "\n";
    echo "Has view_settings permission: " . ($user->hasPermission('view_settings') ? 'YES' : 'NO') . "\n";
    echo "Has manage_roles permission: " . ($user->hasPermission('manage_roles') ? 'YES' : 'NO') . "\n";
} else {
    echo "Admin user not found!\n";
}
