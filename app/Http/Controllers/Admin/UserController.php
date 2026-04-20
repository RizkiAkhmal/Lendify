<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->oldest()->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'in:admin,petugas,peminjam'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);

        event(new Registered($user));

        \App\Models\LogAktivitas::catat(auth()->id(), 'CREATE', 'users', null, $user->toArray());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,petugas,peminjam'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->fill($request->only(['name', 'email', 'role', 'phone', 'address']));

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        \App\Models\LogAktivitas::catat(auth()->id(), 'UPDATE', 'users', null, $user->toArray());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $oldData = $user->toArray();
        $user->delete();
        \App\Models\LogAktivitas::catat(auth()->id(), 'DELETE', 'users', $oldData, null);
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function export()
    {
        $users = User::all();
        $filename = "users-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Address', 'Created At']);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->status,
                $user->phone,
                $user->address,
                $user->created_at
            ]);
        }

        fseek($handle, 0);
        return response()->stream(
            function () use ($handle) {
                fpassthru($handle);
            },
            200,
            [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=\"$filename\"",
            ]
        );
    }
}
