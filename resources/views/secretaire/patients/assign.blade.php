@extends('layouts.secretaire')

@section('title', 'Attribution de patients aux infirmiers')

@section('styles')
<style>
    .nurse-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    
    .nurse-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .nurse-card.selected {
        border-color: #28a745;
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    .nurse-card.selected .check-icon {
        display: block !important;
    }
    
    .patient-item.selected {
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    .nurse-workload {
        width: 100%;
        height: 5px;
        background-color: #e9ecef;
        border-radius: 3px;
        margin-top: 5px;
    }
    
    .nurse-workload-bar {
        height: 100%;
        border-radius: 3px;
        background-color: #28a745;
    }
    
    .badge-hospitalise {
        background-color: #007bff;
        color: white;
    }
    
    .badge-ambulatoire {
        background-color: #28a745;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Attribution de patients aux infirmiers</h1>
    <p class="mb-4">Gérez l'attribution des patients aux infirmiers pour les soins et suivis</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('secretaire.patients.doAssignMultiple') }}" method="POST" id="assignForm">
        @csrf
        <input type="hidden" name="infirmier_id" id="selected_infirmier_id">
        <div class="row">
            <!-- Sélection de l'infirmier -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sélectionner un infirmier</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="searchNurse" placeholder="Rechercher un infirmier...">
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="filterWorkload" class="form-label small"><i class="fas fa-filter me-1"></i>Filtrer par charge de travail :</label>
                            <select class="form-select form-select-sm" id="filterWorkload">
                                <option value="all">Tous les infirmiers</option>
                                <option value="faible">Charge faible</option>
                                <option value="moyenne">Charge moyenne</option>
                                <option value="elevee">Charge élevée</option>
                            </select>
                        </div>
                        
                        <div class="row" id="nursesList">
                            @foreach($infirmiers as $infirmier)
                                @php
                                    $categorie = $infirmier->categorie_charge;
                                    $categorieClass = $categorie === 'elevee' ? 'danger' : ($categorie === 'moyenne' ? 'warning' : 'success');
                                    $categorieIcon = $categorie === 'elevee' ? 'exclamation-triangle' : ($categorie === 'moyenne' ? 'exclamation' : 'check-circle');
                                    $categorieText = $categorie === 'elevee' ? 'Élevée' : ($categorie === 'moyenne' ? 'Moyenne' : 'Faible');
                                @endphp
                                <div class="col-md-12 mb-3 nurse-item">
                                    <div class="card nurse-card" data-nurse-id="{{ $infirmier->id }}" data-workload="{{ $categorie }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-1">{{ $infirmier->utilisateur->prenom }} {{ $infirmier->utilisateur->nom }}</h5>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <small class="text-muted me-3">{{ $infirmier->nombre_patients }} patient(s) assigné(s)</small>
                                                        <span class="badge bg-{{ $categorieClass }} badge-sm"><i class="fas fa-{{ $categorieIcon }} me-1"></i> {{ $categorieText }}</span>
                                                    </div>
                                                    
                                                    <div class="nurse-workload">
                                                        <div class="nurse-workload-bar bg-{{ $categorieClass }}" style="width: {{ $infirmier->pourcentage_charge }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="check-icon text-success d-none ms-3">
                                                    <i class="fas fa-check-circle fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sélection des patients -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Sélectionner des patients</h6>
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllPatients">
                                <i class="fas fa-check-square mr-1"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAllPatients">
                                <i class="fas fa-square mr-1"></i> Tout désélectionner
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" id="searchPatient" placeholder="Rechercher un patient...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" id="filterPatient">
                                    <option value="all">Tous les patients</option>
                                    <option value="unassigned">Patients sans infirmier</option>
                                    <option value="assigned">Patients avec infirmier</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="patientsTable">
                                <thead>
                                    <tr>
                                        <th width="50px">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>Nom</th>
                                        <th>Âge</th>
                                        <th>Chambre</th>
                                        <th>Statut</th>
                                        <th>Infirmier actuel</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr class="patient-item {{ $patient->infirmier_id ? 'assigned' : 'unassigned' }}">
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input patient-checkbox" 
                                                           id="patient{{ $patient->id }}" name="patient_ids[]" value="{{ $patient->id }}">
                                                    <label class="custom-control-label" for="patient{{ $patient->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}</td>
                                            <td>{{ $patient->age ?? 'N/A' }} ans</td>
                                            <td>{{ $patient->chambre ?? 'N/A' }}</td>
                                            <td>
                                                @if($patient->statut === 'HOSPITALISE')
                                                    <span class="badge badge-hospitalise">Hospitalisé</span>
                                                @elseif($patient->statut === 'AMBULATOIRE')
                                                    <span class="badge badge-ambulatoire">Ambulatoire</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $patient->statut ?? 'Indéfini' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($patient->infirmier_id && isset($patient->infirmier->utilisateur))
                                                    {{ $patient->infirmier->utilisateur->prenom }} {{ $patient->infirmier->utilisateur->nom }}
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('secretaire.patients.doAssign') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                                    <button type="button" class="btn btn-sm btn-primary single-assign" 
                                                            data-patient-id="{{ $patient->id }}" 
                                                            data-patient-name="{{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}"
                                                            title="Assigner individuellement">
                                                        <i class="fas fa-user-nurse"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('secretaire.patients.show', $patient->id) }}" class="btn btn-sm btn-info" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span id="selectedPatientsCount" class="badge badge-primary">0</span> patient(s) sélectionné(s)
                            </div>
                            <button type="submit" class="btn btn-success" id="assignButton" disabled>
                                <i class="fas fa-user-nurse mr-1"></i> Assigner à l'infirmier sélectionné
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal d'attribution individuelle -->
<div class="modal fade" id="singleAssignModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assigner un patient à un infirmier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('secretaire.patients.doAssign') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="patient_id" id="modal_patient_id">
                    
                    <p>Vous êtes sur le point d'assigner <strong id="modal_patient_name"></strong> à un infirmier.</p>
                    
                    <div class="form-group">
                        <label for="modal_infirmier_id">Sélectionnez un infirmier :</label>
                        <select class="form-control" id="modal_infirmier_id" name="infirmier_id" required>
                            <option value="">-- Choisir un infirmier --</option>
                            @foreach($infirmiers as $infirmier)
                                <option value="{{ $infirmier->id }}">{{ $infirmier->prenom }} {{ $infirmier->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Assigner</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Recherche d'infirmier
        $("#searchNurse").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            applyNurseFilters();
        });
        
        // Filtre par charge de travail
        $("#filterWorkload").on("change", function() {
            applyNurseFilters();
        });
        
        // Fonction pour appliquer tous les filtres aux infirmiers
        function applyNurseFilters() {
            var searchValue = $("#searchNurse").val().toLowerCase();
            var workloadFilter = $("#filterWorkload").val();
            
            $(".nurse-item").each(function() {
                var nurseCard = $(this).find('.nurse-card');
                var textMatch = $(this).text().toLowerCase().indexOf(searchValue) > -1;
                var workloadMatch = true;
                
                if (workloadFilter !== 'all') {
                    workloadMatch = nurseCard.data('workload') === workloadFilter;
                }
                
                $(this).toggle(textMatch && workloadMatch);
            });
        }
        
        // Recherche de patient
        $("#searchPatient").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#patientsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Filtre de patient
        $("#filterPatient").on("change", function() {
            var value = $(this).val();
            
            if (value === "all") {
                $("#patientsTable tbody tr").show();
            } else if (value === "unassigned") {
                $("#patientsTable tbody tr").hide();
                $("#patientsTable tbody tr.unassigned").show();
            } else if (value === "assigned") {
                $("#patientsTable tbody tr").hide();
                $("#patientsTable tbody tr.assigned").show();
            }
        });
        
        // Sélection d'un infirmier
        $(".nurse-card").on("click", function() {
            $(".nurse-card").removeClass("selected");
            $(this).addClass("selected");
            
            var nurseId = $(this).data("nurse-id");
            $("#selected_infirmier_id").val(nurseId);
            
            // Activer le bouton d'assignation si au moins un patient est sélectionné
            updateAssignButton();
        });
        
        // Sélection de patients
        $(".patient-checkbox").on("change", function() {
            updateSelectedCount();
            updateAssignButton();
        });
        
        // Sélectionner/désélectionner tous les patients
        $("#selectAll").on("change", function() {
            $(".patient-checkbox").prop("checked", $(this).prop("checked"));
            updateSelectedCount();
            updateAssignButton();
        });
        
        // Boutons de sélection rapide
        $("#selectAllPatients").on("click", function() {
            $(".patient-checkbox").prop("checked", true);
            $("#selectAll").prop("checked", true);
            updateSelectedCount();
            updateAssignButton();
        });
        
        $("#deselectAllPatients").on("click", function() {
            $(".patient-checkbox").prop("checked", false);
            $("#selectAll").prop("checked", false);
            updateSelectedCount();
            updateAssignButton();
        });
        
        // Soumission du formulaire
        $("#assignForm").on("submit", function(e) {
            if (!$("#selected_infirmier_id").val()) {
                e.preventDefault();
                alert("Veuillez sélectionner un infirmier.");
                return false;
            }
            
            var checkedPatients = $(".patient-checkbox:checked");
            if (checkedPatients.length === 0) {
                e.preventDefault();
                alert("Veuillez sélectionner au moins un patient.");
                return false;
            }
        });
        
        // Attribution individuelle
        $(".single-assign").on("click", function() {
            var patientId = $(this).data("patient-id");
            var patientName = $(this).data("patient-name");
            
            $("#modal_patient_id").val(patientId);
            $("#modal_patient_name").text(patientName);
            $("#singleAssignModal").modal("show");
        });
        
        function updateSelectedCount() {
            var count = $(".patient-checkbox:checked").length;
            $("#selectedPatientsCount").text(count);
        }
        
        function updateAssignButton() {
            var nurseSelected = $("#selected_infirmier_id").val() !== "";
            var patientsSelected = $(".patient-checkbox:checked").length > 0;
            
            $("#assignButton").prop("disabled", !(nurseSelected && patientsSelected));
        }
    });
</script>
@endsection
