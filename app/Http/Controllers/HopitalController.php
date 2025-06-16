<?php

namespace App\Http\Controllers;

use App\Models\Hopital;
use Illuminate\Http\Request;

class HopitalController extends Controller
{
    // Affiche la liste des hôpitaux
    public function index()
    {
        $hopitaux = Hopital::all();
        return view('admin.hopitaux.index', compact('hopitaux'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('admin.hopitaux.create');
    }

    // Enregistre un nouvel hôpital
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        Hopital::create([
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.hopitaux.index')->with('success', 'Hôpital créé avec succès.');
    }

    // Affiche un hôpital spécifique (si besoin plus tard)
    public function show(Hopital $hopital)
    {
        return view('admin.hopitaux.show', compact('hopital'));
    }

    // Affiche le formulaire d'édition
    public function edit(Hopital $hopital)
    {
        return view('admin.hopitaux.edit', compact('hopital'));
    }

    // Met à jour un hôpital
    public function update(Request $request, Hopital $hopital)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        $hopital->update($request->all());

        return redirect()->route('admin.hopitaux.index')->with('success', 'Hôpital mis à jour avec succès.');
    }

    // Supprime un hôpital
    public function destroy(Hopital $hopital)
    {
        $hopital->delete();

        return redirect()->route('admin.hopitaux.index')->with('success', 'Hôpital supprimé avec succès.');
    }
}
