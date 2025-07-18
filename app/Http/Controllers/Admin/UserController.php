<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Medecin;
use App\Models\Infirmier;
use App\Models\Secretaire_medical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = Utilisateur::paginate(10);
        return view('admin.users.index', [
            'title' => 'Gestion des utilisateurs',
            'users' => $users
        ]);
    }

    public function create()
    {
        $specialites = [
            'Généraliste',
            'Cardiologue',
            'Chirurgien',
            'Radiologue',
            'Pédiatre',
            'Autres'
        ];

        $secteurs = [
            'Médecine',
            'Chirurgie',
            'Urgences',
            'Soins Intensifs',
            'Maternité',
            'Pédiatrie'
        ];

        return view('admin.users.create', [
            'specialites' => $specialites,
            'secteurs' => $secteurs
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:utilisateurs,email',
                'mot_de_passe' => 'required|min:8|confirmed',
                'telephone' => 'required|string|max:20',
                'role' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $role = $validated['role'] ?? Utilisateur::ROLE_SECRETAIRE;

            $utilisateur = Utilisateur::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'mot_de_passe' => Hash::make($validated['mot_de_passe']),
                'role' => $role,
                'telephone' => $validated['telephone'],
                'statut' => 'ACTIF'
            ]);

            // Créer l'entrée correspondante selon le rôle
            if ($role === Utilisateur::ROLE_SECRETAIRE) {
                Secretaire_medical::create([
                    'utilisateur_id' => $utilisateur->id,
                    'matricule' => 'SEC-' . str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT)
                ]);
            } elseif ($role === Utilisateur::ROLE_INFIRMIER) {
                \App\Models\Infirmier::create([
                    'utilisateur_id' => $utilisateur->id,
                    'matricule' => 'INF-' . str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT),
                    'secteur' => $request->secteur ?? 'Général'
                ]);
            } elseif ($role === Utilisateur::ROLE_MEDECIN) {
                \App\Models\Medecin::create([
                    'utilisateur_id' => $utilisateur->id,
                    'matricule' => 'MED-' . str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT),
                    'specialite' => $request->specialite ?? 'Généraliste'
                ]);
            }

            DB::commit();

            $roleLabel = '';
            if ($role === Utilisateur::ROLE_SECRETAIRE) {
                $roleLabel = 'Secrétaire';
            } elseif ($role === Utilisateur::ROLE_INFIRMIER) {
                $roleLabel = 'Infirmier';
            } elseif ($role === Utilisateur::ROLE_MEDECIN) {
                $roleLabel = 'Médecin';
            } elseif ($role === Utilisateur::ROLE_ADMIN) {
                $roleLabel = 'Administrateur';
            } else {
                $roleLabel = 'Utilisateur';
            }

            return redirect()->route('admin.users')
                ->with('success', $roleLabel . ' créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du secrétaire: ' . $e->getMessage()]);
        }
    }

    public function edit(Utilisateur $user)
    {
        $specialites = [
            'Généraliste',
            'Cardiologue',
            'Chirurgien',
            'Radiologue',
            'Pédiatre',
            'Autres'
        ];

        $secteurs = [
            'Médecine',
            'Chirurgie',
            'Urgences',
            'Soins Intensifs',
            'Maternité',
            'Pédiatrie'
        ];

        $medecin = null;
        $infirmier = null;
        if ($user->role === 'MEDECIN') {
            $medecin = Medecin::where('utilisateur_id', $user->id)->first();
        } elseif ($user->role === 'INFIRMIER') {
            $infirmier = Infirmier::where('utilisateur_id', $user->id)->first();
        }

        return view('admin.users.edit', [
            'title' => 'Modifier l\'utilisateur',
            'user' => $user,
            'medecin' => $medecin,
            'infirmier' => $infirmier,
            'specialites' => $specialites,
            'secteurs' => $secteurs
        ]);
    }

    public function update(Request $request, Utilisateur $user)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $user->id,
                'role' => 'required|in:ADMIN,MEDECIN,INFIRMIER,SECRETAIRE',
                'telephone' => 'required|string|max:20',
                'specialite' => 'required_if:role,MEDECIN',
                'secteur' => 'required_if:role,INFIRMIER'
            ]);

            DB::beginTransaction();

            $user->update([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'telephone' => $validated['telephone']
            ]);

            if ($request->filled('mot_de_passe')) {
                $request->validate([
                    'mot_de_passe' => 'required|string|min:8|confirmed'
                ]);
                $user->update([
                    'mot_de_passe' => Hash::make($request->mot_de_passe)
                ]);
            }

            if ($validated['role'] === 'MEDECIN') {
                $medecin = Medecin::where('utilisateur_id', $user->id)->first();
                if ($medecin) {
                    $medecin->update(['specialite' => $validated['specialite']]);
                } else {
                    Medecin::create([
                        'utilisateur_id' => $user->id,
                        'matricule' => 'MED-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'specialite' => $validated['specialite']
                    ]);
                }
            } elseif ($validated['role'] === 'INFIRMIER') {
                $infirmier = Infirmier::where('utilisateur_id', $user->id)->first();
                if ($infirmier) {
                    $infirmier->update(['secteur' => $validated['secteur']]);
                } else {
                    Infirmier::create([
                        'utilisateur_id' => $user->id,
                        'matricule' => 'INF-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'secteur' => $validated['secteur']
                    ]);
                }
            }

            DB::commit();
            Log::info('Utilisateur mis à jour avec succès', ['user_id' => $user->id]);
            return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour de l\'utilisateur', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour de l\'utilisateur: ' . $e->getMessage());
        }
    }

    public function destroy(Utilisateur $user)
    {
        try {
            DB::beginTransaction();
            if ($user->role === 'MEDECIN') {
                Medecin::where('utilisateur_id', $user->id)->delete();
            } elseif ($user->role === 'INFIRMIER') {
                Infirmier::where('utilisateur_id', $user->id)->delete();
            } elseif ($user->role === 'SECRETAIRE') {
                Secretaire_medical::where('utilisateur_id', $user->id)->delete();
            }
            $user->delete();
            DB::commit();
            Log::info('Utilisateur supprimé avec succès', ['user_id' => $user->id]);
            return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la suppression de l\'utilisateur', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur: ' . $e->getMessage());
        }
    }
} 