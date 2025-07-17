<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignee;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\ProjectTeamMember;
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

        $data = Project::where('is_archived',0)->get()
        ->sortBy(function($item) {
            return strtolower($item->name);
        }, SORT_REGULAR, $sort === 'desc')
        ->values();

        // dd($data);

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
        $data = Project::find($id);

        $team = User::where('role','!=','admin')->get();

        $client = Client::all();

        return view('front.edit-project', compact('data','team','client'));
    }

    public function reload($id)
    {
        $data = Project::find($id);

        return view('front.estimate', ['data' => $data]);
    }

    public function index_manage()
    {
        $data = Project::all();

        // dd($data);

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
        $team = User::where('role','!=','admin')->get();

        $client = Client::all();

        return view('front.create-project', compact('team','client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $new = new Project;
        $new->project_number = $request->project_number;
        $new->name = $request->name;
        $new->client_id = $request->client_id;
        $new->expected_profit = $request->expected_profit;
        $new->start_date = Carbon::now()->format('Y-m-d');
        $new->end_date = Carbon::now()->addDays(365)->format('Y-m-d');
        $new->budget_total = 0;
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

        $new = Project::find($id);
        $new->project_number = $request->project_number;
        $new->name = $request->name;
        $new->client_id = $request->client_id;
        $new->expected_profit = $request->expected_profit;
        $new->update();

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



    public function store_task(Request $request, $id)
    {

        $project = Project::find($id);

        $task = new Task;
        $task->project_id = $id;

        // $dates = str_replace('/','-',$request->date);

        $dates = explode(' - ',$request->date);

        $start_date = Carbon::parse($dates[0])->format('Y-m-d');

        $end_date = Carbon::parse($dates[1])->format('Y-m-d');

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

        $start_date = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d');

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
        $data = Project::find($id);

        return view('front.projects', compact('data'));
    }

    public function show_weekly(string $id)
    {
        $data = Project::find($id);

        return view('front.projects-weekly', compact('data'));
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
        $project = Project::findOrFail($id);
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
}
