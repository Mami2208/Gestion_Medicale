@extends('layouts.infirmier')

@section('title', 'Nouvelle observation - ' . $patient->utilisateur->prenom . ' ' . $patient->utilisateur->nom)

@section('content')
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-notes-medical me-2"></i>
                    Nouvelle observation pour {{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}
                </h5>
                <a href="{{ route('infirmier.patients.show', $patient->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('infirmier.observations.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="infirmier_id" value="{{ $infirmier->id }}">
                

                
                <div class="mb-3">
                    <label for="contenu" class="form-label">Contenu détaillé</label>
                    <textarea class="form-control @error('contenu') is-invalid @enderror" 
                              id="contenu" name="contenu" rows="6" required>{{ old('contenu') }}</textarea>
                    @error('contenu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_observation" class="form-label">Date de l'observation</label>
                        <input type="datetime-local" class="form-control @error('date_observation') is-invalid @enderror" 
                               id="date_observation" name="date_observation" 
                               value="{{ old('date_observation', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('date_observation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="type_observation" class="form-label">Type d'observation</label>
                        <select class="form-select @error('type_observation') is-invalid @enderror" 
                                id="type_observation" name="type_observation" required>
                            <option value="" disabled {{ old('type_observation') ? '' : 'selected' }}>Sélectionnez un type</option>
                            <option value="examen" {{ old('type_observation') == 'examen' ? 'selected' : '' }}>Examen clinique</option>
                            <option value="suivi" {{ old('type_observation') == 'suivi' ? 'selected' : '' }}>Suivi de traitement</option>
                            <option value="symptome" {{ old('type_observation') == 'symptome' ? 'selected' : '' }}>Symptôme</option>
                            <option value="observation" {{ old('type_observation') == 'observation' ? 'selected' : '' }}>Observation générale</option>
                            <option value="autre" {{ old('type_observation') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_observation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" 
                               id="est_important" name="est_important" {{ old('est_important') ? 'checked' : '' }}>
                        <label class="form-check-label" for="est_important">
                            Marquer comme important
                        </label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer l'observation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialisation des éléments nécessitant du JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
