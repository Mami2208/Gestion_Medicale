@extends('secretaire.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dossiers médicaux</h2>
        <a href="{{ route('secretaire.dossiers-medicaux.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau dossier
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Motif consultation</th>
                            <th>Médecin</th>
                            <th>Date création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossiers as $dossier)
                        <tr>
                            <td>
                                @if($dossier->patient && $dossier->patient->utilisateur)
                                    {{ $dossier->patient->utilisateur->nom }} {{ $dossier->patient->utilisateur->prenom }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $dossier->motif_consultation ?? 'N/A' }}</td>
                            <td>
                                @if($dossier->medecin)
                                    {{ $dossier->medecin->nom }} {{ $dossier->medecin->prenom }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $dossier->created_at ? $dossier->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('secretaire.dossiers-medicaux.show', $dossier) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('secretaire.dossiers-medicaux.edit', $dossier) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('secretaire.dossiers-medicaux.destroy', $dossier) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $dossiers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
