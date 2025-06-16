@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Barre latérale -->
    @include('medecin.partials.sidebar')

    <!-- Zone principale -->
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Statistiques</h1>
                <div class="flex space-x-4">
                    <select class="rounded-lg border-gray-300">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">90 derniers jours</option>
                        <option value="365">Année en cours</option>
                    </select>
                </div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Consultations -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-stethoscope text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Consultations</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistiques['consultations'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">
                                <i class="fas fa-arrow-up"></i> {{ $statistiques['evolution_consultations'] }}%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">vs période précédente</span>
                        </div>
                    </div>
                </div>

                <!-- Patients -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Patients</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistiques['patients'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">
                                <i class="fas fa-arrow-up"></i> {{ $statistiques['evolution_patients'] }}%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">vs période précédente</span>
                        </div>
                    </div>
                </div>

                <!-- Rendez-vous -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rendez-vous</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistiques['rendez_vous'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">
                                <i class="fas fa-arrow-up"></i> {{ $statistiques['evolution_rendez_vous'] }}%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">vs période précédente</span>
                        </div>
                    </div>
                </div>

                <!-- Chiffre d'affaires -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-euro-sign text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Chiffre d'affaires</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($statistiques['chiffre_affaires'], 2) }} €</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium">
                                <i class="fas fa-arrow-up"></i> {{ $statistiques['evolution_chiffre_affaires'] }}%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">vs période précédente</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Évolution des consultations -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Évolution des consultations</h3>
                    <canvas id="consultationsChart" height="300"></canvas>
                </div>

                <!-- Répartition des types de consultations -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition des types de consultations</h3>
                    <canvas id="typesConsultationsChart" height="300"></canvas>
                </div>

                <!-- Âge des patients -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par âge</h3>
                    <canvas id="agePatientsChart" height="300"></canvas>
                </div>

                <!-- Chiffre d'affaires mensuel -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chiffre d'affaires mensuel</h3>
                    <canvas id="chiffreAffairesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des consultations
    const consultationsCtx = document.getElementById('consultationsChart').getContext('2d');
    new Chart(consultationsCtx, {
        type: 'line',
        data: {
            labels: @json($statistiques['labels']),
            datasets: [{
                label: 'Consultations',
                data: @json($statistiques['consultations_par_jour']),
                borderColor: '#3B82F6',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Graphique des types de consultations
    const typesConsultationsCtx = document.getElementById('typesConsultationsChart').getContext('2d');
    new Chart(typesConsultationsCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statistiques['types_consultations_labels']),
            datasets: [{
                data: @json($statistiques['types_consultations_data']),
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Graphique de l'âge des patients
    const agePatientsCtx = document.getElementById('agePatientsChart').getContext('2d');
    new Chart(agePatientsCtx, {
        type: 'bar',
        data: {
            labels: @json($statistiques['age_labels']),
            datasets: [{
                label: 'Nombre de patients',
                data: @json($statistiques['age_data']),
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Graphique du chiffre d'affaires
    const chiffreAffairesCtx = document.getElementById('chiffreAffairesChart').getContext('2d');
    new Chart(chiffreAffairesCtx, {
        type: 'line',
        data: {
            labels: @json($statistiques['mois_labels']),
            datasets: [{
                label: 'Chiffre d\'affaires',
                data: @json($statistiques['chiffre_affaires_mensuel']),
                borderColor: '#8B5CF6',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
@endsection 