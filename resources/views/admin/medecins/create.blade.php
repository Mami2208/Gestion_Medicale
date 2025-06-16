@extends('layouts.app')

@section('content')
<div class="p-4">
    <a href="{{ route('admin.medecins.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Retour à la liste
    </a>

    <div class="bg-white shadow rounded p-6 mt-4">
        <form action="{{ route('admin.medecins.store') }}" method="POST">
            @csrf

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
                <label for="specialite" class="block font-semibold">Spécialité</label>
                <select name="specialite" id="specialite" class="w-full border rounded p-2">
                    <option value="">Sélectionnez une spécialité</option>
                    @foreach ($specialites as $key => $value)
                        <option value="{{ $value }}" {{ old('specialite') == $value ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
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
