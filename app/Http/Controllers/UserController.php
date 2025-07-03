<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all users from the database with optional search filtering.
        $query = User::query();

        if ($search = request('search')) {
            $query->where('employee_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        }

        $users = $query->get();

        return view('ims/users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all roles for the dropdown
        $roles = Role::all();
        return view('ims/users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            'role_id' => 'required|exists:roles,id',
        ]);

        $lastUser = User::latest('id')->first();
        $lastEmployeeId = $lastUser ? intval(substr($lastUser->employee_id, 4)) : 0;
        $newEmployeeId = 'SKME' . str_pad($lastEmployeeId + 1, 3, '0', STR_PAD_LEFT);

        $defaultPassword = 'SKM@123';

        $user = User::create([
            'employee_id' => $newEmployeeId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($defaultPassword),
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'state' => $request->state,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'doj' => $request->doj,
            'role' => $request->role ?? 'Employee', // Keep for backward compatibility
            'must_change_password' => true,
        ]);

        // Assign role using RBAC system
        $role = Role::find($request->role_id);
        if ($role) {
            $user->assignRole($role);
        }

        // Send email to the new user
        Mail::to($user->email)->send(new UserCreatedMail($user, $defaultPassword));

        return redirect()->route('users.index')->with('success', 'User created successfully and email sent.');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Return the view with the specific user data.
        return view('ims/users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Get all roles for the dropdown
        $roles = Role::all();
        return view('ims/users.edit', compact('user', 'roles'));
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
            'role_id' => 'required|exists:roles,id',
        ]);

        // Update the user with the new data.
        $user->update([
            'employee_id' => $user->employee_id, // Keep the existing employee ID
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
            'role' => $request->role ?? 'Employee', // Keep for backward compatibility
        ]);

        // Update role using RBAC system
        $role = Role::find($request->role_id);
        if ($role) {
            // Remove all current roles and assign the new one
            $user->roles()->detach();
            $user->assignRole($role);
        }

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
