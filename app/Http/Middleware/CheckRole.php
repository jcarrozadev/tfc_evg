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
            return response()->view('errors.error403', [], 403);
        }
        
        $userRole = $user->role->name ?? null; 

        if (!in_array($userRole, $roles)) {
            return response()->view('errors.error403', [], 403);
        }

        if (!$request->route()) {
            return response()->view('errors.error404', [], 404);
        }

        return $next($request);
    }
}