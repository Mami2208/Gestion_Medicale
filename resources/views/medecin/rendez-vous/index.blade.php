@extends('layouts.medecin')

@section('title', 'Gestion des Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class='bx bx-calendar-check'></i> Gestion des Rendez-vous
                    </h3>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class='bx bx-filter'></i> Filtrer
                        </button>
                        <a href="{{ route('medecin.rendez-vous.create') }}" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Nouveau Rendez-vous
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class='bx bx-check-circle'></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Vue Calendrier -->
                    <div class="calendar-view mb-4">
                        <div id="calendar"></div>
                    </div>

                    <!-- Vue Liste -->
                    <div class="list-view">
                        <div class="table-responsive">
                            <table class="table table-hover" id="rendezVousTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Motif</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rendezVous as $rdv)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}</td>
                                            <td>{{ $rdv->heure_rendez_vous }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <img src="{{ $rdv->patient->utilisateur->photo ?? asset('images/default-avatar.png') }}" 
                                                             alt="Avatar" class="rounded-circle">
                                                    </div>
                                                    <div>
                                                        {{ $rdv->patient->utilisateur->nom ?? 'N/A' }} {{ $rdv->patient->utilisateur->prenom ?? '' }}
                                                        <small class="d-block text-muted">{{ $rdv->patient->utilisateur->telephone ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($rdv->motif, 50) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $rdv->statut === 'CONFIRME' ? 'success' : ($rdv->statut === 'EN_ATTENTE' ? 'warning' : 'danger') }}">
                                                    {{ $rdv->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('medecin.rendez-vous.show', $rdv) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Voir les détails">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="{{ route('medecin.rendez-vous.edit', $rdv) }}" 
                                                       class="btn btn-warning btn-sm"
                                                       data-bs-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <form action="{{ route('medecin.rendez-vous.destroy', $rdv) }}" 
                                                          method="POST" 
                                                          class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm"
                                                                data-bs-toggle="tooltip" 
                                                                title="Supprimer">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class='bx bx-calendar-x'></i>
                                                    <p>Aucun rendez-vous trouvé</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $rendezVous->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtrage -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrer les rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('medecin.rendez-vous.index') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="statut">
                            <option value="">Tous</option>
                            <option value="CONFIRME">Confirmé</option>
                            <option value="EN_ATTENTE">En attente</option>
                            <option value="ANNULE">Annulé</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    .calendar-view {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 0;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 10px;
    }
    
    .avatar {
        width: 32px;
        height: 32px;
        overflow: hidden;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .btn-group {
        gap: 5px;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du calendrier
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            @foreach($rendezVous as $rdv)
            {
                title: '{{ $rdv->patient->utilisateur->nom ?? 'Patient' }} {{ $rdv->patient->utilisateur->prenom ?? '' }}',
                start: '{{ $rdv->date_rendez_vous }}T{{ $rdv->heure_rendez_vous }}',
                url: '{{ route('medecin.rendez-vous.show', $rdv) }}',
                backgroundColor: '{{ $rdv->statut === 'CONFIRME' ? '#28a745' : ($rdv->statut === 'EN_ATTENTE' ? '#ffc107' : '#dc3545') }}'
            },
            @endforeach
        ]
    });
    calendar.render();

    // Initialisation des tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirmation de suppression
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush 