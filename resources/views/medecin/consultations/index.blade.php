@extends('layouts.medecin')

@section('title', 'Gestion des Consultations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class='bx bx-clipboard'></i> Gestion des Consultations
                    </h3>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class='bx bx-filter'></i> Filtrer
                        </button>
                        <a href="{{ route('medecin.consultations.create') }}" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Nouvelle Consultation
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

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card bg-primary text-white">
                                <div class="stats-icon">
                                    <i class='bx bx-clipboard'></i>
                                </div>
                                <div class="stats-info">
                                    <h5>Total Consultations</h5>
                                    <h3>{{ $consultations->total() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card bg-success text-white">
                                <div class="stats-icon">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                                <div class="stats-info">
                                    <h5>Consultations Aujourd'hui</h5>
                                    <h3>{{ $consultations->where('date_consultation', \Carbon\Carbon::today())->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card bg-info text-white">
                                <div class="stats-icon">
                                    <i class='bx bx-user'></i>
                                </div>
                                <div class="stats-info">
                                    <h5>Patients Uniques</h5>
                                    <h3>{{ $consultations->unique('patient_id')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card bg-warning text-white">
                                <div class="stats-icon">
                                    <i class='bx bx-file'></i>
                                </div>
                                <div class="stats-info">
                                    <h5>Prescriptions</h5>
                                    <h3>{{ $consultations->whereNotNull('prescription_id')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vue Liste -->
                    <div class="list-view">
                        <div class="table-responsive">
                            <table class="table table-hover" id="consultationsTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Motif</th>
                                        <th>Diagnostic</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($consultations as $consultation)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <img src="{{ $consultation->patient?->utilisateur?->photo ?? asset('images/default-avatar.png') }}" 
                                                             alt="Avatar" class="rounded-circle">
                                                    </div>
                                                    <div>
                                                        @if($consultation->patient && $consultation->patient->utilisateur)
                                                            {{ $consultation->patient->utilisateur->nom }} {{ $consultation->patient->utilisateur->prenom }}
                                                            <small class="d-block text-muted">
                                                                {{ $consultation->patient->utilisateur->telephone }}
                                                            </small>
                                                        @else
                                                            <span class="text-muted">Patient non disponible</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      data-bs-toggle="tooltip" 
                                                      title="{{ $consultation->motif }}">
                                                    {{ Str::limit($consultation->motif, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                      data-bs-toggle="tooltip" 
                                                      title="{{ $consultation->diagnostic }}">
                                                    {{ Str::limit($consultation->diagnostic, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $consultation->statut === 'TERMINE' ? 'success' : ($consultation->statut === 'EN_COURS' ? 'warning' : 'info') }}">
                                                    {{ $consultation->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('medecin.consultations.show', $consultation) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Voir les détails">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="{{ route('medecin.consultations.edit', $consultation) }}" 
                                                       class="btn btn-warning btn-sm"
                                                       data-bs-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <form action="{{ route('medecin.consultations.destroy', $consultation) }}" 
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
                                                    <i class='bx bx-clipboard-x'></i>
                                                    <p>Aucune consultation trouvée</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $consultations->links() }}
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
                <h5 class="modal-title">Filtrer les consultations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('medecin.consultations.index') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="statut">
                            <option value="">Tous</option>
                            <option value="TERMINE">Terminé</option>
                            <option value="EN_COURS">En cours</option>
                            <option value="PLANIFIE">Planifié</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <input type="text" class="form-control" name="patient" placeholder="Nom du patient">
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
<style>
    .stats-card {
        padding: 1.5rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stats-icon {
        font-size: 2.5rem;
        margin-right: 1rem;
    }

    .stats-info h5 {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .stats-info h3 {
        margin: 0.5rem 0 0;
        font-size: 1.8rem;
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

    .text-truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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