@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Uploader un fichier DICOM</h2>
    
    <form method="POST" action="{{ route('dicom.upload') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->has('upload_error'))
            <p class="text-red-500 text-sm mb-4">{{ $errors->first('upload_error') }}</p>
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Fichier DICOM (.dcm)</label>
            <input type="file" name="dicom_file" 
                    class="w-full p-2 border rounded" accept=".dcm">
            @error('dicom_file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Dossier médical associé</label>
            <select name="dossier_id" class="w-full p-2 border rounded">
                @foreach($dossiers as $dossier)
                    <option value="{{ $dossier->id }}">
                        {{ $dossier->patient->user->name }} - {{ $dossier->created_at }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Uploader
        </button>
    </form>
</div>
@endsection