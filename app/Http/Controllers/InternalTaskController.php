<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternalTask;
use App\Models\Company;

class InternalTaskController extends Controller
{
    /**
     * Display a listing of internal tasks
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get internal tasks based on user's company or all if superadmin
        if ($user->role_id == 8) {
            $internalTasks = InternalTask::with('creator', 'company')->orderBy('department')->orderBy('name')->get();
        } else {
            $internalTasks = InternalTask::where('company_id', $user->company_id)
                ->with('creator')
                ->active()
                ->orderBy('department')
                ->orderBy('name')
                ->get();
        }

        return view('front.internal-tasks.index', compact('internalTasks'));
    }

    /**
     * Show the form for creating a new internal task
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $departments = \App\Models\Department::where('company_id', $user->company_id)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        
        // Pre-fill department if provided in query parameter
        $selectedDepartment = $request->query('department');
        
        return view('front.internal-tasks.create', compact('departments', 'selectedDepartment'));
    }

    /**
     * Store a newly created internal task
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string|max:1000',
        //     'department' => 'required|string|max:100',
        //     'category' => 'required|string|max:100',
        //     'hourly_rate' => 'nullable|numeric|min:0|max:9999.99',
        //     'max_hours_per_day' => 'nullable|integer|min:1|max:24',
        //     'requires_approval' => 'boolean',
        //     'is_active' => 'boolean',
        // ]);

        $user = auth()->user();
        
        $task = InternalTask::create([
            'name' => $request->name,
            'description' => $request->description,
            'department' => $request->department_id,
            'max_hours_per_day' => $request->max_hours_per_day,
            'requires_approval' => $request->boolean('requires_approval', false),
            'is_active' => $request->boolean('is_active', true),
            'company_id' => $user->company_id,
            'created_by' => $user->id,
        ]);

        // Handle user assignments
        if ($request->filled('assigned_users')) {
            $userIds = array_filter(explode(',', $request->assigned_users));
            // Verify all users belong to the same company (unless super admin)
            if ($user->role_id != 8) {
                $userIds = \App\Models\User::where('company_id', $user->company_id)
                    ->whereIn('id', $userIds)
                    ->pluck('id')
                    ->toArray();
            }
            $task->assignedUsers()->sync($userIds);
        }

        return redirect()->route('internal-tasks.show', $task->id)
            ->with('success', 'Internal task created successfully.');
    }

    /**
     * Display the specified internal task
     */
    public function show(string $id)
    {
        $task = InternalTask::with(['timeEntries.user', 'creator', 'assignedUsers'])
            ->findOrFail($id);
            
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $task->company_id !== $user->company_id) {
            abort(403);
        }

