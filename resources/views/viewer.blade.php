@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Visualisation DICOM</h1>
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Retour</a>
    </div>

    <div id="dicom-viewer" class="w-full h-screen-60 bg-gray-100"></div>
    
    <div class="mt-4">
        <p class="font-bold">Informations patient :</p>
        <p>Nom: {{ $metadata['PatientName'] }}</p>
        <p>Date de l'étude: {{ $metadata['StudyDate'] }}</p>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cornerstone-core@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/cornerstone-wado-image-loader@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/cornerstone-tools@latest"></script>

<script>
// Configuration Cornerstone
cornerstoneWADOImageLoader.external.cornerstone = cornerstone;

// Initialisation du viewer
const element = document.getElementById('dicom-viewer');
cornerstone.enable(element);

// Chargement de l'image
async function loadImage(orthancId) {
    const imageUrl = `/api/dicom/${orthancId}`;
    const image = await cornerstone.loadImage(imageUrl);
    cornerstone.displayImage(element, image);
}

loadImage('{{ $image->dicom->orthanc_id }}');
</script>
@endsection
