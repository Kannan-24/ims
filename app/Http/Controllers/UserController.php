<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all users from the database and return them.
        $users = User::all();
        return view('users.index', compact('users')); // Update this to your desired view.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view to create a new user.
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'required|string|max:255',
            'blood_group' => 'required|string|max:3',
            'state' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15',
            'doj' => 'required|date',
            'role' => 'required|string|max:255',
        ]);

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'state' => $request->state,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'doj' => $request->doj,
            'role' => $request->role,
        ]);

        // Redirect back with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Return the view with the specific user data.
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Return the edit form for the selected user.
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the incoming request.
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'required|string|max:255',
            'blood_group' => 'required|string|max:3',
            'state' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15',
            'doj' => 'required|date',
            'role' => 'required|string|max:255',
        ]);

        // Update the user with the new data.
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'state' => $request->state,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'doj' => $request->doj,
            'role' => $request->role,
        ]);

        // Redirect back with a success message.
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Delete the user.
        $user->delete();

        // Redirect back with a success message.
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
