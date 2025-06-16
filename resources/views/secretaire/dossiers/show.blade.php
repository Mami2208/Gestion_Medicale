@extends('secretaire.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Détails du dossier médical</h2>
        <div>
            <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
            <a href="{{ route('secretaire.dossiers-medicaux.edit', $dossier) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
        </div>
    </div>

    <!-- Informations du patient -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations du patient</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Photo du patient" class="rounded-circle img-fluid" style="max-width: 120px;">
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5>Identité</h5>
                            <p class="mb-1"><strong>Nom complet:</strong> 
                                @if($dossier->patient && $dossier->patient->utilisateur)
                                    {{ $dossier->patient->utilisateur->nom }} {{ $dossier->patient->utilisateur->prenom }}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="mb-1"><strong>Numéro de dossier:</strong> {{ $dossier->numero_dossier ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Date de naissance:</strong> 
                                @if($dossier->patient && $dossier->patient->utilisateur && $dossier->patient->utilisateur->date_naissance)
                                    {{ \Carbon\Carbon::parse($dossier->patient->utilisateur->date_naissance)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="mb-1"><strong>Sexe:</strong> 
                                @if($dossier->patient && $dossier->patient->utilisateur)
                                    {{ $dossier->patient->utilisateur->sexe == 'H' ? 'Homme' : 'Femme' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5>Contact</h5>
                            <p class="mb-1"><strong>Téléphone:</strong> 
                                @if($dossier->patient && $dossier->patient->utilisateur)
                                    {{ $dossier->patient->utilisateur->telephone ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="mb-1"><strong>Email:</strong> 
                                @if($dossier->patient && $dossier->patient->utilisateur)
                                    {{ $dossier->patient->utilisateur->email ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="mb-1"><strong>Adresse:</strong> 
                                @if($dossier->patient)
                                    {{ $dossier->patient->adresse ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations du dossier médical -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Informations du dossier médical</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5>Détails</h5>
                    <p class="mb-1"><strong>Médecin référent:</strong> 
                        @if($dossier->medecin && $dossier->medecin->utilisateur)
                            {{ $dossier->medecin->utilisateur->nom }} {{ $dossier->medecin->utilisateur->prenom }}
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="mb-1"><strong>Date de création:</strong> {{ $dossier->date_creation ? \Carbon\Carbon::parse($dossier->date_creation)->format('d/m/Y') : ($dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A') }}</p>
                    <p class="mb-1"><strong>Statut:</strong> <span class="badge {{ $dossier->statut == 'ACTIF' ? 'bg-success' : 'bg-danger' }}">{{ $dossier->statut ?? 'N/A' }}</span></p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5>Motif de consultation</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $dossier->motif_consultation ?? 'Aucun motif spécifié' }}
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 mb-3">
                    <h5>Antécédents médicaux</h5>
                    <div class="p-3 bg-light rounded">
                        @php
                            // Vérifier si antecedents_medicaux est déjà un tableau ou une chaîne JSON
                            $antecedents = is_array($dossier->antecedents_medicaux) 
                                ? $dossier->antecedents_medicaux 
                                : (is_string($dossier->antecedents_medicaux) ? json_decode($dossier->antecedents_medicaux, true) : []);
                        @endphp
                        @if(!empty($antecedents))
                            @if(is_array($antecedents))
                                <ul class="list-unstyled">
                                @foreach($antecedents as $antecedent)
                                    <li>{{ is_string($antecedent) ? $antecedent : (is_array($antecedent) && isset($antecedent['description']) ? $antecedent['description'] : '') }}</li>
                                @endforeach
                                </ul>
                            @else
                                {!! nl2br(e(is_string($antecedents) ? $antecedents : 'Aucun antécédent médical enregistré')) !!}
                            @endif
                        @elseif($dossier->antecedents)
                            {!! nl2br(e($dossier->antecedents)) !!}
                        @else
                            Aucun antécédent médical enregistré
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <h5>Allergies</h5>
                    <div class="p-3 bg-light rounded">
                        @php
                            // Vérifier si allergies est déjà un tableau ou une chaîne JSON
                            $allergies = is_array($dossier->allergies) 
                                ? $dossier->allergies 
                                : (is_string($dossier->allergies) ? json_decode($dossier->allergies, true) : []);
                        @endphp
                        @if(!empty($allergies))
                            @if(isset($allergies['description']))
                                {!! nl2br(e($allergies['description'])) !!}
                            @elseif(is_array($allergies))
                                <ul class="list-unstyled">
                                @foreach($allergies as $allergie)
                                    <li>{{ is_string($allergie) ? $allergie : (is_array($allergie) && isset($allergie['nom']) ? $allergie['nom'] : '') }}</li>
                                @endforeach
                                </ul>
                            @else
                                {!! nl2br(e(is_string($allergies) ? $allergies : 'Aucune allergie connue')) !!}
                            @endif
                        @else
                            Aucune allergie connue
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12">
                    <h5>Traitements en cours</h5>
                    <div class="p-3 bg-light rounded">
                        @php
                            // Vérifier si traitements_en_cours est déjà un tableau ou une chaîne JSON
                            $traitements = is_array($dossier->traitements_en_cours) 
                                ? $dossier->traitements_en_cours 
                                : (is_string($dossier->traitements_en_cours) ? json_decode($dossier->traitements_en_cours, true) : []);
                        @endphp
                        @if(!empty($traitements))
                            @if(is_array($traitements))
                                <ul class="list-unstyled">
                                @foreach($traitements as $traitement)
                                    <li>{{ is_string($traitement) ? $traitement : (is_array($traitement) && isset($traitement['nom']) ? $traitement['nom'] : '') }}</li>
                                @endforeach
                                </ul>
                            @else
                                {!! nl2br(e(is_string($traitements) ? $traitements : 'Aucun traitement en cours')) !!}
                            @endif
                        @else
                            Aucun traitement en cours
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rendez-vous -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Rendez-vous</h5>
        </div>
        <div class="card-body">
            @if($dossier->patient && $dossier->patient->rendezVous && $dossier->patient->rendezVous->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Médecin</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dossier->patient->rendezVous as $rdv)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}</td>
                                    <td>{{ $rdv->heure_debut }} - {{ $rdv->heure_fin }}</td>
                                    <td>
                                        @if($rdv->medecin && $rdv->medecin->utilisateur)
                                            {{ $rdv->medecin->utilisateur->nom }} {{ $rdv->medecin->utilisateur->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $rdv->motif }}</td>
                                    <td>
                                        <span class="badge {{ $rdv->statut == 'CONFIRMÉ' ? 'bg-success' : ($rdv->statut == 'ANNULÉ' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ $rdv->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaire.rendez-vous.show', $rdv->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Aucun rendez-vous enregistré pour ce patient.</p>
                <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-outline-success">
                    <i class="fas fa-plus me-1"></i>Planifier un rendez-vous
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
