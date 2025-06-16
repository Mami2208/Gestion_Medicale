@extends('patient.layouts.app')

@section('title', 'Résultats téléchargeables')

@section('page_title', 'Résultats téléchargeables')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-download me-2 text-success"></i>Mes résultats téléchargeables</h5>
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Rechercher un document...">
                            <button class="btn btn-outline-success" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="resultsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Tous</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab" aria-controls="reports" aria-selected="false">Comptes-rendus</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#lab" type="button" role="tab" aria-controls="lab" aria-selected="false">Analyses</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab" aria-controls="prescriptions" aria-selected="false">Ordonnances</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="resultsTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Document</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Médecin</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h6 class="mb-0">Compte-rendu Radio pulmonaire</h6>
                                                    <small class="text-muted">PDF - 1.2 MB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Compte-rendu</td>
                                        <td>20/05/2025</td>
                                        <td>Dr. Dupont</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Télécharger
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye me-1"></i> Aperçu
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h6 class="mb-0">Compte-rendu Scanner abdominal</h6>
                                                    <small class="text-muted">PDF - 2.5 MB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Compte-rendu</td>
                                        <td>10/05/2025</td>
                                        <td>Dr. Martin</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Télécharger
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye me-1"></i> Aperçu
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h6 class="mb-0">Résultats analyse de sang</h6>
                                                    <small class="text-muted">PDF - 0.8 MB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Analyse</td>
                                        <td>05/05/2025</td>
                                        <td>Laboratoire Central</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Télécharger
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye me-1"></i> Aperçu
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h6 class="mb-0">Compte-rendu IRM cérébrale</h6>
                                                    <small class="text-muted">PDF - 3.1 MB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Compte-rendu</td>
                                        <td>15/04/2025</td>
                                        <td>Dr. Leroy</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Télécharger
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye me-1"></i> Aperçu
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 24px;"></i>
                                                <div>
                                                    <h6 class="mb-0">Ordonnance traitements chroniques</h6>
                                                    <small class="text-muted">PDF - 0.5 MB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Ordonnance</td>
                                        <td>15/05/2025</td>
                                        <td>Dr. Martin</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download me-1"></i> Télécharger
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-eye me-1"></i> Aperçu
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <nav aria-label="Pagination des résultats" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Suivant</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                    <!-- Les autres onglets auront un contenu similaire, mais filtré -->
                    <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                        <!-- Contenu filtré pour comptes-rendus -->
                    </div>
                    <div class="tab-pane fade" id="lab" role="tabpanel" aria-labelledby="lab-tab">
                        <!-- Contenu filtré pour analyses -->
                    </div>
                    <div class="tab-pane fade" id="prescriptions" role="tabpanel" aria-labelledby="prescriptions-tab">
                        <!-- Contenu filtré pour ordonnances -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fonctionnalité pour ouvrir un aperçu PDF (à implémenter avec une bibliothèque PDF viewer)
    document.addEventListener('DOMContentLoaded', function() {
        const previewButtons = document.querySelectorAll('.btn-outline-secondary');
        previewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Ici, vous pourriez ouvrir une modal avec un PDF viewer
                alert('Fonctionnalité d\'aperçu à implémenter');
            });
        });
    });
</script>
@endpush
