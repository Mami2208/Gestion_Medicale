@extends('medecin.layouts.app')

@section('title', 'Dossier médical - ' . $dossier->patient->utilisateur->nom . ' ' . $dossier->patient->utilisateur->prenom)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dossier médical</h1>
        <p class="mt-1 text-sm text-gray-500">Informations médicales de {{ $dossier->patient->utilisateur->nom }} {{ $dossier->patient->utilisateur->prenom }}</p>
    </div>

<!-- Informations du patient -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <img src="{{ $dossier->patient->utilisateur->photo ?? asset('images/default-avatar.png') }}" 
                         class="rounded-full h-24 w-24" 
                         alt="Photo patient">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $dossier->patient->utilisateur->nom }} {{ $dossier->patient->utilisateur->prenom }}</h2>
                        <p class="text-gray-600">{{ $dossier->patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($dossier->patient->utilisateur->date_naissance)->format('d/m/Y') : 'Date de naissance non spécifiée' }}</p>
                        <p class="text-gray-600">{{ $dossier->patient->utilisateur->telephone }}</p>
                        <p class="text-gray-600">{{ $dossier->patient->utilisateur->email }}</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-900 mb-2">Informations médicales</h3>
                        <p class="text-gray-600"><span class="font-medium">Groupe sanguin:</span> {{ $dossier->groupe_sanguin ?? 'Non spécifié' }}</p>
                        <p class="text-gray-600"><span class="font-medium">Taille:</span> {{ $dossier->taille ? $dossier->taille . ' cm' : 'Non spécifiée' }}</p>
                        <p class="text-gray-600"><span class="font-medium">Poids:</span> {{ $dossier->poids ? $dossier->poids . ' kg' : 'Non spécifié' }}</p>
                        @if($dossier->taille && $dossier->poids)
                            @php
                                $tailleEnMetres = $dossier->taille / 100;
                                $imc = $dossier->poids / ($tailleEnMetres * $tailleEnMetres);
                            @endphp
                            <p class="text-gray-600"><span class="font-medium">IMC:</span> {{ number_format($imc, 1) }}</p>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-900 mb-2">Antécédents médicaux</h3>
                        @if(!empty($dossier->antecedents_medicaux) && is_array($dossier->antecedents_medicaux) && count($dossier->antecedents_medicaux) > 0)
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                @foreach($dossier->antecedents_medicaux as $antecedent)
                                    @if(!empty($antecedent))
                                        <li>{{ is_array($antecedent) ? json_encode($antecedent) : $antecedent }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @elseif(!empty($dossier->antecedents_medicaux) && is_string($dossier->antecedents_medicaux))
                            <p class="text-gray-600 whitespace-pre-line">{{ $dossier->antecedents_medicaux }}</p>
                        @else
                            <p class="text-gray-500">Aucun antécédent médical enregistré.</p>
                        @endif
                        
                        <h3 class="font-medium text-gray-900 mt-4 mb-2">Allergies</h3>
                        @if(!empty($dossier->allergies) && is_array($dossier->allergies) && count($dossier->allergies) > 0)
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                @foreach($dossier->allergies as $allergie)
                                    @if(!empty($allergie))
                                        <li>{{ is_array($allergie) ? json_encode($allergie) : $allergie }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @elseif(!empty($dossier->allergies) && is_string($dossier->allergies))
                            <p class="text-gray-600">{{ $dossier->allergies }}</p>
                        @else
                            <p class="text-gray-500">Aucune allergie connue.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu de navigation -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4 border-b border-gray-200">
            <nav class="flex space-x-4" aria-label="Menu">
                <a href="#historique" class="bg-blue-100 text-blue-700 px-3 py-2 rounded-md text-sm font-medium">Historique médical</a>
                <a href="#examens" class="text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-md text-sm font-medium">Examens</a>
                <a href="#prescriptions" class="text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-md text-sm font-medium">Prescriptions</a>
            </nav>
        </div>
    </div>

    <!-- Historique médical -->
    <div id="historique" class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Historique médical</h2>
            
            @if(!$dossier->historiquesMedicaux || $dossier->historiquesMedicaux->isEmpty())
                <p class="text-gray-500">Aucun historique médical enregistré.</p>
            @else
                <div class="space-y-4">
                    @foreach($dossier->historiquesMedicaux as $historique)
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="font-medium text-gray-900">{{ $historique->type ?? 'Historique médical' }}</h3>
                            <p class="text-gray-600">{{ $historique->description ?? 'Aucune description' }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $historique->date_debut ? \Carbon\Carbon::parse($historique->date_debut)->format('d/m/Y') : 'Date non spécifiée' }}
                                @if($historique->date_fin)
                                    au {{ \Carbon\Carbon::parse($historique->date_fin)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Examens -->
    <div id="examens" class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Examens</h2>
            
            @if($dossier->examens->isEmpty())
                <p class="text-gray-500">Aucun examen enregistré.</p>
            @else
                <div class="space-y-4">
                    @foreach($dossier->examens as $examen)
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="font-medium text-gray-900">{{ $examen->type }}</h3>
                            <p class="text-gray-600">{{ $examen->description }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($examen->date)->format('d/m/Y') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Prescriptions -->
    <div id="prescriptions" class="bg-white shadow rounded-lg">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Prescriptions</h2>
            
            @if($dossier->prescriptions->isEmpty())
                <p class="text-gray-500">Aucune prescription enregistrée.</p>
            @else
                <div class="space-y-4">
                    @foreach($dossier->prescriptions as $prescription)
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $prescription->medicament }}</h3>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Posologie:</span> {{ $prescription->posologie }}
                                        <span class="mx-2">|</span>
                                        <span class="font-medium">Fréquence:</span> {{ $prescription->frequence }}
                                        <span class="mx-2">|</span>
                                        <span class="font-medium">Durée:</span> {{ $prescription->duree_jours }} jours
                                    </p>
                                    @if($prescription->instructions)
                                        <p class="text-gray-600 mt-1">
                                            <span class="font-medium">Instructions:</span> {{ $prescription->instructions }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->statut === 'EN_COURS' ? 'bg-green-100 text-green-800' : ($prescription->statut === 'TERMINEE' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $prescription->statut }}
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($prescription->date_prescription)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection