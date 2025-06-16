@extends('secretaire.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Nouveau dossier médical</h5>
                <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('secretaire.dossiers-medicaux.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Informations du patient -->
                    <h4 class="mb-3">Informations du patient</h4>
                    
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" class="form-control @error('prenom') is-invalid @enderror" required>
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                        <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}" class="form-control @error('date_naissance') is-invalid @enderror" required>
                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                        <select name="sexe" id="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                            <option value="">Sélectionnez le sexe</option>
                            <option value="H" {{ old('sexe') == 'H' ? 'selected' : '' }}>Homme</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('sexe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" class="form-control @error('telephone') is-invalid @enderror" required>
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" class="form-control @error('adresse') is-invalid @enderror">
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control @error('mot_de_passe') is-invalid @enderror">
                        <small class="form-text text-muted">Facultatif. Si renseigné, un compte patient sera créé.</small>
                        @error('mot_de_passe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                        <select name="groupe_sanguin" id="groupe_sanguin" class="form-select @error('groupe_sanguin') is-invalid @enderror">
                            <option value="">Sélectionnez le groupe sanguin</option>
                            <option value="A+" {{ old('groupe_sanguin') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('groupe_sanguin') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('groupe_sanguin') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('groupe_sanguin') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('groupe_sanguin') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('groupe_sanguin') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('groupe_sanguin') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('groupe_sanguin') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('groupe_sanguin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Séparateur -->
                    <hr class="my-4">
                    <h4 class="mb-3">Informations du dossier médical</h4>

                    <div class="col-md-6 mb-3">
                        <label for="medecin_id" class="form-label">Médecin <span class="text-danger">*</span></label>
                        <select name="medecin_id" id="medecin_id" class="form-select @error('medecin_id') is-invalid @enderror" required>
                            <option value="">Sélectionnez un médecin</option>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                                    Dr. {{ $medecin->utilisateur->prenom }} {{ $medecin->utilisateur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('medecin_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="motif_consultation" class="form-label">Motif de consultation <span class="text-danger">*</span></label>
                        <textarea name="motif_consultation" id="motif_consultation" class="form-control @error('motif_consultation') is-invalid @enderror" rows="3" required>{{ old('motif_consultation') }}</textarea>
                        @error('motif_consultation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="antecedents" class="form-label">Antécédents médicaux</label>
                        <textarea name="antecedents" id="antecedents" class="form-control @error('antecedents') is-invalid @enderror" rows="3">{{ old('antecedents') }}</textarea>
                        <small class="form-text text-muted">Précisez les antécédents médicaux du patient (maladies chroniques, opérations chirurgicales...)</small>
                        @error('antecedents')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="allergies" class="form-label">Allergies connues</label>
                        <textarea name="allergies" id="allergies" class="form-control @error('allergies') is-invalid @enderror" rows="3">{{ old('allergies') }}</textarea>
                        <small class="form-text text-muted">Précisez les allergies connues (médicaments, aliments, etc.)</small>
                        @error('allergies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Créer le dossier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
