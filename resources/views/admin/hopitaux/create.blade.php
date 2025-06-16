@extends('layouts.app')

@section('content')
<div>
    @include('components.admin-sidebar')

    <div class="ml-64 container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Ajouter un Hôpital</h1>

        <form action="{{ route('admin.hopitaux.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow max-w-lg">
            @csrf

            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="adresse" class="block text-gray-700 font-semibold mb-2">Adresse</label>
                <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('adresse')
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

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded px-3 py-2">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ajouter</button>
        </form>

        @if(isset($hopitaux) && $hopitaux->count() > 0)
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Liste des hôpitaux</h2>
            <ul class="list-disc list-inside">
                @foreach($hopitaux as $hopital)
                    <li>{{ $hopital->nom }} - {{ $hopital->email }} - {{ $hopital->telephone }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection
