<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
// Removed unused User import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use App\Models\Rendez_Vous;

class MedecinController extends Controller
{
    public function index()
    {
        $medecins = Utilisateur::where('role', 'MEDECIN')
            ->paginate(10);

        $specialites = config('specialites.medicales');

        return view('admin.medecins.index', compact('medecins', 'specialites'));
    }

    public function create()
    {
        return view('admin.medecins.create', [
            'specialites' => config('specialites.medicales')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email',
            'specialite' => 'required|string',
            'telephone' => 'required|string|max:20'
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make('Passer123'), // Mot de passe temporaire
            'role' => 'MEDECIN',
            'telephone' => $request->telephone,
            'specialite' => $request->specialite
        ]);

        $matricule = 'MED' . time() . rand(100, 999);

        $specialites = array_flip(config('specialites.medicales'));
        $specialiteKey = $specialites[$request->specialite] ?? 'GENERALISTE';

        \App\Models\Medecin::create([
            'matricule' => $matricule,
            'specialite' => $specialiteKey,
            'utilisateur_id' => $utilisateur->id,
        ]);

        return redirect()->route('admin.medecins.index')
            ->with('success', 'Médecin créé avec succès');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->medecin) {
            abort(403, 'Accès refusé : utilisateur non associé à un médecin.');
        }

        $medecinId = $user->medecin->id;

        // Fetch upcoming appointments for the doctor
        $appointments = Rendez_Vous::where('medecin_id', $medecinId)
            ->where('date_rendez_vous', '>=', now())
            ->orderBy('date_rendez_vous', 'asc')
            ->take(10)
            ->get();

        // Fetch recent prescriptions
        $prescriptions = \App\Models\Prescription::where('medecin_id', $medecinId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch recent treatments
        $treatments = \App\Models\Traitement::where('medecin_id', $medecinId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch recent notifications
        $notifications = \App\Models\Notification::where('medecin_id', $medecinId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('medecin.dashboard', compact('appointments', 'prescriptions', 'treatments', 'notifications'));
    }
}
