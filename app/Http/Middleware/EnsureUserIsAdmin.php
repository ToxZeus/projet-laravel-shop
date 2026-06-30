<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('info', 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
