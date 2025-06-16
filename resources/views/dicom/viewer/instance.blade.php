@extends('layouts.app')

@section('title', 'Visualisation DICOM')

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
                                Visualisation DICOM
                            </h4>
                            <p class="text-muted mb-0">
                                Patient ID: {{ $patientId ?? 'N/A' }} | 
                                Study: {{ substr($studyId ?? 'N/A', 0, 8) }}... | 
                                Series: {{ substr($seriesId ?? 'N/A', 0, 8) }}...
                            </p>
                        </div>
                        <div>
                            <button id="fullscreenBtn" class="btn btn-outline-secondary" title="Plein écran">
                                <i class="fas fa-expand me-1"></i> Plein écran
                            </button>
                            <a href="{{ route('dicom.instance.download', $instanceId) }}" class="btn btn-outline-primary ms-2" title="Télécharger">
                                <i class="fas fa-download me-1"></i> Télécharger
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
                <div class="card-body p-0" style="min-height: 70vh;">
                    <div id="dicomViewer" style="width: 100%; height: 100%;">
                        <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                                <p class="mt-3">Chargement de l'image DICOM...</p>
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
<script src="https://unpkg.com/cornerstone-core@2.6.1/dist/cornerstone.min.js"></script>
<script src="https://unpkg.com/cornerstone-math@0.1.8/dist/cornerstoneMath.js"></script>
<script src="https://unpkg.com/hammerjs@2.0.8/hammer.min.js"></script>
<script src="https://unpkg.com/cornerstone-tools@4.26.1/dist/cornerstoneTools.min.js"></script>
<script src="https://unpkg.com/cornerstone-wado-image-loader@4.8.2/dist/cornerstoneWADOImageLoader.min.js"></script>
<script src="https://unpkg.com/dicom-parser@1.8.3/dist/dicomParser.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration de Cornerstone
    cornerstoneWADOImageLoader.external.cornerstone = cornerstone;
    cornerstoneWADOImageLoader.external.dicomParser = dicomParser;
    
    // Configuration du chargeur d'images
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
    
    // Activer les outils de base
    cornerstoneTools.addTool(cornerstoneTools.WwwcTool, {
        name: 'Wwwc',
        supportedInteractionTypes: ['Mouse', 'Touch']
    });
    
    cornerstoneTools.addTool(cornerstoneTools.PanMultiTouchTool);
    cornerstoneTools.addTool(cornerstoneTools.ZoomTool);
    cornerstoneTools.addTool(cornerstoneTools.ZoomTouchPinchTool);
    cornerstoneTools.addTool(cornerstoneTools.StackScrollMouseWheelTool);
    
    // Activer les outils
    cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 1 }); // Bouton gauche
    cornerstoneTools.setToolActive('Pan', { mouseButtonMask: 4 }); // Bouton du milieu
    cornerstoneTools.setToolActive('Zoom', { mouseButtonMask: 2 }); // Bouton droit
    
    // Charger l'image DICOM
    loadDicomImage('{{ $instanceId }}');
    
    // Gestion du plein écran
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', toggleFullscreen);
    }
    
    // Fonction pour charger une image DICOM
    function loadDicomImage(instanceId) {
        const imageId = `wadouri:${window.location.origin}/api/dicom/instances/${instanceId}/file`;
        
        cornerstone.loadImage(imageId).then(function(image) {
            const viewport = cornerstone.getDefaultViewportForImage(element, image);
            cornerstone.displayImage(element, image, viewport);
            
            // Ajuster la fenêtre pour une meilleure visualisation
            const ww = image.windowWidth || 400;
            const wc = image.windowCenter || 50;
            
            viewport.voi.windowWidth = ww;
            viewport.voi.windowCenter = wc;
            cornerstone.setViewport(element, viewport);
            
            // Activer les outils après le chargement de l'image
            cornerstoneTools.setToolActive('Wwwc', { mouseButtonMask: 1 });
            
        }).catch(function(error) {
            console.error('Erreur lors du chargement de l\'image DICOM:', error);
            document.getElementById('dicomViewer').innerHTML = `
                <div class="alert alert-danger m-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erreur lors du chargement de l'image DICOM. Veuillez réessayer.
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
});
</script>
@endsection
