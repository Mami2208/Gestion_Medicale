<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'ADMIN') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'MEDECIN') {
                return redirect()->route('medecin.dashboard');
            }
            if ($user->role === 'SECRETAIRE MEDICAL') {
                return redirect()->route('secretaire.dashboard');
            }
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ]);
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}