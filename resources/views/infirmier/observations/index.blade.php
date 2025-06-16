@extends('layouts.infirmier')

@section('title', 'Observations - ' . $patient->utilisateur->prenom . ' ' . $patient->utilisateur->nom)

@section('content')
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-notes-medical me-2"></i>
                    Observations pour {{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}
                </h5>
                <div>
                    <a href="{{ route('infirmier.patients.show', $patient->id) }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Retour au patient
                    </a>
                    <a href="{{ route('infirmier.observations.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Nouvelle observation
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif
            
            @if($observations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Importance</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($observations as $observation)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $observation->date_observation->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $observation->date_observation->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $observation->titre }}</div>
                                        <small class="text-muted">
                                            {{ Str::limit(strip_tags($observation->contenu), 50) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $observation->type_libelle }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $observation->statut === 'ACTIF' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $observation->statut_libelle }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($observation->est_important)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Important
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Normal</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('infirmier.observations.show', $observation->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($observation->infirmier_id === $infirmier->id)
                                                <a href="#" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{-- {{ $observations->links() }} --}}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-inbox fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Aucune observation enregistrée</h5>
                    <p class="text-muted">Commencez par créer une nouvelle observation pour ce patient.</p>
                    <a href="{{ route('infirmier.observations.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Nouvelle observation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
    // Activer les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
