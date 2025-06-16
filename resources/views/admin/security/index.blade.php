@extends('layouts.admin')

@section('title', 'Paramètres de sécurité')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-800">Paramètres de sécurité</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6 mt-4" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
        <!-- Paramètres de sécurité -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Configuration de sécurité</h2>
            <form action="{{ route('admin.security.update') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-1">Longueur minimale du mot de passe</label>
                    <input type="number" name="password_min_length" id="password_min_length" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        value="8" min="6" max="30">
                </div>
                
                <div class="mb-4">
                    <label for="password_expires_days" class="block text-sm font-medium text-gray-700 mb-1">Expiration des mots de passe (jours)</label>
                    <input type="number" name="password_expires_days" id="password_expires_days" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        value="90" min="0" max="365">
                    <p class="text-xs text-gray-500 mt-1">0 = jamais</p>
                </div>
                
                <div class="mb-4">
                    <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-1">Nombre maximal de tentatives de connexion</label>
                    <input type="number" name="max_login_attempts" id="max_login_attempts" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        value="5" min="3" max="10">
                </div>
                
                <div class="mb-4">
                    <label for="session_timeout_minutes" class="block text-sm font-medium text-gray-700 mb-1">Expiration de session (minutes)</label>
                    <input type="number" name="session_timeout_minutes" id="session_timeout_minutes" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                        value="30" min="5" max="240">
                </div>
                
                <div>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                        Enregistrer les paramètres
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Statistiques d'utilisateurs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Statistiques d'utilisateurs</h2>
            
            <div class="mb-6">
                <h3 class="font-medium text-gray-700 mb-2">Répartition par rôle</h3>
                <div class="space-y-2">
                    @foreach($usersByRole as $role => $total)
                    <div class="flex items-center justify-between">
                        <span class="text-sm">
                            @switch($role)
                                @case('ADMIN')
                                    Administrateurs
                                    @break
                                @case('MEDECIN')
                                    Médecins
                                    @break
                                @case('INFIRMIER')
                                    Infirmiers
                                    @break
                                @case('SECRETAIRE')
                                    Secru00e9taires
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
            
            <div>
                <h3 class="font-medium text-gray-700 mb-2">Comptes verrouillés</h3>
                <div class="bg-red-100 text-red-800 text-xl font-semibold px-4 py-2 rounded text-center">
                    {{ App\Models\Utilisateur::where('statut', 'VERROUILLE')->count() }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-t border-gray-200">
        <!-- Dernières connexions réussies -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Dernières connexions réussies</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date/Heure
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Adresse IP
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($successfulLogins as $login)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $login->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                @if($login->user)
                                    <div class="font-medium text-gray-900">{{ $login->user->nom }} {{ $login->user->prenom }}</div>
                                    <div class="text-xs text-gray-500">{{ $login->user->role }}</div>
                                @else
                                    <span class="text-gray-500">Utilisateur supprimé</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $login->ip_address }}
                            </td>
                        </tr>
                        @endforeach
                        
                        @if(count($successfulLogins) === 0)
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">
                                Aucune connexion enregistrée
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Tentatives de connexion échouées -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Tentatives de connexion échouées</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date/Heure
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Adresse IP
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($failedLogins as $login)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $login->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-900">{{ $login->properties['email'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                {{ $login->ip_address }}
                            </td>
                        </tr>
                        @endforeach
                        
                        @if(count($failedLogins) === 0)
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">
                                Aucune tentative échouée
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
