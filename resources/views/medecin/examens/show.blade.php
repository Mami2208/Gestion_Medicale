@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de l'Examen Médical</h5>
                    <div>
                        <a href="{{ route('medecin.examens.edit', $examen) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('medecin.examens.destroy', $examen) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Patient</h6>
                            <p class="mb-0">{{ $examen->patient->utilisateur->nom }} {{ $examen->patient->utilisateur->prenom }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Date de l'examen</h6>
                            <p class="mb-0">{{ $examen->date_examen ? \Carbon\Carbon::parse($examen->date_examen)->format('d/m/Y H:i') : 'Non spécifié' }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Type d'examen</h6>
                        <p class="mb-0">{{ $examen->type_examen }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $examen->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Résultats</h6>
                        <p class="mb-0">{{ $examen->resultats }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Conclusion</h6>
                        <p class="mb-0">{{ $examen->conclusion }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Observations</h6>
                        <p class="mb-0">{{ $examen->observations ?: 'Non renseigné' }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('medecin.examens.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 