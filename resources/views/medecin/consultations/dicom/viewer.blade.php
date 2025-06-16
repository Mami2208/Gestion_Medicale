@extends('layouts.medecin')

@section('title', 'Visualiseur DICOM - Consultation #' . ($consultation->id ?? ''))

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-x-ray me-2 text-success"></i>
                                @if(isset($consultation) && $consultation->patient && $consultation->patient->utilisateur)
                                    Images médicales - Patient: {{ $consultation->patient->utilisateur->nom }} {{ $consultation->patient->utilisateur->prenom }}
                                @elseif(isset($patient) && $patient->utilisateur)
                                    Images médicales - Patient: {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                @else
                                    Visualisateur DICOM
                                @endif
                            </h4>
                            @if(isset($consultation) && $consultation->date_consultation)
                                <p class="text-muted mb-0">Consultation du {{ $consultation->date_consultation->format('d/m/Y') }}</p>
                            @endif
                        </div>
                        <div>
                            @if(isset($consultation))
                                <div class="btn-group" role="group">
                                    <a href="{{ route('medecin.consultations.show', $consultation->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Retour à la consultation
                                    </a>
                                    <a href="{{ route('medecin.consultations.dicom.upload', $consultation->id) }}" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>
                                        Ajouter une image
                                    </a>
                                </div>
                            @elseif(isset($patient))
                                <div class="btn-group" role="group">
                                    <a href="{{ route('medecin.patients.show', $patient->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Retour au dossier patient
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Panneau latéral avec la liste des études -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-folder-open me-2"></i>
                        Examens DICOM
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="studySearch" class="form-control" placeholder="Rechercher un examen...">
                        </div>
                    </div>
                    <div id="studiesList" class="list-group list-group-flush" style="max-height: 70vh; overflow-y: auto;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2 text-muted">Chargement des examens...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Zone de visualisation principale -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-image me-2"></i>
                        <span id="currentStudyTitle">Sélectionnez un examen</span>
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary ms-2" id="fullscreenBtn" title="Plein écran">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="imagesContainer" style="min-height: 70vh; background-color: #f8f9fa;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <div class="text-muted mb-3">
                                    <i class="fas fa-images fa-3x opacity-25"></i>
                                </div>
                                <h5 class="text-muted">Aucun examen sélectionné</h5>
                                <p class="text-muted small">Sélectionnez un examen dans la liste pour afficher les images</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de visualisation d'image -->
    <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-labelledby="imageViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageViewerModalLabel">Image médicale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="fullSizeImage" src="" alt="Image médicale" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="#" id="downloadImageBtn" class="btn btn-success" download><i class="fas fa-download me-2"></i>Télécharger</a>
                    <a href="#" id="openInStoneViewerBtn" class="btn btn-primary" target="_blank"><i class="fas fa-external-link-alt me-2"></i>Ouvrir dans Stone Viewer</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
    }
    
    .list-group-item {
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0d6efd;
    }
    
    .list-group-item.active {
        background-color: #f0f7ff;
        color: #0a58ca;
        border-left-color: #0d6efd;
        font-weight: 500;
    }
    
    #imagesContainer {
        transition: all 0.3s ease;
    }
    
    /* Styles pour le mode plein écran */
    :fullscreen #imagesContainer {
        min-height: 100vh !important;
        padding: 1rem;
    }
    
    /* Styles pour les aperçus d'images */
    .thumbnail-container {
        position: relative;
        padding-top: 100%; /* Format carré */
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .thumbnail-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: linear-gradient(45deg, #f8f9fa 25%, #f1f3f5 25%, #f1f3f5 50%, #f8f9fa 50%, #f8f9fa 75%, #f1f3f5 75%, #f1f3f5 100%);
        background-size: 20px 20px;
    }
    
    /* Barre de défilement personnalisée */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endsection

@section('scripts')
<script>
    // URL de base de l'API Orthanc
    const ORTHANC_URL = '{{ config("services.orthanc.url", "http://localhost:8042") }}';
    const ORTHANC_USERNAME = '{{ config("services.orthanc.username", "admin") }}';
    const ORTHANC_PASSWORD = '{{ config("services.orthanc.password", "secret") }}';
    const ORTHANC_AUTH = 'Basic ' + btoa(`${ORTHANC_USERNAME}:${ORTHANC_PASSWORD}`);
    
    // Variables globales
    let currentStudyId = null;
    let currentSeriesId = null;
    let consultationId = null;
    
    // Fonction pour charger les études du patient
    function loadPatientStudies() {
        const studiesList = document.getElementById('studiesList');
        studiesList.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des examens en cours...</p>
            </div>`;

        // Récupérer l'ID de la consultation depuis l'URL
        const urlParts = window.location.pathname.split('/');
        consultationId = urlParts[urlParts.length - 2];

        // Appeler l'API pour récupérer les études du patient
        fetch(`/medecin/consultations/${consultationId}/dicom/studies`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des études');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    displayStudies(data.data);
                } else {
                    throw new Error(data.message || 'Aucune donnée valide reçue');
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des études:', error);
                studiesList.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur lors du chargement des examens.
                        <div class="mt-2 small">${error.message}</div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="loadPatientStudies()">
                                <i class="fas fa-sync-alt me-1"></i> Réessayer
                            </button>
                        </div>
                    </div>`;
            });
    }
    
    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initialisation du visualiseur DICOM');
        console.log('URL Orthanc:', ORTHANC_URL);
        
        // Charger les études du patient
        loadPatientStudies();
