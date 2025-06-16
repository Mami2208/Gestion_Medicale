<!-- Rappels automatiques : Soins programmu00e9s -->
<div class="col-md-4 mb-4">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-success">
                <i class="fas fa-clock me-2"></i>
                Rappels des soins programmés
            </h5>
            <button class="btn btn-sm btn-outline-success">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="mb-0">Aujourd'hui</h6>
                <span class="badge bg-primary rounded-pill">{{ $rappels_aujourdhui_count ?? 0 }}</span>
            </div>
            
            @if(isset($rappels_aujourdhui) && $rappels_aujourdhui->count() > 0)
                @foreach($rappels_aujourdhui as $rappel)
                    <div class="reminder-item {{ $rappel->priorite == 'URGENT' ? 'urgent' : '' }} mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $rappel->patient->prenom }} {{ $rappel->patient->nom }}</strong>
                            <span class="badge {{ $rappel->priorite == 'URGENT' ? 'bg-danger' : ($rappel->type == 'TRAITEMENT' ? 'bg-info text-white' : 'bg-secondary') }}">{{ $rappel->priorite == 'URGENT' ? 'URGENT' : $rappel->type }}</span>
                        </div>
                        <div>{{ $rappel->description }}</div>
                        <small class="text-muted">
                            @php
                                $now = \Carbon\Carbon::now();
                                $rappelTime = \Carbon\Carbon::parse($rappel->date_heure);
                                $diff = $now->diffForHumans($rappelTime, ['parts' => 1, 'short' => true]);
                                
                                if ($rappelTime->isToday()) {
                                    if ($rappelTime->isFuture()) {
                                        echo "À " . $rappelTime->format('H:i') . " (dans " . $diff . ")"; 
                                    } else {
                                        echo "À " . $rappelTime->format('H:i') . " (il y a " . $diff . ")"; 
                                    }
                                } else {
                                    echo $rappelTime->format('d/m/Y H:i');
                                }
                            @endphp
                        </small>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun rappel pour aujourd'hui
                </div>
            @endif
            
            @if(isset($rappels_demain) && $rappels_demain->count() > 0)
                <div class="d-flex justify-content-between mt-4 mb-3">
                    <h6 class="mb-0">Demain</h6>
                    <span class="badge bg-secondary rounded-pill">{{ $rappels_demain->count() }}</span>
                </div>
                
                @foreach($rappels_demain as $rappel)
                    <div class="reminder-item mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $rappel->patient->prenom }} {{ $rappel->patient->nom }}</strong>
                            <span class="badge {{ $rappel->type == 'TRAITEMENT' ? 'bg-info text-white' : 'bg-secondary' }}">{{ $rappel->type }}</span>
                        </div>
                        <div>{{ $rappel->description }}</div>
                        <small class="text-muted">Demain à {{ \Carbon\Carbon::parse($rappel->date_heure)->format('H:i') }}</small>
                    </div>
                @endforeach
            @endif
            
            <div class="text-center mt-3">
                <a href="#" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Voir le planning complet
                </a>
            </div>
        </div>
    </div>
</div>
