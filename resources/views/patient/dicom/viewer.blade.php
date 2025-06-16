@extends('patient.layouts.app')

@section('title', 'Mes images médicales')

@section('page_title', 'Visualiseur d\'images médicales')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-folder-open me-2 text-success"></i>Mes examens</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="studiesList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement de vos examens...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-image me-2 text-success"></i><span id="currentStudyTitle">Visualiseur d'images</span></h5>
                    <div>
                        <button class="btn btn-sm btn-outline-success" id="fullscreenBtn" title="Plein écran">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="imagesContainer" class="row" style="min-height: 400px;">
                        <div class="col-12 text-center py-5">
                            <img src="{{ asset('assets/images/dicom-placeholder.png') }}" alt="Sélectionnez un examen" class="img-fluid mb-3" style="max-height: 200px; opacity: 0.5;">
                            <h5 class="text-muted">Sélectionnez un examen pour visualiser les images</h5>
                            <p class="text-muted">Vos examens d'imagerie médicale sont disponibles dans la liste à gauche</p>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadStudies();
    
    // Bouton plein écran
    document.getElementById('fullscreenBtn').addEventListener('click', function() {
        const viewer = document.getElementById('imagesContainer');
        if (viewer.requestFullscreen) {
            viewer.requestFullscreen();
        } else if (viewer.webkitRequestFullscreen) {
            viewer.webkitRequestFullscreen();
        } else if (viewer.msRequestFullscreen) {
            viewer.msRequestFullscreen();
        }
    });
});

function loadStudies() {
    fetch('{{ route("patient.dicom.studies") }}')
        .then(response => response.json())
        .then(data => {
            const studiesList = document.getElementById('studiesList');
            studiesList.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                data.data.forEach(study => {
                    const studyDate = new Date(study.MainDicomTags.StudyDate);
                    const formattedDate = studyDate.toLocaleDateString();
                    
                    const studyItem = document.createElement('a');
                    studyItem.href = '#';
                    studyItem.className = 'list-group-item list-group-item-action';
                    studyItem.innerHTML = `
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${study.MainDicomTags.StudyDescription || 'Examen médical'}</h6>
                            <small>${formattedDate}</small>
                        </div>
                        <p class="mb-1 small">${study.MainDicomTags.ReferringPhysicianName || 'Dr.'}</p>
                    `;
                    
                    studyItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        loadStudyImages(study.ID, study.MainDicomTags.StudyDescription || 'Examen médical');
                    });
                    
                    studiesList.appendChild(studyItem);
                });
            } else {
                studiesList.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="mb-0">Aucun examen d'imagerie disponible</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des études:', error);
            document.getElementById('studiesList').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                    <p class="mb-0">Impossible de charger vos examens</p>
                    <small class="text-muted">Veuillez réessayer ultérieurement</small>
                </div>
            `;
        });
}

function loadStudyImages(studyId, studyTitle) {
    document.getElementById('currentStudyTitle').textContent = studyTitle;
    document.getElementById('imagesContainer').innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des images...</p>
        </div>
    `;
    
    fetch(`{{ url('patient/dicom/study') }}/${studyId}/images`)
        .then(response => response.json())
        .then(data => {
            const imagesContainer = document.getElementById('imagesContainer');
            imagesContainer.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                data.data.forEach(instance => {
                    const imageCol = document.createElement('div');
                    imageCol.className = 'col-md-4 col-sm-6 mb-4';
                    
                    const imageCard = document.createElement('div');
                    imageCard.className = 'card h-100';
                    
                    const imageBody = document.createElement('div');
                    imageBody.className = 'card-body text-center p-2';
                    
                    const image = document.createElement('img');
                    image.src = `{{ url('patient/dicom/preview') }}/${instance.ID}`;
                    image.alt = 'Image médicale';
                    image.className = 'img-fluid mb-2';
                    image.style.maxHeight = '150px';
                    image.style.cursor = 'pointer';
                    
                    image.addEventListener('click', function() {
                        openImageViewer(instance.ID);
                    });
                    
                    const imageInfo = document.createElement('p');
                    imageInfo.className = 'card-text small mb-0';
                    imageInfo.textContent = instance.MainDicomTags.SeriesDescription || 'Image';
                    
                    imageBody.appendChild(image);
                    imageBody.appendChild(imageInfo);
                    imageCard.appendChild(imageBody);
                    imageCol.appendChild(imageCard);
                    imagesContainer.appendChild(imageCol);
                });
            } else {
                imagesContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-images text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="mb-0">Aucune image disponible pour cet examen</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des images:', error);
            document.getElementById('imagesContainer').innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                    <p class="mb-0">Impossible de charger les images</p>
                    <small class="text-muted">Veuillez réessayer ultérieurement</small>
                </div>
            `;
        });
}

function openImageViewer(instanceId) {
    document.getElementById('fullSizeImage').src = `{{ url('patient/dicom/preview') }}/${instanceId}`;
    document.getElementById('downloadImageBtn').href = `{{ url('patient/dicom/image') }}/${instanceId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('imageViewerModal'));
    modal.show();
}
</script>
@endsection
