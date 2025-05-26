<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminPrincipal
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est un administrateur principal
        if (Auth::check() && Auth::user()->role === 'admin_principal') {
            return $next($request);
        }

        // Rediriger les utilisateurs non autorisés
        return redirect()->route('admin.dashboard')->with('error', 'You do not have access to this page.');
    }
}
