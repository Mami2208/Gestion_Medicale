@extends('medecin.layouts.app')

@section('title', 'Modifier le patient - ' . $patient->utilisateur->nom . ' ' . $patient->utilisateur->prenom)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier le patient</h1>
        <p class="mt-1 text-sm text-gray-500">Modifiez les informations du patient</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('medecin.patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Affichage des erreurs de validation -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Veuillez corriger les erreurs suivantes :
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations personnelles -->
                <div class="space-y-6">
                    <h2 class="text-lg font-medium text-gray-900">Informations personnelles</h2>

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $patient->utilisateur->nom) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-500">*</span></label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $patient->utilisateur->prenom) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de naissance <span class="text-red-500">*</span></label>
                        <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance', $patient->utilisateur->date_naissance) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sexe <span class="text-red-500">*</span></label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="sexe" value="M" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" 
                                    {{ old('sexe', $patient->utilisateur->sexe) == 'M' ? 'checked' : '' }} required>
                                <span class="ml-2 text-sm text-gray-700">Masculin</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="sexe" value="F" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                    {{ old('sexe', $patient->utilisateur->sexe) == 'F' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Féminin</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="space-y-6">
                    <h2 class="text-lg font-medium text-gray-900">Coordonnées</h2>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $patient->utilisateur->email) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-500">*</span></label>
                        <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $patient->utilisateur->telephone) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="adresse" id="adresse" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('adresse', $patient->utilisateur->adresse) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Informations médicales -->
            <div class="mt-8 space-y-6">
                <h2 class="text-lg font-medium text-gray-900">Informations médicales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="groupe_sanguin" class="block text-sm font-medium text-gray-700">Groupe sanguin</label>
                        <select name="groupe_sanguin" id="groupe_sanguin"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Sélectionnez un groupe</option>
                            <option value="A+" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('groupe_sanguin', $patient->dossierMedical->groupe_sanguin ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>

                    <div>
                        <label for="poids" class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                        <input type="number" step="0.1" name="poids" id="poids" value="{{ old('poids', $patient->dossierMedical->poids ?? '') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="taille" class="block text-sm font-medium text-gray-700">Taille (cm)</label>
                        <input type="number" name="taille" id="taille" value="{{ old('taille', $patient->dossierMedical->taille ?? '') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies connues</label>
                        <input type="text" name="allergies" id="allergies" value="{{ old('allergies', $patient->dossierMedical->allergies ?? '') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="antecedents" class="block text-sm font-medium text-gray-700">Antécédents médicaux</label>
                    <textarea name="antecedents" id="antecedents" rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('antecedents', $patient->dossierMedical->antecedents ?? '') }}</textarea>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('medecin.patients.show', $patient->id) }}" class="btn btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
