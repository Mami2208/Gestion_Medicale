<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Affiche la liste des logs d'activitu00e9
     */
    public function index()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Recherche dans les logs d'activitu00e9
     */
    public function search(Request \$request)
    {
        \$query = \$request->input('query');
        \$action = \$request->input('action');
        
        \$logsQuery = ActivityLog::with('user');
        
        if (\$query) {
            \$logsQuery->whereHas('user', function(\$q) use (\$query) {
                \$q->where('nom', 'LIKE', \"%{\$query}%\")
                  ->orWhere('prenom', 'LIKE', \"%{\$query}%\");
            })
            ->orWhere('description', 'LIKE', \"%{\$query}%\");
        }
        
        if (\$action) {
            \$logsQuery->where('action', \$action);
        }
        
        \$logs = \$logsQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Supprime les logs d'activitu00e9 plus anciens qu'une certaine date
     */
    public function purge(Request \$request)
    {
        // Par du00e9faut, supprime les logs plus vieux de 30 jours
        \$days = \$request->input('days', 30);
        \$date = now()->subDays(\$days);
        
        \$count = ActivityLog::where('created_at', '<', \$date)->delete();
        
        return redirect()->route('admin.logs')->with('success', "{\$count} logs d'activitu00e9 ont u00e9tu00e9 supprimu00e9s.");
    }
}
