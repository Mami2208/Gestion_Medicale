@extends('medecin.layouts.app')

@section('title', 'Modifier le dossier médical')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier le dossier médical</h1>
        <p class="mt-1 text-sm text-gray-500">Modifiez les informations du dossier médical</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('medecin.dossiers.update', $dossier) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Informations du patient -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informations du patient</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="font-medium">{{ $dossier->patient->utilisateur->prenom }} {{ $dossier->patient->utilisateur->nom }}</p>
                        <p class="text-sm text-gray-500">Né(e) le {{ $dossier->patient->utilisateur->date_naissance->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $dossier->patient->utilisateur->email }}</p>
                        <p class="text-sm text-gray-500">{{ $dossier->patient->utilisateur->telephone }}</p>
                    </div>
                </div>

                <!-- Informations médicales -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informations médicales</h2>
                    
                    <!-- Statut -->
                    <div class="mb-4">
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut <span class="text-red-500">*</span></label>
                        <select name="statut" id="statut" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('statut') border-red-500 @enderror">
                            @foreach(\App\Models\Dossier::statuts() as $value => $label)
                                <option value="{{ $value }}" {{ old('statut', $dossier->statut) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groupe sanguin -->
                    <div class="mb-4">
                        <label for="groupe_sanguin" class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                        <input type="text" name="groupe_sanguin" id="groupe_sanguin" value="{{ old('groupe_sanguin', $dossier->groupe_sanguin) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('groupe_sanguin') border-red-500 @enderror"
                               placeholder="Ex: A+">
                        @error('groupe_sanguin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Taille -->
                    <div class="mb-4">
                        <label for="taille" class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                        <input type="number" name="taille" id="taille" step="0.01" min="0" value="{{ old('taille', $dossier->taille) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('taille') border-red-500 @enderror">
                        @error('taille')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Poids -->
                    <div class="mb-4">
                        <label for="poids" class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                        <input type="number" name="poids" id="poids" step="0.1" min="0" value="{{ old('poids', $dossier->poids) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('poids') border-red-500 @enderror">
                        @error('poids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Antécédents médicaux -->
                    <div class="mb-4">
                        <label for="antecedents_medicaux" class="block text-sm font-medium text-gray-700">Antécédents médicaux</label>
                        <textarea name="antecedents_medicaux" id="antecedents_medicaux" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('antecedents_medicaux') border-red-500 @enderror">{{ old('antecedents_medicaux', $dossier->antecedents_medicaux) }}</textarea>
                        @error('antecedents_medicaux')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Allergies -->
                    <div class="mb-4">
                        <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                        <textarea name="allergies" id="allergies" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('allergies') border-red-500 @enderror">{{ old('allergies', $dossier->allergies) }}</textarea>
                        @error('allergies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observations -->
                    <div class="mb-4">
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                        <textarea name="observations" id="observations" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('observations') border-red-500 @enderror">{{ old('observations', $dossier->observations) }}</textarea>
                        @error('observations')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes', $dossier->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-8 flex justify-between">
                    <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Retour
                    </button>
                    <div class="space-x-4">
                        <a href="{{ route('medecin.dossiers.show', $dossier) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
