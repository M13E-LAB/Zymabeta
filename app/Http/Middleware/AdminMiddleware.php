<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur
        // Seul l'email spécifique est autorisé comme admin
        if ($user->email === 'mohamedanouar.essakhi@skema.edu') {
            return $next($request);
        }

        // Rediriger avec un message d'erreur si pas admin
        return redirect()->route('home')->with('error', 'Accès refusé. Droits administrateur requis.');
    }
}
