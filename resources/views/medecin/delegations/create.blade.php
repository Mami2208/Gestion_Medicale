@extends('layouts.medecin')

@section('title', 'Créer une délégation d\'accès')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Créer une délégation d'accès</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('medecin.delegations.index') }}">Délégations d'accès</a></li>
        <li class="breadcrumb-item active">Créer</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Nouvelle délégation d'accès</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('medecin.delegations.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->nom }} {{ $patient->prenom }} (ID: #{{ $patient->id }})
                                    </option>
                                @endforeach
                                @if($patients->isEmpty())
                                    <option value="" disabled>Aucun patient n'est assigné à votre compte</option>
                                @endif
                            </select>
                            @if($patients->isEmpty())
                                <div class="mt-2 text-danger small">
                                    <i class="fas fa-exclamation-circle"></i> 
                                    Vous n'avez aucun patient assigné à votre compte. Veuillez contacter l'administrateur pour ajouter des patients.
                                </div>
                            @endif
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="infirmier_id" class="form-label">Infirmier <span class="text-danger">*</span></label>
                            <select name="infirmier_id" id="infirmier_id" class="form-select @error('infirmier_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un infirmier</option>
                                @foreach($infirmiers as $infirmier)
                                    <option value="{{ $infirmier->id }}" {{ old('infirmier_id') == $infirmier->id ? 'selected' : '' }}>
                                        {{ $infirmier->nom }} {{ $infirmier->prenom }} ({{ $infirmier->matricule }})
                                    </option>
                                @endforeach
                            </select>
                            @error('infirmier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="date_debut" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut') ?? now()->format('Y-m-d\TH:i') }}" required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="date_fin" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin') ?? now()->addDays(7)->format('Y-m-d\TH:i') }}" required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="raison" class="form-label">Raison de la délégation</label>
                            <textarea id="raison" name="raison" class="form-control @error('raison') is-invalid @enderror" rows="3">{{ old('raison') }}</textarea>
                            @error('raison')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Expliquez pourquoi vous déléguez l'accès à ce dossier patient.</div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('medecin.delegations.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Créer la délégation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>À propos des délégations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-1"></i>Informations importantes</h6>
                        <p class="mb-0">Une délégation d'accès permet à un infirmier de consulter le dossier d'un de vos patients pendant une période limitée.</p>
                    </div>
                    
                    <h6 class="mb-2">Règles de délégation :</h6>
                    <ul class="small">
                        <li>La délégation ne concerne qu'un seul patient à la fois</li>
                        <li>La période doit être clairement définie</li>
                        <li>L'infirmier n'aura accès qu'en lecture seule</li>
                        <li>Toutes les consultations seront tracées dans les journaux d'activité</li>
                        <li>Vous pouvez annuler une délégation à tout moment</li>
                    </ul>
                    
                    <hr>
                    
                    <div class="d-flex align-items-center mb-0">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        <small class="text-muted">Cet accès respecte les règles de confidentialité médicale</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
