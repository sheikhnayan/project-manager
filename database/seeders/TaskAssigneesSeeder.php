<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskAssigneesSeeder extends Seeder
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

        foreach ($projects as $project) {
            // Get employees assigned to the project
            $projectEmployees = DB::table('project_team_members')
                ->where('project_id', $project->id)
                ->pluck('user_id')
                ->toArray();

            // Get tasks for the project
            $tasks = Task::where('project_id', $project->id)->get();

            foreach ($tasks as $task) {
                // Assign random employees from the project to the task
                $assigneesCount = rand(1, 3); // Randomly assign 1 to 3 employees per task
                $assignedEmployees = array_rand(array_flip($projectEmployees), $assigneesCount);

                foreach ((array) $assignedEmployees as $employeeId) {
                    DB::table('task_assignees')->insert([
                        'task_id' => $task->id,
                        'user_id' => $employeeId
                    ]);
                }
            }
        }
    }
}
