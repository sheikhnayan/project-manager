<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user()->load('userRole.permissions');
        
        // If no role assigned, deny access
        if (!$user->role_id || !$user->userRole) {
            abort(403, 'Access denied. No role assigned.');
        }

        // Parse comma-separated permissions if needed
        $permissionList = [];
        foreach ($permissions as $permission) {
            if (strpos($permission, ',') !== false) {
                $permissionList = array_merge($permissionList, explode(',', $permission));
            } else {
                $permissionList[] = $permission;
            }
        }

        // Check if user has any of the required permissions
        $hasPermission = false;
        foreach ($permissionList as $permission) {
            if ($user->hasPermission(trim($permission))) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
