<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $roles = Role::with('permissions')->forUserCompany($user)->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::active()->orderBy('group', 'asc')->orderBy('display_name', 'asc')->get();
        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add logging to see if we reach this point
        \Log::info('RoleController store method called', ['request' => $request->all()]);
        
        try {
            $request->validate([
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $user = auth()->user();
            $role = Role::create([
                'name' => Str::slug($request->display_name, '_'),
                'display_name' => $request->display_name,
                'description' => $request->description,
                'company_id' => $user->company_id,
                'is_active' => true
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->attach($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'role' => $role->load('permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $user = auth()->user();
        
        // Check company access
        if ($user->role_id != 8 && $user->company_id != $role->company_id) {
            abort(404);
        }
        
        return response()->json($role->load('permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $user = auth()->user();
        
        // Check company access
        if ($user->role_id != 8 && $user->company_id != $role->company_id) {
            abort(404);
        }
        
        $permissions = Permission::active()->orderBy('group', 'asc')->orderBy('display_name', 'asc')->get();
        return response()->json([
            'role' => $role->load('permissions'),
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $user = auth()->user();
        
        // Check company access
        if ($user->role_id != 8 && $user->company_id != $role->company_id) {
            abort(404);
        }
        
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => Str::slug($request->display_name, '_'),
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        // Sync permissions
        $role->permissions()->sync($request->permissions ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $user = auth()->user();
        
        // Check company access
        if ($user->role_id != 8 && $user->company_id != $role->company_id) {
            abort(404);
        }
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete role that is assigned to users'
            ], 422);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * Toggle role status
     */
    public function toggleStatus(Role $role)
    {
        $role->update(['is_active' => !$role->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Role status updated successfully',
            'role' => $role
        ]);
    }
}
