@extends('medecin.layouts.app')

@section('title', 'Détails du patient - ' . $patient->utilisateur->nom . ' ' . $patient->utilisateur->prenom)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Détails du patient</h1>
            <p class="mt-1 text-sm text-gray-500">Informations complètes et historique médical</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('medecin.patients.edit', $patient) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('medecin.patients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center mb-6">
                    <img src="{{ $patient->utilisateur->photo ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}" 
                         class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                    </h2>
                    <p class="text-gray-500">
                        {{ $patient->utilisateur->age }} ans • {{ $patient->utilisateur->sexe }}
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Informations de contact</h3>
                        <div class="mt-2 space-y-2">
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-envelope w-5 text-gray-400"></i>
                                {{ $patient->utilisateur->email }}
                            </p>
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-phone w-5 text-gray-400"></i>
                                {{ $patient->utilisateur->telephone }}
                            </p>
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                                {{ $patient->utilisateur->adresse ?? 'Non spécifiée' }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Informations médicales</h3>
                        <div class="mt-2 space-y-2">
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-tint w-5 text-gray-400"></i>
                                Groupe sanguin : {{ $patient->dossierMedical->groupe_sanguin ?? 'Non spécifié' }}
                            </p>
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                Date de naissance : {{ $patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($patient->utilisateur->date_naissance)->format('d/m/Y') : 'Non spécifiée' }}
                            </p>
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-id-card w-5 text-gray-400"></i>
                                Numéro de patient : {{ $patient->numeroPatient }}
                            </p>
                            <p class="flex items-center text-gray-900">
                                <i class="fas fa-folder-medical w-5 text-gray-400"></i>
                                Numéro de dossier : {{ $patient->dossierMedical->numero_dossier }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Statut</h3>
                        <div class="mt-2">
                            <span class="badge badge-{{ $patient->statut === 'ACTIF' ? 'success' : 'danger' }}">
                                {{ $patient->statut }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails médicaux et historique -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Historique médical -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique médical</h3>
                
                @if($patient->dossierMedical->historiques->isEmpty())
                    <p class="text-gray-500">Aucun historique médical enregistré.</p>
                @else
                    <div class="space-y-4">
                        @foreach($patient->dossierMedical->historiques as $historique)
                            <div class="border-b border-gray-200 pb-4">
                                <h4 class="font-medium text-gray-900">{{ $historique->type }}</h4>
                                <p class="text-gray-600">{{ $historique->description }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($historique->date_debut)->format('d/m/Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Examens -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Examens</h3>
                
                @if($patient->dossierMedical->examens->isEmpty())
                    <p class="text-gray-500">Aucun examen enregistré.</p>
                @else
                    <div class="space-y-4">
                        @foreach($patient->dossierMedical->examens as $examen)
                            <div class="border-b border-gray-200 pb-4">
                                <h4 class="font-medium text-gray-900">{{ $examen->type }}</h4>
                                <p class="text-gray-600">{{ $examen->description }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($examen->date)->format('d/m/Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Prescriptions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Prescriptions</h3>
                
                @if($patient->dossierMedical->prescriptions->isEmpty())
                    <p class="text-gray-500">Aucune prescription enregistrée.</p>
                @else
                    <div class="space-y-4">
                        @foreach($patient->dossierMedical->prescriptions as $prescription)
                            <div class="border-b border-gray-200 pb-4">
                                <h4 class="font-medium text-gray-900">{{ $prescription->medicament->nom }}</h4>
                                <p class="text-gray-600">{{ $prescription->posologie }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($prescription->date_prescription)->format('d/m/Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection