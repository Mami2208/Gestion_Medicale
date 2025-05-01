@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 container mx-auto px-4">
        <button id="toggleFormBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-6">Ajouter Médecin</button>

        <div id="medecinForm" class="hidden">
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

        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4">Liste des Médecins</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="text-left py-2 px-4 border-r border-gray-200">Nom</th>
                            <th class="text-left py-2 px-4 border-r border-gray-200">Prénom</th>
                            <th class="text-left py-2 px-4 border-r border-gray-200">Email</th>
                            <th class="text-left py-2 px-4 border-r border-gray-200">Spécialité</th>
                            <th class="text-left py-2 px-4">Téléphone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medecins as $medecin)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-2 px-4 border-r border-gray-200">{{ $medecin->nom }}</td>
                                <td class="py-2 px-4 border-r border-gray-200">{{ $medecin->prenom }}</td>
                                <td class="py-2 px-4 border-r border-gray-200">{{ $medecin->email }}</td>
                                <td class="py-2 px-4 border-r border-gray-200">
                                    @php
                                        $specialites = config('specialites.medicales');
                                        $specialiteKey = array_search($medecin->specialite, $specialites);
                                        $specialiteDisplay = $specialiteKey ? $specialites[$specialiteKey] : $medecin->specialite;
                                    @endphp
                                    {{ $specialiteDisplay }}
                                </td>
                                <td class="py-2 px-4">{{ $medecin->telephone }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Aucun médecin trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $medecins->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleBtn = document.getElementById('toggleFormBtn');
        var formDiv = document.getElementById('medecinForm');
        toggleBtn.addEventListener('click', function () {
            if (formDiv.classList.contains('hidden')) {
                formDiv.classList.remove('hidden');
            } else {
                formDiv.classList.add('hidden');
            }
        });
    });
</script>

@endsection
