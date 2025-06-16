@extends('layouts.medecin')

@section('title', 'Téléverser une image DICOM')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-upload me-2 text-primary"></i>
                                Téléverser une image DICOM
                            </h5>
                            <p class="text-muted mb-0 mt-1">
                                Patient: <strong>{{ $consultation->patient->utilisateur->nom }} {{ $consultation->patient->utilisateur->prenom }}</strong> | 
                                Consultation du {{ $consultation->date_consultation->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('medecin.consultations.dicom.viewer', $consultation) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour au visualisateur
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Erreur lors du téléversement</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <form id="dicomUploadForm" action="{{ route('medecin.consultations.dicom.upload.submit', $consultation) }}" method="POST" enctype="multipart/form-data" class="dropzone">
                                        @csrf
                                        
                                        <div class="text-center mb-4">
                                            <div class="mb-3">
                                                <i class="fas fa-file-medical text-primary" style="font-size: 3.5rem;"></i>
                                            </div>
                                            <h5>Glissez-déposez votre fichier DICOM ici</h5>
                                            <p class="text-muted mb-3">ou</p>
                                            <label for="dicom_file" class="btn btn-outline-primary btn-lg">
                                                <i class="fas fa-folder-open me-2"></i> Sélectionner un fichier
                                            </label>
                                            <input type="file" class="d-none" id="dicom_file" name="dicom_file" accept=".dcm,application/dicom,application/octet-stream" required>
                                            <div class="form-text mt-2">Formats acceptés: .dcm (taille max: 20MB)</div>
                                            
                                            <!-- Zone d'affichage des informations du fichier -->
                                            <div id="fileInfo" class="file-info mt-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">Fichier sélectionné :</h6>
                                                    <button type="button" id="removeFile" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-times"></i> Supprimer
                                                    </button>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div><strong>Nom :</strong> <span id="fileName"></span></div>
                                                    <div><strong>Taille :</strong> <span id="fileSize"></span></div>
                                                    <div><strong>Type :</strong> <span id="fileType"></span></div>
                                                </div>
                                                <img id="filePreview" src="#" alt="Aperçu" class="img-fluid mt-2 d-none">
                                            </div>
                                        </div>
                                        
                                        <!-- Conteneur pour les alertes -->
                                        <div id="alertContainer" class="mb-3"></div>

                                        <div class="mb-4">
                                            <label for="study_description" class="form-label fw-semibold">Description de l'étude</label>
                                            <input type="text" class="form-control form-control-lg" id="study_description" name="study_description" 
                                                   value="{{ old('study_description', 'Étude du ' . now()->format('d/m/Y')) }}" placeholder="Ex: Radiographie pulmonaire - Face">
                                            <div class="form-text">Donnez un nom ou une description pour cette étude DICOM</div>
                                        </div>

                                        <div class="progress mb-3 d-none" id="uploadProgressContainer">
                                            <div id="uploadProgress" class="progress-bar progress-bar-striped progress-bar-animated" 
                                                role="progressbar" style="width: 0%; height: 25px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                <span class="progress-text fw-medium">0%</span>
                                            </div>
                                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medecin.consultations.show', $consultation) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour à la consultation
                            </a>
                            <button type="submit" class="btn btn-primary" id="uploadButton">
                                <i class="fas fa-upload"></i> Téléverser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="uploadSuccessModal" tabindex="-1" aria-labelledby="uploadSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="uploadSuccessModalLabel">Téléversement réussi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Le fichier DICOM a été téléversé avec succès.</p>
                <p class="mb-0">Voulez-vous visualiser l'image ou revenir à la consultation ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="viewDicomLink" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Visualiser
                </a>
                <a href="{{ route('medecin.consultations.show', $consultation) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Retour à la consultation
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styles pour la zone de dépôt */
    .drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        margin-bottom: 1.5rem;
    }
    
    .drop-zone:hover, .drop-zone.dragover {
        border-color: #4e73df;
        background-color: #f1f7ff;
    }
    
    .drop-zone i {
        font-size: 2.5rem;
        color: #4e73df;
        margin-bottom: 1rem;
    }
    
    .file-info {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        border: 1px solid #e3e6f0;
    }
    
    .file-info.show {
        display: block;
    }
    
    .file-preview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 1rem;
        display: none;
        border-radius: 0.25rem;
    }
    
    .file-preview.show {
        display: block;
    }
    
    .progress {
        height: 1.5rem;
    }
    
    .progress-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const form = document.getElementById('dicomUploadForm');
    const uploadButton = document.getElementById('uploadButton');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressText = document.querySelector('.progress-text');
    const uploadProgressContainer = document.getElementById('uploadProgressContainer');
    const viewDicomLink = document.getElementById('viewDicomLink');
    const fileInput = document.getElementById('dicom_file');
    const dropZone = document.querySelector('.drop-zone');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileType = document.getElementById('fileType');
    const filePreview = document.getElementById('filePreview');
    const removeFileBtn = document.getElementById('removeFile');
    const alertContainer = document.getElementById('alertContainer');
    
    // Fonction pour formater la taille du fichier
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Fonction pour afficher les informations du fichier
    function displayFileInfo(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileType.textContent = file.type || 'application/dicom';
        fileInfo.classList.add('show');
        
        // Afficher un aperçu si c'est une image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                filePreview.src = e.target.result;
                filePreview.classList.add('show');
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Gestion du glisser-déposer
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('dragover');
    }
    
    function unhighlight() {
        dropZone.classList.remove('dragover');
    }
    
    // Gestion du dépôt de fichier
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }
    
    // Gestion de la sélection de fichier via le bouton
    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            handleFiles(this.files);
        }
    });
    
    // Gestion de la suppression du fichier
    removeFileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        resetFileInput();
    });
    
    function resetFileInput() {
        fileInput.value = '';
        fileInfo.classList.remove('show');
        filePreview.classList.remove('show');
        filePreview.src = '#';
    }
    
    function handleFiles(files) {
        const file = files[0];
        
        // Vérifier le type de fichier
        const validTypes = ['application/dicom', 'application/octet-stream'];
        const fileExt = file.name.split('.').pop().toLowerCase();
        
        if (!validTypes.includes(file.type) && fileExt !== 'dcm') {
            showAlert('danger', 'Format de fichier non supporté. Veuillez sélectionner un fichier DICOM (.dcm)');
            return;
        }
        
        // Vérifier la taille du fichier (max 20MB)
        const maxSize = 20 * 1024 * 1024; // 20MB
        if (file.size > maxSize) {
            showAlert('danger', 'Le fichier est trop volumineux. La taille maximale autorisée est de 20 Mo.');
            return;
        }
        
        // Afficher les informations du fichier
        displayFileInfo(file);
    }
    
    // Fonction pour afficher les alertes
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="${type === 'danger' ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        `;
        
        // Ajouter l'alerte en haut du conteneur
        alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Supprimer l'alerte après 5 secondes
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
    
    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Vérifier si un fichier est sélectionné
        if (!fileInput.files.length) {
            showAlert('warning', 'Veuillez sélectionner un fichier DICOM à téléverser.');
            return;
        }
        
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        
        // Afficher la barre de progression
        uploadProgressContainer.classList.remove('d-none');
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Téléversement...';
        
        // Suivre la progression de l'upload
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                uploadProgress.style.width = percentComplete + '%';
                uploadProgress.setAttribute('aria-valuenow', percentComplete);
                progressText.textContent = percentComplete + '%';
                
                if (percentComplete === 100) {
                    progressText.textContent = 'Traitement en cours...';
                }
            }
        });
        
        // Gérer la réponse
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                uploadButton.disabled = false;
                uploadButton.innerHTML = '<i class="fas fa-upload me-2"></i> Téléverser';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (xhr.status === 200 && response.success) {
                        // Mettre à jour le lien de visualisation
                        viewDicomLink.href = '{{ route("medecin.consultations.dicom.viewer", $consultation) }}?study=' + response.data.ID;
                        
                        // Afficher la modal de succès
                        const modal = new bootstrap.Modal(document.getElementById('uploadSuccessModal'));
                        modal.show();
                        
                        // Réinitialiser le formulaire
                        form.reset();
                        resetFileInput();
                        uploadProgressContainer.classList.add('d-none');
                        uploadProgress.style.width = '0%';
                        progressText.textContent = '0%';
                    } else {
                        // Afficher les erreurs
                        const errorMessage = response.message || 'Une erreur est survenue lors du téléversement';
                        showAlert('danger', errorMessage);
                    }
                } catch (e) {
                    console.error('Erreur lors du traitement de la réponse:', e);
                    showAlert('danger', 'Une erreur est survenue lors du traitement de la réponse du serveur');
                }
            }
        };
        
        // Gérer les erreurs réseau
        xhr.onerror = function() {
            uploadButton.disabled = false;
            uploadButton.innerHTML = '<i class="fas fa-upload me-2"></i> Téléverser';
            showAlert('danger', 'Erreur de connexion. Veuillez vérifier votre connexion Internet et réessayer.');
        };
        
        // Envoyer la requête
        xhr.open('POST', '{{ route("medecin.consultations.dicom.upload", $consultation) }}', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    });
    
    // Initialiser les tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
