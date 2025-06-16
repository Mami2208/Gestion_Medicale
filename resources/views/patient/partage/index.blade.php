@extends('patient.layouts.app')

@section('title', 'Partage avec un médecin')

@section('page_title', 'Partage avec un médecin')

@section('content')
<div class="row">
    <!-- Formulaire de partage -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-share-alt me-2 text-success"></i>Nouveau partage</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="doctor" class="form-label">Médecin</label>
                        <select class="form-select" id="doctor" required>
                            <option value="" selected disabled>Sélectionnez un médecin</option>
                            <option value="1">Dr. Martin</option>
                            <option value="2">Dr. Dupont</option>
                            <option value="3">Dr. Dubois</option>
                            <option value="4">Dr. Leroy</option>
                            <option value="5">Dr. Bernard</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Éléments à partager</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-all">
                            <label class="form-check-label fw-bold" for="share-all">
                                Tout sélectionner
                            </label>
                        </div>
                        <hr class="my-2">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-medical-record">
                            <label class="form-check-label" for="share-medical-record">
                                Dossier médical complet
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-diagnostic">
                            <label class="form-check-label" for="share-diagnostic">
                                Diagnostics
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-treatments">
                            <label class="form-check-label" for="share-treatments">
                                Traitements en cours
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-xray">
                            <label class="form-check-label" for="share-xray">
                                Radiographie pulmonaire (20/05/2025)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-scan">
                            <label class="form-check-label" for="share-scan">
                                Scanner abdominal (10/05/2025)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-mri">
                            <label class="form-check-label" for="share-mri">
                                IRM cérébrale (15/04/2025)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="share-blood">
                            <label class="form-check-label" for="share-blood">
                                Analyse de sang (05/05/2025)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="duration" class="form-label">Durée du partage</label>
                        <select class="form-select" id="duration" required>
                            <option value="24h">24 heures</option>
                            <option value="7j" selected>7 jours</option>
                            <option value="30j">30 jours</option>
                            <option value="90j">90 jours</option>
                            <option value="permanent">Permanent (jusqu'à révocation)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message (facultatif)</label>
                        <textarea class="form-control" id="message" rows="3" placeholder="Ajouter un message pour le médecin..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-share-alt me-2"></i>Partager
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Historique des partages -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history me-2 text-success"></i>Historique des partages</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Dr. Martin</h6>
                            <small class="text-success">Actif</small>
                        </div>
                        <p class="mb-1">Dossier médical complet, Traitements en cours, Scanner abdominal</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Partagé le 15/05/2025 - Expire dans 5 jours</small>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times me-1"></i>Révoquer
                            </button>
                        </div>
                    </div>
                    
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Dr. Dupont</h6>
                            <small class="text-success">Actif</small>
                        </div>
                        <p class="mb-1">Radiographie pulmonaire</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Partagé le 20/05/2025 - Expire dans 6 jours</small>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times me-1"></i>Révoquer
                            </button>
                        </div>
                    </div>
                    
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Dr. Leroy</h6>
                            <small class="text-secondary">Expiré</small>
                        </div>
                        <p class="mb-1">IRM cérébrale</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Partagé le 15/04/2025 - Expiré le 22/04/2025</small>
                            <button class="btn btn-sm btn-outline-success">
                                <i class="fas fa-redo me-1"></i>Renouveler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Autorisation permanente -->
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-user-md me-2 text-success"></i>Médecin traitant</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/avatars/doctor.jpg') }}" alt="Dr. Martin" class="avatar me-3" style="width: 60px; height: 60px;">
                    <div>
                        <h5 class="mb-1">Dr. Martin</h5>
                        <p class="mb-0 text-muted">Médecin généraliste</p>
                    </div>
                </div>
                
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Votre médecin traitant a un accès permanent à votre dossier médical.
                </div>
                
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="doctor-notifications" checked>
                    <label class="form-check-label" for="doctor-notifications">
                        Notifier des changements dans mon dossier
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la case à cocher "Tout sélectionner"
        const checkAll = document.getElementById('share-all');
        const checkboxes = document.querySelectorAll('.form-check-input:not(#share-all)');
        
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = checkAll.checked;
            });
        });
        
        // Vérifier si toutes les cases sont cochées
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                checkAll.checked = allChecked;
            });
        });
    });
</script>
@endpush
