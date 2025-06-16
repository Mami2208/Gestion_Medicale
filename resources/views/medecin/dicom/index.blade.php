@extends('layouts.medecin')

@section('title', 'Études DICOM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Études DICOM</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-x-ray me-2"></i>Études DICOM
                    </h4>
                    <a href="{{ route('medecin.dicom.upload') }}" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Nouvelle étude
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Description</th>
                                    <th>Ajouté par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studies as $study)
                                    <tr>
                                        <td>{{ $study->study_date->format('d/m/Y H:i') }}</td>
                                        <td>
                                            {{ $study->patient->user->name ?? 'N/A' }}
                                            @if($study->patient->numero_securite_sociale)
                                                <br><small class="text-muted">{{ $study->patient->numero_securite_sociale }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $study->description ?: 'Aucune description' }}</td>
                                        <td>{{ $study->uploader->name ?? 'Système' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('medecin.dicom.view', $study->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Voir l'étude">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Aucune étude DICOM trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $studies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
