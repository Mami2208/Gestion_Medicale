@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    @include('components.medecin-sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-semibold mb-6">Tableau de bord Médecin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" id="appointments">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Rendez-vous à venir</h2>
                @if($appointments->isEmpty())
                    <p class="text-gray-600">Vous n'avez aucun rendez-vous à venir.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->patient->nom ?? 'N/A' }} {{ $appointment->patient->prenom ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($appointment->statut ?? 'En attente') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6" id="prescriptions">
                <h2 class="text-xl font-semibold mb-4">Prescriptions récentes</h2>
                @if($prescriptions->isEmpty())
                    <p class="text-gray-600">Aucune prescription récente.</p>
                @else
                    <ul class="list-disc list-inside">
                        @foreach($prescriptions as $prescription)
                            <li>{{ $prescription->created_at->format('d/m/Y') }} - {{ $prescription->description ?? 'Description non disponible' }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" id="treatments">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Traitements récents</h2>
                @if($treatments->isEmpty())
                    <p class="text-gray-600">Aucun traitement récent.</p>
                @else
                    <ul class="list-disc list-inside">
                        @foreach($treatments as $treatment)
                            <li>{{ $treatment->created_at->format('d/m/Y') }} - {{ $treatment->description ?? 'Description non disponible' }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6" id="notifications">
                <h2 class="text-xl font-semibold mb-4">Notifications récentes</h2>
                @if($notifications->isEmpty())
                    <p class="text-gray-600">Aucune notification récente.</p>
                @else
                    <ul class="list-disc list-inside">
                        @foreach($notifications as $notification)
                            <li>{{ $notification->created_at->format('d/m/Y H:i') }} - {{ $notification->message ?? 'Message non disponible' }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Gestion des images DICOM</h2>
            <div class="flex space-x-4">
                {{-- <a href="{{ route('dicom.viewer', ['orthancId' => '']) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Visualiser les images</a> --}}
                {{-- Upload link removed as per request --}}
            </div>
        </div>
    </main>
</div>
@endsection
