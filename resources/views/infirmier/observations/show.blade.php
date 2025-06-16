@extends('layouts.infirmier')

@section('title', 'Observation - ' . $observation->titre)

@section('content')
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-notes-medical me-2"></i>
                    Détails de l'observation
                </h5>
                <div>
                    <a href="{{ route('infirmier.patients.show', $patient->id) }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Retour au patient
                    </a>
                    @if($observation->infirmier_id === $infirmier->id)
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h4 class="mb-3">{{ $observation->titre }}</h4>
                    <div class="mb-3">
                        <p class="mb-1"><strong>Patient :</strong> {{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}</p>
                        <p class="mb-0"><strong>Date de l'observation :</strong> {{ $observation->date_observation->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block text-end">
                        <span class="badge {{ $observation->est_important ? 'bg-danger' : 'bg-secondary' }} mb-2">
                            {{ $observation->est_important ? 'Important' : 'Normal' }}
                        </span>
                        <span class="badge bg-primary">
                            {{ $observation->type_libelle }}
                        </span>
                        <div class="mt-2">
                            <small class="text-muted">Créé le {{ $observation->created_at->format('d/m/Y \à H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Contenu de l'observation</h6>
                </div>
                <div class="card-body">
                    {!! nl2br(e($observation->contenu)) !!}
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Créée par :</strong> 
                                {{ $observation->infirmier->utilisateur->prenom }} {{ $observation->infirmier->utilisateur->nom }}
                            </p>
                            <p class="mb-1"><strong>Statut :</strong> 
                                <span class="badge {{ $observation->statut === 'ACTIF' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $observation->statut_libelle }}
                                </span>
                            </p>
                            <p class="mb-0"><strong>Dernière mise à jour :</strong> 
                                {{ $observation->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Actions</h6>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <button class="btn btn-outline-primary mb-2">
                                <i class="fas fa-print me-1"></i> Imprimer
                            </button>
                            @if($observation->infirmier_id === $infirmier->id)
                            <button class="btn btn-outline-warning mb-2">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </button>
                            <button class="btn btn-outline-danger">
                                <i class="fas fa-archive me-1"></i> Archiver
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .badge {
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
</style>
@endpush
