@extends('layouts.medecin')

@section('title', 'Tableau de bord - Médecin')

@section('content')
<div class="container-fluid py-3">
    <!-- Bannière de bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body" style="background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%); border-radius: 0.5rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1 text-white">
                                <i class="fas fa-user-md me-2"></i>
                                Bienvenue, Dr. {{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                            </h4>
                            <p class="text-white mb-0">{{ now()->format('l d F Y') }}</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/doctor-icon.png') }}" alt="Doctor" class="d-none d-md-block" height="60">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-users text-success fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['patients_actifs'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Patients actifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-check text-info fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['consultations_aujourdhui'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Consultations aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-folder-open text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['dossiers_medicaux'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Dossiers médicaux</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-alt text-success fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['rendezvous_a_venir'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Rendez-vous à venir</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between flex-wrap">
                    <a href="{{ route('medecin.dossiers.create') }}" class="btn btn-success m-2">
                        <i class="fas fa-folder-plus me-2"></i> Nouveau dossier médical
                    </a>
                    <a href="{{ route('medecin.consultations.create') }}" class="btn btn-info m-2">
                        <i class="fas fa-stethoscope me-2"></i> Nouvelle consultation
                    </a>
                    <a href="{{ route('medecin.prescriptions.create') }}" class="btn btn-warning m-2">
                        <i class="fas fa-prescription me-2"></i> Nouvelle prescription
                    </a>
                    @if(isset($data['consultations']) && $data['consultations']->count() > 0)
                        <a href="{{ route('medecin.consultations.dicom.viewer', $data['consultations']->first()->id) }}" class="btn btn-secondary m-2">
                            <i class="fas fa-x-ray me-2"></i> Visualiser images DICOM
                        </a>
                    @else
                        <button class="btn btn-secondary m-2" disabled>
                            <i class="fas fa-x-ray me-2"></i> Visualiser images DICOM
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sections principales -->
    <div class="row">
        <!-- Rendez-vous à venir -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Rendez-vous à venir
                    </h5>
                    <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-external-link-alt me-1"></i> Voir tout
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                        @forelse($rendezvous as $rdv)
                            <div class="list-group-item border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-user text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $rdv->patient->utilisateur->nom ?? '' }} {{ $rdv->patient->utilisateur->prenom ?? '' }}</h6>
                                            <p class="text-muted small mb-0">{{ $rdv->date_rendez_vous->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-{{ $rdv->statut == 'CONFIRMÉ' ? 'success' : ($rdv->statut == 'EN_ATTENTE' ? 'warning' : 'danger') }} rounded-pill">{{ $rdv->statut }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 d-flex justify-content-end">
                                    <a href="{{ route('medecin.rendez-vous.show', $rdv->id) }}" class="btn btn-sm btn-outline-success me-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medecin.rendez-vous.edit', $rdv->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0">Aucun rendez-vous à venir</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Derniers dossiers médicaux -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-folder-open me-2"></i>
                        Dossiers médicaux récents
                    </h5>
                    <a href="{{ route('medecin.dossiers.index') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-external-link-alt me-1"></i> Voir tout
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                        @forelse($dossiers_medicaux as $dossier)
                            <div class="list-group-item border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                            <i class="fas fa-file-medical text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $dossier->patient->nom ?? '' }} {{ $dossier->patient->prenom ?? '' }}</h6>
                                            <p class="text-muted small mb-0">Dossier #{{ $dossier->numero_dossier }} - Créé le {{ $dossier->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $dossier->statut == 'ACTIF' ? 'success' : 'secondary' }} rounded-pill">{{ $dossier->statut }}</span>
                                </div>
                                <div class="mt-2 d-flex justify-content-end">
                                    <a href="{{ route('medecin.dossiers.show', $dossier->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0">Aucun dossier médical récent</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Deuxième ligne -->  
    <div class="row">
        <!-- Délégations actives -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-user-shield me-2"></i>
                        Délégations actives
                        <span class="badge bg-success ms-2">{{ $stats['delegations_actives'] ?? 0 }}</span>
                    </h5>
                    <a href="{{ route('medecin.delegations.index') }}" class="btn btn-sm btn-outline-success">
                        Voir tout <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(($stats['delegations_actives'] ?? 0) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($stats['delegations'] as $delegation)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-user-injured text-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">
                                                {{ $delegation->patient->utilisateur->prenom }} {{ $delegation->patient->utilisateur->nom }}
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-user-nurse me-1"></i>
                                                {{ $delegation->infirmier->prenom }} {{ $delegation->infirmier->nom }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            Jusqu'au {{ \Carbon\Carbon::parse($delegation->date_fin)->format('d/m/Y') }}
                                        </small>
                                        <a href="{{ route('medecin.delegations.edit', $delegation->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucune délégation active</p>
                            <a href="{{ route('medecin.delegations.create') }}" class="btn btn-success mt-3">
                                <i class="fas fa-plus me-1"></i> Créer une délégation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Espace pour une future intégration -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-puzzle-piece me-2"></i>
                        Module à venir
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Cet espace est réservé pour une future intégration.
                    </p>
                    <div>
                        @if(isset($data['consultations']) && $data['consultations']->count() > 0)
                            <a href="{{ route('medecin.consultations.dicom.viewer', $data['consultations']->first()->id) }}" class="btn btn-success w-100">
                                <i class="fas fa-x-ray me-2"></i>
                                Voir les images DICOM
                            </a>
                        @else
                            <button class="btn btn-outline-secondary w-100" disabled>
                                <i class="fas fa-x-ray me-2"></i>
                                Voir les images DICOM
                            </button>
                            <small class="text-muted d-block mt-2">Créez une consultation pour accéder aux images</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Examens récents -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-stethoscope me-2"></i>
                        Examens récents
                    </h5>
                    <a href="{{ route('medecin.examens.index') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-external-link-alt me-1"></i> Voir tout
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Type d'examen</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($examens as $examen)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-user-circle text-success fa-2x"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $examen->dossierMedical->patient->utilisateur->nom ?? '' }} {{ $examen->dossierMedical->patient->utilisateur->prenom ?? '' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">{{ $examen->type }}</span></td>
                                        <td>{{ $examen->date_examen->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('medecin.examens.show', $examen->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            <i class="fas fa-clipboard-list text-muted mb-2" style="font-size: 2rem;"></i>
                                            <p class="mb-0">Aucun examen récent</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du tableau de bord
    console.log('Tableau de bord initialisé');
                    Une erreur est survenue: ${error.message}
                </div>
            `;
        } finally {
            spinner.style.display = 'none';
            testButton.disabled = false;
        }
    });

    const testConfigButton = document.getElementById('testConfig');
    
    testConfigButton.addEventListener('click', async function() {
        try {
            const response = await fetch('{{ route('medecin.dicom.config') }}');
            const result = await response.json();
            
            if (result.success) {
                alert('Configuration Orthanc:\n' + 
                    'URL: ' + result.config.orthanc_url + '\n' +
                    'Port HTTP: ' + result.config.orthanc_http_port + '\n' +
                    'Port DICOM: ' + result.config.orthanc_dicom_port
                );
            } else {
                alert('Erreur: ' + result.message);
            }
        } catch (error) {
            alert('Erreur lors du test de la configuration: ' + error.message);
        }
    });
});
</script>
@endpush
@endsection
