@extends('layouts.admin')

@section('title', 'Du00e9tails de l\'utilisateur')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Du00e9tails de l'utilisateur</h3>
        <div>
            <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-1"></i> Modifier
            </a>
        </div>
    </div>

    <div class="border-t border-gray-200 pt-4">
        <div class="flex items-center mb-6">
            <div class="h-16 w-16 rounded-full bg-green-500 flex items-center justify-center text-white text-xl mr-4">
                <span>{{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold">{{ $user->prenom }} {{ $user->nom }}</h1>
                <p class="text-gray-600">
                    @switch($user->role)
                        @case('ADMIN')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Administrateur</span>
                            @break
                        @case('MEDECIN')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Mu00e9decin</span>
                            @break
                        @case('INFIRMIER')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Infirmier</span>
                            @break
                        @case('SECRETAIRE')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Secru00e9taire</span>
                            @break
                        @case('PATIENT')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Patient</span>
                            @break
                        @default
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $user->role }}</span>
                    @endswitch
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">Informations de contact</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-gray-500 w-6"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-gray-500 w-6"></i>
                        <span>{{ $user->telephone }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-2">Informations professionnelles</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i class="fas fa-id-badge text-gray-500 w-6"></i>
                        <span>Matricule: {{ $user->matricule ?? 'N/A' }}</span>
                    </div>
                    
                    @if($user->role === 'MEDECIN')
                    <div class="flex items-center">
                        <i class="fas fa-stethoscope text-gray-500 w-6"></i>
                        <span>Spu00e9cialitu00e9: {{ $user->specialite ?? 'Non spu00e9cifiu00e9e' }}</span>
                    </div>
                    @endif
                    
                    @if($user->role === 'INFIRMIER')
                    <div class="flex items-center">
                        <i class="fas fa-hospital text-gray-500 w-6"></i>
                        <span>Secteur: {{ $user->secteur ?? 'Non spu00e9cifiu00e9' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-700 mb-2">Informations systu00e8me</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-gray-500 w-6"></i>
                    <span>Compte cru00e9u00e9 le: {{ $user->created_at ? $user->created_at->format('d/m/Y u00e0 H:i') : 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock text-gray-500 w-6"></i>
                    <span>Derniu00e8re mise u00e0 jour: {{ $user->updated_at ? $user->updated_at->format('d/m/Y u00e0 H:i') : 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-sign-in-alt text-gray-500 w-6"></i>
                    <span>Derniu00e8re connexion: {{ $user->last_login_at ?? 'Jamais' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-gray-500 w-6"></i>
                    <span>Statut: {{ $user->active ? 'Actif' : 'Inactif' }}</span>
                </div>
            </div>
        </div>

        @if($user->role === 'MEDECIN')
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-700 mb-2">Patients associu00e9s</h3>
            <div class="mt-2">
                <!-- Ajouter ici la liste des patients si nu00e9cessaire -->
                <p class="text-gray-500 italic">Aucun patient associu00e9 pour le moment</p>
            </div>
        </div>
        @endif

        @if($user->role === 'INFIRMIER')
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-700 mb-2">Soins programmu00e9s</h3>
            <div class="mt-2">
                <!-- Ajouter ici la liste des soins si nu00e9cessaire -->
                <p class="text-gray-500 italic">Aucun soin programmu00e9 pour le moment</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
