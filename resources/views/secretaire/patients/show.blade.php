@extends('secretaire.layouts.app')

@section('title', 'Détails du patient')
@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <form action="{{ route('patients.delete', $patient) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <p class="form-control-plaintext">{{ $patient->utilisateur->nom }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prénom</label>
                                <p class="form-control-plaintext">{{ $patient->utilisateur->prenom }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <p class="form-control-plaintext">
                                    @if($patient->date_naissance)
                                        {{ $patient->date_naissance->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sexe</label>
                                <p class="form-control-plaintext">{{ $patient->utilisateur->sexe === 'H' ? 'Homme' : 'Femme' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <p class="form-control-plaintext">{{ $patient->utilisateur->telephone }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <p class="form-control-plaintext">{{ $patient->adresse }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informations médicales -->
                    @if($patient->dossiers->count() > 0)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Dossier médical</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Motif de consultation</label>
                                        <p class="form-control-plaintext">{{ $patient->dossiers->first()->motif_consultation }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Antécédents médicaux</label>
                                        <p class="form-control-plaintext">{{ $patient->dossiers->first()->antecedents }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Allergies</label>
                                        <p class="form-control-plaintext">{{ $patient->dossiers->first()->allergies }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle"></i>
                            Aucun dossier médical n'est associé à ce patient.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
