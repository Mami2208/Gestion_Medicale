<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Hopital;
use App\Models\LogAccess;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $medecins = Utilisateur::where('role', 'MEDECIN')->get();
        $secretaires = Utilisateur::where('role', 'SECRETAIRE')->get();
        $totalHopitals = Hopital::count();
        $totalLogs = LogAccess::count();

        return view('admin.dashboard', [
            'totalMedecins' => $medecins->count(),
            'totalSecretaires' => $secretaires->count(),
            'totalHopitals' => $totalHopitals,
            'totalLogs' => $totalLogs,
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
        $secretaires = Utilisateur::where('role', 'SECRETAIRE')->get();
        return view('admin.secretaires.create', [
            'specialites' => config('specialites.medicales'),
            'secretaires' => $secretaires,
        ]);
    }

    public function createMedecin()
    {
        $medecins = Utilisateur::where('role', 'MEDECIN')->get();
        return view('admin.medecins.create', [
            'specialites' => config('specialites.medicales'),
            'medecins' => $medecins,
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
