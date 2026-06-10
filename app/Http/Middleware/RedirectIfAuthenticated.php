<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Si l'utilisateur est déjà connecté (session active après fermeture d'onglet),
     * on le redirige vers le bon dashboard selon son rôle.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // ✅ Redirection selon le rôle
                return redirect($this->redirectTo($user));
            }
        }

        return $next($request);
    }

    /**
     * Retourne l'URL de redirection selon le rôle de l'utilisateur.
     */
    protected function redirectTo($user)
    {
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role === 'agent') {
            return route('agent.dashboard');
        } else {
            return route('dashboard');
        }
    }
}