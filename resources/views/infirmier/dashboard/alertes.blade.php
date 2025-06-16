<!-- Alertes des patients à risque -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Alertes des patients à risque
                </h5>
                <a href="{{ route('infirmier.alertes.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-external-link-alt me-1"></i> Voir toutes les alertes
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(isset($alertes) && $alertes->count() > 0)
                        @foreach($alertes as $alerte)
                            <div class="col-md-4 mb-3">
                                <div class="alert {{ $alerte->niveau == 'CRITIQUE' ? 'alert-danger' : 'alert-warning' }} mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>{{ $alerte->patient->prenom }} {{ $alerte->patient->nom }} ({{ $alerte->patient->age ?? 'N/A' }} ans)</strong>
                                        <span class="badge {{ $alerte->niveau == 'CRITIQUE' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ ucfirst(strtolower($alerte->niveau)) }}</span>
                                    </div>
                                    <p class="mb-1"><i class="fas fa-exclamation-circle me-2"></i>{{ $alerte->description }}</p>
                                    @if($alerte->details)
                                        <p class="mb-1"><i class="fas fa-info-circle me-2"></i>{{ $alerte->details }}</p>
                                    @endif
                                    <p class="mb-0 text-muted small">Signalé {{ $alerte->created_at->diffForHumans() }}</p>
                                    <div class="text-end mt-2">
                                        <a href="{{ route('infirmier.alertes.show', $alerte->id) }}" class="btn btn-sm btn-outline-{{ $alerte->niveau == 'CRITIQUE' ? 'danger' : 'warning' }}">Voir</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune alerte à afficher pour le moment.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
