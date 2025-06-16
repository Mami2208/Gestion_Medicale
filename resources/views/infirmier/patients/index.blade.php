@extends('layouts.infirmier')

@section('title', 'Liste des patients')

@section('styles')
<style>
    .patient-card {
        margin-bottom: 20px;
    }
    
    .patient-info {
        padding: 10px 0;
    }
    
    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
        background-color: #4e73df;
        color: white;
    }
    
    .treatment-item {
        border-left: 3px solid #28a745;
        padding-left: 10px;
        margin-bottom: 8px;
    }
    
    .no-patients-message {
        text-align: center;
        padding: 40px 0;
    }
    
    .no-patients-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="fas fa-user-injured me-2 text-success"></i>
            Patients sous ma responsabilité
        </h1>
        <p class="text-muted">Gérez et suivez l'état de santé de vos patients</p>
    </div>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" id="searchPatient" placeholder="Rechercher un patient...">
            <button class="btn btn-success" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="row align-items-center">
            <div class="col-md-auto mb-2 mb-md-0">
                <strong><i class="fas fa-filter text-success me-2"></i>Filtrer par :</strong>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select class="form-select form-select-sm" id="filterEtat">
                    <option value="">État de santé</option>
                    <option value="CRITIQUE">Critique</option>
                    <option value="ALERTE">Alerte</option>
                    <option value="STABLE">Stable</option>
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select class="form-select form-select-sm" id="filterTraitement">
                    <option value="">Type de traitement</option>
                    <option value="medicament">Médicaments</option>
                    <option value="intervention">Interventions</option>
                    <option value="surveillance">Surveillance</option>
                </select>
            </div>
            <div class="col-md-auto ms-auto">
                <button class="btn btn-sm btn-outline-secondary" id="resetFilters">
                    <i class="fas fa-undo me-1"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @if($patients->count() > 0)
            @foreach($patients as $patient)
                <div class="col-lg-4 mb-4 patient-card">
                    <div class="card h-100">
                        <div class="card-header {{ $patient->etat_sante == 'CRITIQUE' ? 'bg-danger text-white' : ($patient->etat_sante == 'ALERTE' ? 'bg-warning' : 'bg-light') }}">
                            <div class="d-flex align-items-center">
                                <div class="patient-avatar">
                                    {{ substr($patient->prenom, 0, 1) }}{{ substr($patient->nom, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $patient->prenom }} {{ $patient->nom }}</h5>
                                    <span class="badge {{ $patient->etat_sante == 'CRITIQUE' ? 'bg-white text-danger' : ($patient->etat_sante == 'ALERTE' ? 'bg-white text-warning' : 'bg-success text-white') }}">
                                        <i class="fas {{ $patient->etat_sante == 'CRITIQUE' ? 'fa-heartbeat' : ($patient->etat_sante == 'ALERTE' ? 'fa-exclamation-triangle' : 'fa-check-circle') }} me-1"></i>
                                        {{ $patient->etat_sante ? ucfirst(strtolower($patient->etat_sante)) : 'Stable' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block"><i class="fas fa-birthday-cake me-1 text-success"></i> Âge</small>
                                    <div class="fw-bold">{{ $patient->age ?? 'N/A' }} ans</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block"><i class="fas fa-venus-mars me-1 text-success"></i> Sexe</small>
                                    <div class="fw-bold">{{ $patient->sexe ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block"><i class="fas fa-phone me-1 text-success"></i> Contact</small>
                                    <div class="fw-bold">{{ $patient->utilisateur->telephone ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block"><i class="fas fa-door-open me-1 text-success"></i> Chambre</small>
                                    <div class="fw-bold">{{ $patient->chambre ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <hr>

                            <h6><i class="fas fa-pills me-1 text-success"></i> Traitements</h6>
                            @if($patient->traitements && (is_object($patient->traitements) ? $patient->traitements->count() : count($patient->traitements)) > 0)
                                <ul class="list-unstyled small">
                                    @foreach($patient->traitements->take(2) as $traitement)
                                        <li class="treatment-item mb-2">
                                            <strong>{{ $traitement->medicament }}</strong> - {{ $traitement->posologie }}
                                        </li>
                                    @endforeach
                                    @if((is_object($patient->traitements) ? $patient->traitements->count() : count($patient->traitements)) > 2)
                                        <li class="text-center">
                                            <small><a href="{{ route('infirmier.patients.show', $patient->id) }}">+ {{ (is_object($patient->traitements) ? $patient->traitements->count() : count($patient->traitements)) - 2 }} autres traitements</a></small>
                                        </li>
                                    @endif
                                </ul>
                            @else
                                <p class="text-muted small">Aucun traitement en cours</p>
                            @endif

                            <hr>

                            <h6><i class="fas fa-clipboard me-1 text-success"></i> Dernière observation</h6>
                            @if($patient->observations && (is_object($patient->observations) ? $patient->observations->count() : count($patient->observations)) > 0)
                                @php 
                                    $lastObservation = $patient->observations->sortByDesc('created_at')->first();
                                @endphp
                                <p class="small mb-1">{{ Str::limit($lastObservation->contenu, 80) }}</p>
                                <p class="text-muted small"><i class="fas fa-clock me-1"></i> {{ $lastObservation->created_at->format('d/m/Y H:i') }}</p>
                            @else
                                <p class="text-muted small">Aucune observation récente</p>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="{{ route('infirmier.patients.show', $patient->id) }}" class="btn btn-sm btn-primary me-1 flex-grow-1">
                                    <i class="fas fa-eye me-1"></i> Détails
                                </a>
                                <a href="{{ route('infirmier.observations.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-success flex-grow-1">
                                    <i class="fas fa-plus me-1"></i> Observation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-user-nurse text-success mb-3" style="font-size: 3rem;"></i>
                        <h4 class="mb-3">Aucun patient assigné</h4>
                        <p>Vous n'avez aucun patient assigné à votre service pour le moment.</p>
                        <p class="text-muted">Contactez l'administrateur pour plus d'informations.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Recherche de patient
        $("#searchPatient").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".patient-card").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Filtres
        $("#filterEtat, #filterTraitement").on("change", function() {
            applyFilters();
        });
        
        // Réinitialiser les filtres
        $("#resetFilters").on("click", function() {
            $("#filterEtat").val('');
            $("#filterTraitement").val('');
            $("#searchPatient").val('');
            $(".patient-card").show();
        });
        
        function applyFilters() {
            var etatFilter = $("#filterEtat").val().toLowerCase();
            var traitementFilter = $("#filterTraitement").val().toLowerCase();
            
            $(".patient-card").each(function() {
                var card = $(this);
                var etatMatch = true;
                var traitementMatch = true;
                
                if (etatFilter) {
                    etatMatch = card.find(".badge").text().toLowerCase().indexOf(etatFilter.toLowerCase()) > -1;
                }
                
                if (traitementFilter) {
                    traitementMatch = card.find(".treatment-info").text().toLowerCase().indexOf(traitementFilter.toLowerCase()) > -1;
                }
                
                card.toggle(etatMatch && traitementMatch);
            });
        }
    });
</script>
@endsection
