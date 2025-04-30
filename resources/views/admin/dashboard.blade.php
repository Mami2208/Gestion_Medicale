@php
    $hideNavbar = true;
@endphp

@extends('layouts.app', ['hideNavbar' => true])

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 p-6">
        <h1 class="text-3xl font-bold mb-6">Tableau de bord Admin</h1>

        <div class="grid grid-cols-1 gap-8">
            <div class="bg-white rounded-lg shadow p-6 overflow-x-auto max-h-[600px] mb-6">
                <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Médecins</h2>
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spécialité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consultations</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($medecins as $medecin)
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $medecin->prenom }} {{ $medecin->nom }}</td>
                            <td class="px-6 py-4">{{ $medecin->specialite }}</td>
                            <td class="px-6 py-4">{{ $medecin->consultations_count }}</td>
                            <td class="px-6 py-4">{{ $medecin->email }}</td>
                            <td class="px-6 py-4">{{ $medecin->telephone }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow p-6 overflow-x-auto max-h-[600px]">
                <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Secrétaires</h2>
                <table class="min-w-full table-auto border border-gray-200 rounded">
                    <thead class="sticky top-0 bg-gray-50">
                        <tr>
                            <th class="py-2 px-4 border-b">Nom</th>
                            <th class="py-2 px-4 border-b">Prénom</th>
                            <th class="py-2 px-4 border-b">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($secretaires as $secretaire)
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b">{{ $secretaire->nom }}</td>
                            <td class="py-2 px-4 border-b">{{ $secretaire->prenom }}</td>
                            <td class="py-2 px-4 border-b">{{ $secretaire->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
