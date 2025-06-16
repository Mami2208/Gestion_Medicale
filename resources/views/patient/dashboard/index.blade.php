@extends('patient.layouts.app')

@section('title', 'Tableau de bord')

@section('page_title', 'Tableau de bord')

@section('content')
<div class="row">
    <!-- Résumé dossier médical -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-folder-open me-2 text-success"></i>Résumé dossier médical</h5>
                <a href="{{ route('patient.dossier') }}" class="btn btn-sm btn-outline-success">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="medical-info-item">
                    <div class="label">Diagnostiques récents:</div>
                    <div class="value">
                        <span class="badge bg-light text-dark mb-1 p-2">Hypertension artérielle</span>
                        <span class="badge bg-light text-dark mb-1 p-2">Diabète type 2</span>
                    </div>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Traitements en cours:</div>
                    <div class="value">
                        <ul class="mb-0 ps-3">
                            <li>Amlodipine 5mg - 1 comprimé par jour</li>
                            <li>Metformine 500mg - 2 comprimés par jour</li>
                        </ul>
                    </div>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Dernière consultation:</div>
                    <div class="value">15/05/2025 - Dr. Martin</div>
                </div>
                
                <div class="medical-info-item border-0">
                    <div class="label">Prochain rendez-vous:</div>
                    <div class="value">
                        <span class="text-success fw-bold">30/06/2025 - Dr. Martin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Examens récents -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-microscope me-2 text-success"></i>Examens récents</h5>
                <a href="{{ route('patient.examens') }}" class="btn btn-sm btn-outline-success">Voir tous</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card dicom-card border-0 shadow-sm h-100">
                            <img src="https://www.radiologymasterclass.co.uk/images/chest/quality/chest-x-ray-normal-pa.jpg" class="dicom-thumbnail" alt="Radio pulmonaire">
                            <div class="card-body p-3">
                                <h6 class="mb-1">Radio pulmonaire</h6>
                                <p class="text-muted small mb-2">20/05/2025</p>
                                <a href="{{ route('patient.examens.show', 1) }}" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card dicom-card border-0 shadow-sm h-100">
                            <img src="https://prod-images-static.radiopaedia.org/images/157210/332aa0c67cb2e035e372c7cb9798821f_gallery.jpeg" class="dicom-thumbnail" alt="Scanner abdominal">
                            <div class="card-body p-3">
                                <h6 class="mb-1">Scanner abdominal</h6>
                                <p class="text-muted small mb-2">10/05/2025</p>
                                <a href="{{ route('patient.examens.show', 2) }}" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-eye me-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Résultats téléchargeables -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-download me-2 text-success"></i>Résultats téléchargeables</h5>
                <a href="{{ route('patient.resultats') }}" class="btn btn-sm btn-outline-success">Voir tous</a>
            </div>
            <div class="card-body">
                <div class="pdf-item">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf pdf-icon"></i>
                        <div>
                            <h6 class="mb-1">Compte-rendu Radio pulmonaire</h6>
                            <p class="text-muted small mb-0">20/05/2025 - Dr. Dupont</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i> Télécharger
                    </a>
                </div>
                
                <div class="pdf-item">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf pdf-icon"></i>
                        <div>
                            <h6 class="mb-1">Compte-rendu Scanner abdominal</h6>
                            <p class="text-muted small mb-0">10/05/2025 - Dr. Martin</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i> Télécharger
                    </a>
                </div>
                
                <div class="pdf-item">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf pdf-icon"></i>
                        <div>
                            <h6 class="mb-1">Résultats analyse de sang</h6>
                            <p class="text-muted small mb-0">05/05/2025 - Laboratoire Central</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i> Télécharger
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Paramètres notifications -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="fas fa-bell me-2 text-success"></i>Paramètres notifications</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notification par email</label>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email-rdv" checked>
                                <label class="form-check-label" for="email-rdv">Rappel de rendez-vous</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email-result" checked>
                                <label class="form-check-label" for="email-result">Nouveaux résultats disponibles</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email-news">
                                <label class="form-check-label" for="email-news">Informations de santé</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Notification par SMS</label>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="sms-rdv" checked>
                                <label class="form-check-label" for="sms-rdv">Rappel de rendez-vous</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="sms-result">
                                <label class="form-check-label" for="sms-result">Nouveaux résultats disponibles</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Enregistrer les préférences
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
