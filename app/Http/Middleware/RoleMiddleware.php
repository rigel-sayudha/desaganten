<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has the required role
        if ($user->role !== $role) {
            // If user tries to access admin area but is not admin, redirect to home
            if ($role === 'admin' && $user->role === 'user') {
                return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }
            
            // If admin tries to access user area, redirect to admin dashboard
            if ($role === 'user' && $user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini khusus untuk user biasa.');
            }
            
            // For any other unauthorized access
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
