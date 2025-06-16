@extends('layouts.app')

@section('title', 'Série DICOM')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-x-ray me-2 text-success"></i>
                                Série DICOM
                            </h4>
                            <p class="text-muted mb-0">
                                Patient ID: {{ $patientId ?? 'N/A' }} | 
                                Étude: {{ substr($studyId ?? 'N/A', 0, 8) }}... | 
                                {{ $series['MainDicomTags']['SeriesDescription'] ?? 'Sans description' }}
                            </p>
                        </div>
                        <div>
                            <button id="fullscreenBtn" class="btn btn-outline-secondary" title="Plein écran">
                                <i class="fas fa-expand me-1"></i> Plein écran
                            </button>
                            <a href="{{ route('dicom.series.download', $series['ID']) }}" class="btn btn-outline-primary ms-2" title="Télécharger">
                                <i class="fas fa-download me-1"></i> Télécharger la série
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Panneau latéral avec miniatures -->
                        <div class="col-md-3 border-end" style="max-height: 80vh; overflow-y: auto;">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-3">
                                    <i class="fas fa-layer-group me-2 text-primary"></i>
                                    Images ({{ count($instances) }})
                                </h6>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="instanceSearch" class="form-control" placeholder="Rechercher...">
                                </div>
                            </div>
                            <div class="list-group list-group-flush" id="instanceList">
                                @foreach($instances as $index => $instance)
                                    <a href="#" class="list-group-item list-group-item-action instance-item" 
                                       data-instance-id="{{ $instance['ID'] }}"
                                       data-instance-index="{{ $index }}">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 text-center" style="width: 40px;">
                                                <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <small class="d-block text-muted">
                                                    {{ $instance['MainDicomTags']['InstanceNumber'] ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Zone de visualisation principale -->
                        <div class="col-md-9">
                            <div id="dicomViewer" style="width: 100%; height: 80vh; position: relative;">
                                <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
                                        <p class="mt-3">Chargement de l'image DICOM...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contrôles de navigation -->
                            <div class="bg-light p-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button id="previousBtn" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-chevron-left me-1"></i> Précédent
                                        </button>
                                        <button id="nextBtn" class="btn btn-outline-primary btn-sm">
                                            Suivant <i class="fas fa-chevron-right ms-1"></i>
                                        </button>
                                    </div>
                                    <div class="text-muted small">
                                        Image <span id="currentIndex">1</span> sur {{ count($instances) }}
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-outline-secondary btn-sm" id="zoomInBtn" title="Zoom avant">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" id="zoomOutBtn" title="Zoom arrière">
                                            <i class="fas fa-search-minus"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" id="panBtn" title="Déplacer">
                                            <i class="fas fa-arrows-alt"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" id="wwwcBtn" title="Fenêtrage">
                                            <i class="fas fa-sliders-h"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" id="resetViewBtn" title="Réinitialiser la vue">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Curseur de défilement -->
                                <div class="mt-3">
                                    <input type="range" class="form-range" id="instanceSlider" 
                                           min="1" max="{{ count($instances) }}" value="1" 
                                           style="width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclure les bibliothèques nécessaires -->
<link href="https://unpkg.com/cornerstone-core@2.6.1/dist/cornerstone.min.css" rel="stylesheet">
<link href="https://unpkg.com/cornerstone-tools@4.26.1/dist/umd/cornerstoneTools.min.css" rel="stylesheet">
<script src="https://unpkg.com/cornerstone-core@2.6.1/dist/cornerstone.min.js"></script>
<script src="https://unpkg.com/cornerstone-math@0.1.8/dist/cornerstoneMath.js"></script>
<script src="https://unpkg.com/hammerjs@2.0.8/hammer.min.js"></script>
<script src="https://unpkg.com/cornerstone-tools@4.26.1/dist/umd/cornerstoneTools.min.js"></script>
<script src="https://unpkg.com/cornerstone-wado-image-loader@4.8.2/dist/cornerstoneWADOImageLoader.min.js"></script>
<script src="https://unpkg.com/dicom-parser@1.8.3/dist/dicomParser.min.js"></script>

<script>
// Configuration de base
let currentInstanceIndex = 0;
const totalInstances = {{ count($instances) }};
const instances = @json($instances);

// Configuration de Cornerstone
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du chargeur d'images
    cornerstoneWADOImageLoader.external.cornerstone = cornerstone;
    cornerstoneWADOImageLoader.external.dicomParser = dicomParser;
    
    // Configuration du gestionnaire de workers
    cornerstoneWADOImageLoader.webWorkerManager.initialize({
        maxWebWorkers: navigator.hardwareConcurrency || 1,
        startWebWorkersOnDemand: true,
        webWorkerPath: 'https://unpkg.com/cornerstone-wado-image-loader/dist/cornerstoneWADOImageLoaderWebWorker.min.js',
        taskConfiguration: {
            'decodeTask': {
                codecsPath: 'https://unpkg.com/cornerstone-wado-image-loader/dist/cornerstoneWADOImageLoaderCodecs.js'
            }
        }
    });

    // Initialisation du viewer
    const element = document.getElementById('dicomViewer');
    cornerstone.enable(element);
    
    // Configuration des outils
    const toolGroupId = 'viewerTools';
    cornerstoneTools.init();
    
    // Ajout des outils
    const tools = [
        { name: 'Wwwc', supportedInteractionTypes: ['Mouse', 'Touch'] },
        { name: 'Pan', supportedInteractionTypes: ['Mouse', 'Touch'] },
        { name: 'Zoom', supportedInteractionTypes: ['Mouse', 'Touch'] },
        { name: 'StackScrollMouseWheel', supportedInteractionTypes: ['Mouse'] },
        { name: 'StackScroll', supportedInteractionTypes: ['Mouse', 'Touch'] },
        { name: 'Length', supportedInteractionTypes: ['Mouse'] },
        { name: 'Angle', supportedInteractionTypes: ['Mouse'] },
        { name: 'Probe', supportedInteractionTypes: ['Mouse'] }
    ];
    
    tools.forEach(tool => {
        cornerstoneTools.addTool(cornerstoneTools[tool.name], tool.supportedInteractionTypes ? {
            supportedInteractionTypes: tool.supportedInteractionTypes
        } : undefined);
    });
    
    // Activer les outils de base
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 1 }); // Bouton gauche
    cornerstoneTools.setToolActive('Pan', { mouseButtonMask: 4 }); // Bouton du milieu
    cornerstoneTools.setToolActive('Zoom', { mouseButtonMask: 2 }); // Bouton droit
    
    // Charger la première image
    if (totalInstances > 0) {
        loadInstance(0);
    }
    
    // Gestion des événements
    setupEventListeners();
    
    // Mettre à jour l'interface
    updateUI();
});

