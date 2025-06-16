<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs avec pagination
     */
    public function index()
    {
        $users = Utilisateur::paginate(10); // Pagination avec 10 utilisateurs par page
        return view('admin.users.index', compact('users'));
    }

    /**
     * Recherche des utilisateurs par nom/prénom/email et filtre par rôle
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $role = $request->input('role');
        
        $usersQuery = Utilisateur::query();
        
        // Recherche par nom, pru00e9nom ou email
        if ($query) {
            $usersQuery->where(function($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('prenom', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            });
        }
        
        // Filtre par ru00f4le
        if ($role) {
            $usersQuery->where('role', $role);
        }
        
        $users = $usersQuery->paginate(10)->withQueryString(); // Garder les paramu00e8tres de recherche dans les liens de pagination
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $specialites = ['Cardiologie', 'Dermatologie', 'Gastro-entu00e9rologie', 'Gynu00e9cologie', 'Neurologie', 'Ophtalmologie', 'Pu00e9diatrie', 'Psychiatrie', 'Radiologie', 'Urologie'];
        $secteurs = ['Urgences', 'Soins intensifs', 'Chirurgie', 'Maternitu00e9', 'Pu00e9diatrie', 'Gu00e9riatrie', 'Psychiatrie', 'Radiologie', 'Cardiologie', 'Oncologie'];
        
        return view('admin.users.create', compact('specialites', 'secteurs'));
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs',
            'telephone' => 'required|string|max:20',
            'role' => 'required|string|in:ADMIN,MEDECIN,INFIRMIER,SECRETAIRE,PATIENT',
            'mot_de_passe' => 'required|string|min:8',
            'specialite' => 'required_if:role,MEDECIN|nullable|string',
            'secteur' => 'required_if:role,INFIRMIER|nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Générer un matricule en fonction du rôle
        $matricule = null;
        if (in_array($request->role, ['MEDECIN', 'INFIRMIER', 'SECRETAIRE'])) {
            $prefix = substr($request->role, 0, 3);
            $random = strtoupper(substr(md5(time()), 0, 4));
            $matricule = "{$prefix}-{$random}";
        }

        $user = new Utilisateur();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->role = $request->role;
        $user->matricule = $matricule;
        $user->specialite = $request->specialite;
        $user->secteur = $request->secteur;
        $user->mot_de_passe = Hash::make($request->mot_de_passe);
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show($id)
    {
        $user = Utilisateur::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit($id)
    {
        $user = Utilisateur::findOrFail($id);
        $specialites = ['Cardiologie', 'Dermatologie', 'Gastro-entu00e9rologie', 'Gynu00e9cologie', 'Neurologie', 'Ophtalmologie', 'Pu00e9diatrie', 'Psychiatrie', 'Radiologie', 'Urologie'];
        $secteurs = ['Urgences', 'Soins intensifs', 'Chirurgie', 'Maternitu00e9', 'Pu00e9diatrie', 'Gu00e9riatrie', 'Psychiatrie', 'Radiologie', 'Cardiologie', 'Oncologie'];
        
        return view('admin.users.edit', compact('user', 'specialites', 'secteurs'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $id,
            'telephone' => 'required|string|max:20',
            'role' => 'required|string|in:ADMIN,MEDECIN,INFIRMIER,SECRETAIRE,PATIENT',
            'mot_de_passe' => 'nullable|string|min:8',
            'specialite' => 'required_if:role,MEDECIN|nullable|string',
            'secteur' => 'required_if:role,INFIRMIER|nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mettre u00e0 jour les informations de base
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        
        // Vérifier si le rôle a changé
        if ($user->role !== $request->role) {
            $user->role = $request->role;
            
            // Générer un nouveau matricule si nécessaire
            if (in_array($request->role, ['MEDECIN', 'INFIRMIER', 'SECRETAIRE'])) {
                $prefix = substr($request->role, 0, 3);
                $random = strtoupper(substr(md5(time()), 0, 4));
                $user->matricule = "{$prefix}-{$random}";
            } else {
                $user->matricule = null;
            }
        }
        
        // Mettre à jour les champs spécifiques au rôle
        $user->specialite = $request->role === 'MEDECIN' ? $request->specialite : null;
        $user->secteur = $request->role === 'INFIRMIER' ? $request->secteur : null;
        
        // Mettre u00e0 jour le mot de passe si fourni
        if ($request->filled('mot_de_passe')) {
            $user->mot_de_passe = Hash::make($request->mot_de_passe);
        }
        
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
    }
}
