import './bootstrap';

// Configuration Cornerstone pour les miniatures
function initThumbnailViewer(elementId, orthancId) {
    const element = document.getElementById(elementId);
    cornerstone.enable(element);
    
    fetch(`/api/dicom/${orthancId}/thumbnail`)
        .then(response => response.arrayBuffer())
        .then(buffer => {
            const imageId = cornerstoneWADOImageLoader.wadouri.fileManager.add(buffer);
            cornerstone.loadImage(imageId).then(image => {
                cornerstone.displayImage(element, image);
                cornerstone.resize(element, true);
            });
        });
}

// Gestion des erreurs globales
window.addEventListener('error', (e) => {
    console.error('Erreur DICOM:', e);
    alert('Une erreur est survenue lors du chargement de l\'image');
});