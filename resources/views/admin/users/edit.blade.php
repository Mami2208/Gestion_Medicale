@extends('admin.layouts.app')

@section('title', 'Modifier l\'utilisateur - ' . $user->nom . ' ' . $user->prenom)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- En-tête de page -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier l'utilisateur</h1>
        <p class="mt-1 text-sm text-gray-500">Modifiez les informations de l'utilisateur</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-6">
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
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $user->nom) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-500">*</span></label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $user->prenom) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance', $user->date_naissance) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sexe</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="sexe" value="M" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" 
                                    {{ old('sexe', $user->sexe) == 'M' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Masculin</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="sexe" value="F" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                    {{ old('sexe', $user->sexe) == 'F' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Féminin</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Coordonnées et rôle -->
                <div class="space-y-6">
                    <h2 class="text-lg font-medium text-gray-900">Coordonnées et accès</h2>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $user->telephone) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="adresse" id="adresse" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('adresse', $user->adresse) }}</textarea>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Sélectionnez un rôle</option>
                            <option value="ADMIN" {{ old('role', $user->role) == 'ADMIN' ? 'selected' : '' }}>Administrateur</option>
                            <option value="MEDECIN" {{ old('role', $user->role) == 'MEDECIN' ? 'selected' : '' }}>Médecin</option>
                            <option value="SECRETAIRE" {{ old('role', $user->role) == 'SECRETAIRE' ? 'selected' : '' }}>Secrétaire</option>
                            <option value="INFIRMIER" {{ old('role', $user->role) == 'INFIRMIER' ? 'selected' : '' }}>Infirmier</option>
                            <option value="PATIENT" {{ old('role', $user->role) == 'PATIENT' ? 'selected' : '' }}>Patient</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Champ mot de passe -->
            <div class="mt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="changer_mot_de_passe" id="changer_mot_de_passe" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="changer_mot_de_passe" class="ml-2 block text-sm text-gray-700">
                        Changer le mot de passe
                    </label>
                </div>

                <div id="champs_mot_de_passe" class="mt-4 hidden grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            autocomplete="new-password">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Afficher/masquer les champs de mot de passe
    document.getElementById('changer_mot_de_passe').addEventListener('change', function() {
        const champsMotDePasse = document.getElementById('champs_mot_de_passe');
        if (this.checked) {
            champsMotDePasse.classList.remove('hidden');
            champsMotDePasse.classList.add('grid');
            document.getElementById('password').setAttribute('required', 'required');
            document.getElementById('password_confirmation').setAttribute('required', 'required');
        } else {
            champsMotDePasse.classList.add('hidden');
            champsMotDePasse.classList.remove('grid');
            document.getElementById('password').removeAttribute('required');
            document.getElementById('password_confirmation').removeAttribute('required');
        }
    });
</script>
@endpush
@endsection
