<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $medecins = Utilisateur::where('role', 'MEDECIN')->get();
        $secretaires = Utilisateur::where('role', 'SECRETAIRE')->get();

        return view('admin.dashboard', [
            'totalMedecins' => $medecins->count(),
            'totalSecretaires' => $secretaires->count(),
            'medecins' => $medecins,
            'secretaires' => $secretaires,
        ]);
    }

    public function indexSecretaires()
    {
        $secretaires = Utilisateur::where('role', 'SECRETAIRE')->get();
        return view('admin.secretaires.index', compact('secretaires'));
    }

    public function createSecretaire()
    {
        return view('admin.secretaires.create', [
            'specialites' => config('specialites.medicales')
        ]);
    }

    public function storeSecretaire(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
        ]);

        $defaultPassword = 'secret1234';

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => bcrypt($defaultPassword),
            'role' => 'SECRETAIRE MEDICAL',
        ]);

        return redirect()->route('admin.secretaires.index')->with('success', 'Secrétaire créé avec succès. Le mot de passe par défaut est "secret1234".');
    }
}
