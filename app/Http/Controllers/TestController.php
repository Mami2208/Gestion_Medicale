<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function checkMedicaments()
    {
        $medicaments = Medicament::all();
        return response()->json([
            'count' => $medicaments->count(),
            'medicaments' => $medicaments
        ]);
    }
    
    public function testLogging()
    {
        \Log::info('Test de journalisation - niveau INFO');
        \Log::error('Test de journalisation - niveau ERREUR');
        \Log::debug('Test de journalisation - niveau DEBUG');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Messages de test enregistrés dans les logs',
            'log_path' => [
                'laravel' => storage_path('logs/laravel.log'),
                'debug' => storage_path('logs/debug.log')
            ]
        ]);
    }
}
