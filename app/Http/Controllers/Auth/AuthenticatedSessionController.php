<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;

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
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil redirect & date dari hidden input (form login)
        $redirect = $request->input('redirect'); // contoh: /services
        $date = $request->input('date');         // contoh: 2026-02-14

        // Jika ada redirect dari flow booking, hormati tapi AMANKAN
        if (!empty($redirect)) {
            // 1) Wajib berupa path internal (mulai dengan '/'), bukan URL penuh
            if (!Str::startsWith($redirect, '/')) {
                $redirect = '/';
            }

            // 2) Whitelist route yang boleh
            $allowed = [
                '/booking/form',
                // tambahkan path lain jika nanti butuh
            ];

            if (!in_array($redirect, $allowed, true)) {
                $redirect = '/';
            }

            // 3) Bangun URL final + date jika ada
            $url = $redirect;
            if (!empty($date)) {
                $url .= '?date=' . urlencode($date);
            }

            return redirect()->to($url);
        }

        // Bersihkan url.intended jika mengarah ke /dashboard lama yang tidak ada
        $intended = $request->session()->get('url.intended');
        if ($intended && (str_contains($intended, '/dashboard') && !str_contains($intended, '/my-dashboard') && !str_contains($intended, '/karyawan/dashboard'))) {
            $request->session()->forget('url.intended');
        }

        // Role-based dashboard redirection (Hanya Admin dan Karyawan)
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.index'));
        }

        if ($user->isKaryawan()) {
            return redirect()->intended(route('karyawan.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
