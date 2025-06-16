@extends('medecin.layouts.app')

@section('title', 'Liste des patients')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Liste des patients</h1>
        <p class="mt-1 text-sm text-gray-500">Gérez vos patients et leurs dossiers médicaux</p>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form action="{{ route('medecin.patients.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                <input type="text" name="search" id="search" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                       placeholder="Nom, prénom, email..." value="{{ request('search') }}">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">Tous</option>
                    <option value="ACTIF" {{ request('status') == 'ACTIF' ? 'selected' : '' }}>Actif</option>
                    <option value="INACTIF" {{ request('status') == 'INACTIF' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Trier par</label>
                <select name="sort" id="sort" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }}>Nom</option>
                    <option value="date_creation" {{ request('sort') == 'date_creation' ? 'selected' : '' }}>Date d'inscription</option>
                    <option value="derniere_consultation" {{ request('sort') == 'derniere_consultation' ? 'selected' : '' }}>Dernière consultation</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des patients -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dernière consultation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($patients as $patient)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="{{ $patient->utilisateur->photo ?? asset('images/default-avatar.png') }}" 
                                             alt="{{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $patient->utilisateur->nom }} {{ $patient->utilisateur->prenom }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $patient->utilisateur->email }}</div>
                                <div class="text-sm text-gray-500">{{ $patient->utilisateur->telephone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $patient->derniere_consultation ? \Carbon\Carbon::parse($patient->derniere_consultation)->format('d/m/Y') : 'Jamais' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $patient->statut === 'ACTIF' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $patient->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('medecin.patients.show', $patient) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-2"></i>
                                    Voir
                                </a>
                                <a href="{{ route('medecin.patients.edit', $patient) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit mr-2"></i>
                                    Modifier
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucun patient trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(message) {
    return confirm(message);
}
</script>
@endpush