@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 container mx-auto p-6 max-w-lg">
        <button id="toggleFormBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-6">Ajouter Secrétaire</button>

        <div id="secretaireForm" class="hidden">
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

                <p class="mb-4 text-gray-600">Un mot de passe par défaut <strong>secret1234</strong> sera attribué au secrétaire créé.</p>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer le secrétaire</button>
            </form>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4">Liste des Secrétaires</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="text-left py-2 px-4 border-r border-gray-200">Nom</th>
                            <th class="text-left py-2 px-4 border-r border-gray-200">Prénom</th>
                            <th class="text-left py-2 px-4 border-r border-gray-200">Email</th>
                            <th class="text-left py-2 px-4">Téléphone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($secretaires as $secretaire)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-2 px-4 border-r border-gray-200">{{ $secretaire->nom }}</td>
                                <td class="py-2 px-4 border-r border-gray-200">{{ $secretaire->prenom }}</td>
                                <td class="py-2 px-4 border-r border-gray-200">{{ $secretaire->email }}</td>
                                <td class="py-2 px-4">{{ $secretaire->telephone }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Aucun secrétaire trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleBtn = document.getElementById('toggleFormBtn');
        var formDiv = document.getElementById('secretaireForm');
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
