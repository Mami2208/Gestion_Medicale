@extends('patient.layouts.app')

@section('title', 'Mes examens')

@section('page_title', 'Mes examens médicaux')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-microscope me-2 text-success"></i>Tous mes examens</h5>
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Rechercher un examen...">
                            <button class="btn btn-outline-success" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="examsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Tous</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="xray-tab" data-bs-toggle="tab" data-bs-target="#xray" type="button" role="tab" aria-controls="xray" aria-selected="false">Radiographies</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="scan-tab" data-bs-toggle="tab" data-bs-target="#scan" type="button" role="tab" aria-controls="scan" aria-selected="false">Scanners</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mri-tab" data-bs-toggle="tab" data-bs-target="#mri" type="button" role="tab" aria-controls="mri" aria-selected="false">IRM</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="echo-tab" data-bs-toggle="tab" data-bs-target="#echo" type="button" role="tab" aria-controls="echo" aria-selected="false">Échographies</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="examsTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card dicom-card h-100">
                                    <img src="https://www.radiologymasterclass.co.uk/images/chest/quality/chest-x-ray-normal-pa.jpg" class="dicom-thumbnail" alt="Radio pulmonaire">
                                    <div class="card-body">
                                        <h5 class="card-title">Radiographie pulmonaire</h5>
                                        <p class="text-muted mb-2"><i class="far fa-calendar-alt me-2"></i>20/05/2025</p>
                                        <p class="text-muted mb-3"><i class="far fa-user me-2"></i>Dr. Dupont</p>
                                        <div class="d-grid">
                                            <a href="{{ route('patient.examens.show', 1) }}" class="btn btn-success">
                                                <i class="fas fa-eye me-2"></i>Voir l'examen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card dicom-card h-100">
                                    <img src="https://prod-images-static.radiopaedia.org/images/157210/332aa0c67cb2e035e372c7cb9798821f_gallery.jpeg" class="dicom-thumbnail" alt="Scanner abdominal">
                                    <div class="card-body">
                                        <h5 class="card-title">Scanner abdominal</h5>
                                        <p class="text-muted mb-2"><i class="far fa-calendar-alt me-2"></i>10/05/2025</p>
                                        <p class="text-muted mb-3"><i class="far fa-user me-2"></i>Dr. Martin</p>
                                        <div class="d-grid">
                                            <a href="{{ route('patient.examens.show', 2) }}" class="btn btn-success">
                                                <i class="fas fa-eye me-2"></i>Voir l'examen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card dicom-card h-100">
                                    <img src="https://www.startradiology.com/uploads/images/english-class/mri-brain/normal-mri/mri-brain-normal-001.jpg" class="dicom-thumbnail" alt="IRM cérébrale">
                                    <div class="card-body">
                                        <h5 class="card-title">IRM cérébrale</h5>
                                        <p class="text-muted mb-2"><i class="far fa-calendar-alt me-2"></i>15/04/2025</p>
                                        <p class="text-muted mb-3"><i class="far fa-user me-2"></i>Dr. Leroy</p>
                                        <div class="d-grid">
                                            <a href="{{ route('patient.examens.show', 3) }}" class="btn btn-success">
                                                <i class="fas fa-eye me-2"></i>Voir l'examen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card dicom-card h-100">
                                    <img src="https://prod-images-static.radiopaedia.org/images/53456376/842b0fa5a4ab5dc5be9c4afc65171a_big_gallery.jpeg" class="dicom-thumbnail" alt="Échographie cardiaque">
                                    <div class="card-body">
                                        <h5 class="card-title">Échographie cardiaque</h5>
                                        <p class="text-muted mb-2"><i class="far fa-calendar-alt me-2"></i>01/03/2025</p>
                                        <p class="text-muted mb-3"><i class="far fa-user me-2"></i>Dr. Dubois</p>
                                        <div class="d-grid">
                                            <a href="{{ route('patient.examens.show', 4) }}" class="btn btn-success">
                                                <i class="fas fa-eye me-2"></i>Voir l'examen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card dicom-card h-100">
                                    <img src="https://images.radiopaedia.org/images/1371972/fbb94a6d92b2df7129fcb542dd161c_jumbo.jpg" class="dicom-thumbnail" alt="Radiographie du genou">
                                    <div class="card-body">
                                        <h5 class="card-title">Radiographie du genou</h5>
                                        <p class="text-muted mb-2"><i class="far fa-calendar-alt me-2"></i>15/02/2025</p>
                                        <p class="text-muted mb-3"><i class="far fa-user me-2"></i>Dr. Dupont</p>
                                        <div class="d-grid">
                                            <a href="{{ route('patient.examens.show', 5) }}" class="btn btn-success">
                                                <i class="fas fa-eye me-2"></i>Voir l'examen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="card dicom-card h-100">
                                    <img src="https://prod-images-static.radiopaedia.org/images/154285/c488c3ce558d727d57396d6751477b_gallery.jpeg" class="dicom-thumbnail" alt="Scanner cérébral">
                                    <div class="card-body">
                                        <h5 class="card-title">Scanner cérébral</h5>
                                        <p class="text-muted mb-2"><i class="far fa-calendar-alt me-2"></i>05/01/2025</p>
                                        <p class="text-muted mb-3"><i class="far fa-user me-2"></i>Dr. Martin</p>
                                        <div class="d-grid">
                                            <a href="{{ route('patient.examens.show', 6) }}" class="btn btn-success">
                                                <i class="fas fa-eye me-2"></i>Voir l'examen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <nav aria-label="Pagination des examens" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Suivant</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                    <!-- Les autres onglets auront un contenu similaire, mais filtré -->
                    <div class="tab-pane fade" id="xray" role="tabpanel" aria-labelledby="xray-tab">
                        <!-- Contenu filtré pour radiographies -->
                    </div>
                    <div class="tab-pane fade" id="scan" role="tabpanel" aria-labelledby="scan-tab">
                        <!-- Contenu filtré pour scanners -->
                    </div>
                    <div class="tab-pane fade" id="mri" role="tabpanel" aria-labelledby="mri-tab">
                        <!-- Contenu filtré pour IRM -->
                    </div>
                    <div class="tab-pane fade" id="echo" role="tabpanel" aria-labelledby="echo-tab">
                        <!-- Contenu filtré pour échographies -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
