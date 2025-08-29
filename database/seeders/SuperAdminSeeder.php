<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the Super Admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            echo "Super Admin role not found. Please run PermissionSeeder and RoleSeeder first.\n";
            return;
        }

        // Find or create the admin user
        $adminUser = User::where('email', 'admin@admin.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'role_id' => $superAdminRole->id,
                'hourly_rate' => 0,
            ]);
            echo "Created Super Admin user: admin@admin.com\n";
        } else {
            // Update existing user to have Super Admin role
            $adminUser->update([
                'role' => 'admin',
                'role_id' => $superAdminRole->id,
            ]);
            echo "Updated existing user admin@admin.com to Super Admin role\n";
        }
    }
}
