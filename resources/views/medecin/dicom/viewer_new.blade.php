@extends('layouts.medecin')

@section('title', 'Visionneuse DICOM')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('medecin.dashboard') }}">
                            <i class="fas fa-home me-1"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('medecin.dicom.index') }}">Études DICOM</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Visionneuse DICOM</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">
                    <i class="fas fa-x-ray text-primary me-2"></i>Visionneuse DICOM
                </h2>
                <div>
                    <a href="{{ $study->viewer_url }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-external-link-alt me-1"></i> Ouvrir dans Orthanc
                    </a>
                    <a href="{{ route('medecin.dicom.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-circle me-2 text-primary"></i>Informations du patient
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th style="width: 150px;">Nom :</th>
                                            <td>{{ $study->patient->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de naissance :</th>
                                            <td>{{ $study->patient->date_naissance_formatted ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>N° sécurité sociale :</th>
                                            <td>{{ $study->patient->numero_securite_sociale ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-file-medical me-2 text-primary"></i>Informations de l'étude
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th style="width: 150px;">Date de l'étude :</th>
                                            <td>{{ $study->study_date ? $study->study_date->format('d/m/Y H:i') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description :</th>
                                            <td>{{ $study->description ?? 'Aucune description' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ajouté par :</th>
                                            <td>{{ $study->uploader->name ?? 'Système' }}, le {{ $study->created_at ? $study->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-images me-2 text-primary"></i>Visualisation DICOM
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Cliquez sur le bouton "Ouvrir dans Orthanc" ci-dessus pour visualiser les images DICOM dans le visualiseur Orthanc.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .dicom-viewer-container {
        min-height: 500px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1rem 0;
    }
    
    .dicom-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }
    
    .dicom-toolbar {
        display: flex;
        gap: 0.5rem;
    }
    
    .dicom-nav {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .dicom-nav-item {
        flex: 1;
        min-width: 200px;
    }
    
    @media (max-width: 768px) {
        .dicom-nav {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .dicom-nav-item {
            width: 100%;
        }
        
        .dicom-controls {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .dicom-toolbar {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Visionneuse DICOM chargée');
    
    // Initialisation des contrôles DICOM
    const controls = {
        zoomIn: document.getElementById('zoomIn'),
        zoomOut: document.getElementById('zoomOut'),
        pan: document.getElementById('pan'),
        wwwc: document.getElementById('wwwc'),
        resetView: document.getElementById('resetView'),
        prevImage: document.getElementById('prevImage'),
        nextImage: document.getElementById('nextImage'),
        patientSelect: document.getElementById('patientSelect'),
        studySelect: document.getElementById('studySelect'),
        imageSelect: document.getElementById('imageSelect')
    };
    
    // Désactiver tous les contrôles par défaut
    Object.values(controls).forEach(control => {
        if (control) control.disabled = true;
    });
    
    // Activer les contrôles de navigation si un patient est sélectionné
    @if(isset($study) && $study->patient)
        if (controls.patientSelect) {
            controls.patientSelect.disabled = false;
        }
    @endif
    
    // Gestionnaire d'événements pour le changement de patient
    if (controls.patientSelect) {
        controls.patientSelect.addEventListener('change', function() {
            const patientId = this.value;
            if (patientId) {
                // Charger les études du patient
                loadPatientStudies(patientId);
            } else {
                if (controls.studySelect) {
                    controls.studySelect.disabled = true;
                    controls.studySelect.innerHTML = '<option value="">Sélectionnez une étude</option>';
                }
                if (controls.imageSelect) {
                    controls.imageSelect.disabled = true;
                    controls.imageSelect.innerHTML = '<option value="">Sélectionnez une image</option>';
                }
            }
        });
    }
    
    // Fonction pour charger les études d'un patient
    function loadPatientStudies(patientId) {
        // Implémentez le chargement des études via AJAX
        console.log('Chargement des études pour le patient:', patientId);
        
        // Exemple de code AJAX (à adapter selon votre API)
        /*
        fetch(`/api/patients/${patientId}/studies`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour la liste des études
                updateStudySelect(data.studies);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des études:', error);
            });
        */
    }
    
    // Fonction pour mettre à jour la liste des études
    function updateStudySelect(studies) {
        if (!controls.studySelect) return;
        
        controls.studySelect.innerHTML = '<option value="">Sélectionnez une étude</option>';
        
        studies.forEach(study => {
            const option = document.createElement('option');
            option.value = study.id;
            option.textContent = study.description || `Étude du ${study.date}`;
            controls.studySelect.appendChild(option);
        });
        
        controls.studySelect.disabled = false;
    }
    
    // Gestionnaire d'événements pour le changement d'étude
    if (controls.studySelect) {
        controls.studySelect.addEventListener('change', function() {
            const studyId = this.value;
            if (studyId) {
                // Charger les images de l'étude
                loadStudyImages(studyId);
            } else {
                if (controls.imageSelect) {
                    controls.imageSelect.disabled = true;
                    controls.imageSelect.innerHTML = '<option value="">Sélectionnez une image</option>';
                }
            }
        });
    }
    
    // Fonction pour charger les images d'une étude
    function loadStudyImages(studyId) {
        // Implémentez le chargement des images via AJAX
        console.log('Chargement des images pour l\'étude:', studyId);
        
        // Exemple de code AJAX (à adapter selon votre API)
        /*
        fetch(`/api/studies/${studyId}/images`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour la liste des images
                updateImageSelect(data.images);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des images:', error);
            });
        */
    }
    
    // Fonction pour mettre à jour la liste des images
    function updateImageSelect(images) {
        if (!controls.imageSelect) return;
        
        controls.imageSelect.innerHTML = '<option value="">Sélectionnez une image</option>';
        
        images.forEach((image, index) => {
            const option = document.createElement('option');
            option.value = image.id;
            option.textContent = `Image ${index + 1}`;
            controls.imageSelect.appendChild(option);
        });
        
        controls.imageSelect.disabled = false;
    }
    
    // Gestionnaire d'événements pour le changement d'image
    if (controls.imageSelect) {
        controls.imageSelect.addEventListener('change', function() {
            const imageId = this.value;
            if (imageId) {
                // Afficher l'image sélectionnée
                loadImage(imageId);
            } else {
                // Cacher l'image
                const imageContainer = document.querySelector('.dicom-viewer-container');
                if (imageContainer) {
                    imageContainer.innerHTML = '<div class="text-muted">Aucune image sélectionnée</div>';
                }
            }
        });
    }
    
    // Fonction pour charger une image
    function loadImage(imageId) {
        // Implémentez le chargement de l'image via AJAX
        console.log('Chargement de l\'image:', imageId);
        
        // Exemple de code pour afficher l'image (à adapter selon votre API)
        const imageContainer = document.querySelector('.dicom-viewer-container');
        if (imageContainer) {
            imageContainer.innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 mb-0">Chargement de l'image DICOM...</p>
                </div>`;
            
            // Simuler un chargement (à remplacer par le chargement réel)
            setTimeout(() => {
                imageContainer.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-image fa-4x text-muted mb-3"></i>
                        <p class="mb-0">Prévisualisation de l'image DICOM</p>
                        <small class="text-muted">ID: ${imageId}</small>
                    </div>`;
            }, 1000);
        }
    }
    
    // Initialisation des boutons de contrôle
    const initButtons = ['zoomIn', 'zoomOut', 'pan', 'wwwc', 'resetView', 'prevImage', 'nextImage'];
    initButtons.forEach(buttonId => {
        const button = controls[buttonId];
        if (button) {
            button.addEventListener('click', function() {
                console.log('Bouton cliqué:', buttonId);
                // Implémentez la logique de chaque bouton ici
                switch(buttonId) {
                    case 'zoomIn':
                        // Logique de zoom avant
                        break;
                    case 'zoomOut':
                        // Logique de zoom arrière
                        break;
                    case 'pan':
                        // Logique de déplacement
                        break;
                    case 'wwwc':
                        // Logique de réglage du contraste/luminosité
                        break;
                    case 'resetView':
                        // Logique de réinitialisation de la vue
                        break;
                    case 'prevImage':
                        // Logique pour l'image précédente
                        navigateImages(-1);
                        break;
                    case 'nextImage':
                        // Logique pour l'image suivante
                        navigateImages(1);
                        break;
                }
            });
        }
    });
    
    // Fonction pour naviguer entre les images
    function navigateImages(direction) {
        if (!controls.imageSelect) return;
        
        const options = controls.imageSelect.options;
        const selectedIndex = controls.imageSelect.selectedIndex;
        const newIndex = selectedIndex + direction;
        
        if (newIndex >= 0 && newIndex < options.length) {
            controls.imageSelect.selectedIndex = newIndex;
            controls.imageSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush

@endsection
