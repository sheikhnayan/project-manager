<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class TimeEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all(); // Fetch all users
        $projects = Project::all(); // Fetch all projects
        $tasks = Task::all(); // Fetch all tasks

        $timeEntries = [];

        foreach ($users as $user) {
            foreach ($projects as $project) {
                foreach ($tasks as $task) {
                    // Generate random time entries for different weeks
                    for ($i = 0; $i < 5; $i++) { // 5 weeks of data
                        $startDate = Carbon::now()->subWeeks($i)->startOfWeek(); // Start of the week
                        $endDate = $startDate->copy()->endOfWeek(); // End of the week

                        // Generate random hours for each day of the week
                        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                        foreach ($days as $day) {
                            $timeEntries[] = [
                                'user_id' => $user->id,
                                'project_id' => $project->id,
                                'task_id' => $task->id,
                                'entry_date' => $startDate->copy()->addDays(array_search($day, $days))->toDateString(),
                                'hours' => rand(1, 8), // Random hours between 1 and 8
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }

        // Insert the time entries into the database
        DB::table('time_entries')->insert($timeEntries);
    }
}
