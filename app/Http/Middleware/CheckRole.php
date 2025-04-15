<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();  
        if (!$user || !$user->role) {
            abort(403, 'No tienes permiso para acceder a esta ruta');
        }

        
        $userRole = $user->role->name ?? null; 

        if (!in_array($userRole, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta ruta');
        }

        return $next($request);
    }
}