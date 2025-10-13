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
    public function create()
    {
        $user = auth()->user();
        $departments = \App\Models\Department::where('company_id', $user->company_id)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        $categories = \App\Models\Category::where('company_id', $user->company_id)
                                        ->where('is_active', true)
                                        ->orderBy('name')
                                        ->get();
        
        return view('front.internal-tasks.create', compact('departments', 'categories'));
    }

    /**
     * Store a newly created internal task
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'department' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'hourly_rate' => 'nullable|numeric|min:0|max:9999.99',
            'max_hours_per_day' => 'nullable|integer|min:1|max:24',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user = auth()->user();
        
        InternalTask::create([
            'name' => $request->name,
            'description' => $request->description,
            'department' => $request->department,
            'category' => $request->category,
            'hourly_rate' => $request->hourly_rate,
            'max_hours_per_day' => $request->max_hours_per_day,
            'requires_approval' => $request->boolean('requires_approval', false),
            'is_active' => $request->boolean('is_active', true),
            'company_id' => $user->role_id == 8 ? null : $user->company_id,
            'created_by' => $user->id,
        ]);

        return redirect()->route('internal-tasks.index')
            ->with('success', 'Internal task created successfully.');
    }

    /**
     * Display the specified internal task
     */
    public function show(string $id)
    {
        $internalTask = InternalTask::with(['timeEntries.user', 'creator'])
            ->findOrFail($id);
            
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $internalTask->company_id !== $user->company_id) {
            abort(403);
        }

        return view('front.internal-tasks.show', compact('internalTask'));
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
        $internalTask = InternalTask::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $internalTask->company_id !== $user->company_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'department' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'hourly_rate' => 'nullable|numeric|min:0|max:9999.99',
            'max_hours_per_day' => 'nullable|integer|min:1|max:24',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $internalTask->update([
            'name' => $request->name,
            'description' => $request->description,
            'department' => $request->department,
            'category' => $request->category,
            'hourly_rate' => $request->hourly_rate,
            'max_hours_per_day' => $request->max_hours_per_day,
            'requires_approval' => $request->boolean('requires_approval'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('internal-tasks.index')
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
}
