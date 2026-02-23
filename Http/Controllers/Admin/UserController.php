<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ✅ List all users
    public function index()
    {
        $users = User::with('division')->orderBy('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    // ✅ Show edit form
    public function edit(User $user)
    {
        $roles = User::roles(); // ['admin', 'user']
        $divisions = Division::orderBy('division_name')->get();
        return view('admin.users.edit', compact('user', 'roles', 'divisions'));
    }

    // ✅ Update user info
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(User::roles())],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->division_id = $validated['division_id'];

        // ✅ Only update password if it was provided
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', '✅ User updated successfully.');
    }

    // ✅ Delete user
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', '❌ You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', '🗑️ User deleted successfully.');
    }

    // ✅ Show create form
    public function create()
    {
        $roles = User::roles();
        $divisions = Division::orderBy('division_name')->get();
        return view('admin.users.create', compact('roles', 'divisions'));
    }

    // ✅ Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'role' => ['required', Rule::in(User::roles())],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', '✅ User created successfully.');
    }
}
