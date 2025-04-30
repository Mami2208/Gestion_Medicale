<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Rendez_Vous;
use Illuminate\Http\Request;

class SecretaireController extends Controller
{
    public function dashboard()
    {
        // Example data for secretary dashboard
        $totalPatients = Patient::count();
        $totalAppointments = Rendez_Vous::count();

        return view('secretaire.dashboard', [
            'totalPatients' => $totalPatients,
            'totalAppointments' => $totalAppointments,
        ]);
    }

    public function createMedicalRecord()
    {
        // Return a view to create a medical record (to be implemented)
        return view('secretaire.medical_records.create');
    }

    public function storeMedicalRecord(Request $request)
    {
        // Handle storing medical record logic (to be implemented)
        // For now, just redirect back with success message
        return redirect()->route('secretaire.dashboard')->with('success', 'Dossier médical créé avec succès.');
    }
}
