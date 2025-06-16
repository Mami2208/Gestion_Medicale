@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('prenom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('telephone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rôle -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">Sélectionnez un rôle</option>
                    <option value="ADMIN" {{ old('role') == 'ADMIN' ? 'selected' : '' }}>Administrateur</option>
                    <option value="MEDECIN" {{ old('role') == 'MEDECIN' ? 'selected' : '' }}>Médecin</option>
                    <option value="INFIRMIER" {{ old('role') == 'INFIRMIER' ? 'selected' : '' }}>Infirmier</option>
                    <option value="SECRETAIRE" {{ old('role') == 'SECRETAIRE' ? 'selected' : '' }}>Secrétaire</option>
                    <option value="PATIENT" {{ old('role') == 'PATIENT' ? 'selected' : '' }}>Patient</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="mot_de_passe" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('mot_de_passe')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="mot_de_passe_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation" id="mot_de_passe_confirmation" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('mot_de_passe_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Matricule (visible uniquement pour les médecins, infirmiers et secrétaires) -->
            <div id="matricule_container" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Matricule</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2 text-gray-500">
                    <span id="matricule_preview"></span>
                </div>
            </div>

            <!-- Spécialité (visible uniquement pour les médecins) -->
            <div id="specialite_container" class="hidden">
                <label for="specialite" class="block text-sm font-medium text-gray-700">Spécialité</label>
                <select name="specialite" id="specialite"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">Sélectionnez une spécialité</option>
                    @foreach($specialites as $specialite)
                        <option value="{{ $specialite }}" {{ old('specialite') == $specialite ? 'selected' : '' }}>
                            {{ $specialite }}
                        </option>
                    @endforeach
                </select>
                @error('specialite')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Secteur (visible uniquement pour les infirmiers) -->
            <div id="secteur_container" class="hidden">
                <label for="secteur" class="block text-sm font-medium text-gray-700">Secteur</label>
                <select name="secteur" id="secteur"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">Sélectionnez un secteur</option>
                    @foreach($secteurs as $secteur)
                        <option value="{{ $secteur }}" {{ old('secteur') == $secteur ? 'selected' : '' }}>
                            {{ $secteur }}
                        </option>
                    @endforeach
                </select>
                @error('secteur')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Créer l'utilisateur
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const specialiteContainer = document.getElementById('specialite_container');
        const secteurContainer = document.getElementById('secteur_container');
        const matriculeContainer = document.getElementById('matricule_container');
        const matriculePreview = document.getElementById('matricule_preview');

        function toggleFields() {
            const role = roleSelect.value;
            specialiteContainer.classList.toggle('hidden', role !== 'MEDECIN');
            secteurContainer.classList.toggle('hidden', role !== 'INFIRMIER');
            matriculeContainer.classList.toggle('hidden', !['MEDECIN', 'INFIRMIER', 'SECRETAIRE'].includes(role));

            // Mettre à jour le préfixe du matricule en fonction du rôle
            if (['MEDECIN', 'INFIRMIER', 'SECRETAIRE'].includes(role)) {
                const prefix = role === 'MEDECIN' ? 'MED' : role === 'INFIRMIER' ? 'INF' : 'SEC';
                matriculePreview.textContent = `${prefix}-XXXX (sera généré automatiquement)`;
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // État initial
    });
</script>
@endpush
@endsection 