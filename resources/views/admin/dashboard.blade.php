@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Carte Utilisateurs -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Utilisateurs</h3>
                <p class="text-2xl font-semibold">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>

    <!-- Carte Sessions Actives -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <i class="fas fa-signal text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Sessions Actives</h3>
                <p class="text-2xl font-semibold">{{ $stats['active_sessions'] }}</p>
            </div>
        </div>
    </div>

    <!-- Carte Patients -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                <i class="fas fa-user-injured text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Patients</h3>
                <p class="text-2xl font-semibold">{{ $stats['total_patients'] }}</p>
            </div>
        </div>
    </div>

    <!-- Carte Rendez-vous -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Rendez-vous</h3>
                <p class="text-2xl font-semibold">{{ $stats['total_appointments'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Graphique des rendez-vous -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Rendez-vous par jour</h3>
        <canvas id="appointmentsChart"></canvas>
    </div>

    <!-- Graphique des utilisateurs -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Nouveaux utilisateurs</h3>
        <canvas id="usersChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des rendez-vous
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Rendez-vous',
                data: [12, 19, 3, 5, 2, 3, 7],
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        }
    });

    // Graphique des utilisateurs
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: [65, 59, 80, 81, 56, 55],
                backgroundColor: 'rgb(99, 102, 241)'
            }]
        }
    });
</script>
@endpush
@endsection
