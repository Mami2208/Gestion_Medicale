@extends('layouts.app')

@section('content')
<div class="ml-64 p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des hôpitaux</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.hopitaux.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Ajouter un hôpital</a>

    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Nom</th>
                <th class="p-2 text-left">Adresse</th>
                <th class="p-2 text-left">Téléphone</th>
                <th class="p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hopitaux as $hopital)
                <tr class="border-t">
                    <td class="p-2">{{ $hopital->nom }}</td>
                    <td class="p-2">{{ $hopital->adresse }}</td>
                    <td class="p-2">{{ $hopital->telephone }}</td>
                    <td class="p-2">
                        <a href="{{ route('admin.hopitaux.edit', $hopital->id) }}" class="text-blue-500 mr-2">Modifier</a>


                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
