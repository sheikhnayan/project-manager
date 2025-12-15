<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\InternalTaskController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\StripeController;


Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Dashboard route - for authenticated users
Route::get('/dashboard', function () {
    return view('front.index');
})->name('dashboard')->middleware('auth');

Route::get('/', function () {
    // Check if user is authenticated
    if (Auth::check()) {
        $user = Auth::user();
        
        // If user has superadmin role (role_id 8), redirect to dashboard
        if ($user->role_id == 8) {
            return redirect()->route('dashboard');
        }
        
        // For other authenticated users, check if their company has an active subscription
        if ($user->company && $user->company->is_subscribed) {
            return redirect()->route('dashboard');
        }
        
        // If user is authenticated but no active subscription, show subscription page
        return view('front.homepage');
    }
    
    // If not authenticated, show the public subscription page
    return view('front.homepage');
})->name('homepage');

Route::post('/subscribe', [StripeController::class, 'subscribe'])->name('stripe.subscribe');

// Route::get('/', function () {
//     return view('front.index');
// })->name('index');

Route::get('/foo', function () {
    Artisan::call('storage:link');
});


Route::get('/plan', function () {
    return view('front.plan');
})->name('plan');

// Route::get('/client-management', function () {
//     return view('front.client-management');
// })->name('client-management');

Route::get('/create-project', function () {
    return view('front.create-project');
})->name('create-project');

Route::get('/project-management', [ProjectController::class,'index_manage'])->name('project-management');

Route::get('/time-tracking/{user}/{dateRange}',[TimeSheetController::class, 'index'])->name('time-tracking.index')->middleware('permission:view_own_timesheet,view_all_timesheets');
Route::get('/time-tracking/approve', [TimeSheetController::class, 'approve'])->middleware('permission:approve_timesheets');
Route::get('/time-tracking/approval-status', [TimeSheetController::class, 'getApprovalStatus'])->middleware('permission:approve_timesheets');
Route::post('/projects/hide-user', [ProjectController::class, 'hideUser'])->name('projects.hide-user');
Route::post('/projects/change-budget', [ProjectController::class, 'change_budget'])->name('projects.change-budget');
Route::post('/projects/check-dates', [ProjectController::class, 'check_dates'])->name('projects.check-dates');
Route::post('/projects/save-dates', [ProjectController::class, 'save_dates'])->name('projects.save-dates');
Route::post('/projects/update-progress', [ProjectController::class, 'update_progress'])->name('projects.update-progress');
Route::post('/projects/{project}/save-member-order', [ProjectController::class, 'saveMemberOrder'])->name('projects.save-member-order');
Route::get('/projects/{project}/get-member-order', [ProjectController::class, 'getMemberOrder'])->name('projects.get-member-order');
Route::get('/api/tasks/{id}', [ProjectController::class, 'getTaskDetails'])->name('api.tasks.details');

Route::get('/estimated-time-tracking/{id}/get',[TimeSheetController::class, 'index_estimate'])->name('estimated-time-tracking.index');

Route::get('/estimated-time-tracking/get',[TimeSheetController::class, 'index_estimate_all'])->name('estimated-time-tracking.indexall');

Route::get('/estimated-time-tracking-weekly/{id}/get',[TimeSheetController::class, 'index_estimate_weekly'])->name('estimated-time-tracking-weekly.index');


Route::post('/time-tracking/save', [TimeSheetController::class, 'store'])->name('time-tracking.store')->middleware('permission:edit_own_timesheet,edit_all_timesheets');

Route::post('/estimated-time-tracking/save', [TimeSheetController::class, 'store_estimated'])->name('estimated-time-tracking.store')->middleware('permission:edit_own_timesheet,edit_all_timesheets');

Route::post('/estimated-time-tracking/weekly/{id}/save', [TimeSheetController::class, 'store_estimated_weekly'])->name('estimated-time-tracking.store')->middleware('permission:edit_own_timesheet,edit_all_timesheets');

Route::get('/time-tracking/tasks/{projectId}', [TimeSheetController::class, 'getTasksByProject'])->middleware('permission:view_own_timesheet,view_all_timesheets');

Route::get('/time-tracking/internal-tasks', [TimeSheetController::class, 'getInternalTasks'])->middleware('permission:view_own_timesheet,view_all_timesheets');
Route::get('/user/{id}/departments', [TimeSheetController::class, 'getUserDepartments']);
Route::get('/time-tracking/departments/{id}/tasks', [TimeSheetController::class, 'getDepartmentTasks'])->middleware('permission:view_own_timesheet,view_all_timesheets');

