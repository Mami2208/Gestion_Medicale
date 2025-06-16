<!-- Carte des délégations d'accès -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-primary">
            <i class="fas fa-share-alt me-2"></i>Délégations d'accès
        </h5>
        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-bell me-1"></i>Notifications
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($delegations as $delegation)
                        @if($delegation->patient && $delegation->patient->utilisateur && $delegation->medecin)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-injured text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold">{{ $delegation->patient->utilisateur->prenom ?? 'N/A' }} {{ $delegation->patient->utilisateur->nom ?? '' }}</span>
                                        <small class="text-muted">#{{ $delegation->patient->id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block">Dr. {{ $delegation->medecin->prenom ?? 'N/A' }} {{ $delegation->medecin->nom ?? '' }}</span>
                                <small class="text-muted">{{ $delegation->medecin->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="d-block">Du {{ $delegation->date_debut->format('d/m/Y') }}</span>
                                <small class="text-muted">Au {{ $delegation->date_fin->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                @if($delegation->statut == 'active')
                                    @if($delegation->isActive())
                                        <span class="badge bg-success">Active</span>
                                    @elseif($delegation->date_debut > now())
                                        <span class="badge bg-warning text-dark">À venir</span>
                                    @else
                                        <span class="badge bg-secondary">Expirée</span>
                                    @endif
                                @elseif($delegation->statut == 'terminee')
                                    <span class="badge bg-secondary">Terminée</span>
                                @else
                                    <span class="badge bg-danger">Annulée</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('infirmier.patients.show', $delegation->patient_id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Voir le dossier">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="py-3">
                                    <i class="fas fa-share-alt fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 text-muted">Aucune délégation d'accès enregistrée pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light text-center">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Les délégations d'accès vous permettent de consulter temporairement les dossiers médicaux des patients d'autres professionnels de santé
        </small>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
