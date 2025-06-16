@extends('layouts.infirmier')

@section('title', 'Gestion des alertes')

@section('styles')
<style>
    .alert-card {
        transition: all 0.2s ease;
        border-left: 4px solid #28a745;
    }
    
    .alert-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .alert-card.high {
        border-left-color: #dc3545;
    }
    
    .alert-card.medium {
        border-left-color: #ffc107;
    }
    
    .alert-card.low {
        border-left-color: #17a2b8;
    }
    
    .alert-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    
    .alert-icon.high {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .alert-icon.medium {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .alert-icon.low {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }
    
    .badge-high {
        background-color: #dc3545;
        color: white;
    }
    
    .badge-medium {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-low {
        background-color: #17a2b8;
        color: white;
    }
    
    .alert-actions a {
        transition: all 0.2s;
    }
    
    .alert-actions a:hover {
        transform: scale(1.1);
    }
    
    .nav-pills .nav-link.active {
        background-color: #28a745;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="fas fa-bell me-2 text-success"></i>
            Gestion des alertes
        </h1>
        <p class="text-muted">Consultez et gérez les alertes concernant vos patients</p>
    </div>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" id="searchAlerte" placeholder="Rechercher une alerte...">
            <button class="btn btn-success" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<!-- Statistiques des alertes -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="alert-icon high me-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Alertes critiques</h6>
                        <h3 class="mt-2 mb-0 text-danger">{{ isset($alertes_critiques) ? (is_object($alertes_critiques) ? $alertes_critiques->count() : count($alertes_critiques)) : 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="alert-icon medium me-3">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Alertes modérées</h6>
                        <h3 class="mt-2 mb-0 text-warning">{{ isset($alertes_moderees) ? (is_object($alertes_moderees) ? $alertes_moderees->count() : count($alertes_moderees)) : 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="alert-icon low me-3">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Alertes légères</h6>
                        <h3 class="mt-2 mb-0 text-info">{{ isset($alertes_legeres) ? (is_object($alertes_legeres) ? $alertes_legeres->count() : count($alertes_legeres)) : 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="alert-icon me-3" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Alertes résolues</h6>
                        <h3 class="mt-2 mb-0 text-success">{{ isset($alertes_resolues) ? (is_object($alertes_resolues) ? $alertes_resolues->count() : count($alertes_resolues)) : 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et onglets -->
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="row align-items-center">
            <div class="col-md-auto mb-2 mb-md-0">
                <strong><i class="fas fa-filter text-success me-2"></i>Filtrer par :</strong>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select class="form-select form-select-sm" id="filterPriority">
                    <option value="">Priorité</option>
                    <option value="high">Haute</option>
                    <option value="medium">Moyenne</option>
                    <option value="low">Basse</option>
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select class="form-select form-select-sm" id="filterPatient">
                    <option value="">Patient</option>
                    @if(isset($patients) && (is_object($patients) ? $patients->count() : count($patients)) > 0)
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select class="form-select form-select-sm" id="filterStatus">
                    <option value="">Statut</option>
                    <option value="active">Active</option>
                    <option value="resolved">Résolue</option>
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

<!-- Onglets des alertes -->
<ul class="nav nav-pills mb-3" id="alertesTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
            Toutes <span class="badge bg-light text-dark ms-1">{{ isset($alertes) ? (is_object($alertes) ? $alertes->count() : count($alertes)) : 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="today-tab" data-bs-toggle="tab" data-bs-target="#today" type="button" role="tab">
            Aujourd'hui <span class="badge bg-success text-white ms-1">{{ isset($alertes_aujourdhui) ? (is_object($alertes_aujourdhui) ? $alertes_aujourdhui->count() : count($alertes_aujourdhui)) : 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
            Non lues <span class="badge bg-warning text-dark ms-1">{{ isset($alertes_non_lues) ? (is_object($alertes_non_lues) ? $alertes_non_lues->count() : count($alertes_non_lues)) : 0 }}</span>
        </button>
    </li>
</ul>

<!-- Contenu des onglets -->
<div class="tab-content" id="alertesTabsContent">
    <!-- Toutes les alertes -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        @if(isset($alertes) && (is_object($alertes) ? $alertes->count() : count($alertes)) > 0)
            @foreach($alertes as $alerte)
                @php
                    $priorityClass = $alerte->priorite === 'HAUTE' ? 'high' : ($alerte->priorite === 'MOYENNE' ? 'medium' : 'low');
                    $priorityText = $alerte->priorite === 'HAUTE' ? 'Haute' : ($alerte->priorite === 'MOYENNE' ? 'Moyenne' : 'Basse');
                    $priorityIcon = $alerte->priorite === 'HAUTE' ? 'exclamation-triangle' : ($alerte->priorite === 'MOYENNE' ? 'exclamation-circle' : 'info-circle');
                @endphp
                <div class="card alert-card {{ $priorityClass }} mb-3 shadow-sm alerte-item">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="alert-icon {{ $priorityClass }} me-3">
                                <i class="fas fa-{{ $priorityIcon }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-1">{{ $alerte->titre }}</h5>
                                    <div>
                                        <span class="badge badge-{{ $priorityClass }} me-2">{{ $priorityText }}</span>
                                        @if($alerte->statut === 'RESOLVED')
                                            <span class="badge bg-success">Résolue</span>
                                        @else
                                            <span class="badge bg-secondary">Active</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <p class="mb-1">{{ $alerte->message }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="small text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        <strong>Patient:</strong> 
                                        {{ isset($alerte->patient) ? $alerte->patient->prenom . ' ' . $alerte->patient->nom : 'N/A' }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $alerte->created_at ? $alerte->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </div>
                                    <div class="alert-actions">
                                        <a href="{{ route('infirmier.alertes.show', $alerte->id) }}" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($alerte->statut !== 'RESOLVED')
                                            <a href="#" class="btn btn-sm btn-outline-success me-1 resolve-btn" data-id="{{ $alerte->id }}" data-bs-toggle="tooltip" title="Marquer comme résolue">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Ajouter un commentaire">
                                            <i class="fas fa-comment"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-bell-slash text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucune alerte à afficher</h4>
                    <p>Il n'y a actuellement aucune alerte pour vos patients.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Alertes d'aujourd'hui -->
    <div class="tab-pane fade" id="today" role="tabpanel">
        @if(isset($alertes_aujourdhui) && (is_object($alertes_aujourdhui) ? $alertes_aujourdhui->count() : count($alertes_aujourdhui)) > 0)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Vous avez {{ (is_object($alertes_aujourdhui) ? $alertes_aujourdhui->count() : count($alertes_aujourdhui)) }} alerte(s) générée(s) aujourd'hui.
            </div>
            <!-- Contenu similaire à l'onglet "Toutes" mais avec $alertes_aujourdhui -->
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-calendar-check text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucune alerte aujourd'hui</h4>
                    <p>Il n'y a pas eu de nouvelles alertes générées aujourd'hui.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Alertes non lues -->
    <div class="tab-pane fade" id="unread" role="tabpanel">
        @if(isset($alertes_non_lues) && (is_object($alertes_non_lues) ? $alertes_non_lues->count() : count($alertes_non_lues)) > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i>
                Vous avez {{ (is_object($alertes_non_lues) ? $alertes_non_lues->count() : count($alertes_non_lues)) }} alerte(s) non lue(s).
            </div>
            <!-- Contenu similaire à l'onglet "Toutes" mais avec $alertes_non_lues -->
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-check-double text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucune alerte non lue</h4>
                    <p>Toutes les alertes ont été consultées. Bon travail !</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal pour résolution d'alerte -->
<div class="modal fade" id="resolveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Résoudre l'alerte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="resolveForm">
                    <input type="hidden" id="alerteId" name="alerte_id">
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Commentaire de résolution</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" placeholder="Décrivez comment l'alerte a été résolue..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmResolve">Confirmer la résolution</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Recherche d'alerte
        $("#searchAlerte").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".alerte-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Filtres
        $("#filterPriority, #filterPatient, #filterStatus").on("change", function() {
            applyFilters();
        });
        
        // Réinitialiser les filtres
        $("#resetFilters").on("click", function() {
            $("#filterPriority").val('');
            $("#filterPatient").val('');
            $("#filterStatus").val('');
            $("#searchAlerte").val('');
            $(".alerte-item").show();
        });
        
        // Modal de résolution
        $(".resolve-btn").on("click", function(e) {
            e.preventDefault();
            var alerteId = $(this).data("id");
            $("#alerteId").val(alerteId);
            $("#resolveModal").modal("show");
        });
        
        // Confirmation de résolution
        $("#confirmResolve").on("click", function() {
            var alerteId = $("#alerteId").val();
            var commentaire = $("#commentaire").val();
            
            // Ici, vous pouvez ajouter un appel AJAX pour résoudre l'alerte
            // Par exemple:
            /*
            $.ajax({
                url: "/infirmier/alertes/" + alerteId + "/resolve",
                type: "POST",
                data: {
                    commentaire: commentaire,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Actualiser la page ou mettre à jour l'UI
                    window.location.reload();
                }
            });
            */
            
            // Pour l'instant, fermons simplement le modal
            $("#resolveModal").modal("hide");
            
            // Affichage d'un message temporaire
            $("body").append('<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert" id="tempAlert">' +
                'L\'alerte a été marquée comme résolue avec succès.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');
                
            setTimeout(function() {
                $("#tempAlert").alert('close');
            }, 3000);
        });
        
        function applyFilters() {
            var priorityFilter = $("#filterPriority").val().toLowerCase();
            var patientFilter = $("#filterPatient").val();
            var statusFilter = $("#filterStatus").val().toLowerCase();
            
            $(".alerte-item").each(function() {
                var card = $(this);
                var priorityMatch = true;
                var patientMatch = true;
                var statusMatch = true;
                
                if (priorityFilter) {
                    priorityMatch = card.hasClass(priorityFilter);
                }
                
                if (patientFilter) {
                    patientMatch = card.find(".small.text-muted").text().indexOf(patientFilter) > -1;
                }
                
                if (statusFilter) {
                    if (statusFilter === "resolved") {
                        statusMatch = card.find(".badge.bg-success").length > 0;
                    } else if (statusFilter === "active") {
                        statusMatch = card.find(".badge.bg-secondary").length > 0;
                    }
                }
                
                card.toggle(priorityMatch && patientMatch && statusMatch);
            });
        }
    });
</script>
@endsection
