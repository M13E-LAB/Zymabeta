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
        // Pour l'instant, on considère les premiers utilisateurs comme admins
        // ou on peut ajouter un champ 'is_admin' à la table users
        if ($user->id <= 3 || $user->email === 'admin@zyma.app' || $user->points >= 1000) {
            return $next($request);
        }

        // Rediriger avec un message d'erreur si pas admin
        return redirect()->route('home')->with('error', 'Accès refusé. Droits administrateur requis.');
    }
}
