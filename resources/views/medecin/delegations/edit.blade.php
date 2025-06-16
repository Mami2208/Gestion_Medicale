@extends('layouts.app')

@section('title', 'Modifier la délégation d\'accès')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Modifier la délégation d'accès</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('medecin.delegations.index') }}">Délégations d'accès</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Modifier la délégation #{{ $delegation->id }}</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('medecin.delegations.update', $delegation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Informations</h5>
                                    <p class="mb-0">Vous ne pouvez pas modifier le patient ou l'infirmier concerné par cette délégation. Si nécessaire, veuillez annuler cette délégation et en créer une nouvelle.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informations actuelles</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Patient</label>
                                        <div class="form-control bg-light">{{ $delegation->patient->nom }} {{ $delegation->patient->prenom }}</div>
                                        <input type="hidden" name="patient_id" value="{{ $delegation->patient_id }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Infirmier</label>
                                        <div class="form-control bg-light">{{ $delegation->infirmier->nom }} {{ $delegation->infirmier->prenom }}</div>
                                        <input type="hidden" name="infirmier_id" value="{{ $delegation->infirmier_id }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Date de début</label>
                                        <div class="form-control bg-light">{{ $delegation->date_debut->format('d/m/Y H:i') }}</div>
                                        <input type="hidden" name="date_debut" value="{{ $delegation->date_debut->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted">Créée le</label>
                                        <div class="form-control bg-light">{{ $delegation->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="date_fin" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin') ?? $delegation->date_fin->format('Y-m-d\TH:i') }}" required>
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Vous pouvez prolonger ou raccourcir la période de délégation.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="raison" class="form-label">Raison de la délégation</label>
                            <textarea id="raison" name="raison" class="form-control @error('raison') is-invalid @enderror" rows="3">{{ old('raison') ?? $delegation->raison }}</textarea>
                            @error('raison')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                <option value="active" {{ old('statut', $delegation->statut) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="terminee" {{ old('statut', $delegation->statut) == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                <option value="annulee" {{ old('statut', $delegation->statut) == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('medecin.delegations.show', $delegation->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Aide</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Options disponibles :</h6>
                    
                    <div class="d-flex mb-3">
                        <div class="rounded-circle bg-light p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Prolonger la délégation</h6>
                            <p class="small text-muted mb-0">Vous pouvez étendre la date de fin pour donner plus de temps à l'infirmier.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="rounded-circle bg-light p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-ban text-danger"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Terminer la délégation</h6>
                            <p class="small text-muted mb-0">Vous pouvez changer le statut à "Terminée" pour arrêter l'accès avant la date prévue.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="rounded-circle bg-light p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-times-circle text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Annuler la délégation</h6>
                            <p class="small text-muted mb-0">En cas d'erreur, vous pouvez annuler complètement cette délégation.</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-warning mb-0">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-1"></i>Attention</h6>
                        <p class="mb-0">Les changements de statut sont immédiats et affectent l'accès de l'infirmier au dossier du patient.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
