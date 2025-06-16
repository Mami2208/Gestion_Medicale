@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 container mx-auto p-6 max-w-lg">
        <h1 class="text-3xl font-bold mb-6">Créer un nouveau secrétaire</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.secretaires.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-bold mb-2">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="prenom" class="block text-gray-700 font-bold mb-2">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="telephone" class="block text-gray-700 font-bold mb-2">Téléphone</label>
                <input type="number" name="telephone" id="telephone" value="{{ old('telephone') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="hopital_id" class="block text-gray-700 font-bold mb-2">Hôpital</label>
                <select name="hopital_id" id="hopital_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    @foreach($hopitaux as $hopital)
                        <option value="{{ $hopital->id }}" {{ old('hopital_id') == $hopital->id ? 'selected' : '' }}>
                            {{ $hopital->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="mot_de_passe" class="block text-gray-700 font-bold mb-2">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="mot_de_passe_confirmation" class="block text-gray-700 font-bold mb-2">Confirmation du mot de passe</label>
                <input type="password" name="mot_de_passe_confirmation" id="mot_de_passe_confirmation" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer le secrétaire</button>
        </form>
    </div>
</div>
@endsection
