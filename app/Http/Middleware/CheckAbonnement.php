<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbonnement
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Vérifie que l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifie que le compte n'est pas bloqué
        if ($user->isBloque()) {
            abort(403, 'Votre compte a été suspendu. Contactez-nous via WhatsApp.');
        }

        // Vérifie qu'un abonnement actif existe
        if (!$user->abonnementActif) {
            return redirect()->route('dashboard')
                ->with('warning', 'Vous n\'avez pas d\'abonnement actif. Veuillez renouveler votre abonnement.');
        }

        return $next($request);
    }
}