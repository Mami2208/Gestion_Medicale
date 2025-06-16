@extends('secretaire.layouts.app')

@section('title', 'Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Rendez-vous</h5>
                    <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nouveau rendez-vous
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Motif</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rendezVous as $rdv)
                                <tr>
                                    <td>{{ $rdv->patient && $rdv->patient->utilisateur ? $rdv->patient->utilisateur->nom . ' ' . $rdv->patient->utilisateur->prenom : 'Patient inconnu' }}</td>
                                    <td>{{ $rdv->medecin && $rdv->medecin->utilisateur ? $rdv->medecin->utilisateur->nom . ' ' . $rdv->medecin->utilisateur->prenom : 'Médecin inconnu' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rdv->heure_debut)->format('H:i') }}</td>
                                    <td>{{ $rdv->motif }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rdv->statut === 'CONFIRMÉ' ? 'success' : ($rdv->statut === 'ANNULÉ' ? 'danger' : 'warning') }}">
                                            {{ $rdv->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaire.rendez-vous.show', $rdv->id) }}" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('secretaire.rendez-vous.edit', $rdv->id) }}" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Le bouton de suppression a été retiré -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $rendezVous->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
