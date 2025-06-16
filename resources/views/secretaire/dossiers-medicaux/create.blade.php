@extends('secretaire.layouts.app')

@section('title', 'Créer un nouveau dossier médical')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Nouveau dossier médical</h5>
            <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-sm btn-secondary float-end">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
        <div class="card-body">
            <form id="dossierMedicalForm" action="{{ route('secretaire.dossiers-medicaux.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations du patient</h5>
                        
                        <div class="card mt-3 mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informations du nouveau patient</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                                            <select class="form-select" id="sexe" name="sexe">
                                                <option value="">Sélectionnez</option>
                                                <option value="H" {{ old('sexe') == 'H' ? 'selected' : '' }}>Homme</option>
                                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Femme</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <small class="form-text text-muted">Minimum 8 caractères</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse') }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
                                            <select class="form-select" id="groupe_sanguin" name="groupe_sanguin">
                                                <option value="">Sélectionnez</option>
                                                <option value="A+" {{ old('groupe_sanguin') == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('groupe_sanguin') == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('groupe_sanguin') == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('groupe_sanguin') == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('groupe_sanguin') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('groupe_sanguin') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('groupe_sanguin') == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('groupe_sanguin') == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="antecedents_medicaux" class="form-label">Antécédents médicaux</label>
                                    <textarea class="form-control" id="antecedents_medicaux" name="antecedents_medicaux" rows="2">{{ old('antecedents_medicaux') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="allergies" class="form-label">Allergies</label>
                                    <textarea class="form-control" id="allergies" name="allergies" rows="2">{{ old('allergies') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="traitements_en_cours" class="form-label">Traitements en cours</label>
                                    <textarea class="form-control" id="traitements_en_cours" name="traitements_en_cours" rows="2">{{ old('traitements_en_cours') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">Médecin traitant</h5>
                        <div class="form-group">
                            <label for="medecin_id" class="form-label">Sélectionner un médecin <span class="text-danger">*</span></label>
                            <select name="medecin_id" id="medecin_id" class="form-control select2" required>
                                <option value="">Sélectionner un médecin</option>
                                @foreach($medecins as $medecin)
                                    <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
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
                        <div class="form-group">
                            <label for="motif_consultation" class="form-label">Motif de la consultation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="motif_consultation" name="motif_consultation" 
                                   value="{{ old('motif_consultation') }}" required>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="taille" class="form-label">Taille (cm)</label>
                                    <input type="number" class="form-control" id="taille" name="taille" 
                                           value="{{ old('taille') }}" min="0" max="300" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="poids" class="form-label">Poids (kg)</label>
                                    <input type="number" class="form-control" id="poids" name="poids" 
                                           value="{{ old('poids') }}" min="0" max="500" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observations" class="form-label">Observations générales</label>
                                    <textarea class="form-control" id="observations" name="observations" rows="1">{{ old('observations') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="remarques" class="form-label">Remarques supplémentaires</label>
                            <textarea class="form-control" id="remarques" name="remarques" rows="3">{{ old('remarques') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Enregistrer
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fonction pour vérifier si le formulaire est valide
    function isFormValid() {
        // Réinitialiser les messages d'erreur
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        let isValid = true;
        
        // Vérifier les champs requis du patient
        const requiredPatientFields = ['#nom', '#prenom', '#date_naissance', '#sexe', '#telephone', '#password', '#password_confirmation'];
        
        requiredPatientFields.forEach(field => {
            if (!$(field).val()) {
                $(field).addClass('is-invalid');
                $(field).after('<div class="invalid-feedback">Ce champ est obligatoire</div>');
                isValid = false;
            }
        });

        // Vérifier la correspondance des mots de passe
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        const passwordsMatch = password === confirmPassword;
        const isPasswordValid = password.length >= 8;

        if (password && !isPasswordValid) {
            $('#password').addClass('is-invalid');
            $('#password').after('<div class="invalid-feedback">Le mot de passe doit contenir au moins 8 caractères</div>');
            isValid = false;
        }

        if (password && confirmPassword && !passwordsMatch) {
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation').after('<div class="invalid-feedback">Les mots de passe ne correspondent pas</div>');
            isValid = false;
        }

        // Vérifier le médecin
        if (!$('#medecin_id').val()) {
            $('#medecin_id').addClass('is-invalid');
            $('#medecin_id').after('<div class="invalid-feedback">Veuillez sélectionner un médecin</div>');
            isValid = false;
        }

        // Vérifier le motif de consultation
        const motif = $('#motif_consultation').val().trim();
        if (!motif) {
            $('#motif_consultation').addClass('is-invalid');
            $('#motif_consultation').after('<div class="invalid-feedback">Veuillez indiquer le motif de la consultation</div>');
            isValid = false;
        }

        return isValid && passwordsMatch && isPasswordValid;
    }

    // Fonction pour afficher les erreurs de validation
    function showValidationError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur de validation',
            text: message,
            confirmButtonText: 'OK'
        });
    }

    $(document).ready(function() {
        // Initialisation de Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Gérer la soumission du formulaire
        $('#dossierMedicalForm').on('submit', function(e) {
            e.preventDefault();
            
            // Vérifier la validité du formulaire
            if (!isFormValid()) {
                showValidationError('Veuillez remplir tous les champs obligatoires.');
                return false;
            }

            // Soumettre le formulaire via AJAX
            const formData = $(this).serialize();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message || 'Opération effectuée avec succès',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = response.redirect || '{{ route('secretaire.dossiers-medicaux.index') }}';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.message || 'Une erreur est survenue',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Une erreur est survenue lors de la soumission du formulaire.';
                    
                    if (xhr.status === 422) {
                        // Erreurs de validation
                        const errors = xhr.responseJSON.errors;
                        errorMessage = 'Veuillez corriger les erreurs suivantes :\n';
                        
                        for (const field in errors) {
                            errorMessage += `\n- ${errors[field][0]}`;
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endpush
