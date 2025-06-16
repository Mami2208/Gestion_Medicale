@extends('layouts.medecin')

@section('title', 'Nouvelle Consultation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class='bx bx-clipboard-plus'></i> Nouvelle Consultation
                    </h3>
                    <a href="{{ route('medecin.consultations.index') }}" class="btn btn-secondary">
                        <i class='bx bx-arrow-back'></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medecin.consultations.store') }}" class="needs-validation" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="row">
                            <!-- Informations du patient -->
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class='bx bx-user'></i> Informations du patient
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="patient_id" class="form-label">Sélectionner un patient</label>
                                            <select class="form-select select2 @error('patient_id') is-invalid @enderror" 
                                                    id="patient_id" name="patient_id" required>
                                                <option value="">Rechercher un patient...</option>
                                                @foreach($patients as $patient)
                                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                        @if($patient->utilisateur)
                                                            {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                                            ({{ $patient->utilisateur->telephone }})
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

                                        <div id="patient-info" class="d-none">
                                            <div class="text-center mb-3">
                                                <div class="avatar avatar-xl mb-2">
                                                    <img src="" alt="Photo patient" class="rounded-circle" id="patient-photo">
                                                </div>
                                                <h5 id="patient-name"></h5>
                                                <p class="text-muted" id="patient-details"></p>
                                            </div>
                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Date de naissance</span>
                                                        <span id="patient-birthdate"></span>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Sexe</span>
                                                        <span id="patient-gender"></span>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Groupe sanguin</span>
                                                        <span id="patient-blood"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Détails de la consultation -->
                            <div class="col-md-8">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class='bx bx-clipboard'></i> Détails de la consultation
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date_consultation" class="form-label">Date et heure</label>
                                                    <input type="datetime-local" 
                                                           class="form-control @error('date_consultation') is-invalid @enderror" 
                                                           id="date_consultation" 
                                                           name="date_consultation" 
                                                           value="{{ old('date_consultation', now()->format('Y-m-d\TH:i')) }}" 
                                                           required>
                                                    @error('date_consultation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="statut" class="form-label">Statut</label>
                                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                                            id="statut" name="statut" required>
                                                        <option value="PLANIFIE" {{ old('statut') == 'PLANIFIE' ? 'selected' : '' }}>Planifié</option>
                                                        <option value="EN_COURS" {{ old('statut') == 'EN_COURS' ? 'selected' : '' }}>En cours</option>
                                                        <option value="TERMINE" {{ old('statut') == 'TERMINE' ? 'selected' : '' }}>Terminé</option>
                                                    </select>
                                                    @error('statut')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="motif" class="form-label">Motif de consultation</label>
                                            <textarea class="form-control @error('motif') is-invalid @enderror" 
                                                      id="motif" name="motif" rows="2" required>{{ old('motif') }}</textarea>
                                            @error('motif')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="symptomes" class="form-label">Symptômes</label>
                                            <textarea class="form-control @error('symptomes') is-invalid @enderror" 
                                                      id="symptomes" name="symptomes" rows="2">{{ old('symptomes') }}</textarea>
                                            @error('symptomes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="diagnostic" class="form-label">Diagnostic</label>
                                            <textarea class="form-control @error('diagnostic') is-invalid @enderror" 
                                                      id="diagnostic" name="diagnostic" rows="2">{{ old('diagnostic') }}</textarea>
                                            @error('diagnostic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="traitement" class="form-label">Traitement</label>
                                            <textarea class="form-control @error('traitement') is-invalid @enderror" 
                                                      id="traitement" name="traitement" rows="2">{{ old('traitement') }}</textarea>
                                            @error('traitement')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="observations" class="form-label">Observations</label>
                                            <textarea class="form-control @error('observations') is-invalid @enderror" 
                                                      id="observations" name="observations" rows="2">{{ old('observations') }}</textarea>
                                            @error('observations')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 border-top pt-3">
                                            <h5 class="text-success mb-3"><i class="fas fa-x-ray me-2"></i> Images médicales DICOM</h5>
                                            
                                            <div class="mb-3">
                                                <label for="dicom_files" class="form-label">Télécharger des images DICOM</label>
                                                <input class="form-control @error('dicom_files') is-invalid @enderror" 
                                                      type="file" id="dicom_files" name="dicom_files[]" multiple 
                                                      accept=".dcm,.dicom,application/dicom" />
                                                <div class="form-text">
                                                    Formats acceptés : fichiers DICOM (.dcm, .dicom). Taille maximale : 50 Mo par fichier.
                                                </div>
                                                @error('dicom_files')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="alert alert-info" role="alert">
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-info-circle fa-2x"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="alert-heading">Import d'images DICOM</h5>
                                                        <p class="mb-0">Les images sélectionnées seront automatiquement associées au dossier médical du patient et pourront être consultées via le visualiseur DICOM.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-light">
                                    <i class='bx bx-reset'></i> Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> Enregistrer la consultation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .avatar {
        width: 100px;
        height: 100px;
        overflow: hidden;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .form-label {
        font-weight: 500;
    }

    .list-group-item {
        padding: 0.75rem 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: 'Rechercher un patient...',
        allowClear: true
    });

    // Gestion des informations du patient
    $('#patient_id').on('change', function() {
        const patientId = $(this).val();
        if (patientId) {
            // Simuler une requête AJAX pour obtenir les informations du patient
            // Dans un cas réel, vous devriez faire une requête AJAX vers votre backend
            const patientInfo = $('#patient-info');
            patientInfo.removeClass('d-none');
            
            // Mettre à jour les informations du patient (à adapter selon votre structure de données)
            $('#patient-name').text($(this).find('option:selected').text());
            $('#patient-photo').attr('src', '/images/default-avatar.png');
            $('#patient-details').text('Patient régulier');
            $('#patient-birthdate').text('01/01/1990');
            $('#patient-gender').text('Masculin');
            $('#patient-blood').text('O+');
        } else {
            $('#patient-info').addClass('d-none');
        }
    });

    // Validation du formulaire
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endpush 