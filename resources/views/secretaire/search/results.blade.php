@extends('secretaire.layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête des résultats -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-3">
            <h1 class="text-success fs-3 mb-0">
                <i class="fas fa-search me-2"></i>Résultats pour "{{ $query }}"
            </h1>
        </div>
        <p class="text-muted">
            {{ count($patients) + count($dossiers) + count($rendezVous) }} résultat(s) trouvé(s)
        </p>
    </div>

    <!-- Aucun résultat -->
    @if(count($patients) == 0 && count($dossiers) == 0 && count($rendezVous) == 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Aucun résultat trouvé pour "{{ $query }}". Veuillez essayer avec d'autres termes.
        </div>
    @endif

    <!-- Résultats des patients -->
    @if(count($patients) > 0)
        <div class="card border-0 shadow-sm rounded-lg mb-4">
            <div class="card-header bg-success text-white py-3 d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-injured me-2"></i>Patients ({{ count($patients) }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3">Nom</th>
                                <th class="py-3">Contact</th>
                                <th class="py-3">Dossiers</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="width: 45px; height: 45px; border-radius: 50%; background-color: #e6f7ef; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user-circle text-success fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-birthday-cake me-1"></i>
                                                    {{ \Carbon\Carbon::parse($patient->utilisateur->date_naissance)->format('d/m/Y') }}
                                                    ({{ \Carbon\Carbon::parse($patient->utilisateur->date_naissance)->age }} ans)
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @if($patient->utilisateur->email)
                                                <div class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $patient->utilisateur->email }}</div>
                                            @endif
                                            @if($patient->utilisateur->telephone)
                                                <div><i class="fas fa-phone me-2 text-muted"></i>{{ $patient->utilisateur->telephone }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success rounded-pill">
                                            {{ $patient->dossiers_medicaux_count ?? count($patient->dossiers_medicaux) }} dossier(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('secretaire.patients.show', $patient->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <a href="{{ route('secretaire.patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Résultats des dossiers médicaux -->
    @if(count($dossiers) > 0)
        <div class="card border-0 shadow-sm rounded-lg mb-4">
            <div class="card-header bg-info text-white py-3 d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-folder-medical me-2"></i>Dossiers médicaux ({{ count($dossiers) }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3">N° Dossier</th>
                                <th class="py-3">Patient</th>
                                <th class="py-3">Médecin</th>
                                <th class="py-3">Date création</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dossiers as $dossier)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $dossier->numero_dossier }}</span>
                                    </td>
                                    <td>
                                        @if($dossier->patient)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background-color: #e6f7ef; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-circle text-success"></i>
                                                </div>
                                                <div>{{ $dossier->patient->nom }} {{ $dossier->patient->prenom }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dossier->medecin && $dossier->medecin->utilisateur)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background-color: #e6f7ef; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-md text-success"></i>
                                                </div>
                                                <div>Dr. {{ $dossier->medecin->utilisateur->nom }} {{ $dossier->medecin->utilisateur->prenom }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($dossier->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('secretaire.dossiers-medicaux.show', $dossier->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <a href="{{ route('secretaire.dossiers-medicaux.edit', $dossier->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Résultats des rendez-vous -->
    @if(count($rendezVous) > 0)
        <div class="card border-0 shadow-sm rounded-lg mb-4">
            <div class="card-header bg-warning text-dark py-3 d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Rendez-vous ({{ count($rendezVous) }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3">Date & Heure</th>
                                <th class="py-3">Patient</th>
                                <th class="py-3">Médecin</th>
                                <th class="py-3">Motif</th>
                                <th class="py-3">Statut</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rendezVous as $rdv)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="date-box text-center me-3 p-2 rounded" style="background-color: #f8f9fa; min-width: 60px;">
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d') }}</div>
                                                <div class="small text-uppercase">{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->locale('fr')->format('M') }}</div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}</div>
                                                <div class="small text-muted">
                                                    {{ \Carbon\Carbon::parse($rdv->heure_debut)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($rdv->heure_fin)->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($rdv->patient)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background-color: #e6f7ef; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-circle text-success"></i>
                                                </div>
                                                <div>{{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rdv->medecin && $rdv->medecin->utilisateur)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 35px; height: 35px; border-radius: 50%; background-color: #e6f7ef; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-md text-success"></i>
                                                </div>
                                                <div>Dr. {{ $rdv->medecin->utilisateur->nom }} {{ $rdv->medecin->utilisateur->prenom }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $rdv->motif }}
                                    </td>
                                    <td>
                                        @php
                                            $aujourdhui = \Carbon\Carbon::now()->startOfDay();
                                            $dateRdv = \Carbon\Carbon::parse($rdv->date_rendez_vous)->startOfDay();
                                            $statut = '';
                                            $badgeClass = '';
                                            
                                            if ($rdv->statut == 'ANNULE') {
                                                $statut = 'Annulé';
                                                $badgeClass = 'bg-danger';
                                            } elseif ($rdv->statut == 'COMPLETE') {
                                                $statut = 'Terminé';
                                                $badgeClass = 'bg-success';
                                            } elseif ($dateRdv->lt($aujourdhui)) {
                                                $statut = 'Passé';
                                                $badgeClass = 'bg-secondary';
                                            } elseif ($dateRdv->eq($aujourdhui)) {
                                                $statut = 'Aujourd\'hui';
                                                $badgeClass = 'bg-primary';
                                            } else {
                                                $statut = 'À venir';
                                                $badgeClass = 'bg-info';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-pill">{{ $statut }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('secretaire.rendez-vous.show', $rdv->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <a href="{{ route('secretaire.rendez-vous.edit', $rdv->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Retour -->
    <div class="mt-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>
</div>
@endsection
