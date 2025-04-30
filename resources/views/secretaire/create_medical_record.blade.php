@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-lg">
    <h1 class="text-3xl font-bold mb-6">Créer un dossier médical</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('secretaire.medical_records.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="patient_id" class="block text-gray-700 font-bold mb-2">Patient</label>
            <select name="patient_id" id="patient_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">Sélectionnez un patient</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->utilisateur->nom ?? 'Patient #' . $patient->id }}</option>
                @endforeach
            </select>
            @error('patient_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4" required class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer le dossier médical</button>
    </form>
</div>
@endsection
