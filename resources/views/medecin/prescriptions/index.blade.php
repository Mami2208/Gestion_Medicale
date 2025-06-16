@extends('medecin.layouts.app')

@section('title', 'Prescriptions')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Prescriptions</h1>
        <p class="mt-1 text-sm text-gray-500">Gérez les prescriptions médicales</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="patient_id" class="form-label">Patient</label>
                    <select id="patient_id" class="form-control">
                        <option value="">Tous les patients</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->utilisateur ? $patient->utilisateur->nom . ' ' . $patient->utilisateur->prenom : 'Patient #' . $patient->id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date" class="form-label">Date</label>
                    <input type="date" id="date" class="form-control">
                </div>
                <div>
                    <label for="statut" class="form-label">Statut</label>
                    <select id="statut" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" class="btn btn-primary w-full">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des prescriptions -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Liste des prescriptions</h2>
                <a href="{{ route('medecin.prescriptions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle prescription
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Médicaments
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($prescriptions as $prescription)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($prescription->patient && $prescription->patient->utilisateur)
                                        {{ $prescription->patient->utilisateur->nom }} {{ $prescription->patient->utilisateur->prenom }}
                                    @else
                                        Patient #{{ $prescription->patient_id }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $prescription->date_debut ? $prescription->date_debut->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($prescription->medicament)
                                        {{ $prescription->medicament }} ({{ $prescription->posologie }} - {{ $prescription->frequence }} - {{ $prescription->duree_jours }} jours)
                                    @else
                                        Aucun médicament
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $prescription->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($prescription->statut === 'termine' ? 'bg-green-100 text-green-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ $prescription->statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('medecin.prescriptions.show', $prescription) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medecin.prescriptions.edit', $prescription) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('medecin.prescriptions.destroy', $prescription) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette prescription ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Aucune prescription trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 