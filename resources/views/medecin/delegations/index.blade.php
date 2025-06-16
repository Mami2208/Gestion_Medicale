@extends('layouts.medecin')

@section('title', 'Gestion des délégations d\'accès')

@php
    // Ajout de débogage
    \Illuminate\Support\Facades\Log::info('Début de la vue index des délégations');
    \Illuminate\Support\Facades\Log::info('Nombre de délégations: ' . $delegations->count());
    \Illuminate\Support\Facades\Log::info('Données des délégations: ', $delegations->toArray());
    
    // Afficher les données brutes pour le débogage
    $debugDelegations = $delegations->map(function($item) {
        return [
            'id' => $item->id,
            'medecin_id' => $item->medecin_id,
            'infirmier_id' => $item->infirmier_id,
            'patient_id' => $item->patient_id,
            'statut' => $item->statut,
            'date_debut' => $item->date_debut,
            'date_fin' => $item->date_fin,
            'raison' => $item->raison,
            'infirmier' => $item->infirmier ?? null,
            'patient' => $item->patient ? [
                'id' => $item->patient->id,
                'utilisateur' => $item->patient->utilisateur ?? null
            ] : null
        ];
    });
@endphp

@section('content')

{{-- Section de débogage --}}
@if(env('APP_DEBUG'))
<div class="alert alert-warning">
    <h5>Débogage</h5>
    <p>Nombre de délégations: {{ $delegations->count() }}</p>
    <pre>{{ print_r($debugDelegations->toArray(), true) }}</pre>
</div>
@endif
<div class="container-fluid px-4">
    <h1 class="mt-4">Délégations d'accès</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Délégations d'accès</li>
    </ol>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Liste des délégations d'accès</h5>
            <a href="{{ route('medecin.delegations.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i>Nouvelle délégation
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Infirmier</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($delegations as $delegation)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle text-center me-2">
                                            <i class="fas fa-user-injured text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-medium">{{ $delegation->patient->utilisateur->nom ?? 'N/A' }} {{ $delegation->patient->utilisateur->prenom ?? '' }}</span><br>
                                            <small class="text-muted">ID: #{{ $delegation->patient->id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle text-center me-2">
                                            <i class="fas fa-user-nurse text-info"></i>
                                        </div>
                                        {{ $delegation->infirmier->nom ?? 'N/A' }} {{ $delegation->infirmier->prenom ?? '' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small><strong>Du:</strong> {{ $delegation->date_debut->format('d/m/Y H:i') }}</small>
                                        <small><strong>Au:</strong> {{ $delegation->date_fin->format('d/m/Y H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($delegation->statut == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($delegation->statut == 'terminee')
                                        <span class="badge bg-secondary">Terminée</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('medecin.delegations.show', $delegation->id) }}" class="btn btn-sm btn-info text-white" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($delegation->statut == 'active')
                                            <a href="{{ route('medecin.delegations.edit', $delegation->id) }}" class="btn btn-sm btn-warning text-white" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Annuler la délégation" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $delegation->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            
                                            <!-- Modal de confirmation de suppression -->
                                            <div class="modal fade" id="deleteModal{{ $delegation->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $delegation->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $delegation->id }}">Confirmation d'annulation</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Êtes-vous sûr de vouloir annuler la délégation d'accès pour le patient <strong>{{ $delegation->patient->nom }} {{ $delegation->patient->prenom }}</strong> à l'infirmier <strong>{{ $delegation->infirmier->name }}</strong> ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <form action="{{ route('medecin.delegations.destroy', $delegation->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Aucune délégation d'accès trouvée</h5>
                                        <a href="{{ route('medecin.delegations.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i>Créer une délégation
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $delegations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
