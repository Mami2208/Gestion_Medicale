@extends('layouts.app')

@section('content')
<div class="p-4">
    <a href="{{ route('admin.infirmiers.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Retour à la liste
    </a>

    <div class="bg-white shadow rounded p-6 mt-4">
        <form action="{{ route('admin.infirmiers.store') }}" method="POST">
            @csrf
            <!-- Champ caché pour forcer le rôle à INFIRMIER -->
            <input type="hidden" name="role" value="INFIRMIER">

            <div class="mb-4">
                <label for="nom" class="block font-semibold">Nom</label>
                <input type="text" name="nom" id="nom" class="w-full border rounded p-2" value="{{ old('nom') }}">
            </div>

            <div class="mb-4">
                <label for="prenom" class="block font-semibold">Prénom</label>
                <input type="text" name="prenom" id="prenom" class="w-full border rounded p-2" value="{{ old('prenom') }}">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded p-2" value="{{ old('email') }}">
            </div>

            <div class="mb-4">
                <label for="services" class="block font-semibold">Services</label>
                <input type="text" name="services" id="services" class="w-full border rounded p-2" value="{{ old('services') }}">
            </div>

            <div class="mb-4">
                <label for="matricule" class="block font-semibold">Matricule</label>
                <input type="text" name="matricule" id="matricule" class="w-full border rounded p-2" value="{{ old('matricule') }}">
            </div>

            <div class="mb-4">
                <label for="telephone" class="block font-semibold">Téléphone</label>
                <input type="text" name="telephone" id="telephone" class="w-full border rounded p-2" value="{{ old('telephone') }}">
            </div>

            <div class="mb-4">
                <label for="mot_de_passe" class="block font-semibold">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label for="mot_de_passe_confirmation" class="block font-semibold">Confirmation du mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation" id="mot_de_passe_confirmation" class="w-full border rounded p-2">
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
