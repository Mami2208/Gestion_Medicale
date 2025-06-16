<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class PasswordController extends Controller
{
    /**
     * Affiche la page de changement de mot de passe
     */
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Traite la demande de changement de mot de passe
     */
    public function change(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        // Mise à jour du mot de passe
        $user->mot_de_passe = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->force_password_change = false;
        $user->save();

        // Journalisation du changement de mot de passe
        ActivityLog::create([
            'type' => 'security',
            'user_id' => $user->id,
            'action' => 'password_changed',
            'description' => 'Mot de passe changé avec succès',
            'properties' => json_encode([
                'user_id' => $user->id,
                'user_email' => $user->email
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->intended('/')->with('status', 'Votre mot de passe a été changé avec succès');
    }
}
