@extends('layouts.infirmier')

@section('title', 'Tableau de bord Infirmier')

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
                                <i class="fas fa-user-nurse me-2"></i>
                                Bienvenue, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                            </h4>
                            <p class="text-white mb-0">{{ now()->format('l d F Y') }}</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/nurse-icon.png') }}" alt="Infirmier" class="d-none d-md-block" height="60">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="row mb-4">
        <!-- Patients suivis -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-user-injured text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['patients_suivis'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Patients suivis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Traitements actifs -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-pills text-success fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['traitements_actifs'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Traitements actifs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Patients critiques -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['patients_critiques'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Patients critiques</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Soins aujourd'hui -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-check text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['soins_aujourdhui'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Soins aujourd'hui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenu principal avec 3 colonnes -->
    <div class="row">
        <!-- Patients & Traitements -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Patients & Traitements en cours</h5>
                    <a href="{{ route('infirmier.patients.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Voir tous les patients
                    </a>
                </div>
                <div class="card-body">
                    @include('infirmier.dashboard.patients-list')
                </div>
            </div>
        </div>
        
        <!-- Rappels des soins programmés -->
        @include('infirmier.dashboard.rappels')
    </div>
    
    <!-- Délégations d'accès -->
    @include('infirmier.dashboard.delegations')
    
    <!-- Alertes patients u00e0 risque -->
    @include('infirmier.dashboard.alertes')
    
    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between flex-wrap">
                    <a href="{{ route('infirmier.patients.index') }}" class="btn btn-primary m-2">
                        <i class="fas fa-user-injured me-2"></i> Liste des patients
                    </a>
                    <a href="{{ route('infirmier.traitements.index') }}" class="btn btn-info text-white m-2">
                        <i class="fas fa-pills me-2"></i> Gérer les traitements
                    </a>
                    <a href="{{ route('infirmier.alertes.index') }}" class="btn btn-danger m-2">
                        <i class="fas fa-exclamation-triangle me-2"></i> Voir les alertes
                    </a>
                    <a href="#" class="btn btn-success m-2">
                        <i class="fas fa-plus-circle me-2"></i> Nouvelle observation
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
