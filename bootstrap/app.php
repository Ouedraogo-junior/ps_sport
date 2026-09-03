<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'       => \App\Http\Middleware\CheckAdmin::class,
            'abonnement'  => \App\Http\Middleware\CheckAbonnement::class,
            'no-cache'    => \App\Http\Middleware\PreventCaching::class,
        ]);

        // Un utilisateur déjà connecté qui atterrit sur une route "invités uniquement"
        // (ex: retour arrière du navigateur vers /inscription ou /connexion après
        // avoir été redirigé vers Telegram) doit retrouver son tableau de bord,
        // pas la page de connexion (comportement par défaut de Laravel).
        $middleware->redirectUsersTo(fn () => route('dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();