function displayStudies(studies) {
    const studiesList = document.getElementById('studiesList');
    
    // Si aucune étude n'est trouvée
    if (!studies || studies.length === 0) {
        studiesList.innerHTML = `
            <div class="alert alert-info m-3">
                <i class="fas fa-info-circle me-2"></i>
                Aucun examen DICOM trouvé pour ce patient.
            </div>`;
        return;
    }
    
    // Trier les études par date (du plus récent au plus ancien)
    studies.sort((a, b) => {
        const dateA = a.MainDicomTags.StudyDate || '0';
        const dateB = b.MainDicomTags.StudyDate || '0';
        return dateB.localeCompare(dateA);
    });
    
    // Afficher la liste des études
    studiesList.innerHTML = '';
    
    studies.forEach(study => {
        const studyDate = study.MainDicomTags.StudyDate || 'Date inconnue';
        const studyDescription = study.MainDicomTags.StudyDescription || 'Examen sans description';
        const modality = study.MainDicomTags.ModalitiesInStudy || '??';
        const instancesCount = study.MainDicomTags.NumberOfStudyRelatedInstances || 0;
        const seriesCount = study.MainDicomTags.NumberOfStudyRelatedSeries || 0;
        
        // Formater la date si elle est au format YYYYMMDD
        let formattedDate = studyDate;
        if (/^\d{8}$/.test(studyDate)) {
            const year = studyDate.substring(0, 4);
            const month = studyDate.substring(4, 6);
            const day = studyDate.substring(6, 8);
            formattedDate = `${day}/${month}/${year}`;
        }
        
        const studyElement = document.createElement('div');
        studyElement.className = 'list-group-item list-group-item-action border-0 py-3';
        studyElement.style.cursor = 'pointer';
        studyElement.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6 class="mb-0">
                    <span class="badge bg-primary me-2">${modality}</span>
                    ${studyDescription}
                </h6>
                <span class="badge bg-light text-dark">${formattedDate}</span>
            </div>
            <div class="d-flex justify-content-between text-muted small">
                <span>${seriesCount} série(s)</span>
                <span>${instancesCount} image(s)</span>
            </div>
        `;
        
        // Ajouter un gestionnaire d'événements pour charger les images de l'étude
        studyElement.addEventListener('click', () => {
            loadStudyImages(study.ID, studyDescription);
            
            // Mettre en surbrillance l'élément sélectionné
            document.querySelectorAll('#studiesList .list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            studyElement.classList.add('active');
        });
        
        studiesList.appendChild(studyElement);
    });
    
    // Activer la recherche
    const searchInput = document.getElementById('studySearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const items = studiesList.querySelectorAll('.list-group-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

// Fonction pour charger les images d'une étude
function loadStudyImages(studyId, studyTitle) {
    const imagesContainer = document.getElementById('imagesContainer');
    document.getElementById('currentStudyTitle').textContent = studyTitle || 'Examen DICOM';
    
    // Afficher l'indicateur de chargement
    imagesContainer.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3 text-muted">Chargement des images en cours...</p>
            </div>
        </div>`;
    
    // Récupérer les séries de l'étude
    fetch(`/api/dicom/studies/${studyId}/series`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des séries');
            }
            return response.json();
        })
        .then(series => {
            displaySeries(studyId, series);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des séries:', error);
            imagesContainer.innerHTML = `
                <div class="alert alert-danger m-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors du chargement des images. Veuillez réessayer.
                    <div class="mt-2 small">${error.message}</div>
                </div>`;
        });
}

