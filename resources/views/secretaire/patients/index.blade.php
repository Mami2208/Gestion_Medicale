@extends('secretaire.layouts.app')

@section('title', 'Patients')
@section('topbar-actions')
    <!-- Bouton supprimé -->
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom complet</th>
                                    <th>Date de naissance</th>
                                    <th>Dossier</th>
                                    <th>Infirmier assigné</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                <tr>
                                    <td>{{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}</td>
                                    <td>
                                        @if($patient->date_naissance)
                                            {{ $patient->date_naissance->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->dossiers->count() > 0)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-warning">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->infirmier && $patient->infirmier->utilisateur)
                                            <span class="badge bg-info">{{ $patient->infirmier->utilisateur->prenom }} {{ $patient->infirmier->utilisateur->nom }}</span>
                                        @else
                                            <span class="badge bg-secondary">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaire.patients.show', $patient) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('secretaire.patients.edit', $patient) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $patient->id }}">
                                            <i class="fas fa-user-nurse"></i>
                                        </button>
                                        
                                        <!-- Modal d'attribution d'infirmier -->
                                        <div class="modal fade" id="assignModal{{ $patient->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $patient->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="assignModalLabel{{ $patient->id }}">Assigner un infirmier à {{ $patient->utilisateur->prenom }} {{ $patient->utilisateur->nom }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('secretaire.patients.attribution.assign') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="infirmier_id" class="form-label">Sélectionner un infirmier</label>
                                                                <select class="form-select" name="infirmier_id" required>
                                                                    <option value="">Choisir...</option>
                                                                    @foreach(\App\Models\Infirmier::with('utilisateur')->get() as $infirmier)
                                                                        <option value="{{ $infirmier->id }}" {{ $patient->infirmier_id == $infirmier->id ? 'selected' : '' }}>
                                                                            {{ $infirmier->utilisateur->prenom }} {{ $infirmier->utilisateur->nom }} 
                                                                            ({{ $infirmier->nombre_patients ?? 0 }} patients)
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-primary">Assigner</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
