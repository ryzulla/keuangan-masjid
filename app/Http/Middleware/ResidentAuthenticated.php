<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentAuthenticated
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::guard('resident')->check()) {
            return redirect()->route('penghuni.login');
        }

        return $next($request);
    }
}