// Fonction pour afficher les séries d'une étude
function displaySeries(studyId, series) {
    const imagesContainer = document.getElementById('imagesContainer');
    
    // Si aucune série n'est trouvée
    if (!series || series.length === 0) {
        imagesContainer.innerHTML = `
            <div class="alert alert-warning m-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Aucune série d'images trouvée pour cet examen.
            </div>`;
        return;
    }
    
    // Trier les séries par numéro de série
    series.sort((a, b) => {
        const numA = parseInt(a.MainDicomTags.SeriesNumber || '0');
        const numB = parseInt(b.MainDicomTags.SeriesNumber || '0');
        return numA - numB;
    });
    
    let html = '';
    
    // Afficher chaque série avec ses images
    series.forEach((serie, index) => {
        const seriesDescription = serie.MainDicomTags.SeriesDescription || 'Série sans description';
        const modality = serie.MainDicomTags.Modality || '??';
        const instancesCount = serie.Instances ? serie.Instances.length : 0;
        const seriesDate = serie.MainDicomTags.SeriesDate || '';
        
        // Formater la date
        let formattedDate = '';
        if (/^\d{8}$/.test(seriesDate)) {
            const year = seriesDate.substring(0, 4);
            const month = seriesDate.substring(4, 6);
            const day = seriesDate.substring(6, 8);
            formattedDate = ` - ${day}/${month}/${year}`;
        }
        
        html += `
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <span class="badge bg-primary me-2">${modality}</span>
                        ${seriesDescription}
                        <small class="text-muted ms-2">
                            (${instancesCount} image${instancesCount > 1 ? 's' : ''}${formattedDate})
                        </small>
                    </h6>
                    <div>
                        <a href="/dicom/viewer/series/${serie.ID}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-1"></i> Voir la série
                        </a>
                        <a href="/api/dicom/series/${serie.ID}/download" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-download me-1"></i> Télécharger
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row g-2" id="series-${serie.ID}">
                    <div class="col-12 text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="text-muted mt-2">Chargement des aperçus...</p>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Charger les aperçus des images de la série
        loadSeriesThumbnails(serie.ID, index);
    });
    
    // Mettre à jour le conteneur
    imagesContainer.innerHTML = html;
}

// Fonction pour charger les miniatures d'une série
function loadSeriesThumbnails(seriesId, seriesIndex) {
    // Limiter le nombre d'images à afficher pour des raisons de performance
    const maxThumbnails = 6;
    
    // Récupérer les instances de la série
    fetch(`/api/dicom/series/${seriesId}/instances`)
        .then(response => response.json())
        .then(instances => {
            if (!instances || instances.length === 0) {
                document.getElementById(`series-${seriesId}`).innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucune image trouvée dans cette série.
                        </div>
                    </div>`;
                return;
            }
            
            // Trier les instances par numéro d'instance
            instances.sort((a, b) => {
                const numA = parseInt(a.MainDicomTags.InstanceNumber || '0');
                const numB = parseInt(b.MainDicomTags.InstanceNumber || '0');
                return numA - numB;
            });
            
            // Sélectionner un sous-ensemble d'images pour l'aperçu
            const step = Math.max(1, Math.floor(instances.length / maxThumbnails));
            const selectedInstances = [];
            
            // Prendre les premières images, puis des images espacées
            for (let i = 0; i < Math.min(maxThumbnails, instances.length); i++) {
                const index = i === 0 ? 0 : Math.min(i * step, instances.length - 1);
                selectedInstances.push(instances[index]);
            }
            
            // Générer le HTML pour les miniatures
            let thumbnailsHtml = '';
            selectedInstances.forEach((instance, index) => {
                const instanceNumber = instance.MainDicomTags.InstanceNumber || index + 1;
                const previewUrl = `/api/dicom/instances/${instance.ID}/preview?width=200&height=200`;
                
                thumbnailsHtml += `
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="/dicom/viewer/instance/${instance.ID}" target="_blank" class="text-decoration-none">
                            <div class="thumbnail-container">
                                <img src="${previewUrl}" class="card-img-top" alt="Image DICOM">
                            </div>
                            <div class="card-body p-2 text-center">
                                <small class="text-muted">Image ${instanceNumber}</small>
                            </div>
                        </a>
                    </div>
                </div>`;
            });
            
            // Afficher un message s'il y a plus d'images
            if (instances.length > maxThumbnails) {
                const remaining = instances.length - maxThumbnails;
                thumbnailsHtml += `
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center" style="height: 200px;">
                            <div class="text-muted">
                                <i class="fas fa-images fa-2x mb-2"></i>
                                <p class="mb-0">+ ${remaining} image${remaining > 1 ? 's' : ''} supplémentaire${remaining > 1 ? 's' : ''}</p>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
            
            // Mettre à jour l'affichage
            document.getElementById(`series-${seriesId}`).innerHTML = thumbnailsHtml;
        })
        .catch(error => {
            console.error(`Erreur lors du chargement des instances de la série ${seriesId}:`, error);
            document.getElementById(`series-${seriesId}`).innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Erreur lors du chargement des images de la série.
                    </div>
                </div>`;
        });
}

