@extends('medecin.layouts.app')

@section('title', 'Détails de la prescription')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Détails de la prescription</h1>
            <p class="mt-1 text-sm text-gray-500">Consultation des informations de la prescription</p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0">
            <a href="{{ route('medecin.prescriptions.edit', $prescription) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('medecin.prescriptions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Carte d'information du patient -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations du patient</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nom complet</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $prescription->dossierMedical->patient->utilisateur->nom }} {{ $prescription->dossierMedical->patient->utilisateur->prenom }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de naissance</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $prescription->dossierMedical->patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($prescription->dossierMedical->patient->utilisateur->date_naissance)->format('d/m/Y') : 'Non spécifié' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Âge</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $prescription->dossierMedical->patient->utilisateur->date_naissance ? \Carbon\Carbon::parse($prescription->dossierMedical->patient->utilisateur->date_naissance)->age . ' ans' : 'Non spécifié' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte d'information du traitement -->
        @if($prescription->traitement)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations sur le traitement</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Type de traitement</p>
                        <p class="mt-1 text-sm text-gray-900">{{ \App\Models\Traitement::TYPES[$prescription->traitement->type_traitement] ?? $prescription->traitement->type_traitement }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Description</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $prescription->traitement->description }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de début</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($prescription->traitement->date_debut)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Statut</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ \App\Models\Traitement::STATUTS[$prescription->traitement->statut] ?? $prescription->traitement->statut }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Carte de détail de la prescription -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Détails de la prescription</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Médicament</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $prescription->medicament }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Posologie</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $prescription->posologie }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fréquence</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $prescription->frequence }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Durée</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $prescription->duree_jours }} jours</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Instructions</p>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $prescription->instructions }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date de prescription</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($prescription->date_prescription)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Prescrite par</p>
                        <p class="mt-1 text-sm text-gray-900">
                            Dr. {{ $prescription->medecin->utilisateur->prenom }} {{ $prescription->medecin->utilisateur->nom }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des modifications -->
        @if($prescription->created_at != $prescription->updated_at)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Historique</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-900">
                    Dernière mise à jour le 
                    {{ $prescription->updated_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir supprimer cette prescription ? Cette action est irréversible.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" action="{{ route('medecin.prescriptions.destroy', $prescription) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Supprimer
                    </button>
                </form>
                <button id="cancelDelete" class="ml-3 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la modale de suppression
        const deleteModal = document.getElementById('deleteModal');
        const deleteBtn = document.getElementById('deleteBtn');
        const cancelDelete = document.getElementById('cancelDelete');

        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('block');
            });
        }

        if (cancelDelete) {
            cancelDelete.addEventListener('click', function() {
                deleteModal.classList.remove('block');
                deleteModal.classList.add('hidden');
            });
        }

        // Fermer la modale si on clique en dehors
        window.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                deleteModal.classList.remove('block');
                deleteModal.classList.add('hidden');
            }
        });
    });
</script>
@endpush
