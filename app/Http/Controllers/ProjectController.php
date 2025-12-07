<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignee;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\ProjectTeamMember;
use App\Models\Country;
use App\Models\TaskList;
use DB;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'asc');
        $user = auth()->user();

        $query = Project::where('is_archived', 0);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }

        $data = $query->get()
        ->sortBy(function($item) {
            return strtolower($item->name);
        }, SORT_REGULAR, $sort === 'desc')
        ->values();

        return view('front.project-list', compact('data'));
    }

    public function index_weekly(Request $request)
    {
        $sort = $request->get('sort', 'asc');

        $data = Project::where('is_archived',0)->get()
        ->sortBy(function($item) {
            return strtolower($item->name);
        }, SORT_REGULAR, $sort === 'desc')
        ->values();


        return view('front.project-list-weekly', compact('data'));
    }

    public function edits($id){
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        // Filter team by company for non-superadmin users
        $teamQuery = User::where('role','!=','admin');
        if ($user->role_id != 8 && $user->company_id) {
            $teamQuery->where('company_id', $user->company_id);
        }
        $team = $teamQuery->get();

        // Filter clients by company for non-superadmin users
        $clientQuery = Client::query();
        if ($user->role_id != 8 && $user->company_id) {
            $clientQuery->where('company_id', $user->company_id);
        }
        $client = $clientQuery->get();

        return view('front.edit-project', compact('data','team','client'));
    }

    public function reload($id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        return view('front.estimate', ['data' => $data]);
    }

    public function getTotals($id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $project = $query->firstOrFail();

        // Calculate estimated hours and budget
        $estimatedHours = $project->estimatedtimeEntries->sum('hours');
        $estimatedBudget = $project->estimatedtimeEntries->sum(function($entry) {
            return $entry->hours * ($entry->user->hourly_rate ?? 0);
        });

        // Calculate actual hours and spent budget
        $actualHours = $project->timeEntries->sum('hours');
        $actualSpent = $project->timeEntries->sum(function($entry) {
            return $entry->hours * ($entry->user->hourly_rate ?? 0);
        });

        // Calculate task progress
        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('status', 'completed')->count();

        // Calculate percentages
        $budgetProgress = $estimatedBudget > 0 ? ($actualSpent / $estimatedBudget) * 100 : 0;
        $hourProgress = $estimatedHours > 0 ? ($actualHours / $estimatedHours) * 100 : 0;
        $taskProgress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

        return response()->json([
            'budget_progress' => min(100, round($budgetProgress, 2)),
            'budget_total' => $estimatedBudget,
            'budget_spent' => $actualSpent,
            'hour_progress' => min(100, round($hourProgress, 2)),
            'hour_total' => $estimatedHours,
            'hour_spent' => $actualHours,
            'task_progress' => min(100, round($taskProgress, 2)),
            'task_total' => $totalTasks,
            'task_completed' => $completedTasks
        ]);
    }

    public function index_manage()
    {
        $user = auth()->user();
        $query = Project::query();
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->get();

        return view('front.project-management', compact('data'));
    }



    public function hideUser(Request $request)
    {
        // $find = DB::table('task_assignees')->where('id',$request->id)->first();

        $check = DB::table('project_team_members')->where('id',$request->id)->first();

        if($check->archieve == 1){
            $hide = DB::table('project_team_members')->where('id',$request->id)->update(['archieve' => 0]);
        }else{
            $hide = DB::table('project_team_members')->where('id',$request->id)->update(['archieve' => 1]);
        }


        return response()->json(['success' => 'User hidden successfully.']);
    }

    public function change_budget(Request $request)
    {
        $task = Task::find($request->id);
        $task->budget_total = $request->value;
        $task->update();

        $tasks_budget = Task::where('project_id',$task->project_id)->sum('budget_total');

        $project = Project::find($task->project_id);
        $project->budget_total = $tasks_budget;
        $project->update();

        return response()->json(['success' => 'Task Updated successfully.']);
    }

    public function check_dates(Request $request)
    {
        $task = Task::find($request->task_id);

        // Check if the given dates fall between any other task's dates in the same project
        $overlappingTasks = Task::where('project_id', $task->project_id)
        ->where('id', '!=', $task->id) // Exclude the current task
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_date', [$request->stoppedStartDate, $request->stoppedEndDate])
                ->orWhereBetween('end_date', [$request->stoppedStartDate, $request->stoppedEndDate])
                ->orWhere(function ($query) use ($request) {
                    $query->where('start_date', '<=', $request->stoppedStartDate)
                        ->where('end_date', '>=', $request->stoppedEndDate);
                });
        })
        ->get();

        if ($overlappingTasks->isNotEmpty()) {
            return response()->json([
                'overlap' => true,
                'message' => 'The dates overlap with other tasks.',
                'overlapping_tasks' => $overlappingTasks
            ]);
        }

        return response()->json([
            'overlap' => false,
            'message' => 'The dates do not overlap with any other tasks.'
        ]);
    }

    public function save_dates(Request $request)
    {
        $task = Task::find($request->task_id);
        $task->start_date = $request->stoppedStartDate;
        $task->end_date = $request->stoppedEndDate;
        $task->update();

        return response()->json([
            'updated' => true,
            'data' => $task
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Filter clients by company for non-superadmin users
        $clientsQuery = Client::query();
        if ($user->role_id != 8 && $user->company_id) {
            $clientsQuery->where('company_id', $user->company_id);
        }
        $client = $clientsQuery->get();
        
        // Filter users by company for non-superadmin users
        $teamQuery = User::where('role','!=','admin');
        if ($user->role_id != 8 && $user->company_id) {
            $teamQuery->where('company_id', $user->company_id);
        }
        $team = $teamQuery->get();
        
        return view('front.create-project', compact('client', 'team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $new = new Project;
        $new->project_number = $request->project_number;
        $new->name = $request->name;
        $new->client_id = $request->client_id;
        $new->expected_profit = $request->expected_profit;
        $new->start_date = Carbon::now()->format('Y-m-d');
        $new->end_date = Carbon::now()->addDays(365)->format('Y-m-d');
        $new->budget_total = 0;
        
        // Set company_id for new projects (from authenticated user)
        if ($user->company_id) {
            $new->company_id = $user->company_id;
        }
        
        $new->save();

        $members = explode(',',$request->team_members);

        foreach($members as $member){
            if ($member != '') {
                # code...
                DB::table('project_team_members')->insert([
                    'project_id' => $new->id,
                    'user_id' => $member,
                ]);
            }
        }

        $tasks = explode(',',$request->tasks);

        foreach($tasks as $key => $task){
            if ($task != '') {
                # code...
                $ta = new Task;
                $ta->project_id = $new->id;
                $ta->name = $task;
                $ta->budget_total = 0;
                $ta->start_date = Carbon::now()->addDays($key)->format('Y-m-d');
                $ta->end_date = Carbon::now()->addDays(1+$key)->format('Y-m-d');
                $ta->save();
            }
        }


        return redirect(route('project-management'));


    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $new = Project::find($id);
        $new->project_number = $request->project_number;
        $new->name = $request->name;
        $new->client_id = $request->client_id;
        $new->expected_profit = $request->expected_profit;
        $new->update();

        // Handle team members - remove old ones and add new ones
        $members = explode(',',$request->team_members);
        $members = array_filter($members, function($member) {
            return !empty(trim($member));
        });

        // Remove all existing team members for this project
        DB::table('project_team_members')->where('project_id', $new->id)->delete();

        // Add the new team members
        foreach($members as $member){
            if ($member != '') {
                DB::table('project_team_members')->insert([
                    'project_id' => $new->id,
                    'user_id' => $member,
                ]);
            }
        }

        // $tasks = explode(',',$request->tasks);

        // foreach($tasks as $key => $task){
        //     if ($task != '') {
        //         # code...
        //         $ta = new Task;
        //         $ta->project_id = $new->id;
        //         $ta->name = $task;
        //         $ta->budget_total = 0;
        //         $ta->start_date = Carbon::now()->addDays($key)->format('Y-m-d');
        //         $ta->end_date = Carbon::now()->addDays(1+$key)->format('Y-m-d');
        //         $ta->save();
        //     }
        // }


        return redirect(route('project-management'));


    }



    public function store_task(Request $request, $id)
    {

        $project = Project::find($id);

        $task = new Task;
        $task->project_id = $id;

        // $dates = str_replace('/','-',$request->date);

        $dates = explode(' - ',$request->date);

        // Ensure we have two dates after exploding
        if (count($dates) !== 2) {
            return back()->withErrors(['date' => 'Invalid date format. Expected format: dd/mm/yyyy - dd/mm/yyyy']);
        }
        
        try {
            // Clean the date strings and validate format
            $startDateString = trim($dates[0]);
            $endDateString = trim($dates[1]);
            
            // Check if date is in Y-m-d format (2025-08-17) first
            if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $startDateString) && preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $endDateString)) {
                // Date is already in Y-m-d format from daterangepicker
                $start_date = Carbon::createFromFormat('Y-m-d', $startDateString)->format('Y-m-d');
                $end_date = Carbon::createFromFormat('Y-m-d', $endDateString)->format('Y-m-d');
            } elseif (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $startDateString) && preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $endDateString)) {
                // Date is in d/m/Y format - handle day/month correctly
                $start_date = Carbon::createFromFormat('d/m/Y', $startDateString)->format('Y-m-d');
                $end_date = Carbon::createFromFormat('d/m/Y', $endDateString)->format('Y-m-d');
            } else {
                return back()->withErrors(['date' => 'Invalid date format. Please use dd/mm/yyyy or yyyy-mm-dd format.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Error parsing dates: ' . $e->getMessage() . '. Input received: "' . $request->date . '"']);
        }

        $task->start_date = $start_date;
        $task->end_date = $end_date;
        $task->budget_total = $request->budget_total;
        $task->name = $request->name;
        $task->save();

        // $project->budget_total += $request->budget_total;
        // $project->save();



        return redirect()->route('projects.show', $id)->with('success', 'Task added successfully.');
    }

    public function update_task(Request $request, $id)
    {
        $task = Task::find($request->task_id);
        
        $dates = explode(' - ',$request->date);
        
        
        // Ensure we have two dates after exploding
        if (count($dates) !== 2) {
            
            return back()->withErrors(['date' => 'Invalid date format. Expected format: dd/mm/yyyy - dd/mm/yyyy']);
        }
        
        try {
            // Clean the date strings and validate format
            $startDateString = trim($dates[0]);
            $endDateString = trim($dates[1]);
            
            // Check if date is in Y-m-d format (2025-08-17) first
            if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $startDateString) && preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $endDateString)) {
                // Date is already in Y-m-d format from daterangepicker
                $start_date = Carbon::createFromFormat('Y-m-d', $startDateString)->format('Y-m-d');
                $end_date = Carbon::createFromFormat('Y-m-d', $endDateString)->format('Y-m-d');
            } elseif (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $startDateString) && preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $endDateString)) {
                // Date is in d/m/Y format - handle day/month correctly
                $start_date = Carbon::createFromFormat('d/m/Y', $startDateString)->format('Y-m-d');
                $end_date = Carbon::createFromFormat('d/m/Y', $endDateString)->format('Y-m-d');
            } else {
                return back()->withErrors(['date' => 'Invalid date format. Please use dd/mm/yyyy or yyyy-mm-dd format.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Error parsing dates: ' . $e->getMessage() . '. Input received: "' . $request->date . '"']);
        }
        $task->start_date = $start_date;
        $task->end_date = $end_date;
        $task->budget_total = $request->budget_total;
        $task->name = $request->name;
        $task->update();

        // $project->budget_total += $request->budget_total;
        // $project->save();



        return redirect()->route('projects.show', $id)->with('success', 'Task added successfully.');
    }

    public function store_member(Request $request, $id)
    {
        $team = new ProjectTeamMember;
        $team->user_id = $request->user_id;
        $team->project_id = $id;
        $team->save();

        return back()->with('success', 'Member added successfully.');

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        return view('front.projects', compact('data'));
    }

    public function try(string $id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        return view('front.try-weekly', compact('data'));
    }

    public function gantt(string $id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->with(['tasks' => function($query) {
            $query->orderBy('id', 'asc');
        }, 'members.user'])->firstOrFail();

        return view('front.gantt-clean', compact('data'));
    }

    public function show_weekly(string $id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        // return view('front.projects-weekly', compact('data'));
        return view('front.try-weekly', compact('data'));
    }

    public function show_v2(string $id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        return view('front.projects-v2', compact('data'));
    }

    public function show_dhtmlx(string $id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $data = $query->firstOrFail();

        return view('front.projects-dhtmlx', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function archive($id)
    {
        $user = auth()->user();
        $query = Project::where('id', $id);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $project = $query->firstOrFail();
        
        if ($project->is_archived == 1) {
            # code...
            $project->is_archived = 0;
        } else {
            # code...
            $project->is_archived = 1;
        }

        $project->update();
        return redirect()->route('project-management', ['status' => 'active'])->with('success', 'Project archived.');
    }

    public function getCountryTaskLists()
    {
        try {
            // Fetch countries with their task lists
            $countries = Country::with('taskLists')->get();
            
            // Transform the data to the expected format
            $countryTaskLists = $countries->map(function($country) {
                // Get the country-specific tasks sorted by position
                $countryTasks = $country->taskLists()
                    ->orderBy('position')
                    ->get()
                    ->pluck('name')
                    ->toArray();
                
                // Ensure proper numbering if tasks don't have numbers
                $numberedTasks = [];
                foreach ($countryTasks as $index => $task) {
                    if (!preg_match('/^\d{2}_/', $task)) {
                        $numberedTasks[] = sprintf('%02d_%s', $index + 1, $task);
                    } else {
                        $numberedTasks[] = $task;
                    }
                }
                
                // Sort the tasks numerically by their prefix
                usort($numberedTasks, function($a, $b) {
                    // Extract the numeric prefix (e.g., "01" from "01_Task Name")
                    $getNumber = function($str) {
                        $match = preg_match('/^(\d+)_/', $str, $matches);
                        return $match ? (int)$matches[1] : 9999;
                    };
                    
                    $numA = $getNumber($a);
                    $numB = $getNumber($b);
                    
                    return $numA - $numB;
                });
                
                return [
                    'name' => $country->name,
                    'tasks' => $numberedTasks
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $countryTaskLists
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching country task lists: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function update_progress(Request $request)
    {
        try {
            $task = Task::findOrFail($request->task_id);
            
            // Check authorization
            $user = auth()->user();
            if ($user->role_id != 8 && $task->project->company_id != $user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $task->progress = $request->progress;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating progress: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTaskDetails($id)
    {
        try {
            $task = Task::with(['assignees'])->findOrFail($id);
            
            // Check authorization
            $user = auth()->user();
            if ($user->role_id != 8 && $task->project->company_id != $user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            return response()->json([
                'id' => $task->id,
                'name' => $task->name,
                'description' => $task->description,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'progress' => $task->progress ?? 0,
                'assignees' => $task->assignees->map(function($assignee) {
                    return [
                        'id' => $assignee->id,
                        'name' => $assignee->name
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching task details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveMemberOrder(Request $request, $projectId)
    {
        try {
            $user = auth()->user();
            $project = Project::findOrFail($projectId);
            
            // Check authorization
            if ($user->role_id != 8 && $project->company_id != $user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $memberOrder = $request->input('member_order', []);
            
            // Save the order in user preferences/settings table
            // Using a JSON column or separate table to store user preferences
            DB::table('user_project_preferences')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'project_id' => $projectId
                ],
                [
                    'member_order' => json_encode($memberOrder),
                    'updated_at' => now()
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Member order saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving member order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMemberOrder($projectId)
    {
        try {
            $user = auth()->user();
            $project = Project::findOrFail($projectId);
            
            // Check authorization
            if ($user->role_id != 8 && $project->company_id != $user->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            
            $preference = DB::table('user_project_preferences')
                ->where('user_id', $user->id)
                ->where('project_id', $projectId)
                ->first();
            
            $memberOrder = [];
            if ($preference && $preference->member_order) {
                $memberOrder = json_decode($preference->member_order, true);
            }
            
            return response()->json([
                'success' => true,
                'member_order' => $memberOrder
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching member order: ' . $e->getMessage()
            ], 500);
        }
    }
}
