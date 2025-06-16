<!-- Vue synthétique : Liste patients + traitements -->
<div class="row">
    <!-- Patients suivis et leurs traitements -->
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-success">
                    <i class="fas fa-user-injured me-2"></i>
                    Patients suivis et traitements en cours
                </h5>
                <a href="{{ route('infirmier.patients.index') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-external-link-alt me-1"></i> Voir tous
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>État</th>
                                <th>Dernière observation</th>
                                <th>Traitements</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($patients) && $patients->count() > 0)
                                @foreach($patients as $patient)
                                <tr class="{{ isset($patient->etat_sante) && $patient->etat_sante == 'CRITIQUE' ? 'table-danger' : (isset($patient->etat_sante) && $patient->etat_sante == 'ALERTE' ? 'table-warning' : '') }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle {{ isset($patient->etat_sante) && $patient->etat_sante == 'CRITIQUE' ? 'bg-danger' : (isset($patient->etat_sante) && $patient->etat_sante == 'ALERTE' ? 'bg-warning' : 'bg-success') }} text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                {{ substr($patient->prenom, 0, 1) }}{{ substr($patient->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $patient->prenom }} {{ $patient->nom }}</strong><br>
                                                <small class="text-muted">{{ $patient->age ?? 'N/A' }} ans</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ isset($patient->etat_sante) && $patient->etat_sante == 'CRITIQUE' ? 'danger' : (isset($patient->etat_sante) && $patient->etat_sante == 'ALERTE' ? 'warning' : 'success') }} rounded-pill">
                                            {{ isset($patient->etat_sante) ? ucfirst(strtolower($patient->etat_sante)) : 'Stable' }}
                                        </span>
                                        <div class="small mt-1">
                                            @if(isset($patient->signes_vitaux))
                                                @if(isset($patient->signes_vitaux['temperature']))
                                                <span class="text-muted">T°: {{ $patient->signes_vitaux['temperature'] }}°C</span><br>
                                                @endif
                                                @if(isset($patient->signes_vitaux['tension']))
                                                <span class="text-muted">TA: {{ $patient->signes_vitaux['tension'] }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Aucun signe vital enregistré</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($patient->observations && $patient->observations->count() > 0)
                                            @php $derniereObs = $patient->observations->sortByDesc('created_at')->first(); @endphp
                                            <span>{{ $derniereObs->contenu ?? 'Aucune observation' }}</span><br>
                                            <small class="text-muted">{{ $derniereObs->created_at ? $derniereObs->created_at->format('d/m/Y H:i') : '' }}</small>
                                        @else
                                            <span class="text-muted">Aucune observation</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->traitements && $patient->traitements->count() > 0)
                                            @foreach($patient->traitements as $traitement)
                                                <span class="badge bg-info mb-1 d-block text-start">{{ $traitement->medicament }} - {{ $traitement->posologie }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Aucun traitement</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('infirmier.patients.show', $patient->id) }}" class="btn btn-sm btn-outline-primary mb-1 d-block" data-bs-toggle="tooltip" title="Voir la fiche patient">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('infirmier.observations.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-outline-success mb-1 d-block" data-bs-toggle="tooltip" title="Ajouter une observation">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Aucun patient n'est assigné à votre service pour le moment.
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
