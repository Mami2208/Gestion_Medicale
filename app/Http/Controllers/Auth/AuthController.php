<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = auth()->user();
            
            // Redirection en fonction du rôle
            switch ($user->role) {
                case 'ADMIN':
                    return redirect()->route('admin.dashboard');
                case 'MEDECIN':
                    return redirect()->route('medecin.dashboard');
                case 'INFIRMIER':
                    return redirect()->route('infirmier.dashboard');
                case 'SECRETAIRE':
                    return redirect()->route('secretaire.dashboard');
                case 'PATIENT':
                    return redirect()->route('patient.dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Déconnecte l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Crée un administrateur par défaut.
     * Cette méthode est destinée à être utilisée une seule fois pour créer le premier administrateur.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDefaultAdmin()
    {
        // Vérifier si un administrateur existe déjà
        if (Utilisateur::where('role', 'ADMIN')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Un administrateur existe déjà dans le système.'
            ], 400);
        }

        // Créer l'administrateur par défaut
        $admin = Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'Admin',
            'email' => 'admin@example.com',
            'mot_de_passe' => Hash::make('Admin@123'),
            'role' => 'ADMIN',
            'telephone' => '0000000000',
            'adresse' => 'Adresse admin',
            'date_naissance' => '1990-01-01',
            'statut' => 'ACTIF'
        ]);

        if ($admin) {
            return response()->json([
                'success' => true,
                'message' => 'Administrateur créé avec succès!',
                'credentials' => [
                    'email' => 'admin@example.com',
                    'password' => 'Admin@123'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la création de l\'administrateur.'
        ], 500);
    }
}
