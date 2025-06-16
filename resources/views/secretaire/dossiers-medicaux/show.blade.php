@extends('secretaire.layouts.app')

@section('title', 'Dossier médical - ' . $dossier->numero_dossier)

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-folder-open"></i> Dossier médical #{{ $dossier->numero_dossier }}
                <span class="badge bg-{{ $dossier->statut === 'actif' ? 'success' : ($dossier->statut === 'archive' ? 'secondary' : 'warning') }} ms-2">
                    {{ ucfirst($dossier->statut) }}
                </span>
            </h6>
            <div>
                <a href="{{ route('secretaire.dossiers-medicaux.edit', $dossier->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations du patient</h6>
                        </div>
                        <div class="card-body">
                            @if($dossier->patient && $dossier->patient->utilisateur)
                                <div class="mb-3">
                                    <h5>{{ $dossier->patient->utilisateur->prenom }} {{ $dossier->patient->utilisateur->nom }}</h5>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-envelope"></i> {{ $dossier->patient->utilisateur->email }}
                                    </p>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-phone"></i> {{ $dossier->patient->telephone ?? 'Non renseigné' }}
                                    </p>
                                    <p class="text-muted">
                                        <i class="fas fa-birthday-cake"></i> 
                                        {{ $dossier->patient->date_naissance ? \Carbon\Carbon::parse($dossier->patient->date_naissance)->format('d/m/Y') : 'Date de naissance non renseignée' }}
                                        ({{ $dossier->patient->date_naissance ? \Carbon\Carbon::parse($dossier->patient->date_naissance)->age . ' ans' : '' }})
                                    </p>
                                </div>
                            @else
                                <p class="text-muted">Aucune information patient disponible</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Médecin traitant</h6>
                        </div>
                        <div class="card-body">
                            @if($dossier->medecin && $dossier->medecin->utilisateur)
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset($dossier->medecin->utilisateur->photo ? 'storage/' . $dossier->medecin->utilisateur->photo : 'images/default-avatar.png') }}" 
                                             alt="Photo du médecin" class="rounded-circle" width="80">
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-1">Dr. {{ $dossier->medecin->utilisateur->prenom }} {{ $dossier->medecin->utilisateur->nom }}</h5>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-envelope"></i> {{ $dossier->medecin->utilisateur->email }}
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-phone"></i> {{ $dossier->medecin->telephone_cabinet ?? 'Téléphone non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Aucun médecin assigné</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100 mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-heartbeat me-2"></i>Informations médicales
                            </h6>
                            <span class="badge bg-primary">
                                <i class="far fa-calendar-alt me-1"></i>Créé le {{ $dossier->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="info-item mb-3">
                                <h6 class="d-flex align-items-center">
                                    <i class="fas fa-tint text-danger me-2"></i>Groupe sanguin
                                </h6>
                                <p class="mb-0 ps-4">
                                    @if($dossier->groupe_sanguin)
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            {{ $dossier->groupe_sanguin }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="info-item mb-3">
                                <h6 class="d-flex align-items-center">
                                    <i class="fas fa-weight text-info me-2"></i>Taille / Poids
                                </h6>
                                <div class="ps-4">
                                    @if($dossier->taille && $dossier->poids)
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="me-2">{{ $dossier->taille }} cm</span>
                                            <span class="text-muted">/</span>
                                            <span class="ms-2">{{ $dossier->poids }} kg</span>
                                        </div>
                                        @php
                                            $imc = $dossier->poids / (($dossier->taille/100) * ($dossier->taille/100));
                                            if ($imc < 16.5) {
                                                $categorie = 'Dénutrition';
                                                $badgeClass = 'bg-dark';
                                            } elseif ($imc < 18.5) {
                                                $categorie = 'Maigreur';
                                                $badgeClass = 'bg-warning';
                                            } elseif ($imc < 25) {
                                                $categorie = 'Poids normal';
                                                $badgeClass = 'bg-success';
                                            } elseif ($imc < 30) {
                                                $categorie = 'Surpoids';
                                                $badgeClass = 'bg-warning';
                                            } elseif ($imc < 35) {
                                                $categorie = 'Obésité modérée';
                                                $badgeClass = 'bg-danger';
                                            } elseif ($imc < 40) {
                                                $categorie = 'Obésité sévère';
                                                $badgeClass = 'bg-danger';
                                            } else {
                                                $categorie = 'Obésité morbide';
                                                $badgeClass = 'bg-danger';
                                            }
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">IMC: <strong>{{ number_format($imc, 1) }}</strong></span>
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $categorie }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">Non spécifié</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($dossier->allergies)
                                <div class="info-item mb-3">
                                    <h6 class="d-flex align-items-center">
                                        <i class="fas fa-allergies text-warning me-2"></i>Allergies connues
                                    </h6>
                                    <div class="ps-4">
                                        <div class="alert alert-warning alert-sm mb-0">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $dossier->allergies }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($dossier->traitements_en_cours)
                                <div class="info-item">
                                    <h6 class="d-flex align-items-center">
                                        <i class="fas fa-pills text-primary me-2"></i>Traitements en cours
                                    </h6>
                                    <div class="ps-4">
                                        <div class="alert alert-info alert-sm mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            {{ $dossier->traitements_en_cours }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-notes-medical me-2"></i>Antécédents médicaux
                            </h6>
                            @if($dossier->antecedents_medicaux)
                                <span class="badge bg-primary">
                                    <i class="fas fa-info-circle me-1"></i>Complété
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($dossier->antecedents_medicaux)
                                <div class="timeline">
                                    @foreach(explode("\n", $dossier->antecedents_medicaux) as $ligne)
                                        @if(trim($ligne) !== '')
                                            <div class="timeline-item">
                                                <div class="timeline-point">
                                                    <i class="fas fa-circle"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <p class="mb-1">{{ $ligne }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-inbox fa-3x text-muted"></i>
                                    </div>
                                    <p class="text-muted mb-0">Aucun antécédent médical enregistré.</p>
                                    <small class="text-muted">Cliquez sur Modifier pour ajouter des antécédents</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-clipboard-list me-2"></i>Détails complémentaires
                            </h6>
                            @if($dossier->motif_consultation || $dossier->observations)
                                <span class="badge bg-primary">
                                    <i class="far fa-edit me-1"></i>Rempli
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($dossier->motif_consultation)
                                <div class="detail-section mb-4">
                                    <h6 class="d-flex align-items-center text-primary">
                                        <i class="fas fa-comment-medical me-2"></i>Motif de la consultation
                                    </h6>
                                    <div class="ps-4 mt-2">
                                        <div class="p-3 bg-light rounded">
                                            <p class="mb-0">{{ $dossier->motif_consultation }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($dossier->observations)
                                <div class="detail-section">
                                    <h6 class="d-flex align-items-center text-primary">
                                        <i class="fas fa-notes-medical me-2"></i>Observations générales
                                    </h6>
                                    <div class="ps-4 mt-2">
                                        <div class="p-3 bg-light rounded">
                                            <p class="mb-0">{{ $dossier->observations }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if(!$dossier->motif_consultation && !$dossier->observations)
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-clipboard-question fa-3x text-muted"></i>
                                    </div>
                                    <p class="text-muted">Aucun détail complémentaire n'a été enregistré pour ce dossier.</p>
                                    <a href="{{ route('secretaire.dossiers-medicaux.edit', $dossier->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i> Ajouter des détails
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-history me-2"></i>Historique des consultations
                                @if($dossier->consultations && $dossier->consultations->count() > 0)
                                    <span class="badge bg-primary ms-2">
                                        {{ $dossier->consultations->count() }} consultation(s)
                                    </span>
                                @endif
                            </h6>
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Nouvelle consultation
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if($dossier->consultations && $dossier->consultations->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($dossier->consultations as $consultation)
                                        <div class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3 text-center">
                                                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded">
                                                            <i class="fas fa-calendar-check fa-2x"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">
                                                            Consultation du {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y à H:i') }}
                                                        </h6>
                                                        <p class="mb-1">
                                                            <i class="fas fa-user-md text-muted me-1"></i>
                                                            @if($consultation->medecin && $consultation->medecin->utilisateur)
                                                                Dr. {{ $consultation->medecin->utilisateur->prenom }} {{ $consultation->medecin->utilisateur->nom }}
                                                            @else
                                                                Médecin non spécifié
                                                            @endif
                                                        </p>
                                                        @if($consultation->motif)
                                                            <p class="mb-1">
                                                                <i class="fas fa-comment-medical text-muted me-1"></i>
                                                                <strong>Motif :</strong> {{ $consultation->motif }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fas fa-eye me-2"></i>Voir les détails
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fas fa-file-pdf me-2"></i>Exporter en PDF
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#">
                                                                <i class="fas fa-trash-alt me-2"></i>Supprimer
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            @if($consultation->diagnostic)
                                                <div class="mt-2 p-2 bg-light rounded">
                                                    <p class="mb-0 small">
                                                        <strong>Diagnostic :</strong> {{ $consultation->diagnostic }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer bg-white text-center">
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-list me-1"></i> Voir tout l'historique
                                    </a>
                                </div>
                            @else
                                <div class="text-center p-5">
                                    <div class="mb-3">
                                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">Aucune consultation enregistrée</h5>
                                    <p class="text-muted mb-4">Aucune consultation n'a encore été enregistrée pour ce dossier médical.</p>
                                    <a href="#" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i> Planifier une consultation
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styles de base */
    body {
        background-color: #f8f9fc;
    }
    
    .card {
        margin-bottom: 1.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: none;
        border-radius: 0.5rem;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
        padding: 1rem 1.5rem;
    }
    
    .card-header h6 {
        font-weight: 600;
        color: #4e73df;
        margin: 0;
        font-size: 1rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
        font-size: 0.8em;
        letter-spacing: 0.5px;
    }
    
    /* Alertes */
    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
    }
    
    .alert-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    /* Timeline pour les antécédents */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-left: 1.5rem;
        border-left: 2px solid #e3e6f0;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 0;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: #4e73df;
    }
    
    .timeline-point {
        position: absolute;
        left: -1.5rem;
        top: 0;
        color: #4e73df;
        font-size: 0.8rem;
    }
    
    .timeline-content {
        padding-bottom: 1rem;
    }
    
    /* Listes groupées */
    .list-group-item {
        padding: 1.25rem 1.5rem;
        border-left: none;
        border-right: none;
        border-radius: 0 !important;
        transition: background-color 0.2s ease-in-out;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fc;
    }
    
    /* Boutons */
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
    }
    
    /* Sections de détail */
    .detail-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eaecf4;
    }
    
    .detail-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .list-group-item {
            padding: 1rem;
        }
    }
    
    /* Couleurs et états */
    .bg-light {
        background-color: #f8f9fc !important;
    }
    
    .text-primary {
        color: #4e73df !important;
    }
    
    .bg-primary {
        background-color: #4e73df !important;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    /* Typographie */
    h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
    }
    
    /* Icônes */
    .fa-circle {
        font-size: 0.5rem;
        vertical-align: middle;
    }
    
    /* Tableau */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-top: none;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Menu déroulant */
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
        padding: 0.5rem 0;
    }
    
    .dropdown-item {
        padding: 0.5rem 1.25rem;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fc;
        color: #4e73df;
    }
    
    .dropdown-divider {
        margin: 0.5rem 0;
        border-top: 1px solid #eaecf4;
    }
</style>
@endpush