        return view('front.internal-tasks.task-details', compact('task'));
    }

    /**
     * Show the form for editing the specified internal task
     */
    public function edit(string $id)
    {
        $internalTask = InternalTask::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $internalTask->company_id !== $user->company_id) {
            abort(403);
        }

        $departments = \App\Models\Department::where('company_id', $user->company_id)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        $categories = \App\Models\Category::where('company_id', $user->company_id)
                                        ->where('is_active', true)
                                        ->orderBy('name')
                                        ->get();
        
        return view('front.internal-tasks.edit', compact('internalTask', 'departments', 'categories'));
    }

    /**
     * Update the specified internal task
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $internalTask = InternalTask::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $internalTask->company_id !== $user->company_id) {
            abort(403);
        }

        $internalTask->update([
            'name' => $request->name,
            'description' => $request->description,
            'department' => $request->department_id,
            'max_hours_per_day' => $request->max_hours_per_day,
            'requires_approval' => $request->boolean('requires_approval'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Handle user assignments
        \Log::info('Update internal task - assigned_users received:', [
            'task_id' => $id,
            'assigned_users_raw' => $request->input('assigned_users'),
            'is_filled' => $request->filled('assigned_users'),
            'all_request_data' => $request->all()
        ]);

        if ($request->filled('assigned_users')) {
            $userIds = array_filter(explode(',', $request->assigned_users));
            \Log::info('Parsed user IDs:', ['user_ids' => $userIds]);
            
            // Verify all users belong to the same company (unless super admin)
            if ($user->role_id != 8) {
                $userIds = \App\Models\User::where('company_id', $user->company_id)
                    ->whereIn('id', $userIds)
                    ->pluck('id')
                    ->toArray();
                \Log::info('Filtered user IDs by company:', ['filtered_ids' => $userIds]);
            }
            $internalTask->assignedUsers()->sync($userIds);
            \Log::info('Synced assigned users');
        } else {
            $internalTask->assignedUsers()->sync([]);
            \Log::info('Cleared all assigned users');
        }

        return redirect()->route('internal-tasks.show', $internalTask->id)
            ->with('success', 'Internal task updated successfully.');
    }

    /**
     * Toggle active status of internal task
     */
    public function toggleStatus(string $id)
    {
        $internalTask = InternalTask::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $internalTask->company_id !== $user->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $internalTask->update([
            'is_active' => !$internalTask->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $internalTask->is_active,
            'message' => $internalTask->is_active ? 'Task activated' : 'Task deactivated'
        ]);
    }

    /**
     * Get internal tasks for AJAX requests (used in timesheet)
     */
    public function getActiveTasks()
    {
        $user = auth()->user();
        
        $query = InternalTask::active()->select('id', 'name', 'department', 'category', 'max_hours_per_day');
        
        if ($user->role_id != 8) {
            $query->where('company_id', $user->company_id);
        }
        
        $tasks = $query->orderBy('department')->orderBy('name')->get();
        
        return response()->json($tasks);
    }

    /**
     * Remove the specified internal task from storage
     */
    public function destroy(string $id)
    {
        $internalTask = InternalTask::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $internalTask->company_id !== $user->company_id) {
            abort(403);
        }

        // Check if task has time entries
        if ($internalTask->timeEntries()->exists()) {
            return redirect()->route('internal-tasks.index')
                ->with('error', 'Cannot delete internal task that has time entries. Deactivate it instead.');
        }

        $internalTask->delete();

        return redirect()->route('internal-tasks.index')
            ->with('success', 'Internal task deleted successfully.');
    }

    /**
     * Get departments with task counts and user assignments
     */
    public function getDepartments()
    {
        $user = auth()->user();
        
        $query = Department::with([
            'assignedUsers:id,name', // Include assigned users, but only id and name fields
            'internalTasks', // For task count
        ]);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $departments = $query->get();
        
        // Add counts to departments
        $departments->each(function ($department) {
            $department->tasks_count = $department->internalTasks->count();
            $department->assigned_users_count = $department->assignedUsers->count();
        });
        
        return response()->json($departments);
    }

    /**
     * Store a new department
     */
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|json'
        ]);

        $user = auth()->user();
        $companyId = $user->role_id == 8 ? null : $user->company_id;

        $department = \App\Models\Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $companyId
        ]);

        // Handle user assignments if provided
        if ($request->filled('user_ids')) {
            $userIds = json_decode($request->user_ids);
            if (json_last_error() === JSON_ERROR_NONE && is_array($userIds)) {
                // Verify all users belong to the same company (unless super admin)
                if ($user->role_id != 8) {
                    $userIds = \App\Models\User::where('company_id', $user->company_id)
                        ->whereIn('id', $userIds)
                        ->pluck('id')
                        ->toArray();
                }
                // Sync user assignments
                $department->assignedUsers()->sync($userIds);
            }
        }

        // Load the department with its assigned users for the response
        $department->load('assignedUsers:id,name');

        return response()->json(['success' => true, 'department' => $department]);
    }

    /**
     * Show a specific department with its tasks and assigned users
     */
    public function showDepartment($id)
    {
        $user = auth()->user();
        
        $query = \App\Models\Department::with(['assignedUsers']);
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $department = $query->findOrFail($id);
        
        // Load internal tasks - show all for superadmin, filter by assigned user for others
        if ($user->role_id == 8) {
            // Superadmin sees all tasks in the department
            $department->load(['internalTasks' => function($query) {
                $query->with(['timeEntries', 'assignedUsers']);
            }]);
        } else {
            // Regular users only see tasks assigned to them
            $department->load(['internalTasks' => function($query) use ($user) {
                $query->whereHas('assignedUsers', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->with(['timeEntries', 'assignedUsers']);
            }]);
        }
        
        return view('front.internal-tasks.department', compact('department'));
    }

    /**
     * Show the form for editing a department
     */
    public function editDepartment($id)
    {
        $user = auth()->user();
        
        $query = \App\Models\Department::query();
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $department = $query->findOrFail($id);
        
        return view('front.internal-tasks.edit-department', compact('department'));
    }

    /**
     * Update a department
     */
    public function updateDepartment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|json'
        ]);

        $user = auth()->user();
        
        $query = \App\Models\Department::query();
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $department = $query->findOrFail($id);
        
        $department->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Handle user assignments if provided
        if ($request->filled('user_ids')) {
            $userIds = json_decode($request->user_ids);
            if (json_last_error() === JSON_ERROR_NONE && is_array($userIds)) {
                // Verify all users belong to the same company (unless super admin)
                if ($user->role_id != 8) {
                    $userIds = \App\Models\User::where('company_id', $user->company_id)
                        ->whereIn('id', $userIds)
                        ->pluck('id')
                        ->toArray();
                }
                // Sync user assignments
                $department->assignedUsers()->sync($userIds);
            }
        }

        // Load the department with its assigned users for the response
        $department->load('assignedUsers:id,name');

        return response()->json(['success' => true, 'department' => $department]);
    }

    /**
     * Delete a department
     */
    public function deleteDepartment($id)
    {
        $user = auth()->user();
        
        $query = \App\Models\Department::query();
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $department = $query->findOrFail($id);
        
        // Check if department has tasks
        if ($department->internalTasks()->exists()) {
            return response()->json(['error' => 'Cannot delete department that has tasks'], 400);
        }
        
        $department->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * Assign a user to a department
     */
    public function assignUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = auth()->user();
        
        $query = \App\Models\Department::query();
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $department = $query->findOrFail($id);
        
        // Check if user already assigned
        if ($department->assignedUsers()->where('user_id', $request->user_id)->exists()) {
            return response()->json(['error' => 'User is already assigned to this department'], 400);
        }
        
        // Assign user
        $department->assignedUsers()->attach($request->user_id, [
            'assigned_by' => $user->id
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Unassign a user from a department
     */
    public function unassignUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = auth()->user();
        
        $query = \App\Models\Department::query();
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $department = $query->findOrFail($id);
        
        // Unassign user
        $department->assignedUsers()->detach($request->user_id);
        
        return response()->json(['success' => true]);
    }
}
