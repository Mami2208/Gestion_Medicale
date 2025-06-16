@extends('patient.layouts.app')

@section('title', 'Mon dossier médical')

@section('page_title', 'Mon dossier médical')

@section('content')
<div class="row">
    <!-- Informations personnelles -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user me-2 text-success"></i>Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/avatars/default.jpg') }}" alt="Photo de profil" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h5 class="mb-0">{{ $patient->utilisateur->prenom ?? 'Prénom' }} {{ $patient->utilisateur->nom ?? 'Nom' }}</h5>
                    <p class="text-muted">ID Patient: {{ $patient->numeroPatient ?? $patient->id }}</p>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Date de naissance:</div>
                    <div class="value">{{ $patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($patient->utilisateur->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</div>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Sexe:</div>
                    <div class="value">
                        @if($patient->utilisateur->sexe === 'M')
                            Masculin
                        @elseif($patient->utilisateur->sexe === 'F')
                            Féminin
                        @else
                            Autre
                        @endif
                    </div>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Groupe sanguin:</div>
                    <div class="value">{{ $patient->groupe_sanguin ?? 'Non renseigné' }}</div>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Téléphone:</div>
                    <div class="value">{{ $patient->utilisateur->telephone ?? 'Non renseigné' }}</div>
                </div>
                
                <div class="medical-info-item">
                    <div class="label">Email:</div>
                    <div class="value">{{ $patient->utilisateur->email ?? 'Non renseigné' }}</div>
                </div>
                
                <div class="medical-info-item border-0">
                    <div class="label">Adresse:</div>
                    <div class="value">{{ $patient->adresse ?? 'Non renseignée' }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Diagnostics -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-stethoscope me-2 text-success"></i>Diagnostics</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Diagnostic</th>
                                <th>Médecin</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patient->dossierMedical->historiques ?? [] as $historique)
                            <tr>
                                <td>{{ $historique->date ? \Carbon\Carbon::parse($historique->date)->format('d/m/Y') : 'Date non spécifiée' }}</td>
                                <td>{{ $historique->description ?? 'Aucune description' }}</td>
                                <td>{{ $historique->medecin->utilisateur->prenom ?? 'Dr.' }} {{ $historique->medecin->utilisateur->nom ?? 'Inconnu' }}</td>
                                <td><span class="badge bg-{{ $historique->statut === 'résolu' ? 'secondary' : 'success' }}">
                                    {{ $historique->statut === 'résolu' ? 'Résolu' : 'En traitement' }}
                                </span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucun diagnostic enregistré</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Traitements -->
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-pills me-2 text-success"></i>Traitements en cours</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date de début</th>
                                <th>Type de traitement</th>
                                <th>Instructions</th>
                                <th>Médecin</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patient->traitements as $traitement)
                            <tr>
                                <td>{{ $traitement->date_debut ? \Carbon\Carbon::parse($traitement->date_debut)->format('d/m/Y') : 'Date non spécifiée' }}</td>
                                <td>{{ $traitement->type_traitement ?? 'Traitement non spécifié' }}</td>
                                <td>{{ $traitement->description ?? 'Aucune instruction' }}</td>
                                <td>{{ $traitement->medecin->utilisateur->prenom ?? 'Dr.' }} {{ $traitement->medecin->utilisateur->nom ?? 'Inconnu' }}</td>
                                <td>
                                    @if($traitement->statut === 'terminé' || ($traitement->date_fin && $traitement->date_fin < now()))
                                        <span class="badge bg-secondary">Terminé</span>
                                    @else
                                        <span class="badge bg-success">En cours</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucun traitement en cours</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Antécédents -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history me-2 text-success"></i>Antécédents médicaux</h5>
            </div>
            <div class="card-body">
                @if($patient->dossierMedical && !empty($patient->dossierMedical->antecedents_medicaux))
                    <div class="mb-3">
                        <h6 class="card-subtitle mb-2 text-muted">Antécédents médicaux</h6>
                        <p class="card-text">{{ $patient->dossierMedical->antecedents_medicaux }}</p>
                    </div>
                @endif
                
                @if($patient->dossierMedical && !empty($patient->dossierMedical->antecedents))
                    <div class="mb-3">
                        <h6 class="card-subtitle mb-2 text-muted">Antécédents chirurgicaux</h6>
                        <p class="card-text">{{ $patient->dossierMedical->antecedents }}</p>
                    </div>
                @endif
                
                @if($patient->dossierMedical && !empty($patient->dossierMedical->traitements_en_cours))
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Traitements en cours</h6>
                        <p class="card-text">{{ $patient->dossierMedical->traitements_en_cours }}</p>
                    </div>
                @endif
                
                @if((!$patient->dossierMedical) || (empty($patient->dossierMedical->antecedents_medicaux) && empty($patient->dossierMedical->antecedents) && empty($patient->dossierMedical->traitements_en_cours)))
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun antécédent médical n'a été enregistré pour le moment.
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Allergies -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-exclamation-triangle me-2 text-success"></i>Allergies et contre-indications</h5>
            </div>
            <div class="card-body">
                @if($patient->dossierMedical && !empty($patient->dossierMedical->allergies))
                    <div class="mb-3">
                        <h6 class="card-subtitle mb-2 text-muted">Allergies connues</h6>
                        @if(is_array($patient->dossierMedical->allergies))
                            <ul class="list-group list-group-flush">
                                @foreach($patient->dossierMedical->allergies as $allergie)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">{{ $allergie }}</span>
                                        </div>
                                        <span class="badge bg-danger rounded-pill">Allergie</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="card-text">{{ $patient->dossierMedical->allergies }}</p>
                        @endif
                    </div>
                @endif
                
                @if($patient->dossierMedical && !empty($patient->dossierMedical->contre_indications))
                    <div class="mb-3">
                        <h6 class="card-subtitle mb-2 text-muted">Contre-indications</h6>
                        <p class="card-text">{{ $patient->dossierMedical->contre_indications }}</p>
                    </div>
                @endif
                
                @if((!$patient->dossierMedical) || (empty($patient->dossierMedical->allergies) && empty($patient->dossierMedical->contre_indications)))
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Aucune allergie ou contre-indication n'a été enregistrée.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
