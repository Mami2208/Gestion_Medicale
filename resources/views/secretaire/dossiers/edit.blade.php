@extends('secretaire.layouts.app')

@section('title', 'Modifier un dossier médical')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Modifier le dossier médical</h2>
        <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations du patient</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Photo du patient" class="rounded-circle img-fluid" style="max-width: 120px;">
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5>Identité</h5>
                            <p class="mb-1"><strong>Nom complet:</strong> {{ $dossier->patient->utilisateur->nom ?? 'N/A' }} {{ $dossier->patient->utilisateur->prenom ?? '' }}</p>
                            <p class="mb-1"><strong>Date de naissance:</strong> {{ $dossier->patient->date_naissance ? \Carbon\Carbon::parse($dossier->patient->date_naissance)->format('d/m/Y') : 'N/A' }}</p>
                            <p class="mb-1"><strong>Sexe:</strong> {{ $dossier->patient->utilisateur->sexe == 'H' ? 'Homme' : 'Femme' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5>Coordonnées</h5>
                            <p class="mb-1"><strong>Téléphone:</strong> {{ $dossier->patient->utilisateur->telephone ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $dossier->patient->utilisateur->email ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Adresse:</strong> {{ $dossier->patient->adresse ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Informations du dossier médical</h5>
        </div>
        <div class="card-body">

        <form action="{{ route('secretaire.dossiers-medicaux.update', $dossier->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <h5 class="mb-3"><i class="fas fa-heartbeat me-2"></i>Informations médicales essentielles</h5>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="poids" class="form-label">Poids (kg)</label>
                        <input type="number" id="poids" name="poids" step="0.1" min="0" value="{{ old('poids', $dossier->poids) }}" 
                               class="form-control @error('poids') is-invalid @enderror">
                        @error('poids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="taille" class="form-label">Taille (cm)</label>
                        <input type="number" id="taille" name="taille" step="0.1" min="0" value="{{ old('taille', $dossier->taille) }}" 
                               class="form-control @error('taille') is-invalid @enderror">
                        @error('taille')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                        <select name="groupe_sanguin" id="groupe_sanguin" class="form-select @error('groupe_sanguin') is-invalid @enderror">
                            <option value="">Sélectionnez le groupe sanguin</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $groupe)
                                <option value="{{ $groupe }}" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == $groupe ? 'selected' : '' }}>{{ $groupe }}</option>
                            @endforeach
                        </select>
                        @error('groupe_sanguin') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="allergies" class="form-label">Allergies</label>
                    <textarea id="allergies" name="allergies" rows="2" 
                              class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies', is_array($dossier->allergies) ? implode(', ', $dossier->allergies) : $dossier->allergies) }}</textarea>
                    @error('allergies')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="form-text">Séparez les différentes allergies par des virgules</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="antecedents_medicaux" class="form-label">Antécédents médicaux</label>
                    <textarea id="antecedents_medicaux" name="antecedents_medicaux" rows="2" 
                              class="form-control @error('antecedents_medicaux') is-invalid @enderror">{{ old('antecedents_medicaux', is_array($dossier->antecedents_medicaux) ? implode(', ', $dossier->antecedents_medicaux) : $dossier->antecedents_medicaux) }}</textarea>
                    @error('antecedents_medicaux')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="form-text">Séparez les différents antécédents par des virgules</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="mb-3"><i class="fas fa-stethoscope me-2"></i>Informations de consultation</h5>
                
                <div class="mb-3">
                    <label for="motif_consultation" class="form-label">Motif de consultation <span class="text-danger">*</span></label>
                    <textarea id="motif_consultation" name="motif_consultation" rows="2" required
                              class="form-control @error('motif_consultation') is-invalid @enderror">{{ old('motif_consultation', $dossier->motif_consultation) }}</textarea>
                    @error('motif_consultation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="observations" class="form-label">Observations médicales</label>
                    <textarea id="observations" name="observations" rows="2" 
                              class="form-control @error('observations') is-invalid @enderror">{{ old('observations', $dossier->observations) }}</textarea>
                    @error('observations')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="traitements_chroniques" class="form-label">Traitements en cours</label>
                    <textarea id="traitements_chroniques" name="traitements_chroniques" rows="2" 
                              class="form-control @error('traitements_chroniques') is-invalid @enderror">{{ old('traitements_chroniques', is_array($dossier->traitements_chroniques) ? implode(', ', $dossier->traitements_chroniques) : $dossier->traitements_chroniques) }}</textarea>
                    @error('traitements_chroniques')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="form-text">Séparez les différents traitements par des virgules</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="statut" class="form-label">Statut du dossier</label>
                    <select name="statut" id="statut" class="form-select @error('statut') is-invalid @enderror">
                        <option value="ACTIF" {{ old('statut', strtoupper($dossier->statut)) == 'ACTIF' ? 'selected' : '' }}>Actif</option>
                        <option value="ARCHIVE" {{ old('statut', strtoupper($dossier->statut)) == 'ARCHIVE' ? 'selected' : '' }}>Archivé</option>
                        <option value="FERME" {{ old('statut', strtoupper($dossier->statut)) == 'FERME' ? 'selected' : '' }}>Fermé</option>
                    </select>
                    @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-times me-1"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
