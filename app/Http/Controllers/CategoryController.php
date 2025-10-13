<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Category::select('categories.*', 'departments.name as department_name')
            ->leftJoin('departments', 'categories.department_id', '=', 'departments.id')
            ->selectRaw('(SELECT COUNT(*) FROM internal_tasks WHERE internal_tasks.category = categories.name AND internal_tasks.company_id = categories.company_id) as tasks_count');
        
        // Filter by company for non-superadmin users
        if ($user->role_id != 8 && $user->company_id) {
            $query->where('categories.company_id', $user->company_id);
        }
        
        $categories = $query->orderBy('categories.name')->get();
        
        return response()->json($categories);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
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
        
        // Validate department access if specified
        if ($request->department_id) {
            $department = Department::find($request->department_id);
            if (!$department || ($user->role_id != 8 && $department->company_id !== $user->company_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid department selected'
                ], 422);
            }
        }
        
        try {
            $category = Category::create([
                'name' => $request->name,
                'department_id' => $request->department_id,
                'description' => $request->description,
                'company_id' => $user->role_id == 8 ? null : $user->company_id,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $category->company_id !== $user->company_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
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

        // Validate department access if specified
        if ($request->department_id) {
            $department = Department::find($request->department_id);
            if (!$department || ($user->role_id != 8 && $department->company_id !== $user->company_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid department selected'
                ], 422);
            }
        }

        try {
            $category->update([
                'name' => $request->name,
                'department_id' => $request->department_id,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        
        // Check access permissions
        $user = auth()->user();
        if ($user->role_id != 8 && $category->company_id !== $user->company_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if category has associated tasks
        $tasksCount = \App\Models\InternalTask::where('category', $category->name)
            ->where('company_id', $category->company_id)
            ->count();

        if ($tasksCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete category with {$tasksCount} associated tasks. Deactivate it instead."
            ], 422);
        }

        try {
            $category->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }
}
