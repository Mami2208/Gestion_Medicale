@extends('layouts.medecin')

@section('title', 'Téléverser une image DICOM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Téléverser une image DICOM</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Téléverser une image DICOM
                    </h4>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('medecin.dicom.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient *</label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <option value="">Sélectionnez un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">
                                        @if($patient->utilisateur)
                                            {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                        @else
                                            Patient #{{ $patient->id }}
                                        @endif
                                        ({{ $patient->numero_securite_sociale ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dicom_file" class="form-label">Fichier DICOM *</label>
                            <input type="file" class="form-control" id="dicom_file" name="dicom_file" accept=".dcm,application/dicom" required>
                            <div class="form-text">Format accepté : .dcm (taille max : 50 Mo)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (optionnel)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medecin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Téléverser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Afficher le nom du fichier sélectionné
    const fileInput = document.getElementById('dicom_file');
    const fileNameDisplay = document.createElement('div');
    fileNameDisplay.className = 'mt-2 text-muted';
    fileInput.parentNode.insertBefore(fileNameDisplay, fileInput.nextSibling);
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = 'Fichier sélectionné : ' + this.files[0].name;
            
            // Vérifier la taille du fichier (max 50 Mo)
            const fileSize = this.files[0].size / 1024 / 1024; // en Mo
            if (fileSize > 50) {
                alert('Le fichier est trop volumineux. La taille maximale autorisée est de 50 Mo.');
                this.value = '';
                fileNameDisplay.textContent = '';
            }
        } else {
            fileNameDisplay.textContent = '';
        }
    });
});
</script>
@endpush
