@extends('medecin.layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Tableau de bord</h1>
        <p class="mt-2 text-lg text-gray-600">Bienvenue sur votre tableau de bord médical</p>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        <!-- Patients actifs -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Patients actifs</h3>
                    <p class="text-4xl font-bold text-blue-600">{{$stats['patients_actifs']}}</p>
                </div>
                <div class="ml-2">
                    <i class="fas fa-users text-6xl text-blue-500/80 hover:text-blue-500 transition-colors duration-300"></i>
                </div>
            </div>
        </div>

        <!-- Consultations aujourd'hui -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Consultations aujourd'hui</h3>
                    <p class="text-4xl font-bold text-green-600">{{$stats['consultations_aujourdhui']}}</p>
                </div>
                <div class="ml-2">
                    <i class="fas fa-calendar-alt text-6xl text-green-500/80 hover:text-green-500 transition-colors duration-300"></i>
                </div>
            </div>
        </div>

        <!-- Dossiers médicaux -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Dossiers médicaux</h3>
                    <p class="text-4xl font-bold text-purple-600">{{$stats['dossiers_medicaux']}}</p>
                </div>
                <div class="ml-2">
                    <i class="fas fa-folder-medical text-6xl text-purple-500/80 hover:text-purple-500 transition-colors duration-300"></i>
                </div>
            </div>
        </div>

        <!-- Rendez-vous à venir -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Rendez-vous à venir</h3>
                    <p class="text-4xl font-bold text-yellow-600">{{$stats['rendezvous_a_venir']}}</p>
                </div>
                <div class="ml-2">
                    <i class="fas fa-calendar-check text-6xl text-yellow-500/80 hover:text-yellow-500 transition-colors duration-300"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique des consultations -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Consultations par mois</h3>
            <div id="consultationsChart" class="h-72 rounded-lg bg-gray-50"></div>
        </div>

        <!-- Statistiques des patients -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Répartition des patients</h3>
            <div id="patientsChart" class="h-72 rounded-lg bg-gray-50"></div>
        </div>
    </div>

    <!-- Rendez-vous récents -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Rendez-vous récents</h3>
        <div class="space-y-4">
            @forelse($rendezvous as $rv)
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
                    <div>
                        <p class="font-medium text-gray-900">{{$rv->patient->nom}} {{$rv->patient->prenom}}</p>
                        <p class="text-sm text-gray-500">{{$rv->date_rendezvous->format('d/m/Y H:i')}}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('medecin.rendez-vous.show', $rv) }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors duration-300">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('medecin.rendez-vous.edit', $rv) }}" 
                           class="text-yellow-600 hover:text-yellow-800 transition-colors duration-300">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Aucun rendez-vous récent</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des consultations
    const consultationsData = {
        labels: @json($stats['mois']),
        datasets: [{
            label: 'Consultations',
            data: @json($stats['consultations_par_mois']),
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.4
        }]
    };

    // Configuration du graphique des consultations
    const consultationsConfig = {
        type: 'line',
        data: consultationsData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            },
            animation: {
                duration: 1000
            }
        }
    };

    // Création du graphique des consultations
    const ctxConsultations = document.getElementById('consultationsChart').getContext('2d');
    new Chart(ctxConsultations, consultationsConfig);

    // Données pour le graphique des patients
    const patientsData = {
        labels: ['Hommes', 'Femmes'],
        datasets: [{
            label: 'Patients',
            data: @json($stats['patients_par_sexe']),
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 2
        }]
    };

    // Configuration du graphique des patients
    const patientsConfig = {
        type: 'pie',
        data: patientsData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: false
                }
            },
            animation: {
                duration: 1000
            }
        }
    };

    // Création du graphique des patients
    const ctxPatients = document.getElementById('patientsChart').getContext('2d');
    new Chart(ctxPatients, patientsConfig);
});
</script>
@endpush
@endsection
