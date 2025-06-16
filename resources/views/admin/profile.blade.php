@extends('layouts.admin')

@section('title', 'Mon profil')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center mb-6">
        <div class="bg-primary rounded-full p-3 mr-4" style="background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%);">
            <i class="fas fa-user-circle text-white text-3xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-semibold">{{ $user->prenom }} {{ $user->nom }}</h3>
            <p class="text-gray-600">{{ $user->email }}</p>
        </div>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $user->nom) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pru00e9nom -->
            <div>
                <label for="prenom" class="block text-sm font-medium text-gray-700">Pru00e9nom</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $user->prenom) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('prenom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tu00e9lu00e9phone -->
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700">Tu00e9lu00e9phone</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $user->telephone) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('telephone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ru00f4le -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Ru00f4le</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2 text-gray-500">
                    Administrateur
                </div>
            </div>

            <!-- Date de cru00e9ation du compte -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Compte cru00e9u00e9 le</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 px-3 py-2 text-gray-500">
                    {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Changer de mot de passe</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mot de passe actuel -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label for="nouveau_mot_de_passe" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                    <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    @error('nouveau_mot_de_passe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmation du nouveau mot de passe -->
                <div>
                    <label for="nouveau_mot_de_passe_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="nouveau_mot_de_passe_confirmation" id="nouveau_mot_de_passe_confirmation"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>
@endsection
