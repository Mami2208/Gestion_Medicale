<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'title' => 'Tableau de bord',
            'stats' => [
                'total_users' => \App\Models\Utilisateur::count(),
                'active_sessions' => DB::table('sessions')
                    ->where('last_activity', '>', now()->subMinutes(5))
                    ->count(),
                'total_patients' => \App\Models\Patient::count(),
                'total_appointments' => \App\Models\Rendez_Vous::count(),
            ]
        ]);
    }
} 