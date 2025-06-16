@extends('secretaire.layouts.app')

@section('title', 'Tableau de bord - Secrétaire')

@push('styles')
    <style>
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .quick-action-btn {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 0.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .quick-action-btn i {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
        }
        .appointment-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.75rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <style>
        .fc .fc-toolbar-title {
            font-size: 1.2rem;
            margin: 0;
        }
        .fc .fc-button {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active, 
        .fc .fc-button-primary:not(:disabled):active {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        .fc-event {
            cursor: pointer;
            font-size: 0.85em;
            border-radius: 0.25rem;
        }
        .fc-day-today {
            background-color: #f8f9fc !important;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>
                Tableau de bord
            </h1>
            <p class="text-muted mb-0">Bienvenue, {{ auth()->user()->name }}</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="text-end">
                    <div class="text-muted small">Dernière connexion</div>
                    <div class="fw-bold">{{ auth()->user()->last_login_at ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->diffForHumans() : 'Première connexion' }}</div>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-light rounded-circle p-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fa-lg"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('secretaire.profile') }}"><i class="fas fa-user me-2"></i>Mon profil</a></li>
                    <li><a class="dropdown-item" href="{{ route('secretaire.settings') }}"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row g-4 mb-4">
        <!-- Patients -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Patients</h6>
                            <h2 class="mb-0">{{ $stats['patients_actifs'] }}</h2>
                            <div class="small mt-2">
                                <span class="text-success">
                                    <i class="fas fa-male"></i> {{ $stats['patients_hommes'] }} H
                                </span>
                                <span class="ms-2 text-info">
                                    <i class="fas fa-female"></i> {{ $stats['patients_femmes'] }} F
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.patients.index') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-list me-1"></i> Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Rendez-vous Aujourd'hui</h6>
                            <h2 class="mb-0">{{ $stats['rendezvous_aujourdhui'] }}</h2>
                            <div class="small mt-2">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-check"></i> {{ $stats['rendezvous_total'] }} au total
                                </span>
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-alt fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-sm btn-outline-success w-100">
                            <i class="fas fa-calendar-day me-1"></i> Voir le planning
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dossiers médicaux -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Dossiers médicaux</h6>
                            <h2 class="mb-0">{{ $stats['dossiers_encours'] }}</h2>
                            <div class="small mt-2">
                                <span class="text-muted">
                                    <i class="fas fa-file-medical"></i> Dernière mise à jour
                                </span>
                            </div>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-file-medical fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.dossiers.index') }}" class="btn btn-sm btn-outline-info w-100">
                            <i class="fas fa-folder-open me-1"></i> Voir les dossiers
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Notifications</h6>
                            <h2 class="mb-0">{{ $stats['notifications_non_lues'] }}</h2>
                            <div class="small mt-2">
                                <span class="text-muted">
                                    <i class="fas fa-bell"></i> Non lues
                                </span>
                            </div>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded position-relative">
                            <i class="fas fa-bell fa-2x text-warning"></i>
                            @if($stats['notifications_non_lues'] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $stats['notifications_non_lues'] }}
                                    <span class="visually-hidden">nouvelles notifications</span>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-sm btn-outline-warning w-100">
                            <i class="fas fa-inbox me-1"></i> Voir les notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Patients -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Patients</h6>
                            <h2 class="mb-0">{{ $stats['patients_actifs'] }}</h2>
                            <div class="small mt-2">
                                <span class="text-success">
                                    <i class="fas fa-male"></i> {{ $stats['patients_hommes'] }} H
                                </span>
                                <span class="ms-2 text-info">
                                    <i class="fas fa-female"></i> {{ $stats['patients_femmes'] }} F
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.patients.index') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-list me-1"></i> Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous aujourd'hui -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Rendez-vous Aujourd'hui</h6>
                            <h2 class="mb-0">{{ $stats['rendezvous_aujourdhui'] }}</h2>
                            <div class="small mt-2">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-check"></i> {{ $stats['rendezvous_total'] }} au total
                                </span>
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-alt fa-2x text-success"></i>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="small-box-footer">
                        Voir le calendrier <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dossiers médicaux -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dossiers médicaux</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['dossiers_encours'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('secretaire.dossiers.index') }}" class="small-box-footer">
                        Voir les dossiers <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Notifications non lues</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['notifications_non_lues'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('secretaire.notifications.index') }}" class="small-box-footer">
                        Voir les notifications <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Prochains rendez-vous
                    </h6>
                    <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nouveau
                    </a>
                </div>
                <div class="card-body">
                    @if($rendezVousAujourdhui->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Médecin</th>
                                        <th>Heure</th>
                                        <th>Motif</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rendezVousAujourdhui as $rdv)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($rdv->patient->photo)
                                                        <img src="{{ asset('storage/' . $rdv->patient->photo) }}" class="rounded-circle me-2" width="30" height="30" alt="Photo patient">
                                                    @else
                                                        <div class="rounded-circle bg-light text-center me-2" style="width: 30px; height: 30px; line-height: 30px;">
                                                            <i class="fas fa-user text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold">{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</div>
                                                        <small class="text-muted">{{ $rdv->patient->telephone }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Dr. {{ $rdv->medecin->prenom }} {{ $rdv->medecin->nom }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ \Carbon\Carbon::parse($rdv->heure_debut)->format('H:i') }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($rdv->motif, 20) }}</td>
                                            <td>
                                                @if($rdv->statut === 'confirmé')
                                                    <span class="badge bg-success">Confirmé</span>
                                                @elseif($rdv->statut === 'en_attente')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($rdv->statut === 'annulé')
                                                    <span class="badge bg-danger">Annulé</span>
                                                @elseif($rdv->statut === 'terminé')
                                                    <span class="badge bg-info">Terminé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('secretaire.rendez-vous.show', $rdv->id) }}" class="btn btn-sm btn-info" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('secretaire.rendez-vous.edit', $rdv->id) }}" class="btn btn-sm btn-primary" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#confirmRdvModal{{ $rdv->id }}" title="Confirmer">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-outline-primary">
                                Voir tous les rendez-vous <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="far fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun rendez-vous prévu pour aujourd'hui</p>
                            <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Planifier un rendez-vous
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-user-plus"></i>
                            </span>
                            <span class="text">Nouveau patient</span>
                        </a>
                        
                        <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-success btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-calendar-plus"></i>
                            </span>
                            <span class="text">Nouveau rendez-vous</span>
                        </a>
                        
                        <a href="{{ route('secretaire.dossiers.create') }}" class="btn btn-info btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-medical"></i>
                            </span>
                            <span class="text">Nouveau dossier médical</span>
                        </a>
                        
                        <a href="{{ route('secretaire.factures.create') }}" class="btn btn-warning btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            <span class="text">Nouvelle facture</span>
                        </a>
                        
                        <a href="{{ route('secretaire.rapports.index') }}" class="btn btn-secondary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-chart-bar"></i>
                            </span>
                            <span class="text">Générer un rapport</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Derniers dossiers modifiés -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-folder-open me-2"></i>
                        Derniers dossiers modifiés
                    </h6>
                </div>
                <div class="card-body">
                    @if($dossiers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($dossiers->take(5) as $dossier)
                                <a href="{{ route('secretaire.dossiers.show', $dossier->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $dossier->patient->prenom }} {{ $dossier->patient->nom }}</h6>
                                        <small>{{ $dossier->updated_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-stethoscope me-1"></i> {{ $dossier->diagnostic }}
                                    </p>
                                    <small>
                                        <i class="fas fa-user-md me-1"></i> Dr. {{ $dossier->medecin->prenom }} {{ $dossier->medecin->nom }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('secretaire.dossiers.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir tous les dossiers <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-folder-open fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Aucun dossier médical trouvé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section des statistiques -->
    <div class="row">
        <!-- Graphique des rendez-vous -->
        <div class="col-md-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Activité des rendez-vous (30 derniers jours)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="rdvChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques des patients -->
        <div class="col-md-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>
                        Répartition par genre
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-3">
                            <i class="fas fa-circle text-primary"></i> Hommes ({{ $stats['patients_hommes'] ?? 0 }})
                        </span>
                        <span>
                            <i class="fas fa-circle text-success"></i> Femmes ({{ $stats['patients_femmes'] ?? 0 }})
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour les graphiques -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique des rendez-vous
        var ctx = document.getElementById('rdvChart').getContext('2d');
        var rdvChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($rdvStats['labels'] ?? []) !!},
                datasets: [{
                    label: 'Rendez-vous',
                    data: {!! json_encode($rdvStats['data'] ?? []) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            beginAtZero: true
                        },
                        grid: {
                            color: 'rgb(234, 236, 244)',
                            zeroLineColor: 'rgb(234, 236, 244)',
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgb(255,255,255)',
                        bodyColor: '#858796',
                        titleMarginBottom: 10,
                        titleColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10
                    }
                }
            }
        });

        // Graphique des genres
        var ctx2 = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Hommes', 'Femmes'],
                datasets: [{
                    data: [{{ $stats['patients_hommes'] ?? 0 }}, {{ $stats['patients_femmes'] ?? 0 }}],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: 'rgba(234, 236, 244, 1)',
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgb(255,255,255)',
                        bodyColor: '#858796',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                },
                cutout: '70%',
            },
        });
    </script>
    @endpush
                    <h2 class="mb-0">{{ $rendezVousAujourdhui->count() }}</h2>
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir tous
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder-medical me-2"></i>
                        Dossiers médicaux
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $patients->sum('dossiers_medicaux_count') }}</h2>
                    <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir tous
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $notifications->count() }}</h2>
                    <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir toutes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des patients -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des patients</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                <tr>
                                    <td>
                                        @if($patient->utilisateur)
                                            {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaire.patients.show', $patient) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('secretaire.patients.edit', $patient) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.patients.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-list"></i> Voir tous les patients
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des dossiers médicaux -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Derniers dossiers médicaux</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date création</th>
                                    <th>Motif</th>
                                    <th>Médecin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dossiers->take(5) as $dossier)
                                <tr>
                                    <td>
                                        @if($dossier->patient && $dossier->patient->utilisateur)
                                            {{ $dossier->patient->utilisateur->nom }} {{ $dossier->patient->utilisateur->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $dossier->motif_consultation ?? 'N/A' }}</td>
                                    <td>
                                        @if($dossier->medecin)
                                            {{ $dossier->medecin->nom }} {{ $dossier->medecin->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.dossiers-medicaux.index') }}" class="btn btn-info w-100">
                            <i class="fas fa-folder-medical me-2"></i>
                            Voir tous les dossiers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rendez-vous prochains -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Prochains rendez-vous</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Motif</th>
                                    <th>Médecin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prochainsRendezVous as $rendezVous)
                                <tr>
                                    <td>
                                        @if($rendezVous->patient && $rendezVous->patient->utilisateur)
                                            {{ $rendezVous->patient->utilisateur->nom }} {{ $rendezVous->patient->utilisateur->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $rendezVous->date_rendez_vous instanceof \DateTime ? $rendezVous->date_rendez_vous->format('d/m/Y') : $rendezVous->date_rendez_vous }}</td>
                                    <td>{{ $rendezVous->heure_debut }}</td>
                                    <td>{{ $rendezVous->motif ?? 'N/A' }}</td>
                                    <td>
                                        @if($rendezVous->medecin)
                                            {{ $rendezVous->medecin->nom }} {{ $rendezVous->medecin->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Voir tous les rendez-vous
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notifications récentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $notification->type === 'info' ? 'info' : ($notification->type === 'success' ? 'success' : 'warning') }}">
                                            {{ $notification->type }}
                                        </span>
                                    </td>
                                    <td>{{ $notification->data['message'] }}</td>
                                    <td>{{ $notification->created_at ? $notification->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('secretaire.notifications.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-bell me-2"></i>
                            Voir toutes les notifications
                        </a>
                    </div>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3">Actions rapides</h3>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="{{ route('secretaire.dossiers-medicaux.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-folder-plus me-2"></i>
                        Nouveau dossier
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-info w-100">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Nouveau rendez-vous
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('secretaire.patients.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-users me-2"></i>
                        Liste des patients
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-warning w-100">
                        <i class="fas fa-calendar-check me-2"></i>
                        Liste des rendez-vous
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochains rendez-vous -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Prochains rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Motif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prochainsRendezVous as $rendezVous)
                                <tr>
                                    <td>
                                        @if($rendezVous->patient && $rendezVous->patient->utilisateur)
                                            {{ $rendezVous->patient->utilisateur->nom }} {{ $rendezVous->patient->utilisateur->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($rendezVous->medecin)
                                            {{ $rendezVous->medecin->nom }} {{ $rendezVous->medecin->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $rendezVous->date_rendez_vous instanceof \DateTime ? $rendezVous->date_rendez_vous->format('d/m/Y') : $rendezVous->date_rendez_vous }}</td>
                                    <td>{{ $rendezVous->heure_debut }}</td>
                                    <td>{{ $rendezVous->motif ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
