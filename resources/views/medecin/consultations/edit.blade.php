@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier la Consultation</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medecin.consultations.update', $consultation) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">Sélectionnez un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id', $consultation->patient_id) == $patient->id) ? 'selected' : '' }}>
                                        {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_consultation" class="form-label">Date de consultation</label>
                            <input type="datetime-local" class="form-control @error('date_consultation') is-invalid @enderror" 
                                id="date_consultation" name="date_consultation" 
                                value="{{ old('date_consultation', \Carbon\Carbon::parse($consultation->date_consultation)->format('Y-m-d\TH:i')) }}" required>
                            @error('date_consultation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif de consultation</label>
                            <textarea class="form-control @error('motif') is-invalid @enderror" 
                                id="motif" name="motif" rows="3" required>{{ old('motif', $consultation->motif) }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="symptomes" class="form-label">Symptômes</label>
                            <textarea class="form-control @error('symptomes') is-invalid @enderror" 
                                id="symptomes" name="symptomes" rows="3">{{ old('symptomes', $consultation->symptomes) }}</textarea>
                            @error('symptomes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diagnostic" class="form-label">Diagnostic</label>
                            <textarea class="form-control @error('diagnostic') is-invalid @enderror" 
                                id="diagnostic" name="diagnostic" rows="3">{{ old('diagnostic', $consultation->diagnostic) }}</textarea>
                            @error('diagnostic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="traitement" class="form-label">Traitement</label>
                            <textarea class="form-control @error('traitement') is-invalid @enderror" 
                                id="traitement" name="traitement" rows="3">{{ old('traitement', $consultation->traitement) }}</textarea>
                            @error('traitement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observations" class="form-label">Observations</label>
                            <textarea class="form-control @error('observations') is-invalid @enderror" 
                                id="observations" name="observations" rows="3">{{ old('observations', $consultation->observations) }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medecin.consultations.show', $consultation) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour la consultation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 