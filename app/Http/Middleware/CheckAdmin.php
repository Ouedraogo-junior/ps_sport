<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie que l'utilisateur est connecté et a le rôle admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        // Vérifie que le compte admin n'est pas bloqué
        if ($request->user()->isBloque()) {
            abort(403, 'Votre compte a été suspendu.');
        }

        return $next($request);
    }
}