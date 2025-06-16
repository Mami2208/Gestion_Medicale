@extends('layouts.app')

@section('title', 'Mes Études DICOM')

@push('styles')
    <style>
        .study-card {
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 1.5rem;
            border-left: 4px solid #4a90e2;
        }
        .study-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .study-header {
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
        .study-body {
            padding: 1.25rem;
        }
        .study-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .study-actions {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">
                    <i class="fas fa-x-ray me-2"></i>Mes Études DICOM
                </h1>
                @can('create', \App\Models\DicomStudy::class)
                    <a href="{{ route('dicom.upload') }}" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Nouvelle étude
                    </a>
                @endcan
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Études DICOM</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($studies as $study)
            @can('view', $study)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card study-card h-100">
                        <div class="study-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas {{ $study->modality_icon }} me-2"></i>
                                {{ $study->study_description ?? 'Étude sans description' }}
                            </h5>
                            <span class="badge bg-{{ $study->isRecent() ? 'success' : 'secondary' }}">
                                {{ $study->study_date->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="study-body">
                            <div class="study-meta mb-3">
                                <div><i class="fas fa-user me-2"></i> {{ $study->patient->full_name ?? 'Patient inconnu' }}</div>
                                <div><i class="fas fa-id-card me-2"></i> {{ $study->patient->patient_id ?? 'N/A' }}</div>
                                <div><i class="fas fa-layer-group me-2"></i> {{ $study->number_of_series }} séries</div>
                                <div><i class="fas fa-image me-2"></i> {{ $study->number_of_instances }} images</div>
                            </div>
                            
                            <div class="study-actions d-flex justify-content-between">
                                <a href="{{ route('dicom.studies.show', $study) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </a>
                                
                                @can('download', $study)
                                    <a href="{{ route('dicom.download.study', $study) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download me-1"></i> Télécharger
                                    </a>
                                @endcan
                                
                                @can('delete', $study)
                                    <form action="{{ route('dicom.studies.destroy', $study) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette étude ?')">
                                            <i class="fas fa-trash-alt me-1"></i> Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune étude DICOM n'a été trouvée.
                    @can('create', \App\Models\DicomStudy::class)
                        <a href="{{ route('dicom.upload') }}" class="alert-link">
                            Cliquez ici pour en ajouter une nouvelle.
                        </a>
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    @if($studies->hasPages())
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $studies->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
