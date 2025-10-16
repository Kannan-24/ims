<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $query = User::query();

        if ($search = request('search')) {
            $query->where('employee_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        }

        $users = $query->paginate(20);

        return view('ims.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextEmployeeId = $this->getNextEmployeeId();
        return view('ims.users.create', compact('nextEmployeeId'));
    }

    /**
     * Get the next employee ID
     */
    public function getNextEmployeeId()
    {
        $lastUser = User::latest('created_at')->first();
        
        if (!$lastUser || !$lastUser->employee_id) {
            return 'SKME001';
        }
        
        // Extract number from employee_id (format: SKME001, SKME002, etc.)
        $lastEmployeeId = $lastUser->employee_id;
        $lastNumber = intval(substr($lastEmployeeId, 4));
        $nextNumber = $lastNumber + 1;
        
        return 'SKME' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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
            'role' => 'required|string|max:255',
        ]);

        // Auto-generate employee ID
        $newEmployeeId = $this->getNextEmployeeId();

        // Generate a secure temporary password meeting policy requirements
        $defaultPassword = $this->generateTemporaryPassword();

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
            'role' => $request->role,
            'must_change_password' => true,
            'password_expires_at' => now()->addDays(config('password_policy.expiry_days')),
            'last_password_changed_at' => null,
        ]);

        Mail::to($user->email)->send(new UserCreatedMail($user, $defaultPassword));

        return redirect()->route('users.show', $user)->with('success', 'User created successfully with Employee ID: ' . $newEmployeeId . '. Email sent to user.');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Get navigation data
        $previousUser = User::where('id', '<', $user->id)->orderBy('id', 'desc')->first();
        $nextUser = User::where('id', '>', $user->id)->orderBy('id', 'asc')->first();
        
        return view('ims.users.show', compact('user', 'previousUser', 'nextUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('ims.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
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
            'role' => $request->role,
        ]);

        return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    /**
     * Create a random password matching configured policy.
     */
    protected function generateTemporaryPassword(int $length = null): string
    {
        $config = config('password_policy');
        $length = $length ?? max(12, $config['min_length'] ?? 12);
        $sets = [
            'upper' => 'ABCDEFGHJKLMNPQRSTUVWXYZ',
            'lower' => 'abcdefghjkmnpqrstuvwxyz',
            'digits' => '23456789',
            'symbols' => '!@#$%^&*()-_=+[]{}',
        ];
        $password = '';
        if (($config['require_uppercase'] ?? true)) $password .= $sets['upper'][random_int(0, strlen($sets['upper'])-1)];
        if (($config['require_lowercase'] ?? true)) $password .= $sets['lower'][random_int(0, strlen($sets['lower'])-1)];
        if (($config['require_number'] ?? true)) $password .= $sets['digits'][random_int(0, strlen($sets['digits'])-1)];
        if (($config['require_symbol'] ?? true)) $password .= $sets['symbols'][random_int(0, strlen($sets['symbols'])-1)];
        $all = '';
        foreach ($sets as $set) { $all .= $set; }
        while (strlen($password) < $length) {
            $password .= $all[random_int(0, strlen($all)-1)];
        }
        return str_shuffle($password);
    }
}
