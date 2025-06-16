@extends('patient.layouts.app')

@section('title', 'Mon profil')

@section('page_title', 'Profil du patient')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2 text-success"></i>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('patient.profile.update') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="{{ auth()->user()->nom }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ auth()->user()->prenom }}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ auth()->user()->telephone ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="{{ auth()->user()->date_naissance ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="sexe" class="form-label">Sexe</label>
                                <select class="form-select" id="sexe" name="sexe">
                                    <option value="">Sélectionner</option>
                                    <option value="M" {{ auth()->user()->sexe == 'M' ? 'selected' : '' }}>Homme</option>
                                    <option value="F" {{ auth()->user()->sexe == 'F' ? 'selected' : '' }}>Femme</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="2">{{ $patient->adresse ?? '' }}</textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mot de passe et sécurité -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-lock me-2 text-warning"></i>Mot de passe</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('patient.password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-key me-2"></i>Modifier le mot de passe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Informations sur le compte -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>Informations sur le compte</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Date d'inscription</small>
                        <p>{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Numéro patient</small>
                        <p>{{ $patient->numeroPatient ?? 'Non défini' }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Statut du compte</small>
                        <p><span class="badge bg-success">Actif</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>
@endsection
