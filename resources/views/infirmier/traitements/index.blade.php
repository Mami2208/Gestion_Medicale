@extends('layouts.infirmier')

@section('title', 'Suivi des traitements')

@section('styles')
<style>
    .traitement-card {
        transition: all 0.2s ease;
        margin-bottom: 15px;
        height: 100%;
    }
    
    .traitement-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        background-color: #28a745;
        color: white;
        text-transform: uppercase;
    }
    
    .treatment-progress {
        height: 6px;
        margin-top: 5px;
    }
    
    .treatment-stats {
        display: flex;
        justify-content: space-between;
    }
    
    .stat-card {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        color: white;
        text-align: center;
    }
    
    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin: 5px 0;
    }
    
    .stat-card .stat-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }
    
    .filter-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .treatment-meta {
        margin-top: 10px;
        font-size: 0.9rem;
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
            <i class="fas fa-pills me-2 text-success"></i>
            Suivi des traitements
        </h1>
        <p class="text-muted">Gérez et suivez les traitements de vos patients</p>
    </div>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" id="searchTraitement" placeholder="Rechercher un traitement...">
            <button class="btn btn-success" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
            <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-label">Traitements actifs</div>
            <i class="fas fa-pills fa-2x opacity-50 float-end mt-2"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
            <div class="stat-value">{{ $stats['en_cours'] ?? 0 }}</div>
            <div class="stat-label">En cours</div>
            <i class="fas fa-spinner fa-2x opacity-50 float-end mt-2"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
            <div class="stat-value">{{ $stats['en_attente'] ?? 0 }}</div>
            <div class="stat-label">En attente</div>
            <i class="fas fa-clock fa-2x opacity-50 float-end mt-2"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);">
            <div class="stat-value">{{ $stats['en_retard'] ?? 0 }}</div>
            <div class="stat-label">En retard</div>
            <i class="fas fa-exclamation-triangle fa-2x opacity-50 float-end mt-2"></i>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filtersForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filterType" class="form-label">Type de traitement</label>
                    <select class="form-select" id="filterType" name="type">
                        <option value="">Tous les types</option>
                        @foreach(\App\Models\Traitement::TYPES as $value => $label)
                            <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Statut</label>
                    <select class="form-select" id="filterStatus" name="statut">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\Traitement::STATUTS as $value => $label)
                            <option value="{{ $value }}" {{ request('statut') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterPatient" class="form-label">Patient</label>
                    <select class="form-select" id="filterPatient" name="patient_id">
                        <option value="">Tous les patients</option>
                        @foreach($infirmier->patients as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <a href="{{ route('infirmier.traitements.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Onglets des traitements -->
<ul class="nav nav-pills mb-3" id="traitementsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tous-tab" data-bs-toggle="tab" data-bs-target="#tous" type="button" role="tab">
            Tous <span class="badge bg-light text-dark ms-1">{{ isset($traitements) ? (is_object($traitements) ? $traitements->count() : count($traitements)) : 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="aujourdhui-tab" data-bs-toggle="tab" data-bs-target="#aujourdhui" type="button" role="tab">
            Aujourd'hui <span class="badge bg-success text-white ms-1">{{ isset($traitements_aujourdhui) ? (is_object($traitements_aujourdhui) ? $traitements_aujourdhui->count() : count($traitements_aujourdhui)) : 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="a-valider-tab" data-bs-toggle="tab" data-bs-target="#a-valider" type="button" role="tab">
            À valider <span class="badge bg-warning text-dark ms-1">{{ isset($traitements_a_valider) ? (is_object($traitements_a_valider) ? $traitements_a_valider->count() : count($traitements_a_valider)) : 0 }}</span>
        </button>
    </li>
</ul>

<!-- Contenu des onglets -->
<div class="tab-content" id="traitementsTabsContent">
    <!-- Tous les traitements -->
    <div class="tab-pane fade show active" id="tous" role="tabpanel">
        @if(isset($traitements) && (is_object($traitements) ? $traitements->count() : count($traitements)) > 0)
            <div class="row">
                @foreach($traitements as $traitement)
                    <div class="col-md-6 traitement-card">
                        <div class="card">
                            <div class="card-body">
                                @php
                                    $patient = $traitement->patient;
                                    $medecin = $traitement->medecin;
                                    $badgeClass = [
                                        'EN_ATTENTE' => 'bg-warning',
                                        'EN_COURS' => 'bg-primary',
                                        'TERMINE' => 'bg-secondary',
                                        'ANNULE' => 'bg-danger',
                                        'PAUSE' => 'bg-info'
                                    ][$traitement->status] ?? 'bg-secondary';
                                    
                                    $debut = $traitement->date_debut ? \Carbon\Carbon::parse($traitement->date_debut) : null;
                                    $fin = $traitement->dateFin ? \Carbon\Carbon::parse($traitement->dateFin) : null;
                                    $aujourdhui = now();
                                    $total = $debut && $fin ? $debut->diffInDays($fin) : 0;
                                    $reste = $fin ? $aujourdhui->diffInDays($fin, false) : 0;
                                    $progres = $total > 0 ? max(0, min(100, 100 - ($reste * 100 / $total))) : 0;
                                @endphp
                                
                                <!-- En-tête avec patient et statut -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="patient-avatar me-2">
                                            {{ $patient ? substr($patient->utilisateur->prenom, 0, 1) . substr($patient->utilisateur->nom, 0, 1) : 'P' }}
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $traitement->type_libelle }}</h5>
                                            <small class="text-muted">
                                                {{ $patient ? $patient->utilisateur->prenom . ' ' . $patient->utilisateur->nom : 'Patient inconnu' }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $traitement->status_libelle ?? $traitement->status }}
                                    </span>
                                </div>
                                
                                <!-- Détails du traitement -->
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-pills me-1"></i>Description
                                    </h6>
                                    <p class="mb-0">{{ $traitement->description ?? 'Aucune description' }}</p>
                                </div>
                                
                                <!-- Période de traitement -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="text-muted small">Date de début</div>
                                        <div><i class="far fa-calendar-alt me-2"></i>{{ $debut ? $debut->format('d/m/Y') : 'Non définie' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">Date de fin</div>
                                        <div>
                                            <i class="far fa-calendar-alt me-2"></i>
                                            {{ $fin ? $fin->format('d/m/Y') : 'Non définie' }}
                                        </div>
                                    </div>
                                    @if($fin)
                                        <div class="col-12 mt-2">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $aujourdhui > $fin ? 'danger' : 'success' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $progres }}%" 
                                                     aria-valuenow="{{ $progres }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"
                                                     title="{{ $traitement->duree_restante }}">
                                                </div>
                                            </div>
                                            <div class="text-end small text-muted mt-1">
                                                {{ $traitement->duree_restante }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Médicaments associés -->
                                @if($traitement->medicaments->isNotEmpty())
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-pills me-1"></i>Médicaments
                                        </h6>
                                        <div class="ms-3">
                                            @foreach($traitement->medicaments as $medicament)
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span>{{ $medicament->nom }}</span>
                                                    <small class="text-muted">
                                                        {{ $medicament->pivot->posologie }} - {{ $medicament->pivot->frequence }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Observations -->
                                @if($traitement->observations)
                                    <div class="alert alert-light border small p-2 mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ Str::limit($traitement->observations, 100) }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent border-top-0 pt-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-user-md me-1"></i>
                                        {{ $medecin ? 'Dr. ' . $medecin->utilisateur->prenom . ' ' . $medecin->utilisateur->nom : 'Médecin non spécifié' }}
                                        <span class="mx-2">•</span>
                                        <i class="far fa-clock me-1"></i>
                                        {{ $traitement->created_at ? $traitement->created_at->diffForHumans() : 'Date inconnue' }}
                                    </div>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($traitement->status === 'EN_ATTENTE')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Valider le traitement"
                                                    onclick="confirmAction('{{ $traitement->id }}', 'valider')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if($traitement->status === 'EN_COURS')
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Mettre en pause"
                                                    onclick="confirmAction('{{ $traitement->id }}', 'pause')">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Annuler le traitement"
                                                    onclick="confirmAction('{{ $traitement->id }}', 'annuler')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        @if($traitement->status === 'PAUSE')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Reprendre le traitement"
                                                    onclick="confirmAction('{{ $traitement->id }}', 'reprendre')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-pills text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucun traitement à afficher</h4>
                    <p>Il n'y a actuellement aucun traitement enregistré pour vos patients.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Traitements d'aujourd'hui -->
    <div class="tab-pane fade" id="aujourdhui" role="tabpanel">
        @if(isset($traitements_aujourdhui) && (is_object($traitements_aujourdhui) ? $traitements_aujourdhui->count() : count($traitements_aujourdhui)) > 0)
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Vous avez {{ (is_object($traitements_aujourdhui) ? $traitements_aujourdhui->count() : count($traitements_aujourdhui)) }} traitement(s) à administrer aujourd'hui.
            </div>
            <!-- Contenu similaire à l'onglet "Tous" mais avec $traitements_aujourdhui -->
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-calendar-check text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucun traitement prévu aujourd'hui</h4>
                    <p>Il n'y a aucun traitement à administrer pour aujourd'hui.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Traitements à valider -->
    <div class="tab-pane fade" id="a-valider" role="tabpanel">
        @if(isset($traitements_a_valider) && (is_object($traitements_a_valider) ? $traitements_a_valider->count() : count($traitements_a_valider)) > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i>
                Vous avez {{ (is_object($traitements_a_valider) ? $traitements_a_valider->count() : count($traitements_a_valider)) }} traitement(s) en attente de validation.
            </div>
            <!-- Contenu similaire à l'onglet "Tous" mais avec $traitements_a_valider -->
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="mb-3">Aucun traitement à valider</h4>
                    <p>Tous les traitements ont été validés. Bon travail !</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialisation des tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Filtrage des traitements
        $('#searchTraitement').on('input', function() {
            var searchValue = $(this).val().toLowerCase();
            $('.traitement-card').each(function() {
                var cardText = $(this).text().toLowerCase();
                $(this).toggle(cardText.includes(searchValue));
            });
        });
        
        // Gestion des onglets
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            // Réinitialiser la recherche lors du changement d'onglet
            $('#searchTraitement').val('').trigger('input');
        });
        
        // Réinitialisation des filtres
        $('#resetFilters').on('click', function() {
            $('#filtersForm')[0].reset();
            $('.traitement-card').show();
        });
    });
    
    // Gestion des actions sur les traitements
    function confirmAction(traitementId, action) {
        const messages = {
            'valider': {
                title: 'Valider le traitement',
                text: 'Êtes-vous sûr de vouloir valider ce traitement ?',
                icon: 'question',
                confirmButtonText: 'Oui, valider',
                success: 'Traitement validé avec succès',
                error: 'Erreur lors de la validation du traitement',
                newStatus: 'EN_COURS'
            },
            'pause': {
                title: 'Mettre en pause',
                text: 'Voulez-vous vraiment mettre ce traitement en pause ?',
                icon: 'question',
                confirmButtonText: 'Oui, mettre en pause',
                success: 'Traitement mis en pause avec succès',
                error: 'Erreur lors de la mise en pause du traitement',
                newStatus: 'PAUSE'
            },
            'reprendre': {
                title: 'Reprendre le traitement',
                text: 'Voulez-vous reprendre ce traitement ?',
                icon: 'question',
                confirmButtonText: 'Oui, reprendre',
                success: 'Traitement repris avec succès',
                error: 'Erreur lors de la reprise du traitement',
                newStatus: 'EN_COURS'
            },
            'annuler': {
                title: 'Annuler le traitement',
                text: 'Êtes-vous sûr de vouloir annuler ce traitement ? Cette action est irréversible.',
                icon: 'warning',
                confirmButtonText: 'Oui, annuler',
                confirmButtonColor: '#dc3545',
                success: 'Traitement annulé avec succès',
                error: 'Erreur lors de l\'annulation du traitement',
                newStatus: 'ANNULE'
            }
        };
        
        const config = messages[action];
        
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon,
            showCancelButton: true,
            confirmButtonText: config.confirmButtonText,
            confirmButtonColor: config.confirmButtonColor || '#198754',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                updateTraitementStatus(traitementId, config);
            }
        });
    }
    
    function updateTraitementStatus(traitementId, config) {
        // Afficher un indicateur de chargement
        Swal.fire({
            title: 'Traitement en cours...',
            text: 'Veuillez patienter',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Envoyer la requête AJAX
        $.ajax({
            url: `/infirmier/traitements/${traitementId}/status`,
            type: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                statut: config.newStatus
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Succès',
                        text: config.success,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Recharger la page pour afficher les changements
                        window.location.reload();
                    });
                } else {
                    throw new Error(response.message || config.error);
                }
            },
            error: function(xhr) {
                let errorMessage = config.error;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    title: 'Erreur',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
</script>
@endsection