Route::get('/api/countries/task-lists', [ProjectController::class, 'getCountryTaskLists']);

Route::get('/api/users/{id}', [UserController::class, 'getUserData']);

Route::controller(ProjectController::class)->prefix('/projects')->group(function () {
    Route::get('/', 'index')->name('projects')->middleware('permission:view_projects');
    Route::get('/weekly', 'index_weekly')->name('projects.weekly')->middleware('permission:view_projects');
    Route::get('/weekly/{id}', 'show_weekly')->name('projects-weekly')->middleware('permission:view_projects');
    Route::get('/create', 'create')->name('projects.create')->middleware('permission:create_projects');
    Route::get('/edits/{id}', 'edits')->name('projects.edits')->middleware('permission:edit_projects');
    Route::get('/{id}', 'show')->name('projects.show')->middleware('permission:view_projects');
    Route::get('/try/{id}', 'show_weekly')->name('projects.try')->middleware('permission:view_projects');
    Route::get('/{id}/v2', 'show_v2')->name('projects.v2')->middleware('permission:view_projects');
    Route::get('/{id}/dhtmlx', 'show_dhtmlx')->name('projects.dhtmlx')->middleware('permission:view_projects');
    Route::get('/{id}/gantt', 'gantt')->name('projects.gantt')->middleware('permission:view_projects');
    Route::get('/reload-data/{id}', 'reload')->name('projects.reload')->middleware('permission:view_projects');
    Route::get('/{id}/totals', 'getTotals')->name('projects.totals')->middleware('permission:view_projects');
    Route::get('/{id}/edit', 'edit')->name('projects.edit')->middleware('permission:edit_projects');
    Route::post('/', 'store')->name('projects.store')->middleware('permission:create_projects');
    Route::post('/update-project/{id}', 'update')->name('projects.update')->middleware('permission:edit_projects');
    Route::post('/{id}', 'store_task')->name('projects.task.store')->middleware('permission:create_tasks');
    Route::post('/update/{id}', 'update_task')->name('projects.task.update')->middleware('permission:edit_tasks');
    Route::post('/member-store/{id}', 'store_member')->name('projects.member.store')->middleware('permission:manage_project_team');
    Route::put('/{id}', 'update')->name('projects.update')->middleware('permission:edit_projects');
    Route::delete('/{id}', 'destroy')->name('projects.destroy')->middleware('permission:delete_projects');
    Route::get('/{project}/archive', 'archive')->name('projects.archive')->middleware('permission:archive_projects');
});

Route::controller(ClientController::class)->prefix('/client-management')->group(function () {
    Route::get('/', 'index')->name('client')->middleware('permission:view_clients');
    Route::post('/{id}/archive', 'archive')->name('client.archive')->middleware('permission:archive_clients');
    Route::get('/create', 'create')->name('client.create')->middleware('permission:create_clients');
    Route::get('/{id}', 'show')->name('client.show')->middleware('permission:view_clients');
    Route::get('/{id}/edit', 'edit')->name('client.edit')->middleware('permission:edit_clients');
    Route::post('/', 'store')->name('client.store')->middleware('permission:create_clients');
    Route::put('/{id}', 'update')->name('client.update')->middleware('permission:edit_clients');
    Route::delete('/{id}', 'destroy')->name('client.destroy')->middleware('permission:delete_clients');
});

Route::get('/projects_two', function () {
    return view('front.projects_second');
})->name('projects_two');

Route::get('/reports', [ReportController::class,'index'])->name('reports')->middleware('permission:view_reports');

Route::get('/reports/{id}', [ReportController::class,'index_project'])->name('report')->middleware('permission:view_reports');

Route::get('/resources', [UserController::class, 'resources'])->name('resources')->middleware('permission:view_users');
Route::get('/resources/weekly', [UserController::class, 'resources_weekly'])->name('resources.weekly')->middleware('permission:view_users');

Route::get('/settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:view_settings');

Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:edit_settings');

Route::post('/settings/presets', [SettingController::class, 'savePresets'])->name('settings.presets')->middleware('permission:edit_settings');

