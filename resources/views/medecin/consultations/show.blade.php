@extends('layouts.medecin')

@section('title', 'Détails de la consultation')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec gradient vert -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-5 mb-5 text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 font-weight-bold">Consultation du {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y à H:i') }}</h3>
                <p class="mb-0 font-weight-light">Patient : {{ $consultation->patient->utilisateur->nom }} {{ $consultation->patient->utilisateur->prenom }}</p>
            </div>
            <div>
                <a href="{{ route('medecin.consultations.edit', $consultation) }}" class="btn btn-light me-2">
                    <i class="bx bxs-edit"></i> Modifier
                </a>
                <form action="{{ route('medecin.consultations.destroy', $consultation) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-light" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette consultation ?')">
                        <i class="bx bxs-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Contenu principal dans des cartes avec ombres et espacement -->
    <div class="row g-4">
        <!-- Carte principale avec les détails -->        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body p-4">
                    <!-- Motif avec style distinctif -->
                    <div class="p-3 bg-light rounded-lg mb-4">
                        <h5 class="text-green-700 fw-bold">Motif de consultation</h5>
                        <p class="fs-5 mb-0">{{ $consultation->motif }}</p>
                    </div>
                    
                    <!-- Informations cliniques en grille -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="border-start border-green-500 border-3 ps-3">
                                <h5 class="text-green-700">Symptômes</h5>
                                <p class="mb-0 fs-5">{{ $consultation->symptomes ?: 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-green-500 border-3 ps-3">
                                <h5 class="text-green-700">Diagnostic</h5>
                                <p class="mb-0 fs-5">{{ $consultation->diagnostic ?: 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Traitement avec fond subtil -->
                    <div class="bg-green-50 p-4 rounded-lg mb-4">
                        <h5 class="text-green-700 mb-3">Traitement prescrit</h5>
                        <p class="fs-5 mb-0">{{ $consultation->traitement ?: 'Aucun traitement prescrit' }}</p>
                    </div>
                    
                    <!-- Observations -->
                    <div class="mb-4">
                        <h5 class="text-green-700">Observations</h5>
                        <p class="fs-5 mb-0">{{ $consultation->observations ?: 'Aucune observation' }}</p>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-5 d-flex gap-2">
                        <a href="{{ route('medecin.consultations.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Retour à la liste
                        </a>
                        @if($consultation->dicomStudies->isNotEmpty())
                            <a href="{{ route('dicom.view', $consultation->dicomStudies->first()) }}" class="btn btn-primary">
                                <i class="fas fa-x-ray me-1"></i> Visualiser les images DICOM
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-x-ray me-1"></i> Aucune image DICOM
                            </button>
                        @endif
                        <a href="{{ route('medecin.consultations.dicom.viewer', $consultation) }}" class="btn btn-success">
                            <i class="bx bx-image me-1"></i> Voir les images médicales
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte latérale avec informations supplémentaires -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body p-4">
                    <!-- Statut de la consultation -->
                    <div class="text-center mb-4">
                        @php
                            $statusClass = [
                                'PLANIFIE' => 'bg-info',
                                'EN_COURS' => 'bg-warning',
                                'TERMINE' => 'bg-success'
                            ][$consultation->statut] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClass }} p-2 fs-6 mb-2 d-inline-block">{{ $consultation->statut }}</span>
                        <h5 class="mb-0 mt-2">{{ $consultation->prix ? number_format($consultation->prix, 2) . ' €' : 'Prix non défini' }}</h5>
                        <p class="text-muted small">{{ $consultation->paye ? 'Consultation payée' : 'Consultation non payée' }}</p>
                    </div>
                    
                    <!-- Informations sur le patient -->
                    <div class="mb-4">
                        <h5 class="text-green-700 border-bottom pb-2 mb-3">Informations du patient</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-green-100 p-2 rounded-circle me-3">
                                <i class="bx bxs-user text-green-700 fs-5"></i>
                            </div>
                            <div>
                                <strong>{{ $consultation->patient->utilisateur->nom }} {{ $consultation->patient->utilisateur->prenom }}</strong>
                                <div class="text-muted small">ID: {{ $consultation->patient->id }}</div>
                            </div>
                        </div>
                        @if($consultation->patient->date_naissance)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-green-100 p-2 rounded-circle me-3">
                                <i class="bx bxs-calendar text-green-700 fs-5"></i>
                            </div>
                            <div>
                                <strong>{{ \Carbon\Carbon::parse($consultation->patient->date_naissance)->age }} ans</strong>
                                <div class="text-muted small">Né(e) le {{ \Carbon\Carbon::parse($consultation->patient->date_naissance)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        @endif
                        @if($consultation->patient->telephone)
                        <div class="d-flex align-items-center">
                            <div class="bg-green-100 p-2 rounded-circle me-3">
                                <i class="bx bxs-phone text-green-700 fs-5"></i>
                            </div>
                            <div>
                                <strong>{{ $consultation->patient->telephone }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Liens rapides -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('medecin.patients.show', $consultation->patient_id) }}" class="btn btn-outline-green-700">
                            <i class="bx bxs-user-detail me-1"></i> Voir dossier patient
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-green-700 {
        color: #276749;
    }
    .border-green-500 {
        border-color: #38a169 !important;
    }
    .bg-green-50 {
        background-color: rgba(56, 161, 105, 0.1);
    }
    .bg-green-100 {
        background-color: rgba(56, 161, 105, 0.2);
    }
    .btn-outline-green-700 {
        color: #276749;
        border-color: #276749;
    }
    .btn-outline-green-700:hover {
        background-color: #276749;
        color: white;
    }
</style>
@endsection