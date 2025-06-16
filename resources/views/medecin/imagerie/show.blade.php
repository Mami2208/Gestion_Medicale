@extends('medecin.layouts.app')

@section('title', 'Détails de l\'image médicale')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Détails de l'image médicale</h1>
            <p class="mt-1 text-sm text-gray-500">Informations complètes de l'image</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('medecin.imagerie.edit', $imagerie) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('medecin.imagerie.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations de l'image -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Informations de l'image</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Patient</h3>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $imagerie->dossierMedical->patient->utilisateur->nom ?? 'N/A' }}
                        {{ $imagerie->dossierMedical->patient->utilisateur->prenom ?? '' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Type d'image</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $imagerie->type_image ?? 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Format</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $imagerie->is_dicom ? 'DICOM' : 'Standard' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Date de création</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $imagerie->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Visualisation -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Visualisation</h2>
            @if($imagerie->is_dicom)
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="{{ $viewerUrl ?? '#' }}" 
                            class="w-full h-full rounded-lg"
                            allowfullscreen>
                    </iframe>
                </div>
                <div class="mt-4">
                    <a href="{{ route('medecin.imagerie.viewer', $imagerie) }}" class="btn btn-primary">
                        <i class="fas fa-expand mr-2"></i>
                        Ouvrir dans le visualiseur
                    </a>
                </div>
            @else
                <div class="text-center text-gray-500">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p>Visualisation non disponible pour ce type d'image</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 