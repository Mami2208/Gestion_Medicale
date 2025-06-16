@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Visualisation DICOM - {{ $study->study_description ?? 'Étude sans description' }}</h4>
                    <a href="{{ route('consultations.show', $study->consultation_id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la consultation
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Détails de l'étude</h5>
                                </div>
                                <div class="card-body">
                                    <dl class="mb-0">
                                        <dt>Patient</dt>
                                        <dd>{{ $study->patient_name ?? 'Inconnu' }}</dd>
                                        
                                        <dt>ID Patient</dt>
                                        <dd>{{ $study->patient_id ?? 'Non spécifié' }}</dd>
                                        
                                        <dt>Date de l'étude</dt>
                                        <dd>{{ $study->study_date ? $study->study_date->format('d/m/Y H:i') : 'Date inconnue' }}</dd>
                                        
                                        <dt>Description</dt>
                                        <dd>{{ $study->study_description ?? 'Aucune description' }}</dd>
                                        
                                        <dt>Séries</dt>
                                        <dd>{{ $study->number_of_series ?? 0 }}</dd>
                                        
                                        <dt>Instances</dt>
                                        <dd>{{ $study->number_of_instances ?? 0 }}</dd>
                                    </dl>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ $webViewerUrl }}" class="btn btn-primary btn-block" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Ouvrir dans Orthanc Web Viewer
                                </a>
                                
                                <button id="download-study" class="btn btn-outline-primary btn-block mt-2">
                                    <i class="fas fa-download"></i> Télécharger l'étude
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <div id="viewer" style="width: 100%; height: 700px; background-color: #000;"></div>
                            
                            <div class="mt-3">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="ww-wc" title="Fenêtrage">
                                        <i class="fas fa-sliders-h"></i> Fenêtrage
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="zoom" title="Zoom">
                                        <i class="fas fa-search-plus"></i> Zoom
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="pan" title="Déplacement">
                                        <i class="fas fa-arrows-alt"></i> Déplacer
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="length" title="Mesure">
                                        <i class="fas fa-ruler"></i> Mesurer
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="reset" title="Réinitialiser">
                                        <i class="fas fa-sync-alt"></i> Réinitialiser
                                    </button>
                                </div>
                                
                                <div class="btn-group float-right" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="previous" title="Image précédente">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="next" title="Image suivante">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-2 text-center text-muted">
                                <small>Utilisez la molette de la souris pour zoomer et le clic droit pour vous déplacer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/cornerstone-wado-image-loader/dist/cornerstoneWADOImageLoader.min.css" rel="stylesheet">
