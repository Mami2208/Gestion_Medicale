@extends('layouts.admin')

@section('title', 'Statistiques du système')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-800">Statistiques du système</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
        <!-- Statistiques des utilisateurs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Utilisateurs</h2>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-700">Total des utilisateurs :</span>
                <span class="text-2xl font-bold text-blue-600">{{ $totalUtilisateurs }}</span>
            </div>
            
            <h3 class="font-medium text-gray-700 mb-2">Répartition par rôle</h3>
            <div class="space-y-2">
                @foreach($utilisateursByRole as $role => $total)
                <div class="flex items-center justify-between">
                    <span class="text-sm">
                        @switch($role)
                            @case('ADMIN')
                                Administrateurs
                                @break
                            @case('MEDECIN')
                                Médecins
                                @break
                            @case('SECRETAIRE')
                                Secrétaires
                                @break
                            @case('INFIRMIER')
                                Infirmiers
                                @break
                            @case('PATIENT')
                                Patients
                                @break
                            @default
                                {{ $role }}
                        @endswitch
                    </span>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $total }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Statistiques des patients -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Patients</h2>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-700">Total des patients :</span>
                <span class="text-2xl font-bold text-green-600">{{ $totalPatients }}</span>
            </div>
            
            <h3 class="font-medium text-gray-700 mb-2">Répartition par sexe</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <i class="fas fa-male text-2xl text-blue-500 mb-2"></i>
                    <div class="text-xl font-semibold">{{ $patientsParSexe['M'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Hommes</div>
                </div>
                <div class="bg-pink-50 p-4 rounded-lg text-center">
                    <i class="fas fa-female text-2xl text-pink-500 mb-2"></i>
                    <div class="text-xl font-semibold">{{ $patientsParSexe['F'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Femmes</div>
                </div>
            </div>
        </div>
        
        <!-- Statistiques des médecins -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Médecins</h2>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-700">Total des médecins :</span>
                <span class="text-2xl font-bold text-purple-600">{{ $totalMedecins }}</span>
            </div>
            
            <h3 class="font-medium text-gray-700 mb-2">Répartition par spécialité</h3>
            <div class="space-y-2">
                @forelse($medecinsParSpecialite as $specialite => $total)
                <div class="flex items-center justify-between">
                    <span class="text-sm">{{ $specialite ?: 'Non spécifié' }}</span>
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $total }}</span>
                </div>
                @empty
                <div class="text-sm text-gray-500 italic">Aucune donnée disponible</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-t border-gray-200">
        <!-- Statistiques des consultations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Consultations</h2>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-700">Total des consultations :</span>
                <span class="text-2xl font-bold text-yellow-600">{{ $totalConsultations }}</span>
            </div>
            
            <h3 class="font-medium text-gray-700 mb-2">Consultations par mois ({{ date('Y') }})</h3>
            <div class="relative">
                <div class="h-40 flex items-end space-x-1">
                    @for($i = 1; $i <= 12; $i++)
                    @php
                        $height = isset($consultationsParMois[$i]) ? min(100, $consultationsParMois[$i] / max(1, max($consultationsParMois)) * 100) : 0;
                        $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'][$i-1];
                    @endphp
                    <div class="flex-1 flex flex-col items-center">
                        <div class="text-xs mb-1">{{ $consultationsParMois[$i] ?? 0 }}</div>
                        <div class="w-full bg-yellow-200 rounded-t" style="height: {{ $height }}%"></div>
                        <div class="text-xs mt-1">{{ $mois }}</div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Statistiques des rendez-vous et dossiers -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Autres statistiques</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-indigo-50 p-6 rounded-lg">
                    <div class="text-indigo-500 text-3xl font-bold mb-1">{{ $totalRendezVous }}</div>
                    <div class="text-gray-700">Total des rendez-vous</div>
                    <div class="mt-2 text-sm text-indigo-600">
                        <i class="fas fa-calendar-check mr-1"></i> {{ $rendezVousAVenir }} à venir
                    </div>
                </div>
                
                <div class="bg-teal-50 p-6 rounded-lg">
                    <div class="text-teal-500 text-3xl font-bold mb-1">{{ $totalDossiers }}</div>
                    <div class="text-gray-700">Dossiers médicaux</div>
                    <div class="mt-2 text-sm text-teal-600">
                        <i class="fas fa-folder-open mr-1"></i> {{ round($totalDossiers / max(1, $totalPatients) * 100) }}% des patients
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
