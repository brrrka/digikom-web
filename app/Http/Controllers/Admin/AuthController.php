<?php
// app/Http/Controllers/Admin/AuthController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Cek apakah user adalah admin
            if ($this->isAdmin($user)) {
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang di panel admin, ' . $user->name . '!');
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akses ditolak. Anda tidak memiliki izin sebagai admin.',
                ]);
            }
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    private function isAdmin($user): bool
    {
        return $user->id_roles === 1 ||
            $user->role?->roles === 'admin' ||
            $user->is_admin ?? false;
    }
}
