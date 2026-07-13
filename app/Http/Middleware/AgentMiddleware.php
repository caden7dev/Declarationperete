<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté possède le rôle agent.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier d'abord si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifier le rôle
        if (auth()->user()->role !== 'agent') {
            return response()->view('errors.403', [
                'message' => 'Accès réservé aux agents.'
            ], 403);
        }

        return $next($request);
    }
}