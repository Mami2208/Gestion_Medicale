@extends('secretaire.layouts.app')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Nouveau rendez-vous</h5>
                <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('secretaire.rendez-vous.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Informations du patient -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Patient</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Sélectionner un patient <span class="text-danger">*</span></label>
                                    <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner un patient --</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                @if($patient->utilisateur)
                                                    {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                                @else
                                                    Patient #{{ $patient->id }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations du médecin -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Médecin</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="medecin_id" class="form-label">Sélectionner un médecin <span class="text-danger">*</span></label>
                                    <select name="medecin_id" id="medecin_id" class="form-select @error('medecin_id') is-invalid @enderror" required>
                                        <option value="">-- Sélectionner un médecin --</option>
                                        @foreach($medecins as $medecin)
                                            <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                                                @if($medecin->utilisateur)
                                                    Dr. {{ $medecin->utilisateur->nom }} {{ $medecin->utilisateur->prenom }}
                                                    @if($medecin->specialite)
                                                        - {{ $medecin->specialite }}
                                                    @endif
                                                @else
                                                    Médecin #{{ $medecin->id }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('medecin_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date et heure -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Date et heure du rendez-vous</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="date_rendez_vous" class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="date_rendez_vous" id="date_rendez_vous" 
                                            class="form-control @error('date_rendez_vous') is-invalid @enderror"
                                            value="{{ old('date_rendez_vous') ?? date('Y-m-d') }}" required>
                                        @error('date_rendez_vous')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="heure_debut" class="form-label">Heure de début <span class="text-danger">*</span></label>
                                        <input type="time" name="heure_debut" id="heure_debut" 
                                            class="form-control @error('heure_debut') is-invalid @enderror"
                                            value="{{ old('heure_debut') ?? '09:00' }}" required>
                                        @error('heure_debut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="heure_fin" class="form-label">Heure de fin <span class="text-danger">*</span></label>
                                        <input type="time" name="heure_fin" id="heure_fin" 
                                            class="form-control @error('heure_fin') is-invalid @enderror"
                                            value="{{ old('heure_fin') ?? '09:30' }}" required>
                                        <small class="form-text text-muted">L'heure de fin doit être postérieure à l'heure de début</small>
                                        @error('heure_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Motif de consultation -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Détails du rendez-vous</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="motif" class="form-label">Motif du rendez-vous <span class="text-danger">*</span></label>
                                    <textarea name="motif" id="motif" rows="4" 
                                        class="form-control @error('motif') is-invalid @enderror"
                                        placeholder="Décrivez le motif de la consultation..." required>{{ old('motif') }}</textarea>
                                    @error('motif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer le rendez-vous
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