<style>
    #viewer {
        width: 100%;
        height: 700px;
        background-color: #000;
    }
    
    .btn-tool {
        min-width: 40px;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    dt {
        font-weight: 600;
        margin-top: 0.5rem;
        color: #6c757d;
    }
    
    dd {
        margin-bottom: 0.5rem;
        margin-left: 0;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    dd:last-child {
        border-bottom: none;
    }
</style>
@endpush

@push('scripts')
<!-- Bibliothèques Cornerstone -->
<script src="https://unpkg.com/cornerstone-core"></script>
<script src="https://unpkg.com/cornerstone-math"></script>
<script src="https://unpkg.com/cornerstone-tools@2.4.0/dist/cornerstoneTools.min.js"></script>
<script src="https://unpkg.com/cornerstone-wado-image-loader/dist/cornerstoneWADOImageLoader.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration de base de Cornerstone
    const element = document.getElementById('viewer');
    
    // Activer l'élément de visualisation
    cornerstone.enable(element);
    
    // Configurer le chargeur d'images DICOM
    cornerstoneWADOImageLoader.external.cornerstone = cornerstone;
    cornerstoneWADOImageLoader.webWorkerManager.initialize({
        webWorkerPath: 'https://unpkg.com/cornerstone-wado-image-loader/dist/cornerstoneWADOImageLoaderWebWorker.min.js',
        taskConfiguration: {
            'decodeTask': {
                codecsPath: 'https://unpkg.com/cornerstone-wado-image-loader/dist/cornerstoneWADOImageLoaderCodecs.js'
            }
        }
    });
    
    // Variables pour la navigation
    let currentInstanceIndex = 0;
    let instances = [];
    let currentImageId = null;
    
    // Initialisation des outils
    function initTools() {
        // Activer les outils de base
        const toolGroupId = 'viewer-tools';
        
        // Créer un groupe d'outils
        const toolGroup = cornerstoneTools.ToolGroupManager.createToolGroup(toolGroupId);
        
        // Ajouter les outils
        cornerstoneTools.addTool(cornerstoneTools.WindowLevelTool);
        cornerstoneTools.addTool(cornerstoneTools.ZoomTool);
        cornerstoneTools.addTool(cornerstoneTools.PanTool);
        cornerstoneTools.addTool(cornerstoneTools.LengthTool);
        cornerstoneTools.addTool(cornerstoneTools.StackScrollMouseWheelTool);
        
        // Configurer le groupe d'outils
        toolGroup.addTool('WindowLevelTool');
        toolGroup.addTool('ZoomTool');
        toolGroup.addTool('PanTool');
        toolGroup.addTool('LengthTool');
        toolGroup.addTool('StackScrollMouseWheelTool');
        
        // Activer les outils par défaut
        toolGroup.setToolActive('WindowLevelTool', {
            bindings: [
                {
                    mouseButton: cornerstoneTools.Enums.MouseBindings.Left
                }
            ]
        });
        
        toolGroup.setToolActive('PanTool', {
            bindings: [
                {
                    mouseButton: cornerstoneTools.Enums.MouseBindings.Right
                }
            ]
        });
        
        toolGroup.setToolActive('StackScrollMouseWheelTool', {
            bindings: [
                {
                    mouseButton: null
                }
            ]
        });
        
        // Activer le groupe d'outils pour l'élément
        toolGroup.addViewport(element.id, 'default');
        
        // Gestion des boutons d'outils
        document.getElementById('ww-wc').addEventListener('click', function() {
            toolGroup.setToolActive('WindowLevelTool', {
                bindings: [
                    {
                        mouseButton: cornerstoneTools.Enums.MouseBindings.Left
                    }
                ]
            });
        });
        
        document.getElementById('zoom').addEventListener('click', function() {
            toolGroup.setToolActive('ZoomTool', {
                bindings: [
                    {
                        mouseButton: cornerstoneTools.Enums.MouseBindings.Left
                    }
                ]
            });
        });
        
        document.getElementById('pan').addEventListener('click', function() {
            toolGroup.setToolActive('PanTool', {
                bindings: [
                    {
                        mouseButton: cornerstoneTools.Enums.MouseBindings.Left
                    }
                ]
            });
        });
        
        document.getElementById('length').addEventListener('click', function() {
            toolGroup.setToolActive('LengthTool', {
                bindings: [
                    {
                        mouseButton: cornerstoneTools.Enums.MouseBindings.Left
                    }
                ]
            });
        });
        
        document.getElementById('reset').addEventListener('click', function() {
            const viewport = cornerstone.getViewport(element);
            viewport.voi.windowWidth = undefined;
            viewport.voi.windowCenter = undefined;
            viewport.scale = 1;
            viewport.translation.x = 0;
            viewport.translation.y = 0;
            cornerstone.setViewport(element, viewport);
        });
    }
    
    // Charger une instance DICOM
    async function loadInstance(instanceId) {
        try {
            // Afficher un indicateur de chargement
            element.style.backgroundColor = '#000';
            
            // Construire l'URL WADO pour cette instance
            const imageId = `wadouri:${instanceId}`;
            
            // Charger l'image
            const image = await cornerstone.loadImage(imageId);
            
            // Afficher l'image
            await cornerstone.displayImage(element, image);
            
            // Activer les outils
            initTools();
            
            // Mettre à jour l'interface
            updateNavigationUI();
            
            // Redimensionner la vue
            cornerstone.resize(element, true);
            
            return true;
        } catch (error) {
            console.error('Erreur lors du chargement de l\'instance:', error);
            alert('Impossible de charger l\'image DICOM. Veuillez réessayer.');
            return false;
        }
    }
    
    // Mettre à jour l'interface de navigation
    function updateNavigationUI() {
        document.getElementById('previous').disabled = currentInstanceIndex <= 0;
        document.getElementById('next').disabled = currentInstanceIndex >= instances.length - 1;
    }
    
    // Navigation entre les images
    document.getElementById('previous').addEventListener('click', function() {
        if (currentInstanceIndex > 0) {
            currentInstanceIndex--;
            loadInstance(instances[currentInstanceIndex].ID);
        }
    });
    
    document.getElementById('next').addEventListener('click', function() {
        if (currentInstanceIndex < instances.length - 1) {
            currentInstanceIndex++;
            loadInstance(instances[currentInstanceIndex].ID);
        }
    });
    
    // Téléchargement de l'étude
    document.getElementById('download-study').addEventListener('click', function() {
        window.location.href = `{{ route('dicom.download', $study->id) }}`;
    });
    
    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        switch (e.key) {
            case 'ArrowLeft':
                if (currentInstanceIndex > 0) {
                    currentInstanceIndex--;
                    loadInstance(instances[currentInstanceIndex].ID);
                }
                break;
                
            case 'ArrowRight':
                if (currentInstanceIndex < instances.length - 1) {
                    currentInstanceIndex++;
                    loadInstance(instances[currentInstanceIndex].ID);
                }
                break;
        }
    });
    
    // Charger les instances de l'étude
    async function loadStudyInstances() {
        try {
            const response = await fetch(`/api/dicom/studies/{{ $study->study_uid }}/instances`);
            
            if (!response.ok) {
                throw new Error('Erreur lors du chargement des instances');
            }
            
            instances = await response.json();
            
            if (instances.length > 0) {
                // Trier les instances par position (si disponible)
                instances.sort((a, b) => {
                    const aPos = a.MainDicomTags?.InstanceNumber || 0;
                    const bPos = b.MainDicomTags?.InstanceNumber || 0;
                    return aPos - bPos;
                });
                
                // Charger la première instance
                currentInstanceIndex = 0;
                await loadInstance(instances[0].ID);
            } else {
                throw new Error('Aucune instance trouvée pour cette étude');
            }
        } catch (error) {
            console.error('Erreur:', error);
            element.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-white">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h4>Impossible de charger l'étude DICOM</h4>
                    <p class="text-muted">${error.message}</p>
                    <a href="{{ route('consultations.show', $study->consultation_id) }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left"></i> Retour à la consultation
                    </a>
                </div>
            `;
        }
    }
    
    // Démarrer le chargement de l'étude
    loadStudyInstances();
    
    // Gérer le redimensionnement de la fenêtre
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (cornerstone.getEnabledElements().length > 0) {
                cornerstone.resize(element, true);
            }
        }, 250);
    });
});
</script>
@endpush
