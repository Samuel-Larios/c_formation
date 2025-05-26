<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Vérifier si le site_id dans la session correspond à celui de la requête
        $siteId = session('site_id');

        // Si le site_id n'est pas trouvé dans la session ou est invalide, rediriger
        if (!$siteId) {
            return redirect()->route('login');
        }

        return $next($request);
    }

}
