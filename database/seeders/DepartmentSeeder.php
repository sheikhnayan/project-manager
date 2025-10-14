<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Company;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company or create one for testing
        $company = Company::first();
        if (!$company) {
            $company = Company::create([
                'name' => 'Sample Company',
                'email' => 'contact@sample.com'
            ]);
        }

        $departments = [
            [
                'name' => 'Engineering',
                'description' => 'Software Development and Engineering',
                'company_id' => $company->id,
                'is_active' => true
            ],
            [
                'name' => 'Human Resources',
                'description' => 'HR and People Operations',
                'company_id' => $company->id,
                'is_active' => true
            ],
            [
                'name' => 'Marketing',
                'description' => 'Marketing and Communications',
                'company_id' => $company->id,
                'is_active' => true
            ],
            [
                'name' => 'Finance',
                'description' => 'Finance and Accounting',
                'company_id' => $company->id,
                'is_active' => true
            ],
            [
                'name' => 'Operations',
                'description' => 'Operations and Administration',
                'company_id' => $company->id,
                'is_active' => true
            ]
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['name' => $dept['name'], 'company_id' => $dept['company_id']],
                $dept
            );
        }
    }
}