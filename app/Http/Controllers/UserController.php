<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Hash;
use Mail;
use App\Mail\Register;

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
            $project = Project::find($value->project_id);

            array_push($projects,$project);
        }

        return response()->json($projects);
    }

    public function index()
    {
        $data = User::orderBy('name')->get();

        return view('front.user-management', compact('data'));
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
            'role' => 'required|string',
            'hourly_rate' => 'required|numeric',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $new = new User;
        $new->name = $request->name;
        $new->email = $request->email;
        $new->role = $request->role;
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'role' => 'required|string',
            'hourly_rate' => 'required|numeric',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }



        $new = User::find($id);
        $new->name = $request->name;
        $new->email = $request->email;
        $new->role = $request->role;
        $new->hourly_rate = $request->hourly_rate;
        if ($request->hasFile('profile_picture')) {
            $new->profile_image_url = $data['profile_picture'];
        }
        $new->update();

        return redirect()->route('user-management')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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


}
