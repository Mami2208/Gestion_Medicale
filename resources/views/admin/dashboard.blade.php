@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 p-6">
        <h1 class="text-3xl font-bold mb-6">Tableau de bord Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <a href="{{ route('admin.medecins.index') }}" class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center hover:bg-teal-100 transition duration-200 cursor-pointer">
                <h2 class="text-xl font-semibold mb-2">Gérer les médecins</h2>
                <p class="text-3xl font-bold text-teal-600">{{ $totalMedecins }}</p>
            </a>
            <a href="{{ route('admin.secretaires.index') }}" class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center hover:bg-teal-100 transition duration-200 cursor-pointer">
                <h2 class="text-xl font-semibold mb-2">Gérer les secrétaires</h2>
                <p class="text-3xl font-bold text-teal-600">{{ $totalSecretaires }}</p>
            </a>
            <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center hover:bg-teal-100 transition duration-200 cursor-pointer">
                <h2 class="text-xl font-semibold mb-2">Gérer les hôpitaux</h2>
                <p class="text-3xl font-bold text-teal-600">{{ $totalHopitals }}</p>
            </a>
            <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center justify-center hover:bg-teal-100 transition duration-200 cursor-pointer">
                <h2 class="text-xl font-semibold mb-2">Voir les logs</h2>
                <p class="text-3xl font-bold text-teal-600">{{ $totalLogs }}</p>
            </a>
        </div>
    </div>
</div>
@endsection
