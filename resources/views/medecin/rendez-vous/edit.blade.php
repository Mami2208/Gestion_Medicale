@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier le Rendez-vous</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medecin.rendez-vous.update', $rendezVous) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-3">
                            <label for="patient_id" class="col-md-4 col-form-label text-md-right">Patient</label>
                            <div class="col-md-6">
                                <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ (old('patient_id', $rendezVous->patient_id) == $patient->id) ? 'selected' : '' }}>
                                            {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="date_rendez_vous" class="col-md-4 col-form-label text-md-right">Date</label>
                            <div class="col-md-6">
                                <input type="date" class="form-control @error('date_rendez_vous') is-invalid @enderror" 
                                    name="date_rendez_vous" value="{{ old('date_rendez_vous', $rendezVous->date_rendez_vous) }}" required>
                                @error('date_rendez_vous')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="heure_rendez_vous" class="col-md-4 col-form-label text-md-right">Heure</label>
                            <div class="col-md-6">
                                <input type="time" class="form-control @error('heure_rendez_vous') is-invalid @enderror" 
                                    name="heure_rendez_vous" value="{{ old('heure_rendez_vous', $rendezVous->heure_rendez_vous) }}" required>
                                @error('heure_rendez_vous')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="motif" class="col-md-4 col-form-label text-md-right">Motif</label>
                            <div class="col-md-6">
                                <textarea name="motif" id="motif" class="form-control @error('motif') is-invalid @enderror" 
                                    required>{{ old('motif', $rendezVous->motif) }}</textarea>
                                @error('motif')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Mettre à jour
                                </button>
                                <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-secondary">
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 