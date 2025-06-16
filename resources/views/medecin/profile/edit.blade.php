@extends('layouts.app')

@section('styles')
<style>
    :root {
        --medical-primary: #2c3e50;
        --medical-secondary: #34495e;
        --medical-accent: #3498db;
        --medical-success: #27ae60;
        --medical-danger: #e74c3c;
    }

    .medical-section-header {
        background: linear-gradient(135deg, var(--medical-primary), var(--medical-secondary));
        color: white;
        padding: 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .medical-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .medical-input:focus,
    .medical-select:focus,
    .medical-textarea:focus {
        border-color: var(--medical-accent);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .medical-label {
        color: var(--medical-primary);
    }

    .medical-error {
        background-color: var(--medical-danger);
        color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.25rem;
    }

    .medical-success {
        background-color: var(--medical-success);
        color: white;
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.25rem;
    }

    .profile-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto;
        display: block;
    }

    .photo-upload {
        cursor: pointer;
        background: var(--medical-accent);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        text-align: center;
        margin-top: 1rem;
        transition: background-color 0.3s;
    }

    .photo-upload:hover {
        background: #2980b9;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 pt-20">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto medical-card">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-blue-800 mb-6">Modifier mon profil</h1>

                <!-- Messages globaux -->
                @if(session('success'))
                    <div class="medical-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="medical-error mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('medecin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Photo de profil -->
                    <div class="text-center mb-6">
                        @if($medecin->photo)
                            <img src="{{ asset('storage/medecins/' . $medecin->photo) }}" alt="Photo de profil" class="profile-photo">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" alt="Photo de profil par défaut" class="profile-photo">
                        @endif
                        <div class="photo-upload">
                            <label for="photo" class="cursor-pointer">
                                <i class="fas fa-upload mr-2"></i> Changer la photo
                                <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="nom" class="medical-label block mb-1">Nom</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $medecin->nom) }}" 
                                   class="medical-input mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('nom') error @enderror">
                            @error('nom')
                                <div class="medical-error mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="prenom" class="medical-label block mb-1">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $medecin->prenom) }}" 
                                   class="medical-input mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('prenom') error @enderror">
                            @error('prenom')
                                <div class="medical-error mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="specialite" class="medical-label block mb-1">Spécialité</label>
                            <input type="text" name="specialite" id="specialite" value="{{ old('specialite', $medecin->specialite) }}" 
                                   class="medical-input mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('specialite') error @enderror">
                            @error('specialite')
                                <div class="medical-error mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone" class="medical-label block mb-1">Téléphone</label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $medecin->telephone) }}" 
                                   class="medical-input mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('telephone') error @enderror">
                            @error('telephone')
                                <div class="medical-error mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="adresse" class="medical-label block mb-1">Adresse</label>
                            <textarea name="adresse" id="adresse" rows="3" 
                                      class="medical-textarea mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('adresse') error @enderror">
                                {{ old('adresse', $medecin->adresse) }}
                            </textarea>
                            @error('adresse')
                                <div class="medical-error mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('medecin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Sauvegarder les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
