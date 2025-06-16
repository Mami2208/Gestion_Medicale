<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('activity_logs')
            ->select('activity_logs.*', 'users.name as user_name')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->orderBy('activity_logs.created_at', 'desc');

        // Filtrage par type d'activité
        if ($request->has('type')) {
            $query->where('activity_logs.type', $request->type);
        }

        // Filtrage par utilisateur
        if ($request->has('user_id')) {
            $query->where('activity_logs.user_id', $request->user_id);
        }

        // Filtrage par date
        if ($request->has('date_from')) {
            $query->whereDate('activity_logs.created_at', '>=', Carbon::parse($request->date_from));
        }
        if ($request->has('date_to')) {
            $query->whereDate('activity_logs.created_at', '<=', Carbon::parse($request->date_to));
        }

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('activity_logs.description', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20);
        $users = DB::table('users')->select('id', 'name')->get();
        $types = DB::table('activity_logs')->select('type')->distinct()->pluck('type');

        return view('admin.logs.index', [
            'title' => 'Traçabilité & journaux',
            'logs' => $logs,
            'users' => $users,
            'types' => $types,
            'filters' => $request->all()
        ]);
    }

    public function export(Request $request)
    {
        $query = DB::table('activity_logs')
            ->select('activity_logs.*', 'users.name as user_name')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->orderBy('activity_logs.created_at', 'desc');

        // Appliquer les mêmes filtres que dans index()
        if ($request->has('type')) {
            $query->where('activity_logs.type', $request->type);
        }
        if ($request->has('user_id')) {
            $query->where('activity_logs.user_id', $request->user_id);
        }
        if ($request->has('date_from')) {
            $query->whereDate('activity_logs.created_at', '>=', Carbon::parse($request->date_from));
        }
        if ($request->has('date_to')) {
            $query->whereDate('activity_logs.created_at', '<=', Carbon::parse($request->date_to));
        }

        $logs = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="logs.csv"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Utilisateur', 'Type', 'Description', 'IP', 'User Agent']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at,
                    $log->user_name,
                    $log->type,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 