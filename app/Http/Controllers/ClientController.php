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
        $request->validate([
            'name' => 'required|string|max:255',
            'custom_id' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:255',
        ]);

        $add = new Client;
        $add->name = $request->name;
        $add->custom_id = $request->custom_id;
        $add->contact_person = $request->contact_person;
        $add->email = $request->email;
        $add->phone = $request->phone;
        $add->address = $request->address;
        $add->tax_number = $request->tax_number;
        $add->save();

        return redirect()->route('client')->with('success', 'Client created successfully.');
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
            'custom_id' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:255',
        ]);

        $client = Client::findOrFail($id);
        $client->name = $request->name;
        $client->custom_id = $request->custom_id;
        $client->contact_person = $request->contact_person;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->tax_number = $request->tax_number;
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