// Fonction pour basculer en mode plein écran
function toggleFullscreen() {
    const elem = document.documentElement;
    if (!document.fullscreenElement) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
    }
}
    
// Fonction pour afficher les études dans la liste
function displayStudies(studies) {
    const studiesList = document.getElementById('studiesList');
    
    // Si aucune étude n'est trouvée
    if (!studies || studies.length === 0) {
        studiesList.innerHTML = `
            <div class="alert alert-info m-3">
                <i class="fas fa-info-circle me-2"></i>
                Aucun examen DICOM trouvé pour ce patient.
            </div>`;
        return;
    }
    
    // Trier les études par date (du plus récent au plus ancien)
    studies.sort((a, b) => {
        const dateA = a.MainDicomTags.StudyDate || '0';
        const dateB = b.MainDicomTags.StudyDate || '0';
        return dateB.localeCompare(dateA);
    });
    
    // Afficher la liste des études
    studiesList.innerHTML = '';
    
    studies.forEach(study => {
        const studyDate = study.MainDicomTags.StudyDate || 'Date inconnue';
        const studyDescription = study.MainDicomTags.StudyDescription || 'Examen sans description';
        const modality = study.MainDicomTags.ModalitiesInStudy || '??';
        const instancesCount = study.MainDicomTags.NumberOfStudyRelatedInstances || 0;
        const seriesCount = study.MainDicomTags.NumberOfStudyRelatedSeries || 0;
        
        // Formater la date si elle est au format YYYYMMDD
        let formattedDate = studyDate;
        if (/^\d{8}$/.test(studyDate)) {
            const year = studyDate.substring(0, 4);
            const month = studyDate.substring(4, 6);
            const day = studyDate.substring(6, 8);
            formattedDate = `${day}/${month}/${year}`;
        }
        
        const studyElement = document.createElement('div');
        studyElement.className = 'list-group-item list-group-item-action border-0 py-3';
        studyElement.style.cursor = 'pointer';
        studyElement.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h6 class="mb-0">
                    <span class="badge bg-primary me-2">${modality}</span>
                    ${studyDescription}
                </h6>
                <span class="badge bg-light text-dark">${formattedDate}</span>
            </div>
            <div class="d-flex justify-content-between text-muted small">
                <span>${seriesCount} série(s)</span>
                <span>${instancesCount} image(s)</span>
            </div>
        `;
        
        // Ajouter un gestionnaire d'événements pour charger les images de l'étude
        studyElement.addEventListener('click', () => {
            loadStudyImages(study.ID, studyDescription);
            
            // Mettre en surbrillance l'élément sélectionné
            document.querySelectorAll('#studiesList .list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            studyElement.classList.add('active');
        });
        
        studiesList.appendChild(studyElement);
    });
    
    // Activer la recherche
    const searchInput = document.getElementById('studySearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const items = studiesList.querySelectorAll('.list-group-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

// Fonction pour charger les images d'une étude
function loadStudyImages(studyId, studyTitle) {
    if (!consultationId) {
        console.error('ID de consultation non défini');
        return;
    }
    
    const imagesContainer = document.getElementById('imagesContainer');
    document.getElementById('currentStudyTitle').textContent = studyTitle || 'Examen DICOM';
    
    // Afficher l'indicateur de chargement
    imagesContainer.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3 text-muted">Chargement des images en cours...</p>
            </div>
        </div>`;
    
    // Récupérer les séries de l'étude via l'API Laravel
    fetch(`/medecin/consultations/${consultationId}/dicom/study/${studyId}/images`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des séries');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                displaySeries(studyId, data.data);
            } else {
                throw new Error(data.message || 'Aucune donnée valide reçue');
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des séries:', error);
            imagesContainer.innerHTML = `
                <div class="alert alert-danger m-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors du chargement des images. Veuillez réessayer.
                    <div class="mt-2 small">${error.message}</div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="loadStudyImages('${studyId}', '${studyTitle.replace(/'/g, "\'")}')">
                            <i class="fas fa-sync-alt me-1"></i> Réessayer
                        </button>
                    </div>
                </div>`;
        });
}
            const moreImagesNote = document.createElement('div');
            moreImagesNote.className = 'col-12 text-center mt-3';
            moreImagesNote.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    ${instanceIds.length - maxInstances} images supplémentaires sont disponibles. 
                    <a href="{{ config("services.orthanc.url") }}/app/explorer.html" target="_blank" class="alert-link">
                        Ouvrir Stone Web Viewer
                    </a> pour voir toutes les images.
                </div>
            `;
            imagesContainer.appendChild(moreImagesNote);
        }
    })
    .catch(error => {
        console.error('Erreur lors du chargement des instances:', error);
    });
})
.catch(error => {
    console.error('Erreur lors du chargement des détails de l\'étude:', error);
    });
        });
        
        // Vérifier si des études ont été trouvées pour ce patient après un court délai
        setTimeout(() => {
            if (matchingStudies === 0 && processedStudies === studiesToProcess.length) {
                imagesContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-circle text-warning mb-3" style="font-size: 3rem;"></i>
                        <p class="mb-0">Aucune image médicale trouvée pour ce patient</p>
                        <small class="text-muted">Ajoutez des images DICOM lors de la création d'une consultation</small>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-secondary" onclick="testOrthancConnection()">Tester la connexion</button>
                        </div>
                    </div>
                `;
            }
        }, 2000); // Attendre 2 secondes pour être sûr que tout a été traité
    })
    .catch(error => {
        console.error('Erreur lors du chargement des études:', error);
        imagesContainer.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                <p class="mb-0">Erreur lors du chargement des images</p>
                <small class="text-muted">${error.message}</small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-outline-secondary" onclick="testOrthancConnection()">Tester la connexion</button>
                </div>
            </div>
        `;
    });
}

// Fonction pour tester la connexion à Orthanc
function testOrthancConnection() {
    const orthancUrl = '{{ config("services.orthanc.url", "http://localhost:8042") }}';
    const username = '{{ config("services.orthanc.username", "admin") }}';
    const password = '{{ config("services.orthanc.password", "secret") }}';
    const auth = btoa(`${username}:${password}`);
    
    console.log('Tentative de connexion à Orthanc:', { orthancUrl, username });
    
    fetch(`${orthancUrl}/system`, {
        headers: {
            'Authorization': `Basic ${auth}`
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        alert(`Connexion réussie à Orthanc!\nVersion: ${data.Version}\nNom: ${data.Name}`);
    })
    .catch(error => {
        alert(`Erreur de connexion à Orthanc: ${error.message}`);
    });
}
</script>
@endsection
