@extends('secretaire.layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-success mb-0"><i class="fas fa-user-edit me-3"></i>Modifier mon profil</h1>
                <a href="{{ route('secretaire.profile') }}" class="btn btn-outline-success px-4 py-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour au profil
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Formulaire d'édition du profil -->
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden mb-5" id="profile-info">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-id-card me-2"></i>Informations personnelles</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('secretaire.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <div class="profile-image-container mx-auto mb-3" style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; position: relative;">
                                        @if($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}" alt="Photo de profil" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="photo-upload-container mt-2">
                                        <label for="photo" class="btn btn-outline-success w-100">
                                            <i class="fas fa-camera me-2"></i>Changer la photo
                                        </label>
                                        <input type="file" class="form-control d-none @error('photo') is-invalid @enderror" 
                                               id="photo" name="photo" accept="image/*" onchange="previewImage(this)">
                                        <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF (max 2Mo)</small>
                                        @error('photo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2 text-center">
                                            <button type="button" id="remove-photo" class="btn btn-sm btn-outline-danger d-none mt-2">
                                                <i class="fas fa-times me-1"></i>Annuler
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nom" class="form-label fw-bold">Nom</label>
                                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                                id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="prenom" class="form-label fw-bold">Prénom</label>
                                    <input type="text" class="form-control form-control-lg @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="telephone" class="form-label fw-bold">Téléphone</label>
                                    <input type="tel" class="form-control form-control-lg @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulaire de changement de mot de passe -->
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden" id="password">
                <div class="card-header bg-warning text-dark py-3">
                    <h4 class="mb-0"><i class="fas fa-key me-2"></i>Changer mon mot de passe</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('secretaire.profile.update-password') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="current_password" class="form-label fw-bold">Mot de passe actuel</label>
                                    <input type="password" class="form-control form-control-lg @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label fw-bold">Nouveau mot de passe</label>
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control form-control-lg" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning btn-lg px-5">
                                <i class="fas fa-key me-2"></i>Changer mon mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const imageContainer = document.querySelector('.profile-image-container');
        const removeButton = document.getElementById('remove-photo');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Mettre à jour l'aperçu de l'image
                const img = imageContainer.querySelector('img');
                img.src = e.target.result;
                
                // Afficher le bouton d'annulation
                removeButton.classList.remove('d-none');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Fonction pour annuler la sélection d'image
    document.getElementById('remove-photo').addEventListener('click', function() {
        // Réinitialiser le champ de fichier
        const photoInput = document.getElementById('photo');
        photoInput.value = '';
        
        // Restaurer l'image d'origine
        const imageContainer = document.querySelector('.profile-image-container');
        const img = imageContainer.querySelector('img');
        
        @if($user->photo)
            img.src = "{{ asset('storage/' . $user->photo) }}";
        @else
            img.src = "{{ asset('images/default-avatar.png') }}";
        @endif
        
        // Cacher le bouton d'annulation
        this.classList.add('d-none');
    });
</script>
@endpush
