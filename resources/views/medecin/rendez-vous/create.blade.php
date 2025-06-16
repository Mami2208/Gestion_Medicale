@extends('layouts.medecin')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <!-- En-tête -->
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Nouveau Rendez-vous
                    </h4>
                </div>

                <!-- Corps du formulaire -->
                <div class="card-body">
                    <form method="POST" action="{{ route('medecin.rendez-vous.store') }}">
                        @csrf

                        <!-- Section Patient -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>
                                    Informations du patient
                                </h5>
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient</label>
                                    <select name="patient_id" id="patient_id" 
                                            class="form-select @error('patient_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Sélectionnez un patient</option>
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

                        <!-- Section Date et Heure -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    Date et heure du rendez-vous
                                </h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="date_rendez_vous" class="form-label">Date</label>
                                        <input type="date" name="date_rendez_vous" id="date_rendez_vous" 
                                               class="form-control @error('date_rendez_vous') is-invalid @enderror" 
                                               value="{{ old('date_rendez_vous') }}" required>
                                        @error('date_rendez_vous')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="heure_debut" class="form-label">Heure de début</label>
                                        <input type="time" name="heure_debut" id="heure_debut" 
                                               class="form-control @error('heure_debut') is-invalid @enderror" 
                                               value="{{ old('heure_debut') }}" required>
                                        @error('heure_debut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="heure_fin" class="form-label">Heure de fin</label>
                                        <input type="time" name="heure_fin" id="heure_fin" 
                                               class="form-control @error('heure_fin') is-invalid @enderror" 
                                               value="{{ old('heure_fin') }}" required>
                                        @error('heure_fin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Motif -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    <i class="fas fa-comment-medical me-2 text-primary"></i>
                                    Détails du rendez-vous
                                </h5>
                                <div class="mb-3">
                                    <label for="motif" class="form-label">Motif de la consultation</label>
                                    <textarea name="motif" id="motif" rows="4" 
                                              class="form-control @error('motif') is-invalid @enderror" 
                                              placeholder="Décrivez le motif de la consultation..."
                                              required>{{ old('motif') }}</textarea>
                                    @error('motif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('medecin.rendez-vous.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer le rendez-vous
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush 