<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function handle(Request $request, Closure $next): Response
{
    $role = auth()->user()->role ?? 'null';
    if (!auth()->check() || auth()->user()->role !== 'agent') {
        return response("Rôle actuel : $role - Accès refusé", 403);
    }
    return $next($request);
}
}