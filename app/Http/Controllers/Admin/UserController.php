<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('id_roles', $request->role_id);
        }

        // Filter by asisten status
        if ($request->filled('is_asisten')) {
            $query->where('is_asisten', $request->is_asisten === '1');
        }

        // Filter by email verification
        if ($request->filled('email_verified')) {
            if ($request->email_verified === '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('roles')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nim' => 'required|string|max:20|unique:users',
            'no_telp' => 'nullable|string|max:15',
            'id_roles' => 'required|exists:roles,id',
            'is_asisten' => 'boolean',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_asisten'] = $request->boolean('is_asisten');
        $validated['email_verified_at'] = now(); // Auto verify for admin created users

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat!');
    }

    public function show(User $user)
    {
        $user->load(['role', 'peminjaman.detailPeminjaman.inventaris', 'artikel']);

        // Get user statistics
        $stats = [
            'total_peminjaman' => $user->peminjaman()->count(),
            'active_peminjaman' => $user->peminjaman()->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'overdue_peminjaman' => $user->peminjaman()->where('status', 'jatuh tenggat')->count(),
            'total_artikel' => $user->artikel()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('roles')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nim' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'no_telp' => 'nullable|string|max:15',
            'id_roles' => 'required|exists:roles,id',
            'is_asisten' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $validated['is_asisten'] = $request->boolean('is_asisten');

        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        // Check if user has related data
        if ($user->peminjaman()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus user yang memiliki data peminjaman!');
        }

        if ($user->artikel()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus user yang memiliki artikel!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }

    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terverifikasi'
            ]);
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diverifikasi'
        ]);
    }
}
