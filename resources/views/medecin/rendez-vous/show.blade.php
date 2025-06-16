@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du Rendez-vous</h5>
                    <div>
                        <a href="{{ route('medecin.rendez-vous.edit', $rendezVous) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right fw-bold">Patient :</label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                {{ $rendezVous->patient->utilisateur->nom }} {{ $rendezVous->patient->utilisateur->prenom }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right fw-bold">Date :</label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                {{ $rendezVous->date_rendez_vous ? \Carbon\Carbon::parse($rendezVous->date_rendez_vous)->format('d/m/Y') : 'Non spécifié' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right fw-bold">Heure :</label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                {{ $rendezVous->heure_rendez_vous }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right fw-bold">Motif :</label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                {{ $rendezVous->motif }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right fw-bold">Créé le :</label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                {{ $rendezVous->created_at ? $rendezVous->created_at->format('d/m/Y H:i') : 'Non spécifié' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-right fw-bold">Dernière modification :</label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                {{ $rendezVous->updated_at ? $rendezVous->updated_at->format('d/m/Y H:i') : 'Non spécifié' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8 offset-md-4">
                            <form action="{{ route('medecin.rendez-vous.destroy', $rendezVous) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 