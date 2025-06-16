@extends('secretaire.layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- En-tête du profil -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-success mb-0"><i class="fas fa-user-circle me-3"></i>Mon Profil</h1>
                <a href="{{ route('secretaire.profile.edit') }}" class="btn btn-success px-4 py-2">
                    <i class="fas fa-edit me-2"></i>Modifier mon profil
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Carte principale du profil -->
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden mb-5">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-id-card me-2"></i>Informations personnelles</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            <div class="avatar-container mx-auto mb-3" style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%;">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Photo de profil" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <h5 class="fw-bold">{{ $user->nom }} {{ $user->prenom }}</h5>
                            <span class="badge bg-success px-3 py-2 rounded-pill">Secrétaire</span>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label text-muted">Nom</label>
                                        <p class="form-control-plaintext fs-5">{{ $user->nom }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label text-muted">Prénom</label>
                                        <p class="form-control-plaintext fs-5">{{ $user->prenom }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label text-muted">Email</label>
                                        <p class="form-control-plaintext fs-5">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label text-muted">Téléphone</label>
                                        <p class="form-control-plaintext fs-5">{{ $user->telephone ?? 'Non renseigné' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <div class="card-header bg-warning text-dark py-3">
                    <h4 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Sécurité</h4>
                </div>
                <div class="card-body p-4">
                    <p class="mb-4">Pour modifier votre mot de passe, cliquez sur le bouton ci-dessous.</p>
                    <a href="{{ route('secretaire.profile.edit') }}#password" class="btn btn-warning px-4 py-2">
                        <i class="fas fa-key me-2"></i>Changer mon mot de passe
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
