<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Only get non-archived projects
        $data = Project::where(function($query) {
                    $query->where('is_archived', 0)
                          ->orWhereNull('is_archived');
                })->get();
        // Only get tasks from non-archived projects
        $task = Task::whereHas('project', function($query) {
                    $query->where(function($q) {
                        $q->where('is_archived', 0)
                          ->orWhereNull('is_archived');
                    });
                })
                ->get();
        
        $hours = TimeEntry::whereBetween('created_at', [$startDate, $endDate])->get();

        if ($request->has('start_date') && $request->has('end_date')){
            return view('front.report', compact('data', 'hours', 'task', 'startDate', 'endDate'));
        }

        return view('front.reports', compact('data','hours','task','startDate', 'endDate'));
    }

    public function index_project(Request $request, $id)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = Project::findOrFail($id);

        // Get all tasks for this project
        $task = Task::where('project_id', $id)->get();
        
        $hours = TimeEntry::where('project_id', $id)->whereBetween('created_at', [$startDate, $endDate])->get();

        // Handle sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');

        if ($request->has('start_date') && $request->has('end_date')){
            // Apply sorting to tasks
            $task = $this->sortTasks($task, $hours, $sortBy, $sortOrder, $startDate, $endDate);
            return view('front.report-project', compact('data', 'hours', 'task', 'startDate', 'endDate', 'sortBy', 'sortOrder'));
        }

        // Apply sorting to tasks for initial load
        $task = $this->sortTasks($task, $hours, $sortBy, $sortOrder, $startDate, $endDate);
        return view('front.reports-project', compact('data','hours','task','startDate', 'endDate', 'sortBy', 'sortOrder'));
    }

    private function sortTasks($tasks, $hours, $sortBy, $sortOrder, $startDate, $endDate)
    {
        return $tasks->sortBy(function($task) use ($sortBy, $hours, $startDate, $endDate) {
            switch($sortBy) {
                case 'hours':
                    return \DB::table('time_entries')
                        ->where('task_id', $task->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('hours');
                case 'cost':
                    $taskHours = \DB::table('time_entries')
                        ->where('task_id', $task->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                    $cost = 0;
                    foreach ($taskHours as $h) {
                        $user = \DB::table('users')->where('id', $h->user_id)->first();
                        $cost += $user->hourly_rate * $h->hours;
                    }
                    return $cost;
                default: // name
                    return strtolower($task->name);
            }
        }, SORT_REGULAR, $sortOrder === 'desc');
    }

    private function sortProjects($projects, $hours, $sortBy, $sortOrder, $startDate, $endDate)
    {
        return $projects->sortBy(function($project) use ($sortBy, $hours, $startDate, $endDate) {
            switch($sortBy) {
                case 'hours':
                    return \DB::table('time_entries')
                        ->where('project_id', $project->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('hours');
                case 'cost':
                    $projectHours = \DB::table('time_entries')
                        ->where('project_id', $project->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                    $cost = 0;
                    foreach ($projectHours as $h) {
                        $user = \DB::table('users')->where('id', $h->user_id)->first();
                        $cost += $user->hourly_rate * $h->hours;
                    }
                    return $cost;
                default: // name
                    return strtolower($project->name);
            }
        }, SORT_REGULAR, $sortOrder === 'desc');
    }
}
