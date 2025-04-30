@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Ajouter un Médecin</h1>

        <form action="{{ route('admin.medecins.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow max-w-lg">
            @csrf

            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('prenom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="specialite" class="block text-gray-700 font-semibold mb-2">Spécialité</label>
                <select name="specialite" id="specialite" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Sélectionnez une spécialité</option>
                    @foreach ($specialites as $specialite)
                        <option value="{{ $specialite }}" {{ old('specialite') == $specialite ? 'selected' : '' }}>{{ $specialite }}</option>
                    @endforeach
                </select>
                @error('specialite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="telephone" class="block text-gray-700 font-semibold mb-2">Téléphone</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('telephone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
        </form>
    </div>
</div>
@endsection