// Role Management Routes  
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:manage_roles');
Route::post('/test-roles', function (Request $request) {
    \Log::info('Test route hit with data: ' . json_encode($request->all()));
    return response()->json(['status' => 'test route works', 'data' => $request->all()]);
});
Route::controller(RoleController::class)->prefix('/roles')->group(function () {
    Route::get('/', 'index')->name('roles.index')->middleware('permission:manage_roles');
    Route::get('/create', 'create')->name('roles.create')->middleware('permission:manage_roles');
    Route::get('/{role}', 'show')->name('roles.show')->middleware('permission:manage_roles');
    Route::get('/{role}/edit', 'edit')->name('roles.edit')->middleware('permission:manage_roles');
    Route::put('/{role}', 'update')->name('roles.update')->middleware('permission:manage_roles');
    Route::delete('/{role}', 'destroy')->name('roles.destroy')->middleware('permission:manage_roles');
    Route::post('/{role}/toggle-status', 'toggleStatus')->name('roles.toggle-status')->middleware('permission:manage_roles');
});

// Test route for debugging
Route::post('/test-role', function() {
    return response()->json(['message' => 'Route is working']);
});


Route::get('/time-tracking', [TimeSheetController::class,'show'])->name('time-tracking')->middleware('permission:view_own_timesheet,view_all_timesheets');

Route::get('/user-management',[UserController::class,'index'])->name('user-management')->middleware('permission:view_users');
Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create_users');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit_users');
Route::post('/users/{id}/archive', [UserController::class, 'archive'])->name('users.archive')->middleware('permission:archive_users');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete_users');

Route::get('/users/list', [UserController::class, 'list'])->middleware('auth');

Route::get('/user/{user}/projects', [UserController::class, 'getProjects']);

Route::get('/project/{project}/tasks', [UserController::class, 'getTasks']);

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/test-mail', [\App\Http\Controllers\UserController::class, 'testMail']);

// Internal Tasks Management Routes (Admin only)
Route::middleware(['auth', 'permission:manage_settings'])->group(function () {
    Route::resource('internal-tasks', InternalTaskController::class);
    Route::post('/internal-tasks/{id}/toggle-status', [InternalTaskController::class, 'toggleStatus']);
    
    // Department Management Routes
    Route::get('/internal-tasks/departments', [InternalTaskController::class, 'getDepartments']);
    Route::post('/internal-tasks/departments', [InternalTaskController::class, 'storeDepartment']);
    Route::get('/internal-tasks/departments/{id}', [InternalTaskController::class, 'showDepartment']);
    Route::get('/internal-tasks/departments/{id}/edit', [InternalTaskController::class, 'editDepartment']);
    Route::put('/internal-tasks/departments/{id}', [InternalTaskController::class, 'updateDepartment']);
    Route::delete('/internal-tasks/departments/{id}', [InternalTaskController::class, 'deleteDepartment']);
    Route::post('/internal-tasks/departments/{id}/archive', [InternalTaskController::class, 'archiveDepartment']);
    Route::post('/internal-tasks/departments/{id}/assign-user', [InternalTaskController::class, 'assignUser']);
    Route::post('/internal-tasks/departments/{id}/unassign-user', [InternalTaskController::class, 'unassignUser']);
});

// Internal Tasks API for Time Tracking (All authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/internal-tasks/active', [InternalTaskController::class, 'getActiveTasks']);
});

// Department and Category Management API (Admin users with edit_settings permission)
Route::middleware(['auth', 'permission:edit_settings'])->group(function () {
    Route::get('/api/departments', [App\Http\Controllers\DepartmentController::class, 'index']);
    Route::post('/api/departments', [App\Http\Controllers\DepartmentController::class, 'store']);
    Route::put('/api/departments/{id}', [App\Http\Controllers\DepartmentController::class, 'update']);
    Route::delete('/api/departments/{id}', [App\Http\Controllers\DepartmentController::class, 'destroy']);
    
    Route::get('/api/categories', [App\Http\Controllers\CategoryController::class, 'index']);
    Route::post('/api/categories', [App\Http\Controllers\CategoryController::class, 'store']);
    Route::put('/api/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/api/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy']);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    return back()->with('error', 'Invalid credentials.');
})->name('login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Debug endpoint to check user permissions
Route::get('/debug/user', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    return response()->json([
        'user_id' => $user->id,
        'email' => $user->email,
        'role_id' => $user->role_id,
        'company_id' => $user->company_id,
        'has_edit_settings' => $user->hasPermission('edit_settings')
    ]);
})->middleware('auth');
