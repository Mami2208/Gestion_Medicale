@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4">
        @if(auth()->user()->role === 'MEDECIN')
            Toutes les études DICOM
        @else
            Mes examens d'imagerie
        @endif
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($images as $image)
            <div class="border rounded-lg p-4 hover:shadow-lg">
                <div class="h-48 bg-gray-100 mb-2" 
                    id="viewer-{{ $image->dicom->orthanc_id }}"></div>
                <h3 class="font-bold">{{ $image->dicom->study_date }}</h3>
                <p>Modalité: {{ $image->dicom->modality }}</p>
                <a href="/view/{{ $image->dicom->orthanc_id }}" 
                    class="text-blue-600 hover:underline">Voir en détail</a>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
// Initialisation des viewers miniature
document.addEventListener('DOMContentLoaded', function() {
    @foreach($images as $image)
        initThumbnailViewer('viewer-{{ $image->dicom->orthanc_id }}', '{{ $image->dicom->orthanc_id }}');
    @endforeach
});
</script>
@endsection
