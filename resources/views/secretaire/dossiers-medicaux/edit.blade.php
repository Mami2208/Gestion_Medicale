@extends('secretaire.layouts.app')

@section('title', 'Modifier le dossier médical - ' . $dossier->numero_dossier)

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit"></i> Modifier le dossier médical #{{ $dossier->numero_dossier }}
            </h6>
            <div>
                <a href="{{ route('secretaire.dossiers-medicaux.show', $dossier->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Voir
                </a>
                <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('secretaire.dossiers-medicaux.update', $dossier->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations du patient</h5>
                        <div class="form-group">
                            <label for="patient_id" class="form-label">Sélectionner un patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-control select2" required>
                                <option value="">Sélectionner un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $dossier->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }} 
                                        ({{ $patient->utilisateur->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">Médecin traitant</h5>
                        <div class="form-group">
                            <label for="medecin_id" class="form-label">Sélectionner un médecin <span class="text-danger">*</span></label>
                            <select name="medecin_id" id="medecin_id" class="form-control select2" required>
                                <option value="">Sélectionner un médecin</option>
                                @foreach($medecins as $medecin)
                                    <option value="{{ $medecin->id }}" {{ old('medecin_id', $dossier->medecin_id) == $medecin->id ? 'selected' : '' }}>
                                        Dr. {{ $medecin->utilisateur->prenom }} {{ $medecin->utilisateur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">Informations du dossier</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero_dossier" class="form-label">Numéro de dossier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero_dossier" name="numero_dossier" 
                                           value="{{ old('numero_dossier', $dossier->numero_dossier) }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_creation" class="form-label">Date de création <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_creation" name="date_creation" 
                                           value="{{ old('date_creation', $dossier->date_creation ? $dossier->date_creation->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                                    <select name="groupe_sanguin" id="groupe_sanguin" class="form-control">
                                        <option value="">Sélectionner</option>
                                        <option value="A+" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('groupe_sanguin', $dossier->groupe_sanguin) == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motif_consultation" class="form-label">Motif de la consultation</label>
                                    <input type="text" class="form-control" id="motif_consultation" name="motif_consultation" 
                                           value="{{ old('motif_consultation', $dossier->motif_consultation) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="taille" class="form-label">Taille (cm)</label>
                                    <input type="number" class="form-control" id="taille" name="taille" 
                                           value="{{ old('taille', $dossier->taille) }}" min="0" max="300" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="poids" class="form-label">Poids (kg)</label>
                                    <input type="number" class="form-control" id="poids" name="poids" 
                                           value="{{ old('poids', $dossier->poids) }}" min="0" max="500" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observations" class="form-label">Observations générales</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="1">{{ old('observations', $dossier->observations) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="allergies" class="form-label">Allergies connues</label>
                            <textarea class="form-control" id="allergies" name="allergies" rows="3">{{ old('allergies', $dossier->allergies) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="traitements_en_cours" class="form-label">Traitements en cours</label>
                            <textarea class="form-control" id="traitements_en_cours" name="traitements_en_cours" rows="3">{{ old('traitements_en_cours', $dossier->traitements_en_cours) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="antecedents_medicaux" class="form-label">Antécédents médicaux</label>
                            <textarea class="form-control" id="antecedents_medicaux" name="antecedents_medicaux" rows="5">{{ old('antecedents_medicaux', $dossier->antecedents_medicaux) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="remarques" class="form-label">Remarques supplémentaires</label>
                            <textarea class="form-control" id="remarques" name="remarques" rows="3">{{ old('remarques', $dossier->remarques) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endpush
