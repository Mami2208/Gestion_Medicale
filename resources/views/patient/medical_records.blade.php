@extends('patient.layouts.app')

@section('title', 'Mes dossiers médicaux')

@section('page_title', 'Mes dossiers médicaux')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-folder-medical me-2 text-success"></i>Mes dossiers médicaux et résultats</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload me-2"></i>Ajouter un document
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Résumé du dossier médical -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-heartbeat me-2 text-danger"></i>Résumé médical</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Informations de base</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <small class="d-block text-muted">Groupe sanguin</small>
                                            <strong>{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <small class="d-block text-muted">Allergies</small>
                                            <strong>{{ isset($dossierMedical->allergies) && $dossierMedical->allergies ? implode(', ', $dossierMedical->allergies) : 'Aucune' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Antécédents médicaux</h6>
                                @if(isset($dossierMedical->antecedents_medicaux) && is_array($dossierMedical->antecedents_medicaux) && count($dossierMedical->antecedents_medicaux) > 0)
                                    <ul class="list-group">
                                        @foreach($dossierMedical->antecedents_medicaux as $antecedent)
                                            <li class="list-group-item">{{ $antecedent }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Aucun antécédent médical enregistré</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Traitements en cours</h6>
                                @if(isset($traitements) && count($traitements) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Médicament</th>
                                                    <th>Posologie</th>
                                                    <th>Durée</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($traitements as $traitement)
                                                    <tr>
                                                        <td>{{ $traitement->medicament }}</td>
                                                        <td>{{ $traitement->posologie }}</td>
                                                        <td>{{ $traitement->duree }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">Aucun traitement en cours</p>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Notes importantes</h6>
                                <div class="p-3 border rounded bg-light">
                                    {{ $dossierMedical->notes ?? 'Aucune note' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Documents médicaux -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-file-medical me-2 text-info"></i>Documents médicaux</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="documentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                                Tous les documents
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button" role="tab" aria-controls="results" aria-selected="false">
                                Résultats d'analyses
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab" aria-controls="reports" aria-selected="false">
                                Comptes-rendus
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab" aria-controls="prescriptions" aria-selected="false">
                                Ordonnances
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="documentTabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            @if(isset($documents) && count($documents) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Titre</th>
                                                <th>Date</th>
                                                <th>Médecin</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($documents as $document)
                                                <tr>
                                                    <td>
                                                        @if($document->type == 'analyse')
                                                            <span class="badge bg-info">Analyse</span>
                                                        @elseif($document->type == 'compte-rendu')
                                                            <span class="badge bg-primary">Compte-rendu</span>
                                                        @elseif($document->type == 'ordonnance')
                                                            <span class="badge bg-success">Ordonnance</span>
                                                        @else
                                                            <span class="badge bg-secondary">Document</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $document->titre }}</td>
                                                    <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                                    <td>Dr. {{ $document->medecin->nom }} {{ $document->medecin->prenom }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-success me-2"><i class="fas fa-eye"></i></button>
                                                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5>Aucun document médical disponible</h5>
                                    <p class="text-muted">Vos documents médicaux apparaîtront ici</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                            <div class="text-center py-5">
                                <i class="fas fa-vial text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5>Aucun résultat d'analyse disponible</h5>
                                <p class="text-muted">Vos résultats d'analyses apparaîtront ici</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5>Aucun compte-rendu disponible</h5>
                                <p class="text-muted">Vos comptes-rendus apparaîtront ici</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="prescriptions" role="tabpanel" aria-labelledby="prescriptions-tab">
                            <div class="text-center py-5">
                                <i class="fas fa-prescription text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5>Aucune ordonnance disponible</h5>
                                <p class="text-muted">Vos ordonnances apparaîtront ici</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour ajouter un document -->
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDocumentModalLabel"><i class="fas fa-upload me-2 text-success"></i>Ajouter un document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="documentForm" action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="document_type" class="form-label">Type de document</label>
                            <select class="form-select" id="document_type" name="document_type" required>
                                <option value="">Sélectionner le type</option>
                                <option value="analyse">Résultat d'analyse</option>
                                <option value="compte-rendu">Compte-rendu</option>
                                <option value="ordonnance">Ordonnance</option>
                                <option value="autre">Autre document</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="document_title" class="form-label">Titre du document</label>
                            <input type="text" class="form-control" id="document_title" name="document_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="document_date" class="form-label">Date du document</label>
                            <input type="date" class="form-control" id="document_date" name="document_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="document_file" class="form-label">Fichier</label>
                            <input type="file" class="form-control" id="document_file" name="document_file" required>
                            <div class="form-text">Formats acceptés : PDF, JPG, PNG (Max 5MB)</div>
                        </div>
                        <div class="mb-3">
                            <label for="document_notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="document_notes" name="document_notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="documentForm" class="btn btn-success">Enregistrer le document</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
