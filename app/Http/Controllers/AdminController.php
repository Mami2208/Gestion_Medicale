<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\LogAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SecretaireCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $medecins = Utilisateur::where('role', 'MEDECIN')->get();
        $secretaires = Utilisateur::where('role', 'SECRETAIRE')->get();
        $infirmiers = Utilisateur::where('role', 'INFIRMIER')->get();
        $totalLogs = LogAccess::count();

        return view('admin.dashboard', [
            'totalMedecins' => $medecins->count(),
            'totalSecretaires' => $secretaires->count(),
            'totalInfirmiers' => $infirmiers->count(),
            'totalLogs' => $totalLogs,
        ]);
    }

    /**
     * Affiche le profil de l'administrateur
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Met à jour le profil de l'administrateur
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'current_password' => 'nullable|required_with:nouveau_mot_de_passe',
            'nouveau_mot_de_passe' => 'nullable|min:8|confirmed',
        ]);

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;

        if ($request->filled('nouveau_mot_de_passe')) {
            if (!Hash::check($request->current_password, $user->mot_de_passe)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
            }
            $user->mot_de_passe = Hash::make($request->nouveau_mot_de_passe);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil mis à jour avec succès');
    }

    public function indexSecretaires()
    {
        $secretaires = Utilisateur::where('role', 'SECRETAIRE')->get();
        return view('admin.secretaires.index', compact('secretaires'));
    }

    public function createSecretaire()
    {
        return view('admin.secretaires.create');
    }

    public function storeSecretaire(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'telephone' => 'required|string|max:20'
        ]);

        DB::beginTransaction();

        try {
            $password = $request->mot_de_passe;

            $secretaire = Utilisateur::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'mot_de_passe' => bcrypt($password),
                'role' => 'SECRETAIRE',
                'telephone' => $request->telephone,
                'statut' => 'ACTIF'
            ]);

            // Créer l'entrée dans la table secretaire_medicals
            \App\Models\Secretaire_medical::create([
                'utilisateur_id' => $secretaire->id,
                'matricule' => 'SEC-' . str_pad($secretaire->id, 4, '0', STR_PAD_LEFT)
            ]);

            try {
                Mail::to($secretaire->email)->send(new SecretaireCreated($secretaire, $password));
            } catch (\Exception $mailException) {
                Log::error('Erreur lors de l\'envoi de l\'email : ' . $mailException->getMessage());
            }

            DB::commit();

            return redirect()->route('admin.secretaires.index')
                ->with('success', 'Secrétaire créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du secrétaire : ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du secrétaire : ' . $e->getMessage()]);
        }
    }
    // New methods for infirmiers management

    public function indexInfirmiers()
    {
        $infirmiers = Utilisateur::where('role', 'INFIRMIER')->paginate(10);
        return view('admin.infirmiers.index', compact('infirmiers'));
    }

    public function createInfirmier()
    {
        return view('admin.infirmiers.create');
    }

    public function storeInfirmier(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'services' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'matricule' => 'nullable|string|max:255',
            'mot_de_passe' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string',
        ]);

        $infirmier = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => bcrypt($request->mot_de_passe ?? Str::random(8)),
            'role' => $request->role ?? 'INFIRMIER',
            'telephone' => $request->telephone,
            'statut' => 'ACTIF',
        ]);

        // Vérifier que le rôle est bien INFIRMIER
        if ($infirmier->role != 'INFIRMIER') {
            $infirmier->role = 'INFIRMIER';
            $infirmier->save();
        }

        // Create infirmier record
        \App\Models\Infirmier::create([
            'utilisateur_id' => $infirmier->id,
            'matricule' => $request->matricule ?? '',
            'secteur' => $request->services // Le champ services correspond probablement au secteur
        ]);

        // Enregistrer l'activité
        \App\Services\ActivityLogService::log(
            'creation',
            'user',
            'Création d\'un nouvel infirmier',
            $infirmier,
            ['role' => 'INFIRMIER']
        );

        // Optionally send email with password here

        return redirect()->route('admin.infirmiers.index')->with('success', 'Infirmier créé avec succès.');
    }
}
