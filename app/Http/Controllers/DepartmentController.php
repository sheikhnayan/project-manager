<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Department::select('departments.*')
            ->selectRaw('(SELECT COUNT(*) FROM internal_tasks WHERE internal_tasks.department = departments.name AND internal_tasks.company_id = departments.company_id) as tasks_count');
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        
        $departments = $query->orderBy('name')->get();
        
        return response()->json($departments);
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        
        try {
            $department = Department::create([
                'name' => $request->name,
                'description' => $request->description,
                'company_id' => $user->role_id == 8 ? null : $user->company_id,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating department: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $department->company_id !== $user->company_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $department->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating department: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified department.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $department->company_id !== $user->company_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if department has associated tasks
        $tasksCount = \App\Models\InternalTask::where('department', $department->name)
            ->where('company_id', $department->company_id)
            ->count();

        if ($tasksCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete department with {$tasksCount} associated tasks. Deactivate it instead."
            ], 422);
        }

        try {
            $department->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting department: ' . $e->getMessage()
            ], 500);
        }
    }
}
