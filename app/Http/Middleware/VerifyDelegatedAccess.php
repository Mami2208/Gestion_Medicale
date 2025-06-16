<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\DelegationAcces;
use App\Models\ActivityLog;

class VerifyDelegatedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $patientId = $request->route('patient');
        
        // Si c'est un médecin ou un administrateur, accès direct
        if ($user->role === 'MEDECIN' || $user->role === 'ADMIN') {
            return $next($request);
        }
        
        // Si c'est un infirmier, vérifier s'il a une délégation active
        if ($user->role === 'INFIRMIER') {
            $hasDelegation = DelegationAcces::where('infirmier_id', $user->id)
                ->where('patient_id', $patientId)
                ->where('statut', 'active')
                ->where('date_debut', '<=', now())
                ->where('date_fin', '>=', now())
                ->exists();
                
            if ($hasDelegation) {
                // Enregistrer cette action dans les logs
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'view',
                    'type' => 'delegation',
                    'description' => "Accès au dossier du patient #{$patientId} via délégation",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                
                return $next($request);
            }
        }
        
        // Accès refusé
        return redirect()->route('infirmier.dashboard')
            ->with('error', 'Vous n\'avez pas accès à ce dossier patient');
    }
}
