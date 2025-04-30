<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function dashboard()
    {
        return view('patient.dashboard');
    }

    public function profile()
    {
        return view('patient.profile');
    }

    public function appointments()
    {
        return view('patient.appointments');
    }

    public function medicalRecords()
    {
        return view('patient.medical_records');
    }
}
