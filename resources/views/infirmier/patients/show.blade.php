@extends('layouts.infirmier')

@section('title', 'Détails du patient')

@section('styles')
<style>
    .card-header-tabs .nav-link {
        color: #333;
        padding: 0.75rem 1rem;
    }
    
    .card-header-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 2px solid #28a745;
        background-color: #f8f9fa;
    }
    
    .info-group {
        margin-bottom: 1.5rem;
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-weight: 500;
    }
    
    .treatment-item {
        border-left: 3px solid #28a745;
        padding-left: 10px;
        margin-bottom: 15px;
    }
    
    .observation-item {
        padding: 15px;
        border-radius: 8px;
        background-color: #f8f9fa;
        margin-bottom: 15px;
        border-left: 3px solid #17a2b8;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        background-color: #4e73df;
        color: white;
        margin-right: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Retour et en-tête -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('infirmier.patients.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-user-injured me-2 text-success"></i>
                Fiche patient
            </h1>
            <p class="text-muted mb-0">Informations détaillées et suivi du patient</p>
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
        <!-- Informations personnelles -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2 text-success"></i>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-4">
                        <div class="avatar-lg">
                            {{ strtoupper(substr($patient->utilisateur->prenom, 0, 1) . substr($patient->utilisateur->nom, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}</h4>
                            <span class="badge {{ $patient->etat_sante == 'CRITIQUE' ? 'bg-danger' : ($patient->etat_sante == 'ALERTE' ? 'bg-warning' : 'bg-success') }}">
                                {{ $patient->etat_sante ?? 'STABLE' }}
                            </span>
                        </div>
                    </div>

                    <div class="info-group">
                        <div class="info-label"><i class="fas fa-birthday-cake me-1 text-success"></i> Date de naissance</div>
                        <div class="info-value">{{ $patient->date_naissance ? $patient->date_naissance->format('d/m/Y') : 'Non renseignée' }}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label"><i class="fas fa-venus-mars me-1 text-success"></i> Genre</div>
                        <div class="info-value">{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label"><i class="fas fa-phone me-1 text-success"></i> Téléphone</div>
                        <div class="info-value">{{ $patient->utilisateur->telephone ?? 'Non renseigné' }}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label"><i class="fas fa-envelope me-1 text-success"></i> Email</div>
                        <div class="info-value">{{ $patient->utilisateur->email ?? 'Non renseigné' }}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label"><i class="fas fa-map-marker-alt me-1 text-success"></i> Adresse</div>
                        <div class="info-value">{{ $patient->utilisateur->adresse ?? 'Non renseignée' }}</div>
                    </div>

                    <div class="info-group">
                        <div class="info-label"><i class="fas fa-bed me-1 text-success"></i> Chambre</div>
                        <div class="info-value">{{ $patient->chambre ?? 'Non assignée' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dossier médical et informations médicales -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white px-3 py-0">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="observations-tab" data-bs-toggle="tab" href="#observations" role="tab">
                                <i class="fas fa-clipboard-list me-1"></i> Observations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="traitements-tab" data-bs-toggle="tab" href="#traitements" role="tab">
                                <i class="fas fa-pills me-1"></i> Traitements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="dossier-tab" data-bs-toggle="tab" href="#dossier" role="tab">
                                <i class="fas fa-folder-open me-1"></i> Dossier médical
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Observations -->
                        <div class="tab-pane fade show active" id="observations" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-clipboard-list me-2 text-success"></i>Observations</h5>
                                <a href="{{ route('infirmier.observations.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus me-1"></i> Nouvelle observation
                                </a>
                            </div>

                            @if($patient->observations && (is_object($patient->observations) ? $patient->observations->count() : count($patient->observations)) > 0)
                                @foreach($patient->observations->sortByDesc('created_at') as $observation)
                                    <div class="observation-item">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">{{ $observation->created_at->format('d/m/Y H:i') }}</span>
                                            <span class="text-muted">Par: {{ $observation->infirmier ? $observation->infirmier->utilisateur->prenom . ' ' . $observation->infirmier->utilisateur->nom : 'Inconnu' }}</span>
                                        </div>
                                        <p class="mb-0">{{ $observation->contenu }}</p>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-clipboard text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5>Aucune observation</h5>
                                    <p class="text-muted mb-0">Aucune observation n'a été enregistrée pour ce patient.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab Traitements -->
                        <div class="tab-pane fade" id="traitements" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-pills me-2 text-success"></i>Traitements</h5>
                                <a href="#" class="btn btn-sm btn-success">
                                    <i class="fas fa-check me-1"></i> Valider administration
                                </a>
                            </div>

                            @if($patient->traitements && (is_object($patient->traitements) ? $patient->traitements->count() : count($patient->traitements)) > 0)
                                @foreach($patient->traitements->sortByDesc('created_at') as $traitement)
                                    <div class="treatment-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold">{{ $traitement->nom }}</h6>
                                            <span class="badge bg-primary">{{ $traitement->type }}</span>
                                        </div>
                                        <p class="mb-1">{{ $traitement->description }}</p>
                                        <div class="d-flex align-items-center text-muted small">
                                            <span class="me-3"><i class="fas fa-calendar me-1"></i> {{ $traitement->date_debut->format('d/m/Y') }} - {{ $traitement->date_fin ? $traitement->date_fin->format('d/m/Y') : 'En cours' }}</span>
                                            <span><i class="fas fa-clock me-1"></i> {{ $traitement->frequence }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-pills text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5>Aucun traitement</h5>
                                    <p class="text-muted mb-0">Aucun traitement n'est actuellement prescrit pour ce patient.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab Dossier médical -->
                        <div class="tab-pane fade" id="dossier" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-folder-open me-2 text-success"></i>Dossier médical</h5>

                            @if($patient->dossier_medical)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <div class="info-label">Médecin traitant</div>
                                            <div class="info-value">
                                                @if($patient->dossier_medical->medecin && $patient->dossier_medical->medecin->utilisateur)
                                                    Dr. {{ $patient->dossier_medical->medecin->utilisateur->prenom }} {{ $patient->dossier_medical->medecin->utilisateur->nom }}
                                                @else
                                                    Non assigné
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group">
                                            <div class="info-label">Date de création</div>
                                            <div class="info-value">{{ $patient->dossier_medical->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-notes-medical me-1"></i> Antécédents médicaux</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($patient->dossier_medical->antecedents)
                                            <p>{{ is_array($patient->dossier_medical->antecedents) ? json_encode($patient->dossier_medical->antecedents) : $patient->dossier_medical->antecedents }}</p>
                                        @else
                                            <p class="text-muted">Aucun antécédent médical enregistré.</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-allergies me-1"></i> Allergies</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($patient->dossier_medical->allergies)
                                            <p>{{ is_array($patient->dossier_medical->allergies) ? json_encode($patient->dossier_medical->allergies) : $patient->dossier_medical->allergies }}</p>
                                        @else
                                            <p class="text-muted">Aucune allergie connue.</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-1"></i> Informations complémentaires</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($patient->dossier_medical->notes)
                                            <p>{{ is_array($patient->dossier_medical->notes) ? json_encode($patient->dossier_medical->notes) : $patient->dossier_medical->notes }}</p>
                                        @else
                                            <p class="text-muted">Aucune information complémentaire.</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5>Aucun dossier médical</h5>
                                    <p class="text-muted mb-0">Ce patient n'a pas encore de dossier médical créé.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('infirmier.observations.create', ['patient_id' => $patient->id]) }}" class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i>Nouvelle observation
                        </a>
                        <a href="{{ route('infirmier.traitements.create', $patient->id) }}" class="btn btn-info text-white">
                            <i class="fas fa-pills me-2"></i>Nouveau traitement
                        </a>
                        <a href="{{ route('infirmier.patients.observations', $patient->id) }}" class="btn btn-primary">
                            <i class="fas fa-list-ul me-2"></i>Voir toutes les observations
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Prendre un RDV
                        </a>
                        <a href="#" class="btn btn-outline-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Signaler un problème
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Activation des onglets Bootstrap
        $('.nav-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection
