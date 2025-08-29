<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Country;
use App\Models\TaskList;
use App\Models\Role;
use App\Models\Permission;

class SettingController extends Controller
{
    public function index()
    {
        $data = Setting::first();
        
        // Get all countries with their task lists
        $countries = Country::with(['taskLists' => function($query) {
            $query->orderBy('position');
        }])->get();
        
        // Format data for the frontend
        $taskPresets = $countries->map(function($country) {
            return [
                'title' => $country->name, // Use country name as title
                'tasks' => $country->taskLists->map(function($task) {
                    return [
                        'name' => $task->name,
                        'position' => $task->position
                    ];
                })->toArray()
            ];
        })->toArray();
        
        // Get roles and permissions for role management
        $roles = Role::with('permissions')->get();
        $permissions = Permission::active()->orderBy('group', 'asc')->orderBy('display_name', 'asc')->get();
        $permissionGroups = $permissions->groupBy('group');
        
        // Add task_presets to the data object if it exists
        if ($data) {
            $data->task_presets = $taskPresets;
        } else {
            $data = (object) ['task_presets' => $taskPresets];
        }

        return view('front.settings', compact('data', 'roles', 'permissions', 'permissionGroups'));
    }

    public function update(Request $request)
    {
        $settings = Setting::firstOrNew([]);
        
        // Update new fields
        $settings->time_format = $request->input('time_format');
        $settings->date_format = $request->input('date_format');
        $settings->currency = $request->input('currency');
        $settings->working_hour = $request->input('working_hour');
        
        // Handle task presets
        if ($request->filled('task_presets')) {
            $taskPresets = json_decode($request->input('task_presets'), true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                // Get all existing countries that have task lists
                $existingCountries = Country::with('taskLists')->get();
                
                // Get country names from the submitted presets (now using title as country name)
                $submittedCountryNames = collect($taskPresets)->pluck('title')->filter()->toArray();
                
                // Delete countries and their task lists that are no longer in the submitted data
                foreach ($existingCountries as $existingCountry) {
                    if (!in_array($existingCountry->name, $submittedCountryNames)) {
                        // Delete all task lists for this country
                        TaskList::where('country_id', $existingCountry->id)->delete();
                        // Delete the country itself
                        $existingCountry->delete();
                    }
                }
                
                // Process each preset
                foreach ($taskPresets as $preset) {
                    if (!empty($preset['title'])) {
                        // Find or create country using title as country name
                        $country = Country::firstOrCreate(['name' => $preset['title']]);
                        
                        // Delete existing task lists for this country
                        TaskList::where('country_id', $country->id)->delete();
                        
                        // Create new task lists
                        if (isset($preset['tasks']) && is_array($preset['tasks'])) {
                            foreach ($preset['tasks'] as $task) {
                                if (!empty($task['name'])) {
                                    TaskList::create([
                                        'name' => $task['name'],
                                        'country_id' => $country->id,
                                        'position' => $task['position'] ?? 1
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo = $path;
        }

        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