// Fonction pour charger une instance DICOM
function loadInstance(index) {
    if (index < 0 || index >= totalInstances) return;
    
    currentInstanceIndex = index;
    const instanceId = instances[index].ID;
    const imageId = `wadouri:${window.location.origin}/api/dicom/instances/${instanceId}/file`;
    const element = document.getElementById('dicomViewer');
    
    // Afficher l'indicateur de chargement
    element.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3">Chargement de l'image ${index + 1} sur ${totalInstances}...</p>
            </div>
        </div>`;
    
    // Charger et afficher l'image
    cornerstone.loadImage(imageId).then(function(image) {
        const viewport = cornerstone.getDefaultViewportForImage(element, image);
        cornerstone.displayImage(element, image, viewport);
        
        // Ajuster la fenêtre pour une meilleure visualisation
        const ww = image.windowWidth || 400;
        const wc = image.windowCenter || 50;
        
        viewport.voi.windowWidth = ww;
        viewport.voi.windowCenter = wc;
        cornerstone.setViewport(element, viewport);
        
        // Mettre à jour l'interface
        updateUI();
        
    }).catch(function(error) {
        console.error('Erreur lors du chargement de l\'image DICOM:', error);
        element.innerHTML = `
            <div class="alert alert-danger m-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur lors du chargement de l'image DICOM. Veuillez réessayer.
            </div>`;
    });
}

// Fonction pour configurer les écouteurs d'événements
function setupEventListeners() {
    // Navigation avec les boutons
    document.getElementById('previousBtn').addEventListener('click', () => {
        if (currentInstanceIndex > 0) {
            loadInstance(currentInstanceIndex - 1);
        }
    });
    
    document.getElementById('nextBtn').addEventListener('click', () => {
        if (currentInstanceIndex < totalInstances - 1) {
            loadInstance(currentInstanceIndex + 1);
        }
    });
    
    // Navigation avec le curseur
    document.getElementById('instanceSlider').addEventListener('input', (e) => {
        const index = parseInt(e.target.value) - 1;
        if (index !== currentInstanceIndex) {
            loadInstance(index);
        }
    });
    
    // Navigation avec les touches du clavier
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft' && currentInstanceIndex > 0) {
            loadInstance(currentInstanceIndex - 1);
        } else if (e.key === 'ArrowRight' && currentInstanceIndex < totalInstances - 1) {
            loadInstance(currentInstanceIndex + 1);
        }
    });
    
    // Outils
    document.getElementById('zoomInBtn').addEventListener('click', () => {
        const element = document.getElementById('dicomViewer');
        const viewport = cornerstone.getViewport(element);
        viewport.scale += 0.2;
        cornerstone.setViewport(element, viewport);
    });
    
    document.getElementById('zoomOutBtn').addEventListener('click', () => {
        const element = document.getElementById('dicomViewer');
        const viewport = cornerstone.getViewport(element);
        viewport.scale = Math.max(0.2, viewport.scale - 0.2);
        cornerstone.setViewport(element, viewport);
    });
    
    document.getElementById('panBtn').addEventListener('click', () => {
        cornerstoneTools.setToolActive('Pan', { mouseButtonMask: 1 });
    });
    
    document.getElementById('wwwcBtn').addEventListener('click', () => {
        cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 1 });
    });
    
    document.getElementById('resetViewBtn').addEventListener('click', () => {
        const element = document.getElementById('dicomViewer');
        const image = cornerstone.getImage(element);
        if (image) {
            const viewport = cornerstone.getDefaultViewportForImage(element, image);
            cornerstone.setViewport(element, viewport);
        }
    });
    
    // Recherche dans la liste des instances
    document.getElementById('instanceSearch').addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.instance-item');
        
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Clic sur une miniature
    document.querySelectorAll('.instance-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const index = parseInt(item.getAttribute('data-instance-index'));
            if (!isNaN(index)) {
                loadInstance(index);
            }
        });
    });
    
    // Plein écran
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', toggleFullscreen);
    }
}

// Fonction pour mettre à jour l'interface utilisateur
function updateUI() {
    // Mettre à jour le curseur
    document.getElementById('instanceSlider').value = currentInstanceIndex + 1;
    document.getElementById('currentIndex').textContent = currentInstanceIndex + 1;
    
    // Mettre à jour l'état des boutons de navigation
    document.getElementById('previousBtn').disabled = currentInstanceIndex === 0;
    document.getElementById('nextBtn').disabled = currentInstanceIndex === totalInstances - 1;
    
    // Mettre en surbrillance l'élément actif dans la liste
    document.querySelectorAll('.instance-item').forEach((item, index) => {
        if (index === currentInstanceIndex) {
            item.classList.add('active');
            item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            item.classList.remove('active');
        }
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
</script>

<style>
.instance-item {
    border-left: 3px solid transparent;
    transition: all 0.2s;
}

.instance-item:hover {
    background-color: #f8f9fa;
    border-left-color: #0d6efd;
}

.instance-item.active {
    background-color: #e7f1ff;
    border-left-color: #0d6efd;
    font-weight: 500;
}

#dicomViewer {
    background-color: #000;
}

.cornerstone-enabled-image {
    width: 100%;
    height: 100%;
}

/* Styles pour le mode plein écran */
:fullscreen #dicomViewer {
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
}

:-webkit-full-screen #dicomViewer {
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
}

:-ms-fullscreen #dicomViewer {
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
}
</style>
@endsection
