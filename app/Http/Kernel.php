<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Les middlewares globaux appliqués à toutes les requêtes.
     *
     * Ces middlewares sont exécutés pour chaque requête entrante.
     *
     * @var array
     */
    protected $middleware = [
        // Middleware pour vérifier si l'application est en maintenance
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Middleware pour valider la taille de la requête
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Middleware pour nettoyer les données de la requête
        // \App\Http\Middleware\TrimStrings::class,

        // Middleware pour convertir les chaînes vides en null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        // Middleware pour corriger les en-têtes HTTP
        // \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * Les groupes de middlewares applicables aux routes.
     *
     * Ces groupes peuvent être appliqués à des routes spécifiques ou à des groupes de routes.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Middleware pour crypter les cookies
            // \App\Http\Middleware\EncryptCookies::class,

            // Middleware pour ajouter des cookies à la réponse
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // Middleware pour démarrer la session
            \Illuminate\Session\Middleware\StartSession::class,

            // Middleware pour partager les erreurs de validation avec les vues
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // Middleware pour vérifier les tokens CSRF
            // \App\Http\Middleware\VerifyCsrfToken::class,

            // Middleware pour lier la requête à l'application
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Middleware pour limiter les requêtes API
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Les middlewares de route individuels.
     *
     * Ces middlewares peuvent être appliqués à des routes spécifiques.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Middleware pour l'authentification
        // 'auth' => \App\Http\Middleware\Authenticate::class,

        // Middleware pour rediriger les utilisateurs authentifiés
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        // Middleware pour vérifier les tokens CSRF
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,

        // Middleware pour vérifier les permissions
        'can' => \Illuminate\Auth\Middleware\Authorize::class,

        // Middleware pour les invités (utilisateurs non authentifiés)
        // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // Middleware pour limiter les requêtes
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        // Middleware pour vérifier les signatures de route
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,

        // Middleware pour vérifier si l'utilisateur est un administrateur principal
        'admin' => \App\Http\Middleware\IsAdminPrincipal::class,
    ];

    /**
     * Les middlewares de priorité.
     *
     * Ces middlewares sont triés par ordre de priorité.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        // \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
