@extends('secretaire.layouts.app')

@section('title', 'Liste des dossiers médicaux')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des dossiers médicaux</h6>
            <a href="{{ route('secretaire.dossiers-medicaux.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau dossier
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dossiers as $dossier)
                            <tr>
                                <td>{{ $dossier->numero_dossier }}</td>
                                <td>
                                    @if($dossier->patient && $dossier->patient->utilisateur)
                                        {{ $dossier->patient->utilisateur->prenom }} {{ $dossier->patient->utilisateur->nom }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($dossier->medecin && $dossier->medecin->utilisateur)
                                        Dr. {{ $dossier->medecin->utilisateur->prenom }} {{ $dossier->medecin->utilisateur->nom }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($dossier->date_creation)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('secretaire.dossiers-medicaux.show', $dossier->id) }}" class="btn btn-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('secretaire.dossiers-medicaux.edit', $dossier->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('secretaire.dossiers-medicaux.destroy', $dossier->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucun dossier médical trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $dossiers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
            }
        });
    });
</script>
@endpush
