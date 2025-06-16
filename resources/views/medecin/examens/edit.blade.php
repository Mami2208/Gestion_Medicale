@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier l'Examen Médical</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medecin.examens.update', $examen) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                <option value="">Sélectionnez un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id', $examen->patient_id) == $patient->id) ? 'selected' : '' }}>
                                        {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date_examen" class="form-label">Date de l'examen</label>
                            <input type="datetime-local" class="form-control @error('date_examen') is-invalid @enderror" 
                                id="date_examen" name="date_examen" 
                                value="{{ old('date_examen', \Carbon\Carbon::parse($examen->date_examen)->format('Y-m-d\TH:i')) }}" required>
                            @error('date_examen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type_examen" class="form-label">Type d'examen</label>
                            <input type="text" class="form-control @error('type_examen') is-invalid @enderror" 
                                id="type_examen" name="type_examen" value="{{ old('type_examen', $examen->type_examen) }}" required>
                            @error('type_examen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" required>{{ old('description', $examen->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="resultats" class="form-label">Résultats</label>
                            <textarea class="form-control @error('resultats') is-invalid @enderror" 
                                id="resultats" name="resultats" rows="3" required>{{ old('resultats', $examen->resultats) }}</textarea>
                            @error('resultats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="conclusion" class="form-label">Conclusion</label>
                            <textarea class="form-control @error('conclusion') is-invalid @enderror" 
                                id="conclusion" name="conclusion" rows="3" required>{{ old('conclusion', $examen->conclusion) }}</textarea>
                            @error('conclusion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observations" class="form-label">Observations</label>
                            <textarea class="form-control @error('observations') is-invalid @enderror" 
                                id="observations" name="observations" rows="3">{{ old('observations', $examen->observations) }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medecin.examens.show', $examen) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour l'examen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 