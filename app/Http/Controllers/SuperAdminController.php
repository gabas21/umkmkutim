<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $adminUsers = User::whereIn('role', ['admin', 'superadmin'])->count();
        $superAdminUsers = User::where('role', 'superadmin')->count();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'adminUsers',
            'superAdminUsers'
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);

        return view('superadmin.users', compact('users'));
    }

    public function admins()
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->latest()->paginate(20);

        return view('superadmin.admins', compact('admins'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,superadmin',
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== 'superadmin') {
            return back()->with('error', 'Anda tidak dapat menurunkan role akun Anda sendiri.');
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        ActivityLog::record(
            'user_management',
            'role_update',
            "Mengubah role pengguna {$user->name} ({$user->email}) menjadi {$validated['role']}.",
            Auth::id()
        );

        return back()->with('success', "Role pengguna {$user->name} berhasil diubah menjadi {$validated['role']}.");
    }
}
