<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;

class ProjectTeamMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all projects
        $projects = Project::all();

        // Get all employees
        $employees = User::where('role', 'employee')->pluck('id')->toArray();

        foreach ($projects as $project) {
            // Assign random employees to each project
            $teamSize = rand(3, 5); // Randomly assign 3 to 5 employees per project
            $assignedEmployees = array_rand(array_flip($employees), $teamSize);

            foreach ((array) $assignedEmployees as $employeeId) {
                DB::table('project_team_members')->insert([
                    'project_id' => $project->id,
                    'user_id' => $employeeId
                ]);
            }
        }
    }
}
