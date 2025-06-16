@extends('secretaire.layouts.app')

@section('title', 'Tableau de bord - Secrétaire')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user-secret me-2 text-primary"></i>
                        Bienvenue, {{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections principales -->
    <div class="row">
        <!-- Statistiques -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Patients
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $patients->count() }}</h2>
                    <a href="{{ route('secretaire.patients.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir tous
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Rendez-vous aujourd'hui
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $rendezVousAujourdhui->count() }}</h2>
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir tous
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder-medical me-2"></i>
                        Dossiers médicaux
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $patients->sum('dossiers_medicaux_count') }}</h2>
                    <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir tous
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $notifications->count() }}</h2>
                    <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir toutes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3">Actions rapides</h3>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('secretaire.dossiers-medicaux.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-folder-plus me-2"></i>
                        Nouveau dossier
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-info w-100">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Nouveau rendez-vous
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('secretaire.patients.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-users me-2"></i>
                        Liste des patients
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-warning w-100">
                        <i class="fas fa-calendar-check me-2"></i>
                        Liste des rendez-vous
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Prochains rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Motif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rendezvous as $rdv)
                                <tr>
                                    <td>{{ $rdv->patient->utilisateur->nom ?? 'N/A' }}</td>
                                    <td>{{ $rdv->medecin->utilisateur->nom ?? 'N/A' }}</td>
                                    <td>{{ $rdv->date_rendez_vous->format('d/m/Y') }}</td>
                                    <td>{{ $rdv->heure_debut }}</td>
                                    <td>{{ $rdv->motif }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $rendezvous->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications récentes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notifications récentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $notification->type === 'info' ? 'info' : ($notification->type === 'success' ? 'success' : 'warning') }}">
                                            {{ $notification->type }}
                                        </span>
                                    </td>
                                    <td>{{ $notification->data['message'] }}</td>
                                    <td>{{ $notification->created_at ? $notification->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-bell me-2"></i>
                            Voir toutes les notifications
                        </a>
                    </div>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
