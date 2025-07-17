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
        $startDate = $request->input('start_date', now()->startOfWeek());
        $endDate = $request->input('end_date', now()->endOfWeek());

        $data = Project::all();
        $task = Task::all();
        $hours = TimeEntry::whereBetween('created_at', [$startDate, $endDate])->get();

        if ($request->has('start_date') && $request->has('end_date')){
            // dd($hours);
            return view('front.report', compact('data', 'hours', 'task', 'startDate', 'endDate'));
        }

        return view('front.reports', compact('data','hours','task','startDate', 'endDate'));
    }

    public function index_project(Request $request, $id)
    {
        $startDate = $request->input('start_date', now()->startOfWeek());
        $endDate = $request->input('end_date', now()->endOfWeek());

        $data = Project::findOrFail($id);

        $task = Task::where('project_id', $id)->get();
        $hours = TimeEntry::where('project_id', $id)->whereBetween('created_at', [$startDate, $endDate])->get();

        if ($request->has('start_date') && $request->has('end_date')){
            // dd($hours);
            return view('front.report-project', compact('data', 'hours', 'task', 'startDate', 'endDate'));
        }

        return view('front.reports-project', compact('data','hours','task','startDate', 'endDate'));
    }
}
