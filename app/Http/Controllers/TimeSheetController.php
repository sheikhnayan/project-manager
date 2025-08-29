<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Task;
use App\Models\EstimatedTimeEntry;
use App\Models\Project;
use App\Models\ProjectTeamMember;
use Carbon\Carbon;

class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user, $daterange)
    {
        $currentUser = auth()->user();
        
        // Check if user can view the requested user's timesheet
        if (!$currentUser->hasPermission('view_all_timesheets') && $currentUser->id != $user) {
            abort(403, 'You can only view your own timesheet.');
        }
        
        // Parse the date range
        $dates = explode(' - ', $daterange);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        // Fetch time entries for the user and date range
        $timeEntries = TimeEntry::with(['project', 'task'])
            ->where('user_id', $user)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        // dd($timeEntries);

        // Transform the data into the required format
        $groupedEntries = $timeEntries->groupBy('project.id')->map(function ($entries, $project) {
            $tasks = $entries->groupBy('task.id')->map(function ($taskEntries, $task) {
                $days = $taskEntries->mapWithKeys(function ($entry) {
                    $day = strtolower(date('D', strtotime($entry->entry_date))); // Get day abbreviation (e.g., 'mon')
                    return [$day => $entry->hours];
                });
                return array_merge(['task' => $task], $days->toArray());
            });

            return $tasks->values();
        });

        // Flatten the data for JSON response
        $response = [];
        foreach ($groupedEntries as $project => $tasks) {
            foreach ($tasks as $task) {
                $response[] = array_merge(['project' => $project], $task);
            }
        }

        // Get all projects the user is a member of
        $projectIds = ProjectTeamMember::where('user_id', $user)->pluck('project_id');
        $projects = Project::whereIn('id', $projectIds)->get(['id', 'name', 'project_number']);

        // Return the transformed data as JSON, including projects list
        return response()->json([
            'entries' => $response,
            'projects' => $projects,
        ]);
    }

    public function index_estimate($id)
    {
        // Fetch all estimated time entries
        $timeEntries = EstimatedTimeEntry::where('project_id',$id)->get();

        // Transform the data into the required format
        $response = $timeEntries->map(function ($entry) {
            return [
                'task_id' => $entry->task_id,
                'user_id' => $entry->user_id,
                'date' => $entry->entry_date,
                'time' => $this->convertDecimalToTime($entry->hours), // Convert decimal hours to time format
            ];
        });

        // Return the transformed data as JSON
        return response()->json($response);
    }

    public function index_estimate_all()
    {
        // Fetch all estimated time entries
        $timeEntries = TimeEntry::get();

        // Transform the data into the required format
        $response = $timeEntries->map(function ($entry) {
            return [
                'task_id' => $entry->task_id,
                'project_id' => $entry->project_id,
                'user_id' => $entry->user_id,
                'date' => $entry->entry_date,
                'time' => $this->convertDecimalToTime($entry->hours), // Convert decimal hours to time format
            ];
        });

        // Return the transformed data as JSON
        return response()->json($response);
    }

    public function index_estimate_weekly($id)
    {
        $timeEntries = EstimatedTimeEntry::where('project_id', $id)->get()
        ->groupBy('user_id') // Group by user_id
        ->map(function ($userEntries, $userId) {
            // Group the user's entries by the starting date of the week
            return $userEntries->groupBy(function ($entry) {
                return \Carbon\Carbon::parse($entry->entry_date)->startOfWeek()->toDateString();
            })->map(function ($entries, $weekStartDate) use ($userId) {
                // Calculate the total time for the week
                $totalTime = $entries->sum('hours');

                // Return the grouped data
                return [
                    'week_start_date' => $weekStartDate,
                    'user_id' => $userId,
                    'total_time' => $totalTime, // Total hours for the week
                ];
            })->values(); // Reset keys for the inner map
        });

        // dd($timeEntries);

        // Return the transformed data as JSON
        return response()->json($timeEntries);
    }

    private function convertDecimalToTime($decimalHours)
    {
        $hours = floor($decimalHours); // Get the whole number part as hours
        $minutes = round(($decimalHours - $hours) * 60); // Get the fractional part as minutes
        return sprintf('%d:%02d', $hours, $minutes); // Format as "hours:minutes"
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getTasksByProject($id)
    {
        $tasks = Task::where('project_id', $id)->get(['id', 'name']);
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUser = auth()->user();
        
        // Check if user can edit the requested user's timesheet
        if (!$currentUser->hasPermission('edit_all_timesheets') && $currentUser->id != $request['user']) {
            return response()->json(['error' => 'You can only edit your own timesheet.'], 403);
        }
        
        // Parse the date range
        $dates = explode(' - ', $request['dateRange']);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));

        // Loop through the data and save each entry
        foreach ($request['data'] as $entry) {
            // dd($entry);
            foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day) {
                if (!empty($entry[$day]) && $entry[$day] > 0) {
                    $entryDate = date('Y-m-d', strtotime("{$day} this week", strtotime($startDate)));

                    // dd($entryDate, $entry[$day]);

                    try {
                        //code...
                        // dd($entryDate);
                        $check = TimeEntry::where('user_id', $request['user'])
                            ->where('project_id', $entry['project'])
                            ->where('task_id', $entry['task'])
                            ->where('entry_date', $entryDate)
                            ->first();

                        if ($check) {

                            $check->hours = $entry[$day];

                            $check->update();
                        } else {
                            // Create a new time entry
                            $d = new TimeEntry;
                            $d->user_id = $request['user'];
                            $d->project_id = $entry['project'];
                            $d->task_id = $entry['task'];
                            $d->entry_date = $entryDate;
                            $d->hours = $entry[$day];
                            $d->save();
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                        dd($th);
                    }



                    // dd($d);
                }
            }
        }

        return response()->json(['message' => 'Time entries saved successfully.']);
    }

    public function store_estimated(Request $request)
    {
        $currentUser = auth()->user();
        
        // Check if user can edit the requested user's timesheet
        if (!$currentUser->hasPermission('edit_all_timesheets') && $currentUser->id != $request->user_id) {
            return response()->json(['error' => 'You can only edit your own timesheet.'], 403);
        }
        
        // Parse the date from the request
        $date = Carbon::parse($request->date);

        // If task_id is provided, use that specific task, otherwise find task by project and date range
        if ($request->has('task_id') && $request->task_id) {
            $task = Task::find($request->task_id);
        } else {
            // Search for tasks where the date falls between start_date and end_date
            $task = Task::where('project_id', $request->project_id)->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();
        }

        // If no task found, return error
        if (!$task) {
            return response()->json(['error' => 'No task found for the given criteria'], 400);
        }

        $check = EstimatedTimeEntry::where('user_id', $request->user_id)
            ->where('task_id', $task->id)
            ->where('entry_date', $request->date)
            ->first();


        if ($check) {
            try {
                //code...
                $check->update([
                    'hours' => $request->data,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                dd($th);
            }
            // $check->update([
            //     'hours' => $request->data,
            // ]);
        } else {
            // Create a new time entry
            $d = new EstimatedTimeEntry;
            $d->user_id = $request->user_id;
            $d->project_id = $task->project_id;
            $d->task_id = $task->id;
            $d->entry_date = $request->date;
            $d->hours = $request->data;
            $d->save();
        }

        $data = [];

        $data['total'] = EstimatedTimeEntry::where('project_id',$request->project_id)->where('user_id',$request->user_id)->sum('hours');

        $user = User::find($request->user_id);

        $data['cost'] = $data['total']*$user->hourly_rate;


        return response()->json(['data' => $data, 'message' => 'Time entries saved successfully.']);
    }

    public function store_estimated_weekly(Request $request)
    {
        $currentUser = auth()->user();
        
        // Check if user can edit the requested user's timesheet
        if (!$currentUser->hasPermission('edit_all_timesheets') && $currentUser->id != $request->user_id) {
            return response()->json(['error' => 'You can only edit your own timesheet.'], 403);
        }

        // Parse the date from the request
        $date = Carbon::parse($request->date);

        $task = Task::where('project_id',$request->project_id)->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        // Get the start date from the request
        $startDate = \Carbon\Carbon::parse($request->date);

        // Calculate the next 6 days excluding Saturday and Sunday
        $validDates = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            if (!$currentDate->isSaturday() && !$currentDate->isSunday()) {
                $validDates[] = $currentDate->toDateString();
            }
        }

        // dd($validDates);

        $delete = EstimatedTimeEntry::where('user_id', $request->user_id)
            ->where('project_id', $task->project_id)
            ->where('task_id', $task->id)
            ->whereIn('entry_date', $validDates) // Use whereIn with the calculated dates
            ->delete();

        $data = $request->data/5;

        foreach ($validDates as $key => $value) {
            // Create a new time entry
            $d = new EstimatedTimeEntry;
            $d->user_id = $request->user_id;
            $d->project_id = $task->project_id;
            $d->task_id = $task->id;
            $d->entry_date = $value;
            $d->hours = $data;
            $d->save();
        }

        $data = [];

        $data['total'] = EstimatedTimeEntry::where('project_id',$request->project_id)->where('user_id',$request->user_id)->sum('hours');

        $user = User::find($request->user_id);

        $data['cost'] = $data['total']*$user->hourly_rate;




        return response()->json(['data'=> $data, 'message' => 'Time entries saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        
        // If user can view all timesheets, show all users
        if ($user->hasPermission('view_all_timesheets')) {
            $users = User::all();
        } else {
            // If user can only view own timesheet, show only themselves
            $users = User::where('id', $user->id)->get();
        }

        $projects = Project::all();

        return view('front.time-tracking',compact('users','projects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function approve(Request $request)
    {
        $userId = $request->query('user_id');
        $dates = $request->query('dates');

        // Validate the input
        if (!$userId || !$dates) {
            return response()->json(['success' => false, 'message' => 'Invalid input'], 400);
        }

        $dateArray = explode(',', $dates);

        // Update the approved status for the specified dates
        TimeEntry::where('user_id', $userId)
            ->whereIn('entry_date', $dateArray)
            ->update(['approved' => 1]);

        return response()->json(['success' => true, 'message' => 'Entries approved successfully']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function getApprovalStatus(Request $request)
    {
        $id = $request->query('user_id');

        $dates = $request->query('dates');

        // Validate the input
        if (!$id || !$dates) {
            return response()->json(['success' => false, 'message' => 'Invalid input'], 400);
        }

        $dateArray = explode(',', $dates);

        $status = TimeEntry::where('user_id', $id)
            ->whereIn('entry_date', $dateArray)
            ->where('approved', 1)
            ->exists();

        return response()->json(['is_approved' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
