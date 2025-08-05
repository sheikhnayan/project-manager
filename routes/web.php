<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return view('front.index');
})->name('index');

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

Route::get('/time-tracking/{user}/{dateRange}',[TimeSheetController::class, 'index'])->name('time-tracking.index');
Route::post('/projects/hide-user', [ProjectController::class, 'hideUser'])->name('projects.hide-user');
Route::post('/projects/change-budget', [ProjectController::class, 'change_budget'])->name('projects.change-budget');
Route::post('/projects/check-dates', [ProjectController::class, 'check_dates'])->name('projects.check-dates');
Route::post('/projects/save-dates', [ProjectController::class, 'save_dates'])->name('projects.save-dates');

Route::get('/estimated-time-tracking/{id}/get',[TimeSheetController::class, 'index_estimate'])->name('estimated-time-tracking.index');

Route::get('/estimated-time-tracking/get',[TimeSheetController::class, 'index_estimate_all'])->name('estimated-time-tracking.indexall');

Route::get('/estimated-time-tracking-weekly/{id}/get',[TimeSheetController::class, 'index_estimate_weekly'])->name('estimated-time-tracking-weekly.index');


Route::post('/time-tracking/save', [TimeSheetController::class, 'store'])->name('time-tracking.store');

Route::post('/estimated-time-tracking/save', [TimeSheetController::class, 'store_estimated'])->name('estimated-time-tracking.store');

Route::post('/estimated-time-tracking/weekly/{id}/save', [TimeSheetController::class, 'store_estimated_weekly'])->name('estimated-time-tracking.store');

Route::get('/time-tracking/tasks/{projectId}', [TimeSheetController::class, 'getTasksByProject']);

Route::controller(ProjectController::class)->prefix('/projects')->group(function () {
    Route::get('/', 'index')->name('projects');
    Route::get('/weekly', 'index_weekly')->name('projects.weekly');
    Route::get('/weekly/{id}', 'show_weekly')->name('projects-weekly');
    Route::get('/create', 'create')->name('projects.create');
    Route::get('/edits/{id}', 'edits')->name('projects.edits');
    Route::get('/{id}', 'show')->name('projects.show');
    Route::get('/reload-data/{id}', 'reload')->name('projects.reload');
    Route::get('/{id}/edit', 'edit')->name('projects.edit');
    Route::post('/', 'store')->name('projects.store');
    Route::post('/update/{id}', 'update')->name('projects.update');
    Route::post('/{id}', 'store_task')->name('projects.task.store');
    Route::post('/update/{id}', 'update_task')->name('projects.task.update');
    Route::post('/member-store/{id}', 'store_member')->name('projects.member.store');
    Route::put('/{id}', 'update')->name('projects.update');
    Route::delete('/{id}', 'destroy')->name('projects.destroy');
    Route::get('/{project}/archive', 'archive')->name('projects.archive');
});

Route::controller(ClientController::class)->prefix('/client-management')->group(function () {
    Route::get('/', 'index')->name('client');
    Route::post('/{id}/archive', 'archive')->name('client.archive');
    Route::get('/create', 'create')->name('client.create');
    Route::get('/{id}', 'show')->name('client.show');
    Route::get('/{id}/edit', 'edit')->name('client.edit');
    Route::post('/', 'store')->name('client.store');
    Route::put('/{id}', 'update')->name('client.update');
    Route::delete('/{id}', 'destroy')->name('client.destroy');
});

Route::get('/projects_two', function () {
    return view('front.projects_second');
})->name('projects_two');

Route::get('/reports', [ReportController::class,'index'])->name('reports');

Route::get('/reports/{id}', [ReportController::class,'index_project'])->name('report');

Route::get('/resources', [UserController::class, 'resources'])->name('resources');

Route::get('/settings', function () {
    return view('front.settings');
})->name('settings');


Route::get('/time-tracking', [TimeSheetController::class,'show'])->name('time-tracking');

Route::get('/user-management',[UserController::class,'index'])->name('user-management');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::post('/users/{id}/archive', [UserController::class, 'archive'])->name('users.archive');

Route::get('/user/{user}/projects', [UserController::class, 'getProjects']);

Route::get('/project/{project}/tasks', [UserController::class, 'getTasks']);

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/test-mail', [\App\Http\Controllers\UserController::class, 'testMail']);

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }
    return back()->with('error', 'Invalid credentials.');
})->name('login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
