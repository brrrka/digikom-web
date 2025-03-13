<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Tambahkan flash message untuk login sukses
            session()->flash('success', 'Login berhasil!');

            if (Auth::user()->id_roles == 1) {
                return redirect('/admin');
            }

            return redirect('/');
        } catch (\Exception $e) {
            // Flash message untuk login gagal
            session()->flash('error', 'Email atau password salah!');
            return back()->withInput($request->only('email'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Tambahkan flash message untuk logout sukses
        session()->flash('info', 'Anda telah keluar dari sistem');

        return redirect('/');
    }
}
