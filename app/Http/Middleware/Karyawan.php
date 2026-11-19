<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Karyawan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Semua karyawan dan akun dengan akses admin berhak mengakses portal presensi & scan wajah
            if ($user->usertype === 'karyawan' || $user->usertype === 'admin' || $user->hasAdminAccess()) {
                return $next($request);
            }
        }

        return redirect()->route('home')->with('error', 'Akses khusus karyawan!');
    }
}
