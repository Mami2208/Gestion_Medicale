@extends('layouts.app')

@section('title', 'Détails de la délégation d\'accès')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails de la délégation d'accès</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('medecin.delegations.index') }}">Délégations d'accès</a></li>
        <li class="breadcrumb-item active">Détails</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-share-alt me-2"></i>Délégation #{{ $delegation->id }}
                        @if($delegation->statut == 'active')
                            <span class="badge bg-success ms-2">Active</span>
                        @elseif($delegation->statut == 'terminee')
                            <span class="badge bg-secondary ms-2">Terminée</span>
                        @else
                            <span class="badge bg-danger ms-2">Annulée</span>
                        @endif
                    </h5>
                    <div>
                        @if($delegation->statut == 'active')
                            <a href="{{ route('medecin.delegations.edit', $delegation->id) }}" class="btn btn-warning btn-sm text-white">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times me-1"></i>Annuler
                            </button>
                        @endif
                        <a href="{{ route('medecin.delegations.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Patient</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-md bg-light rounded-circle text-center me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user-injured fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $delegation->patient->nom }} {{ $delegation->patient->prenom }}</h5>
                                            <span class="text-muted">ID Patient: #{{ $delegation->patient->id }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-6">
                                            <div class="small text-muted">Date de naissance</div>
                                            <div>{{ $delegation->patient->date_naissance ? $delegation->patient->date_naissance->format('d/m/Y') : 'Non spécifiée' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="small text-muted">Téléphone</div>
                                            <div>{{ $delegation->patient->telephone ?? 'Non spécifié' }}</div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('medecin.patients.show', $delegation->patient->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-folder-open me-1"></i>Voir le dossier complet
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3 border shadow-none">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user-nurse me-2"></i>Infirmier</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-md bg-light rounded-circle text-center me-3 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user-nurse fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $delegation->infirmier->name }}</h5>
                                            <span class="text-muted">ID Utilisateur: #{{ $delegation->infirmier->id }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-12">
                                            <div class="small text-muted">Email</div>
                                            <div>{{ $delegation->infirmier->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Période de délégation</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="small text-muted">Date de début</div>
                                    <div class="fs-5 mb-3">{{ $delegation->date_debut->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="small text-muted">Date de fin</div>
                                    <div class="fs-5 mb-3">{{ $delegation->date_fin->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                            
                            <div class="progress" style="height: 8px;">
                                @php
                                    $now = now();
                                    $start = $delegation->date_debut;
                                    $end = $delegation->date_fin;
                                    $total = $start->diffInSeconds($end);
                                    $elapsed = $start->diffInSeconds($now > $end ? $end : ($now < $start ? $start : $now));
                                    $percentage = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;
                                @endphp
                                
                                <div class="progress-bar {{ $delegation->statut == 'active' ? 'bg-success' : ($delegation->statut == 'terminee' ? 'bg-secondary' : 'bg-danger') }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small>Début</small>
                                <small>Fin</small>
                            </div>
                            
                            <div class="mt-3">
                                <span class="badge {{ $now < $start ? 'bg-warning' : ($now > $end ? 'bg-secondary' : 'bg-success') }}">
                                    @if($now < $start)
                                        Commence dans {{ $now->diffForHumans($start, ['parts' => 2]) }}
                                    @elseif($now > $end)
                                        Terminée depuis {{ $now->diffForHumans($end, ['parts' => 2]) }}
                                    @else
                                        En cours - Se termine dans {{ $now->diffForHumans($end, ['parts' => 2]) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($delegation->raison)
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Raison de la délégation</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $delegation->raison }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>Créée le {{ $delegation->created_at->format('d/m/Y à H:i') }}
                        </small>
                        @if($delegation->created_at != $delegation->updated_at)
                            <small class="text-muted">
                                <i class="fas fa-edit me-1"></i>Mise à jour le {{ $delegation->updated_at->format('d/m/Y à H:i') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Informations de sécurité</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-1"><i class="fas fa-info-circle me-1"></i>Suivi des accès</h6>
                        <p class="mb-0">Tous les accès effectués par l'infirmier dans le cadre de cette délégation sont tracés dans les journaux d'activité.</p>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light p-2 me-2">
                            <i class="fas fa-key text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Niveau d'accès</h6>
                            <small class="text-muted">Lecture seule</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light p-2 me-2">
                            <i class="fas fa-folder-open text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Données accessibles</h6>
                            <small class="text-muted">Dossier médical, consultations, traitements</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light p-2 me-2">
                            <i class="fas fa-eye-slash text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Données non accessibles</h6>
                            <small class="text-muted">Informations administratives sensibles</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <a href="/admin/activity-logs-direct?type=delegation" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-history me-1"></i>Consulter les journaux d'accès
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation d'annulation -->
@if($delegation->statut == 'active')
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Confirmation d'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler cette délégation d'accès ?</p>
                <p class="mb-0"><strong>Attention :</strong> Cette action est définitive et l'infirmier n'aura plus accès au dossier du patient.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('medecin.delegations.destroy', $delegation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
