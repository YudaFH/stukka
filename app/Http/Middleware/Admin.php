<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 1. Cek apakah user memiliki hak akses ke Panel Admin sama sekali
        if (!$user->hasAdminAccess()) {
            if ($user->isKaryawan()) {
                return redirect()->route('karyawan.dashboard')->with('error', 'Akun Anda tidak memiliki izin untuk mengakses Panel Admin.');
            }
            return redirect()->route('user.dashboard')->with('error', 'Akses ditolak.');
        }

        $defaultRedirect = function() use ($user) {
            if ($user->canManageEventFinance()) {
                return redirect()->route('admin.scheduled-events.index');
            }
            if ($user->canManageContent()) {
                return redirect()->route('admin.events.index');
            }
            return redirect()->route('karyawan.dashboard');
        };

        // 2. Cek izin per modul jika ditentukan
        if ($permission) {
            switch ($permission) {
                case 'content':
                    if (!$user->canManageContent()) {
                        return ($user->isFullAccess() ? redirect()->route('admin.index') : $defaultRedirect())->with('error', 'Akses ditolak: Anda tidak memiliki izin Content Management.');
                    }
                    break;

                case 'event_finance':
                    if (!$user->canManageEventFinance()) {
                        return ($user->isFullAccess() ? redirect()->route('admin.index') : $defaultRedirect())->with('error', 'Akses ditolak: Anda tidak memiliki izin Event & Finansial.');
                    }
                    break;

                case 'employees':
                    if (!$user->canManageEmployees()) {
                        return ($user->isFullAccess() ? redirect()->route('admin.index') : $defaultRedirect())->with('error', 'Akses ditolak: Menu Manajemen Karyawan hanya dapat diakses oleh Full Access Administrator.');
                    }
                    break;

                case 'attendance':
                    if (!$user->canManageAttendance()) {
                        return ($user->isFullAccess() ? redirect()->route('admin.index') : $defaultRedirect())->with('error', 'Akses ditolak: Menu Rekap Absensi hanya dapat diakses oleh Full Access Administrator.');
                    }
                    break;

                case 'full':
                    if (!$user->isFullAccess()) {
                        return $defaultRedirect()->with('error', 'Akses ditolak: Fitur ini hanya untuk Full Access Administrator.');
                    }
                    break;
            }
        }

        return $next($request);
    }
}