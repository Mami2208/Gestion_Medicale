@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 p-6">
        <h1 class="text-3xl font-bold mb-6">Tableau de bord Secrétaire</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Nombre total de patients</h2>
                <p class="text-4xl font-bold">{{ $totalPatients }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Nombre total de rendez-vous</h2>
                <p class="text-4xl font-bold">{{ $totalAppointments }}</p>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-semibold mb-4">Actions rapides</h2>
            <a href="{{ route('secretaire.medical_records.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Créer un dossier médical
            </a>
        </div>
    </div>
</div>
@endsection
