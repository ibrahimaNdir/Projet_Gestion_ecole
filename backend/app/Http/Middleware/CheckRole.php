<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Gérer une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // L'utilisateur n'est pas authentifié
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Récupère les rôles de l'utilisateur. Assurez-vous que votre modèle User a une relation avec les rôles
        $userRoles = Auth::user()->role()->pluck('nom_roles')->toArray();

        // Vérifie si l'un des rôles requis est présent dans les rôles de l'utilisateur
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Accès non autorisé : rôle insuffisant.'], 403);
    }
}
