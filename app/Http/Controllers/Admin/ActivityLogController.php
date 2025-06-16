<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /**
     * Affiche la liste des journaux d'activité avec filtres et statistiques
     */
    public function index(Request $request)
    {
        // Récupérer tous les utilisateurs pour le filtre
        $users = User::all();
        
        // Préparer la requête de base
        $query = $this->buildFilteredQuery($request);
        
        // Récupérer les logs paginés
        $logs = $query->orderBy($request->get('sort', 'created_at'), $request->get('direction', 'desc'))
                      ->paginate(10);
        
        // Calculer les statistiques
        $stats = $this->getActivityStats();
        
        // Utiliser notre vue moderne améliorée
        return view('admin.activity_logs.modern', compact('logs', 'users', 'stats'));
    }
    
    /**
     * Construit la requête avec les filtres appliqués
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            if (is_array($request->user_id)) {
                $query->whereIn('user_id', $request->user_id);
            } else {
                $query->where('user_id', $request->user_id);
            }
        }

        // Filtre par type d'activité
        if ($request->filled('type')) {
            if (is_array($request->type)) {
                $query->whereIn('type', $request->type);
            } else {
                $query->where('type', $request->type);
            }
        }

        // Filtre par action
        if ($request->filled('action')) {
            if (is_array($request->action)) {
                $query->whereIn('action', $request->action);
            } else {
                $query->where('action', $request->action);
            }
        }

        // Filtre par période prédéfinie
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;
                case 'custom':
                    if ($request->filled('date_start')) {
                        $query->whereDate('created_at', '>=', $request->date_start);
                    }
                    if ($request->filled('date_end')) {
                        $query->whereDate('created_at', '<=', $request->date_end);
                    }
                    break;
            }
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        return $query;
    }
    
    /**
     * Récupère les statistiques d'activité
     */
    private function getActivityStats()
    {
        $stats = [
            'today' => ActivityLog::whereDate('created_at', Carbon::today())->count(),
            'security' => ActivityLog::where('type', 'security')->count(),
            'data' => ActivityLog::where('type', 'data')->count(),
            'user' => ActivityLog::where('type', 'user')->count(),
        ];
        
        return $stats;
    }
    
    /**
     * Marquer tout comme lu ou supprimer des logs spécifiques
     */
    public function delete($id)
    {
        // Vérifier que l'utilisateur est bien un administrateur
        if (auth()->user()->role !== 'ADMIN') {
            return redirect()->route('admin.activity-logs.index')
                             ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }
        
        // Rechercher et supprimer le log
        $log = ActivityLog::findOrFail($id);
        $log->delete();
        
        // Créer un log pour cette suppression
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_log',
            'type' => 'security',
            'description' => 'Suppression d\'une entrée du journal d\'activité',
            'properties' => json_encode([
                'deleted_log_id' => $id,
                'deleted_log_action' => $log->action,
                'deleted_log_type' => $log->type,
                'deleted_log_date' => $log->created_at->format('Y-m-d H:i:s')
            ])
        ]);
        
        return redirect()->route('admin.activity-logs.index')
                         ->with('success', 'Entrée du journal supprimée avec succès.');
    }
    
    /**
     * Exporter les logs d'activité en différents formats
     */
    public function export(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'format' => 'required|in:csv,pdf,json',
            'export_scope' => 'required|in:current,all',
            'include_details' => 'boolean',
            'include_user_info' => 'boolean',
            'export_filters' => 'nullable|string'
        ]);
        
        // Récupérer les logs selon le scope
        if ($validated['export_scope'] === 'current' && $request->filled('export_filters')) {
            $filters = json_decode($request->export_filters, true);
            $request->merge($filters);
            $query = $this->buildFilteredQuery($request);
        } else {
            $query = ActivityLog::with('user');
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        // Préparer les données pour l'export
        $exportData = [];
        foreach ($logs as $log) {
            $row = [
                'id' => $log->id,
                'date' => $log->created_at->format('Y-m-d H:i:s'),
                'type' => $log->type,
                'action' => $log->action,
                'description' => $log->description,
            ];
            
            if ($request->include_user_info && $log->user) {
                $row['user_id'] = $log->user_id;
                $row['user_name'] = $log->user->prenom . ' ' . $log->user->nom;
                $row['user_role'] = $log->user->role;
            }
            
            if ($request->include_details && $log->properties) {
                $row['properties'] = $log->properties;
            }
            
            $exportData[] = $row;
        }
        
        // Générer l'export selon le format demandé
        switch ($validated['format']) {
            case 'csv':
                return $this->exportToCsv($exportData);
                
            case 'json':
                return $this->exportToJson($exportData);
                
            case 'pdf':
                // Pour l'export PDF, nous utiliserions une bibliothèque comme DomPDF
                // Dans cet exemple, nous retournons simplement un message
                return redirect()->route('admin.activity-logs.index')
                                 ->with('info', 'L\'export PDF sera bientôt disponible.');
        }
    }
    
    /**
     * Exporter les données au format CSV
     */
    private function exportToCsv($data)
    {
        if (empty($data)) {
            return redirect()->route('admin.activity-logs.index')
                             ->with('error', 'Aucune donnée à exporter.');
        }
        
        $headers = array_keys($data[0]);
        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($data as $row) {
                // Convertir les propriétés JSON en chaîne si nécessaire
                if (isset($row['properties']) && is_string($row['properties'])) {
                    $row['properties'] = str_replace('\n', ' ', $row['properties']);
                }
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        $filename = 'logs_activite_' . date('Y-m-d_His') . '.csv';
        
        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * Exporter les données au format JSON
     */
    private function exportToJson($data)
    {
        $filename = 'logs_activite_' . date('Y-m-d_His') . '.json';
        
        return Response::make(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
