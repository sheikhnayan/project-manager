<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\TaskList;

class CountryTaskListSeeder extends Seeder
{
    public function run()
    {
        // Create Countries
        $countries = [
            [
                'name' => 'Denmark',
                'tasks' => [
                    '03_Concept Design',
                    '04_Preliminary Design', 
                    '05_Planning Submission Stage',
                    '06_Detail Design Stage',
                    '07_Technical Design',
                    '08_Design Review',
                    '09_Design Approval',
                    '10_Extra Work'
                ]
            ],
            [
                'name' => 'Germany',
                'tasks' => [
                    '03_Requirements Analysis',
                    '04_System Architecture',
                    '05_Frontend Development',
                    '06_Backend Development',
                    '07_Database Design',
                    '08_Testing Phase',
                    '09_Quality Assurance',
                    '10_Extra Work'
                ]
            ],
            [
                'name' => 'Sweden',
                'tasks' => [
                    '03_Site Survey',
                    '04_Foundation Work',
                    '05_Structural Work',
                    '06_Electrical Installation',
                    '07_Plumbing Installation',
                    '08_Finishing Work',
                    '09_Final Inspection',
                    '10_Extra Work'
                ]
            ],
            [
                'name' => 'Norway',
                'tasks' => [
                    '03_Project Planning',
                    '04_Resource Allocation',
                    '05_Timeline Management',
                    '06_Budget Control',
                    '07_Risk Management',
                    '08_Team Coordination',
                    '09_Progress Monitoring',
                    '10_Extra Work'
                ]
            ]
        ];

        foreach ($countries as $countryData) {
            // Create country
            $country = Country::create([
                'name' => $countryData['name']
            ]);

            // Create task lists for this country
            foreach ($countryData['tasks'] as $index => $taskName) {
                TaskList::create([
                    'country_id' => $country->id,
                    'name' => $taskName,
                    'position' => $index + 1
                ]);
            }
        }
    }
}
