<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use Hash;
use Mail;
use App\Mail\Register;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getProjects($id)
    {
        $user = User::find($id);
        $project_team = $user->projects;
        $projects = [];

        foreach ($project_team as $key => $value) {
            # code...
            $project = Project::with('client')->find($value->project_id);
            array_push($projects, $project);
        }

        return response()->json($projects);
    }

    public function index()
    {
        $data = User::with('userRole')->get();
        $roles = \App\Models\Role::all();

        return view('front.user-management', compact('data', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getTasks(Project $project)
    {
        $tasks = $project->tasks; // Assuming a `tasks` relationship exists in the Project model
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'hourly_rate' => 'required|numeric',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Get the role to set the legacy role field
        $role = \App\Models\Role::find($request->role_id);

        $new = new User;
        $new->name = $request->name;
        $new->email = $request->email;
        $new->role = $role->name; // Keep for backward compatibility
        // $new->role_id = $request->role_id;
        $new->hourly_rate = $request->hourly_rate;
        $new->profile_image_url = $data['profile_picture'] ?? null;
        $new->password = Hash::make('password'); // Temporary password
        $new->save();

        // Generate password reset token and URL
        $token = app('auth.password.broker')->createToken($new);
        $setPasswordUrl = url(route('password.reset', ['token' => $token, 'email' => $new->email], false));

        try {
            Mail::to($new->email)->send(new Register($new, $setPasswordUrl));
        } catch (\Exception $e) {
            // You can log the error or return it for debugging
            return back()->with('error', 'Mail sending failed: ' . $e->getMessage());
        }

        return redirect()->route('user-management')->with('success', 'User added successfully.');
    }


    /**
     * Delete the specified user and remove from all related tables.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Remove user from all related tables with user_id foreign key
            \DB::table('project_team_members')->where('user_id', $id)->delete();
            \DB::table('task_assignees')->where('user_id', $id)->delete();
            \DB::table('time_entries')->where('user_id', $id)->delete();
            \DB::table('estimated_time_entries')->where('user_id', $id)->delete();
            
            // Delete the user
            $user->delete();

            return back()->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function resources( )
    {
        $data = User::get();

        return view('front.resources',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'hourly_rate' => 'required|numeric',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Get the role to set the legacy role field
        $role = \App\Models\Role::find($request->role_id);

        $new = User::find($id);
        $new->name = $request->name;
        $new->email = $request->email;
        // $new->role = $role->name; // Keep for backward compatibility
        $new->role_id = $request->role_id;
        $new->hourly_rate = $request->hourly_rate;
        if ($request->hasFile('profile_picture')) {
            $new->profile_image_url = $data['profile_picture'];
        }
        $new->update();

        return redirect()->route('user-management')->with('success', 'User updated successfully.');
    }


    public function testMail()
    {
        try {
            // Use your own email address here
            $to = 'nman0171@gmail.com'; // Replace with your email address
            \Mail::raw('This is a test email from EasyPeasly Laravel app.', function ($message) use ($to) {
                $message->to($to)
                    ->subject('Test Email from EasyPeasly');
            });
            return 'Test email sent! Check your inbox (and spam folder).';
        } catch (\Exception $e) {
            return 'Mail sending failed: ' . $e->getMessage();
        }
    }
//  for archive functionality
public function archive($id)
{
    $user = User::findOrFail($id);
    $user->is_archived = $user->is_archived ? 0 : 1; // Toggle
    $user->save();

    return response()->json([
        'success' => true,
        'is_archived' => $user->is_archived
    ]);
}

public function getUserData($id)
{
    try {
        $user = User::with('userRole')->findOrFail($id);
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'employee',
            'role_id' => $user->role_id,
            'profile_image_url' => $user->profile_image_url,
            'profile_image_path' => $user->profile_image_path,
            'hourly_rate' => $user->hourly_rate ?? 0,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }
}


}
