<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamenController extends Controller
{
    public function index()
    {
        $medecin = auth()->user()->medecin;
        $examens = Examen::with(['patient.utilisateur'])
            ->where('medecin_id', $medecin->id)
            ->latest()
            ->paginate(10);

        return view('medecin.examens.index', compact('examens'));
    }

    public function create()
    {
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.examens.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'statut' => 'required|in:en_cours,termine,annule',
            'description' => 'nullable|string',
            'conclusion' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Récupérer le patient
            $patient = Patient::find($validated['patient_id']);
            $dossierMedicalId = null;
            
            // Vérifier si le patient a un dossier médical (attention au nom de la table)
            $dossierMedical = DB::table('dossiers__medicauxes')->where('patient_id', $patient->id)->first();
            
            // S'il n'a pas de dossier médical, en créer un
            if (!$dossierMedical) {
                // Ajouter les champs obligatoires
                $dossierMedicalId = DB::table('dossiers__medicauxes')->insertGetId([
                    'patient_id' => $patient->id,
                    'dateCreation' => now(), // Champ obligatoire
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $dossierMedicalId = $dossierMedical->id;
            }

            // Générer le numéro d'examen
            $numeroExamen = Examen::genererNumeroExamen();
            $dateExamen = $validated['date'];
            $description = $validated['description'] ?? '';
            $conclusion = $validated['conclusion'] ?? '';
            $statut = $validated['statut'];
            $medecinId = auth()->user()->medecin->id;
            $patientId = $validated['patient_id'];
            $typeExamen = $validated['type'];
            $now = now();

            // Insertion directe via SQL pour éviter le problème avec le champ dossiers_medicaux_id
            $examenId = DB::table('examens')->insertGetId([
                'numero_examen' => $numeroExamen,
                'patient_id' => $patientId,
                'medecin_id' => $medecinId,
                'type_examen' => $typeExamen,
                'date_examen' => $dateExamen,
                'description' => $description,
                'conclusion' => $conclusion,
                'statut' => $statut,
                'dossiers_medicaux_id' => $dossierMedicalId, // Utiliser l'ID du dossier médical que nous venons de créer
                'created_at' => $now,
                'updated_at' => $now
            ]);
            
            // Récupérer l'objet examen
            $examen = Examen::find($examenId);

            DB::commit();

            return redirect()->route('medecin.examens.index')
                ->with('success', 'Examen créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de l\'examen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Afficher l'erreur explicitement pour le débogage
            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function show(Examen $examen)
    {
        $this->authorize('view', $examen);
        return view('medecin.examens.show', compact('examen'));
    }

    public function edit(Examen $examen)
    {
        $this->authorize('update', $examen);
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.examens.edit', compact('examen', 'patients'));
    }

    public function update(Request $request, Examen $examen)
    {
        $this->authorize('update', $examen);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'statut' => 'required|in:en_cours,termine,annule',
            'description' => 'nullable|string',
            'conclusion' => 'nullable|string'
        ]);

        $examen->update($validated);

        return redirect()->route('medecin.examens.index')
            ->with('success', 'Examen mis à jour avec succès.');
    }

    public function destroy(Examen $examen)
    {
        $this->authorize('delete', $examen);
        $examen->delete();

        return redirect()->route('medecin.examens.index')
            ->with('success', 'Examen supprimé avec succès.');
    }
} 