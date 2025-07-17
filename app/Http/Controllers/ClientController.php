<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = client::all();

        return view('front.client-management', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function archive(string $id)
    {
        $client = Client::findOrFail($id);

        // Toggle the archive status
        $client->is_archived = !$client->is_archived;
        $client->save();

        $status = $client->is_archived ? 'archived' : 'unarchived';
        return redirect()->route('client')->with('success', "Client successfully {$status}.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $add = new Client;
        $add->name = $request->name;
        $add->save();

        return redirect()->route('client');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $client = Client::findOrFail($id);
        $client->name = $request->name;
        $client->save();

        return redirect()->route('client')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
