<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedecinController extends Controller
{
    public function profileEdit()
    {
        $medecin = auth()->user()->medecin;
        return view('medecin.profile.edit', compact('medecin'));
    }

    public function profileUpdate(Request $request)
    {
        $medecin = auth()->user()->medecin;

        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'specialite' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $medecin->update($validated);

            // Si une nouvelle photo est uploadée
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/medecins', $photoName);
                $medecin->photo = $photoName;
                $medecin->save();
            }

            DB::commit();

            return redirect()->route('medecin.profile.edit')
                ->with('success', 'Profil mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du profil', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du profil.');
        }
    }
}
