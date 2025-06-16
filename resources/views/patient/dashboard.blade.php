@extends('patient.layouts.app')

@section('title', 'Tableau de bord - Patient')

@section('content')
<div class="container-fluid py-4">
    <!-- Bannière de bienvenue -->
    <div class="card bg-gradient-medical mb-4 text-white shadow">
        <div class="card-body py-4 px-4">
            <h2 class="mb-1">Bienvenue sur votre espace santé</h2>
            <p class="mb-0">Accédez à vos informations médicales en un coup d'œil</p>
        </div>
    </div>

    <!-- Profil simplifié -->
    <div class="text-center mb-4">
        <div class="avatar-circle mx-auto mb-3">
            <i class="fas fa-user-circle fa-4x text-success"></i>
        </div>
        <h4>{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</h4>
        <p class="text-muted">Patient</p>
    </div>

    <!-- Statistiques simplifiées -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 rounded-lg shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-success-light text-success mb-3 mx-auto">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="counter">{{ $rendezVous ? count($rendezVous) : 0 }}</h3>
                    <p class="text-muted mb-0">Rendez-vous</p>
                    <a href="{{ route('patient.appointments') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 rounded-lg shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-medical-light text-medical mb-3 mx-auto">
                        <i class="fas fa-folder-medical"></i>
                    </div>
                    <h3 class="counter">{{ $dossiersMedicaux ? count($dossiersMedicaux) : 0 }}</h3>
                    <p class="text-muted mb-0">Dossiers médicaux</p>
                    <a href="{{ route('patient.medical_records') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 rounded-lg shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-success-light text-success mb-3 mx-auto">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <h3 class="counter">{{ $traitements ? count($traitements) : 0 }}</h3>
                    <p class="text-muted mb-0">Traitements</p>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 rounded-lg shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="icon-circle bg-warning-light text-warning mb-3 mx-auto">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="counter">0</h3>
                    <p class="text-muted mb-0">Notifications</p>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochain rendez-vous (version simplifiée) -->
    @if($rendezVous && count($rendezVous) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 rounded-lg shadow-sm">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="icon-circle bg-success-light text-success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Prochain rendez-vous</h5>
                            <p class="text-muted mb-0">Votre consultation à venir</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <p class="mb-1 text-muted">Date</p>
                                <h6>{{ $rendezVous->first()->date_rendez_vous instanceof \DateTime ? $rendezVous->first()->date_rendez_vous->format('d/m/Y') : $rendezVous->first()->date_rendez_vous }}</h6>
                            </div>
                            <div>
                                <p class="mb-1 text-muted">Heure</p>
                                <h6>{{ $rendezVous->first()->heure_debut }}</h6>
                            </div>
                            <div>
                                <p class="mb-1 text-muted">Médecin</p>
                                <h6>
                                    @if($rendezVous->first()->medecin)
                                        Dr. {{ $rendezVous->first()->medecin->nom }} {{ $rendezVous->first()->medecin->prenom }}
                                    @else
                                        Non assigné
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-outline-success">Voir tous les rendez-vous</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        /* Styles personnalisés pour un dashboard plus joli */
        .bg-gradient-medical {
            background: linear-gradient(to right, #2d8659, #1a6540);
        }
        
        .bg-medical-light {
            background-color: rgba(45, 134, 89, 0.1);
        }
        
        .text-medical {
            color: #2d8659;
        }

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(45, 134, 89, 0.05);
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto;
}

.counter {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}





.bg-success-light {
    background-color: rgba(28, 200, 138, 0.1);
}

.bg-warning-light {
    background-color: rgba(246, 194, 62, 0.1);
}

.action-button {
    text-decoration: none;
    color: #5a5c69;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
}

.action-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    color: #5a5c69;
}

.icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
</style>

@endsection
