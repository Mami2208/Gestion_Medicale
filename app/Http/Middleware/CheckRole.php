<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Activer le mode débogage
        \Log::info('\n\n=== DÉBUT MIDDLEWARE CHECKROLE ===');
        \Log::info('URL: ' . $request->fullUrl());
        \Log::info('Méthode: ' . $request->method());
        \Log::info('Rôles requis: ' . json_encode($roles));
        \Log::info('Session ID: ' . session()->getId());
        \Log::info('Session data: ' . json_encode(session()->all()));
        \Log::info('Headers: ' . json_encode($request->headers->all()));
        
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            \Log::warning('Accès refusé : utilisateur non connecté');
            return redirect('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();
        $userRole = $user->role;
        
        // Déboguer l'information sur le rôle
        \Log::info('=== INFORMATIONS UTILISATEUR ===');
        \Log::info('Utilisateur connecté : ' . $user->email);
        \Log::info('ID utilisateur : ' . $user->id);
        \Log::info('Rôle utilisateur : ' . $userRole);
        \Log::info('Type du rôle : ' . gettype($userRole));
        \Log::info('Longueur du rôle : ' . strlen($userRole));
        \Log::info('Rôle en hexadécimal : ' . bin2hex($userRole));
        \Log::info('Rôles autorisés : ' . json_encode($roles));
        \Log::info('Tous les attributs : ' . json_encode($user->toArray()));
        \Log::info('Session ID: ' . session()->getId());
        \Log::info('Session data: ' . json_encode(session()->all()));
        
        // Nettoyer et mettre en majuscules les rôles pour comparaison
        $userRoleUpper = strtoupper(trim($userRole));
        $rolesUpper = array_map('strtoupper', array_map('trim', $roles));
        
        // Vérifier si l'utilisateur a le rôle requis
        if (in_array($userRoleUpper, $rolesUpper)) {
            \Log::info('=== ACCÈS AUTORISÉ ===');
            \Log::info('Utilisateur: ' . $user->email);
            \Log::info('Rôle: ' . $userRoleUpper);
            \Log::info('URL: ' . $request->fullUrl());
            \Log::info('=== FIN ACCÈS AUTORISÉ ===');
            \Log::info('');
            
            return $next($request);
        }

        \Log::warning('Accès refusé : Rôle ' . $userRoleUpper . ' non autorisé pour cette route. Rôles attendus : ' . implode(', ', $rolesUpper));
        return redirect('/')->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}
