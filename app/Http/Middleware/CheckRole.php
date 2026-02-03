<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Vérifie les rôles de l'utilisateur.
     *
     * Usage dans routes/web.php :
     * ->middleware('checkrole:super_admin')                    // pour super admin uniquement
     * ->middleware('checkrole:super_admin,admin_entreprise')   // pour super admin OU admin entreprise
     * ->middleware('checkrole:collaborateur')                  // pour tous les collaborateurs
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Accès interdit');
        }

        // Super Admin a accès à tout
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Si l'utilisateur est un collaborateur, vérifier son rôle spécifique
        if ($user->role === 'collaborateur') {
            $collab = $user->collaborateurs()->first();

            if (!$collab) {
                abort(403, 'Accès interdit - Aucun profil collaborateur trouvé');
            }

            // Vérifie si le rôle du collaborateur est dans la liste autorisée
            // ou si 'collaborateur' est dans la liste (accès pour tous les collaborateurs)
            if (in_array($collab->role, $roles) || in_array('collaborateur', $roles)) {
                return $next($request);
            }

            // Vérifie aussi si admin_entreprise est autorisé
            if ($collab->role === 'admin_entreprise' && in_array('admin_entreprise', $roles)) {
                return $next($request);
            }

            abort(403, 'Accès interdit - Rôle insuffisant');
        }

        // Vérifie le rôle global pour les autres types d'utilisateurs
        if (!in_array($user->role, $roles)) {
            abort(403, 'Accès interdit');
        }

        return $next($request);
    }
}
