@extends('patient.layouts.app')

@section('title', 'Mes rendez-vous')

@section('page_title', 'Mes rendez-vous')

@section('content')
<div class="container-fluid">
    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-success"></i>Gérer mes rendez-vous</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                        <i class="fas fa-plus me-2"></i>Demander un rendez-vous
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglets pour les rendez-vous -->
    <ul class="nav nav-tabs mb-4" id="appointmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                <i class="fas fa-calendar-day me-2"></i>Rendez-vous à venir
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                <i class="fas fa-history me-2"></i>Rendez-vous passés
            </button>
        </li>
    </ul>
    
    <!-- Contenu des onglets -->
    <div class="tab-content" id="appointmentTabContent">
        <!-- Rendez-vous à venir -->
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
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
                                        @if(isset($rendezVous) && count($rendezVous) > 0)
                                            @foreach($rendezVous as $rdv)
                                                <tr>
                                                    <td>{{ $rdv->date_rendez_vous instanceof \DateTime ? $rdv->date_rendez_vous->format('d/m/Y') : date('d/m/Y', strtotime($rdv->date_rendez_vous)) }}</td>
                                                    <td>{{ $rdv->heure_debut }}</td>
                                                    <td>
                                                        @if($rdv->medecin)
                                                            Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}
                                                        @else
                                                            Non assigné
                                                        @endif
                                                    </td>
                                                    <td>{{ $rdv->motif ?? 'Non spécifié' }}</td>
                                                    <td><span class="badge bg-success">Confirmé</span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal{{ $rdv->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelAppointmentModal{{ $rdv->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 2.5rem;"></i>
                                                        <p class="mb-0">Vous n'avez pas de rendez-vous à venir</p>
                                                        <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                                                            <i class="fas fa-plus-circle me-2"></i>Prendre rendez-vous
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rendez-vous passés -->
        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
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
                                        @if(isset($rendezVousPasses) && count($rendezVousPasses) > 0)
                                            @foreach($rendezVousPasses as $rdv)
                                                <tr>
                                                    <td>{{ $rdv->date_rendez_vous instanceof \DateTime ? $rdv->date_rendez_vous->format('d/m/Y') : date('d/m/Y', strtotime($rdv->date_rendez_vous)) }}</td>
                                                    <td>{{ $rdv->heure_debut }}</td>
                                                    <td>
                                                        @if($rdv->medecin)
                                                            Dr. {{ $rdv->medecin->nom }} {{ $rdv->medecin->prenom }}
                                                        @else
                                                            Non assigné
                                                        @endif
                                                    </td>
                                                    <td>{{ $rdv->motif ?? 'Non spécifié' }}</td>
                                                    <td><span class="badge bg-secondary">Terminé</span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-history text-muted mb-3" style="font-size: 2.5rem;"></i>
                                                        <p class="mb-0">Aucun historique de rendez-vous</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour nouveau rendez-vous -->
    <div class="modal fade" id="newAppointmentModal" tabindex="-1" aria-labelledby="newAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newAppointmentModalLabel"><i class="fas fa-calendar-plus me-2 text-success"></i>Demander un rendez-vous</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm" action="{{ route('patient.appointments.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Sélection du médecin</h6>
                            <div class="mb-3">
                                <label for="medecin" class="form-label">Médecin <span class="text-danger">*</span></label>
                                <select class="form-select" id="medecin" name="medecin_id" required>
                                    <option value="">Sélectionnez un médecin</option>
                                    @forelse($medecins as $medecin)
                                        @if($medecin->utilisateur)
                                            <option value="{{ $medecin->id }}" data-specialite="{{ $medecin->specialite }}">
                                                Dr. {{ $medecin->utilisateur->prenom }} {{ $medecin->utilisateur->nom }}
                                                @if($medecin->specialite)
                                                    - {{ $medecin->specialite }}
                                                @endif
                                            </option>
                                        @endif
                                    @empty
                                        <option value="" disabled>Aucun médecin disponible</option>
                                    @endforelse
                                </select>
                                @if($medecins->isEmpty())
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Aucun médecin n'est actuellement disponible.
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date souhaitée</label>
                            <input type="date" class="form-control" id="date" name="date_rendez_vous" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="time" class="form-label">Créneau horaire</label>
                                <select class="form-select" id="time" name="creneau" required>
                                    <option value="">Choisir un créneau</option>
                                    <option value="matin">Matin (8h-12h)</option>
                                    <option value="apres-midi">Après-midi (14h-18h)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif du rendez-vous</label>
                            <textarea class="form-control" id="motif" name="motif" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="appointmentForm" class="btn btn-success">Demander le rendez-vous</button>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>

@endsection

@if(isset($rendezVous) && count($rendezVous) > 0)
    @foreach($rendezVous as $rdv)
    <!-- Modal pour visualiser un rendez-vous -->
    <div class="modal fade" id="viewAppointmentModal{{ $rdv->id }}" tabindex="-1" aria-labelledby="viewAppointmentModalLabel{{ $rdv->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="viewAppointmentModalLabel{{ $rdv->id }}">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>Détails du rendez-vous
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-3">Informations du rendez-vous</h6>
                        <div class="d-flex mb-2">
                            <div class="me-3 text-muted">
                                <i class="far fa-calendar-alt me-2"></i>Date :
                            </div>
                            <div>{{ $rdv->date_rendez_vous instanceof \DateTime ? $rdv->date_rendez_vous->format('d/m/Y') : date('d/m/Y', strtotime($rdv->date_rendez_vous)) }}</div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-3 text-muted">
                                <i class="far fa-clock me-2"></i>Heure :
                            </div>
                            <div>{{ $rdv->heure_debut }} - {{ $rdv->heure_fin }}</div>
                        </div>
                        @if($rdv->medecin && $rdv->medecin->utilisateur)
                        <div class="d-flex mb-2">
                            <div class="me-3 text-muted">
                                <i class="fas fa-user-md me-2"></i>Médecin :
                            </div>
                            <div>Dr. {{ $rdv->medecin->utilisateur->prenom }} {{ $rdv->medecin->utilisateur->nom }}</div>
                        </div>
                        @endif
                        @if($rdv->motif)
                        <div class="d-flex mb-2">
                            <div class="me-3 text-muted">
                                <i class="fas fa-comment me-2"></i>Motif :
                            </div>
                            <div>{{ $rdv->motif }}</div>
                        </div>
                        @endif
                        <div class="d-flex">
                            <div class="me-3 text-muted">
                                <i class="fas fa-info-circle me-2"></i>Statut :
                            </div>
                            <div>
                                @if($rdv->statut === 'CONFIRME')
                                    <span class="badge bg-success">Confirmé</span>
                                @elseif($rdv->statut === 'ANNULE')
                                    <span class="badge bg-danger">Annulé</span>
                                @elseif($rdv->statut === 'TERMINE')
                                    <span class="badge bg-secondary">Terminé</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour annuler un rendez-vous -->
    <div class="modal fade" id="cancelAppointmentModal{{ $rdv->id }}" tabindex="-1" aria-labelledby="cancelAppointmentModalLabel{{ $rdv->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('patient.appointments.cancel', $rdv->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="cancelAppointmentModalLabel{{ $rdv->id }}">
                            <i class="fas fa-exclamation-triangle me-2"></i>Annuler le rendez-vous
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir annuler ce rendez-vous ?</p>
                        <p class="mb-0"><strong>Date :</strong> {{ $rdv->date_rendez_vous instanceof \DateTime ? $rdv->date_rendez_vous->format('d/m/Y') : date('d/m/Y', strtotime($rdv->date_rendez_vous)) }}</p>
                        <p class="mb-0"><strong>Heure :</strong> {{ $rdv->heure_debut }} - {{ $rdv->heure_fin }}</p>
                        @if($rdv->medecin && $rdv->medecin->utilisateur)
                            <p class="mb-3"><strong>Avec :</strong> Dr. {{ $rdv->medecin->utilisateur->prenom }} {{ $rdv->medecin->utilisateur->nom }}</p>
                        @endif
                        <div class="mb-3">
                            <label for="raison_annulation" class="form-label">Raison de l'annulation (optionnel) :</label>
                            <textarea class="form-control" id="raison_annulation" name="raison_annulation" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder le rendez-vous</button>
                        <button type="submit" class="btn btn-danger">Oui, annuler le rendez-vous</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script pour gérer la sélection de la date et des créneaux horaires
        // Aucune donnée fictive n'est plus ajoutée ici
    });
</script>
@endpush
