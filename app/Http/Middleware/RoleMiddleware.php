<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (Auth::user()->role !== $role) {
            // PERBAIKAN DI SINI:
            // Jika peran tidak cocok, logout pengguna saat ini.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect ke halaman login dengan pesan error.
            return redirect('login')->with('error', 'Anda tidak memiliki hak akses. Silakan login kembali dengan akun yang benar.');
        }

        // Jika peran cocok, izinkan pengguna untuk melanjutkan.
        return $next($request);
    }
}

