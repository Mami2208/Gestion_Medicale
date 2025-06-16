<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirection basée sur le rôle
                switch (strtoupper(trim($user->role))) {
                    case 'ADMIN':
                        return redirect('/admin/dashboard');
                    case 'MEDECIN':
                        return redirect('/medecin/dashboard');
                    case 'INFIRMIER':
                        return redirect('/infirmier/dashboard');
                    case 'SECRETAIRE':
                        return redirect('/secretaire/dashboard');
                    case 'PATIENT':
                        return redirect('/patient/dashboard');
                    default:
                        return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}
