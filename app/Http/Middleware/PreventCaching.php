<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCaching
{
    /**
     * Empêche le navigateur de restaurer la page depuis son cache mémoire
     * (bfcache) lors d'un retour arrière. Sans ça, un utilisateur qui vient
     * d'être authentifié peut revoir une version de /inscription ou /connexion
     * générée AVANT sa connexion, car le navigateur ne recontacte pas le
     * serveur — la redirection définie dans bootstrap/app.php (redirectUsersTo)
     * n'a alors jamais l'occasion de s'exécuter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}