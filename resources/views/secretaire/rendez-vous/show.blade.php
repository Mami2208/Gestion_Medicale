@extends('secretaire.layouts.app')

@section('title', 'Détails du rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Détails du rendez-vous
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Informations du rendez-vous -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-success bg-opacity-25">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Patient</h5>
                                </div>
                                <div class="card-body">
                                    @if($rendezVous->patient && $rendezVous->patient->utilisateur)
                                        <h5 class="card-title">{{ $rendezVous->patient->utilisateur->nom }} {{ $rendezVous->patient->utilisateur->prenom }}</h5>
                                        <p class="card-text">
                                            <i class="fas fa-phone-alt me-2 text-success"></i>{{ $rendezVous->patient->utilisateur->telephone ?? 'Non renseigné' }}<br>
                                            <i class="fas fa-envelope me-2 text-success"></i>{{ $rendezVous->patient->utilisateur->email ?? 'Non renseigné' }}
                                        </p>
                                        @if($rendezVous->patient->groupe_sanguin)
                                            <div class="mt-2">
                                                <span class="badge bg-danger">Groupe sanguin: {{ $rendezVous->patient->groupe_sanguin }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-muted">Patient inconnu ou supprimé</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-success bg-opacity-25">
                                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Médecin</h5>
                                </div>
                                <div class="card-body">
                                    @if($rendezVous->medecin && $rendezVous->medecin->utilisateur)
                                        <h5 class="card-title">Dr. {{ $rendezVous->medecin->utilisateur->nom }} {{ $rendezVous->medecin->utilisateur->prenom }}</h5>
                                        <p class="card-text">
                                            <i class="fas fa-stethoscope me-2 text-success"></i>{{ $rendezVous->medecin->specialite ?? 'Spécialité non précisée' }}<br>
                                            <i class="fas fa-id-card me-2 text-success"></i>Matricule: {{ $rendezVous->medecin->matricule ?? 'Non renseigné' }}
                                        </p>
                                    @else
                                        <p class="text-muted">Médecin inconnu ou supprimé</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails du rendez-vous -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-success bg-opacity-25">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations du rendez-vous</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-calendar me-2 text-success"></i>Date:</strong> {{ \Carbon\Carbon::parse($rendezVous->date_rendez_vous)->format('d/m/Y') }}</p>
                                    <p><strong><i class="fas fa-clock me-2 text-success"></i>Heure:</strong> {{ \Carbon\Carbon::parse($rendezVous->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($rendezVous->heure_fin)->format('H:i') }}</p>
                                    <p>
                                        <strong><i class="fas fa-tag me-2 text-success"></i>Statut:</strong> 
                                        <span class="badge bg-{{ $rendezVous->statut === 'CONFIRMÉ' ? 'success' : ($rendezVous->statut === 'ANNULÉ' ? 'danger' : 'warning') }}">
                                            {{ $rendezVous->statut ?? 'PLANIFIÉ' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-clipboard me-2 text-success"></i>Motif:</strong> {{ $rendezVous->motif }}</p>
                                    @if($rendezVous->notes)
                                        <p><strong><i class="fas fa-sticky-note me-2 text-success"></i>Notes:</strong> {{ $rendezVous->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions disponibles -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success bg-opacity-25">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2">
                                <a href="{{ route('secretaire.rendez-vous.edit', $rendezVous->id) }}" class="btn btn-success">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                                <button type="button" class="btn btn-success" onclick="confirmerRendezVous()">
                                    <i class="fas fa-check-circle me-2"></i>Confirmer
                                </button>
                                <!-- Le bouton d'annulation a été retiré -->
                                <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-outline-success">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation d'annulation supprimé -->

<script>
    function confirmerRendezVous() {
        // Ici, vous pourriez implémenter une requête AJAX pour changer le statut du rendez-vous à "CONFIRMÉ"
        alert('Fonctionnalité à implémenter: Confirmation du rendez-vous');
    }
</script>
@endsection
